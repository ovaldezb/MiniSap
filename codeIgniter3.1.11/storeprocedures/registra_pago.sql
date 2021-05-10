CREATE FUNCTION "registra_pago" ("fechapago" timestamp without time zone, "importepago" double precision, "movimiento" integer, "banco" integer, "cheque" character, "depositoen" integer, "poliza" character, "importebase" double precision, "idempresa" integer, "aniofiscal" integer, "idcompra" integer, "idproveedor" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int := 0;

BEGIN

INSERT INTO "PAGOS" ("FECHA_PAGO",
"IMPORTE_PAGO","ID_MOVIMIENTO",
"ID_BANCO","CHEQUE",
"DEPOSITO","POLIZA",
"IMPORTE_BASE","ID_EMPRESA","ANIO_FISCAL","ID_COMPRA","ID_PROVEEDOR")
VALUES(fechapago,importepago,movimiento,banco,cheque,depositoen,poliza,importebase,idempresa,aniofiscal,idcompra,idproveedor);

WITH U as ( UPDATE "COMPRAS" SET "SALDO" = "SALDO" - importepago WHERE "ID_COMPRA" = idcompra RETURNING *)
SELECT COUNT(*) FROM U INTO x;
return x;
END
';