PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "method" (
    "name" text NOT NULL,
    "status" text NOT NULL DEFAULT 'PUBLIC',
    "params" text NULL
);
CREATE UNIQUE INDEX "method_name" ON "method" ("name");
CREATE INDEX "method_status" ON "method" ("status");

CREATE TABLE "permission" (
    "app_id" integer NOT NULL,
    "method" text NOT NULL
);
CREATE INDEX "permissions_app_id" ON "permission" ("app_id");
CREATE UNIQUE INDEX "permissions_unique" ON "permission" ("app_id","method");

CREATE TABLE "app" (
    "id" integer NOT NULL PRIMARY KEY AUTOINCREMENT,
    "sig" text NOT NULL,
    "description" text NOT NULL
);
CREATE UNIQUE INDEX "app_id_sig" ON "app" ("id","sig");
COMMIT;
