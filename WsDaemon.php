<?php 
$socket  = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
socket_bind($socket,'127.0.0.1','8888');
socket_set_nonblock($socket);
socket_listen($socket);

$clients = [$socket];
$null = null;

while(true){
    $read = $clients;
    if (socket_select($read, $write = NULL, $except = NULL, 0) < 1) continue;
    if(in_array($socket,$read)){
        $key = array_search($socket, $read);
        unset($read[$key]);
    }
}

/*
$read = [$socket];
$write = null;
$execept = null;
while($select = socket_select($read,$write,$execept,0)){
    socket_listen($read[0]);
    var_dump($read);
    sleep(1);
}

/*
$client = [];
$read = [$socket];
$write = null;
$execept = null;
$select = socket_select([$socket],$write,$execept,0);
var_dump($select);
/*
while(true){
    $accept = socket_accept($socket);
    if($accept !== false) $client[] = $accept;
    var_dump($client);
    sleep(1);
}
/*
while($client = socket_accept($socket)){
    var_dump($client);

    /*
    $headers = socket_read($client, 5000);

    if(preg_match("/GET (.*) HTTP/", $headers, $match))
                $root = $match[1];
            if(preg_match("/Host: (.*)\r\n/", $headers, $match))
                $host = $match[1];
            if(preg_match("/Origin: (.*)\r\n/", $headers, $match))
                $origin = $match[1];
            if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match))
                $key = $match[1];

            $acceptKey = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
            $acceptKey = base64_encode(sha1($acceptKey, true));

            $upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
                       "Upgrade: websocket\r\n".
                       "Connection: Upgrade\r\n".
                       "Sec-WebSocket-Accept: $acceptKey".
                       "\r\n\r\n";

            socket_write($client, $upgrade);
    $i=0;
    while($headers = socket_read($client, 10)){
        if($i == 50) break;
        $headers = bindec($headers);
        var_dump($headers);
        $i++;
    }
    socket_close($socket);
     */
//}


#var_dump(socket_connect($socket,'127.0.0.1','8888'));
#$listen = socket_listen($socket);
#var_dump($listen);

?>
