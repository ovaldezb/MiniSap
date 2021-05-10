CREATE FUNCTION "inserta_compra" ("documento" character, "claveprov" character, "fec_comp" timestamp without time zone, "tipo_pago" integer, "moneda" integer, "tipo_cambio" double precision, "contra_rec" text, "fec_pago" timestamp without time zone, "fec_rev" timestamp without time zone, "id_empresa" integer, "docprev" character, "diascred" integer, "importe" double precision, "iva" double precision, "anio_fiscal" integer, "descuento" double precision, "idsucursal" integer, "idproveedor" integer, "notas" text, "saldo" double precision) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

x int :=0;
docinsert character(10) :='''';

BEGIN


IF EXISTS (SELECT 1 FROM "COMPRAS" WHERE "DOCUMENTO" = documento AND "ID_EMPRESA" = id_empresa ) THEN
SELECT TRIM("PREFIJO")||TRIM(to_char("VALOR",''00000''))
FROM "INCREMENTOS" WHERE "NOMBRE_INC" = ''CMPR'' AND "ID_EMPRESA" = id_empresa into docinsert ;
ELSE
docinsert = documento;
END IF;


INSERT INTO "COMPRAS" 
("DOCUMENTO","CLAVE_PROVEEDOR","FECHA_COMPRA","ID_TIPO_PAGO",
"ID_MONEDA","TIPO_CAMBIO","CONTRA_RECIBO",
"FECHA_PAGO","FECHA_REVISION",
"ID_EMPRESA","TIPO_ORDENCOMPRA",
"DIAS_PAGO","IMPORTE", "IVA",
"ANIO_FISCAL", "DESCUENTO","ID_SUC_COMPRO","ID_PROVEEDOR","NOTAS","SALDO")
VALUES (docinsert,claveprov,fec_comp,tipo_pago,moneda,
tipo_cambio,contra_rec,fec_pago,fec_rev,id_empresa,
docprev,diascred,importe,iva,anio_fiscal,descuento,idsucursal,idproveedor,notas,saldo);

UPDATE "INCREMENTOS" SET "VALOR" = "VALOR" + 1 
WHERE "NOMBRE_INC" = ''CMPR'' AND "ID_EMPRESA" = id_empresa;

SELECT "last_value" FROM "COMPRAS_ID_COMPRA_seq" into x;

return x;

END';