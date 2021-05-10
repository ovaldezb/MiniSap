CREATE FUNCTION "registra_corte" ("documento" character, "fechacorte" timestamp without time zone, "cliente" text, "producto" text, "importe" double precision, "vendedor" text, "tipopago" character, "formapago" character, "metodopago" character, "idempresa" integer, "aniofiscal" integer, "idsucursal" integer, "usocfdi" character, "moneda" integer, "operaciones" integer, "canceladas" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

ult_val int :=0;

BEGIN

INSERT INTO "CORTE_CAJA" (
"DOCUMENTO","FECHA_CORTE", "CLIENTE",
"PRODUCTO","IMPORTE","VENDEDOR",
"TIPO_PAGO","FORMA_PAGO","METODO_PAGO",
"ID_EMPRESA","ANIO_FISCAL","ID_SUCURSAL",
"USO_CFDI","MONEDA","OPERACIONES","CANCELADAS") 
VALUES(documento, fechacorte, cliente, producto, importe, vendedor, tipopago,
formapago, metodopago, idempresa, aniofiscal, idsucursal, usocfdi, moneda, operaciones,
canceladas );

SELECT "last_value" 
FROM "CORTE_CAJA_ID_CORTE_seq" into ult_val;

return ult_val;

END
';