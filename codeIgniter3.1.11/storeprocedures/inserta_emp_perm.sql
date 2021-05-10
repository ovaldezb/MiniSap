CREATE FUNCTION "insert_emp_perm" ("idusuario" integer, "idempresa" integer) RETURNS void LANGUAGE plpgsql AS '
DECLARE

suc int :=0;

BEGIN

SELECT MIN("ID_SUCURSAL") FROM "SUCURSALES" WHERE "ID_EMPRESA" = idempresa into suc;

INSERT INTO "USUARIO_EMPRESA" ("ID_USUARIO","ID_EMPRESA","PERMITIDO","ID_SUCURSAL") 

VALUES(idusuario,idempresa,true,suc);

END';