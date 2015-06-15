<?php 
namespace humanity;

class WsDaemon {

    private $socket;
    private $clients;
    private $redis;

    public function __construct($host,$port){
        $this->socket  = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->socket,$host,$port);
        socket_set_block($this->socket);
        socket_listen($this->socket);
        $this->clients = [$this->socket];
    }

    private function handshake($socket){
        $headers = socket_read($socket,12000);
        if(preg_match("/GET (.*) HTTP/", $headers, $match)) $root = $match[1];
        if(preg_match("/Host: (.*)\r\n/", $headers, $match)) $host = $match[1];
        if(preg_match("/Origin: (.*)\r\n/", $headers, $match)) $origin = $match[1];
        if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match)) $key = $match[1];
        $acceptKey = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
        $acceptKey = base64_encode(sha1($acceptKey, true));
        $upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
                   "Upgrade: websocket\r\n".
                   "Connection: Upgrade\r\n".
                   "Sec-WebSocket-Accept: $acceptKey".
                   "\r\n\r\n";
        socket_write($socket,$upgrade);
    }

    public function decode($data) {
        $unmaskedPayload = '';
        $decodedData = array();

        // estimate frame type:
        $firstByteBinary = sprintf('%08b', ord($data[0]));
        $secondByteBinary = sprintf('%08b', ord($data[1]));
        $opcode = bindec(substr($firstByteBinary, 4, 4));
        $isMasked = ($secondByteBinary[0] == '1') ? true : false;
        $payloadLength = ord($data[1]) & 127;

        // unmasked frame is received:
        if (!$isMasked) {
            return array('type' => '', 'payload' => '', 'error' => 'protocol error (1002)');
        }

        switch ($opcode) {
            // text frame:
            case 1:
                $decodedData['type'] = 'text';
                break;

            case 2:
                $decodedData['type'] = 'binary';
                break;

            // connection close frame:
            case 8:
                $decodedData['type'] = 'close';
                break;

            // ping frame:
            case 9:
                $decodedData['type'] = 'ping';
                break;

            // pong frame:
            case 10:
                $decodedData['type'] = 'pong';
                break;

            default:
                return array('type' => '', 'payload' => '', 'error' => 'unknown opcode (1003)');
        }

        if ($payloadLength === 126) {
            $mask = substr($data, 4, 4);
            $payloadOffset = 8;
            $dataLength = bindec(sprintf('%08b', ord($data[2])) . sprintf('%08b', ord($data[3]))) + $payloadOffset;
        } elseif ($payloadLength === 127) {
            $mask = substr($data, 10, 4);
            $payloadOffset = 14;
            $tmp = '';
            for ($i = 0; $i < 8; $i++) {
                $tmp .= sprintf('%08b', ord($data[$i + 2]));
            }
            $dataLength = bindec($tmp) + $payloadOffset;
            unset($tmp);
        } else {
            $mask = substr($data, 2, 4);
            $payloadOffset = 6;
            $dataLength = $payloadLength + $payloadOffset;
        }

        /**
         * We have to check for large frames here. socket_recv cuts at 1024 bytes
         * so if websocket-frame is > 1024 bytes we have to wait until whole
         * data is transferd.
         */
        if (strlen($data) < $dataLength) {
            return false;
        }

        if ($isMasked) {
            for ($i = $payloadOffset; $i < $dataLength; $i++) {
                $j = $i - $payloadOffset;
                if (isset($data[$i])) {
                    $unmaskedPayload .= $data[$i] ^ $mask[$j % 4];
                }
            }
            $decodedData['payload'] = $unmaskedPayload;
        } else {
            $payloadOffset = $payloadOffset - 4;
            $decodedData['payload'] = substr($data, $payloadOffset);
        }

        return $decodedData;
    }

    function encode($payload, $type = 'text', $masked = false) {
        $frameHead = array();
        $payloadLength = strlen($payload);

        switch ($type) {
            case 'text':
                // first byte indicates FIN, Text-Frame (10000001):
                $frameHead[0] = 129;
                break;

            case 'close':
                // first byte indicates FIN, Close Frame(10001000):
                $frameHead[0] = 136;
                break;

            case 'ping':
                // first byte indicates FIN, Ping frame (10001001):
                $frameHead[0] = 137;
                break;

            case 'pong':
                // first byte indicates FIN, Pong frame (10001010):
                $frameHead[0] = 138;
                break;
        }

        // set mask and payload length (using 1, 3 or 9 bytes)
        if ($payloadLength > 65535) {
            $payloadLengthBin = str_split(sprintf('%064b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 255 : 127;
            for ($i = 0; $i < 8; $i++) {
                $frameHead[$i + 2] = bindec($payloadLengthBin[$i]);
            }
            // most significant bit MUST be 0
            if ($frameHead[2] > 127) {
                return array('type' => '', 'payload' => '', 'error' => 'frame too large (1004)');
            }
        } elseif ($payloadLength > 125) {
            $payloadLengthBin = str_split(sprintf('%016b', $payloadLength), 8);
            $frameHead[1] = ($masked === true) ? 254 : 126;
            $frameHead[2] = bindec($payloadLengthBin[0]);
            $frameHead[3] = bindec($payloadLengthBin[1]);
        } else {
            $frameHead[1] = ($masked === true) ? $payloadLength + 128 : $payloadLength;
        }

        // convert frame-head to string:
        foreach (array_keys($frameHead) as $i) {
            $frameHead[$i] = chr($frameHead[$i]);
        }
        if ($masked === true) {
            // generate a random mask:
            $mask = array();
            for ($i = 0; $i < 4; $i++) {
                $mask[$i] = chr(rand(0, 255));
            }

            $frameHead = array_merge($frameHead, $mask);
        }
        $frame = implode('', $frameHead);

        // append payload to frame:
        for ($i = 0; $i < $payloadLength; $i++) {
            $frame .= ($masked === true) ? $payload[$i] ^ $mask[$i % 4] : $payload[$i];
        }

        return $frame;
    }

    public function run($func){
        while(true){
            usleep(12000);
            $read = $this->clients;
            socket_select($read, $write = NULL, $except = NULL, 30);
            # Connect
            if (in_array($this->socket, $read)) {
                $this->clients[] = $newsock = socket_accept($this->socket);
                $this->handshake($newsock);
                socket_getpeername($newsock, $ip);
                echo "Новое соединение с адреса: {$ip}\n";
                $key = array_search($this->socket, $read);
                unset($read[$key]);
            }
            # Read
            foreach($read as $client){
                $data = @socket_read($client, 12000);
                $data = $this->decode($data);
                if($data['type'] == 'close'){
                    unset($this->clients[array_search($client, $this->clients)]);
                    echo "Клиент отключился.\n";
                    continue;
                }

                if(!empty($data)){
                    $clientId = array_search($client,$this->clients);
                    $response = $func($data['payload'],$clientId);
                    if(!is_array($response['clients'])) $response['clients'] = [$response['clients']];
                    foreach($response['clients'] as $clientId){
                        if(!isset($this->clients[$clientId])) continue;
                        socket_write($this->clients[$clientId],$this->encode($response['msg'],'text',false));
                    }
                }
            }
        }
    }

}
?>
