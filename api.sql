PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "method" (
  "name" text NOT NULL,
  "status" text NOT NULL DEFAULT 'PUBLIC',
  "params" text NULL,
  PRIMARY KEY ("name")
);
CREATE INDEX "method_status" ON "method" ("status");
COMMIT;
