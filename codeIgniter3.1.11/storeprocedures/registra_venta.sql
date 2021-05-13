CREATE FUNCTION "registra_venta" ("docto" character, "cod_clte" integer, "cod_vend" integer, "fec_venta" timestamp without time zone, "anio_fisc" integer, "id_empresa" integer, "id_tipo_pago" integer, "pag_efct" double precision, "pag_tjta" double precision, "pag_chqe" double precision, "pag_vales" double precision, "id_tjta" integer, "id_banc" integer, "id_vales" integer, "importe" double precision, "cambio" double precision, "idsucursal" integer, "facturado" boolean, "idfactura" integer, "origen" character, "iva" double precision, "idusuario" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

ult_val int :=0;

doctoinsert character(10) :='''';

BEGIN

IF EXISTS (SELECT 1 FROM "VENTAS" WHERE "DOCUMENTO" = docto AND "ID_EMPRESA" = id_empresa) THEN
SELECT TRIM("PREFIJO")||TRIM(to_char("VALOR",''0000000''))
FROM "INCREMENTOS" WHERE "NOMBRE_INC" = ''TPVS'' AND "ID_EMPRESA" = id_empresa  into doctoinsert;
ELSE
doctoinsert = docto;
END IF;

INSERT INTO "VENTAS" ("DOCUMENTO","CODIGO_CLIENTE",
"CODIGO_VENDEDOR","FECHA_VENTA",
"ANIO_FISCAL","ID_EMPRESA","ID_TIPO_PAGO","PAG_EFECTIVO",
"PAG_TARJETA","PAG_CHEQUE","PAG_VALES","ID_TARJETA",
"ID_BANCO","ID_VALES","IMPORTE","CAMBIO","ID_SUC_VENDIO",
"FACTURADO","ID_FACTURA","CANCELADO","CORTECAJA","ORIGEN","IVA","ID_USUARIO") 
VALUES(doctoinsert,cod_clte,cod_vend,fec_venta,anio_fisc,id_empresa,
id_tipo_pago,pag_efct,pag_tjta,pag_chqe,pag_vales,
id_tjta,id_banc,id_vales,importe,cambio,idsucursal,facturado,
idfactura,false,false,origen,iva,idusuario);

IF id_vales IS NOT NULL THEN
UPDATE "INCREMENTOS" SET "VALOR" = "VALOR" + 1 
WHERE "NOMBRE_INC" = ''TPVS'' AND "ID_EMPRESA" = id_empresa;
END IF;

SELECT "last_value" 
FROM "VENTAS_ID_VENTA_seq" into ult_val;

return ult_val;

END';