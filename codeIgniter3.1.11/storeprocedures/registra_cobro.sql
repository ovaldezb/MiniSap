CREATE FUNCTION "registra_cobro" ("fechacobro" timestamp without time zone, "importecobro" double precision, "movimiento" integer, "banco" integer, "cheque" character, "depositoen" integer, "poliza" character, "importebase" double precision, "idempresa" integer, "aniofiscal" integer, "idfactura" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int := 0;

BEGIN

INSERT INTO "COBROS" ("FECHA_COBRO",
"IMPORTE_COBRO","ID_MOVIMIENTO",
"ID_BANCO","CHEQUE",
"DEPOSITO","POLIZA",
"IMPORTE_BASE","ID_EMPRESA","ANIO_FISCAL","ID_FACTURA")
VALUES(fechacobro,importecobro,movimiento,banco,cheque,depositoen,poliza,importebase,idempresa,aniofiscal,idfactura);

WITH U as ( UPDATE "FACTURA" SET "SALDO" = "SALDO" - importecobro WHERE "ID_FACTURA" = idfactura RETURNING *)
SELECT COUNT(*) FROM U INTO x;
return x;
END
';