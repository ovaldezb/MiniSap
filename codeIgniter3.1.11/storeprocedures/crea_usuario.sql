CREATE FUNCTION "crea_usuario" ("nombre" character, "usrname" character, "paswd" character) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int :=0;
BEGIN
IF EXISTS(SELECT 1 FROM "USUARIO" WHERE "CLAVE_USR" = usrname) THEN
x = -1;
ELSE
INSERT INTO "USUARIO" ("NOMBRE","CLAVE_USR","PASSWORD","ACTIVO")
VALUES(nombre,usrname,paswd,true);
SELECT "last_value" FROM "USUARIO_ID_USUARIO_seq" into x;
END IF;
return x;
END';