CREATE FUNCTION "crea_sucursal" ("clave" character, "direccion" text, "responsable" character, "telefono" character, "cp" character, "alias" character, "notas" text, "idempresa" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

x int :=0;

BEGIN

INSERT INTO "SUCURSALES" ("CLAVE","DIRECCION","RESPONSABLE","TELEFONO","CP","ALIAS","NOTAS","ID_EMPRESA","ACTIVO")
VALUES (clave,direccion,responsable,telefono,cp,alias,notas,idempresa,true);

SELECT "last_value" FROM "SUCURSAL_ID_SUCURSAL_seq" into x;

return x;



END';