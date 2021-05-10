CREATE FUNCTION "registra_pedido" ("docto" character, "id_clte" integer, "id_vend" integer, "fec_pedido" timestamp without time zone, "anio_fisc" integer, "id_empresa" integer, "importe" double precision, "idsucursal" integer, "fpago" integer, "tpago" integer, "comentarios" character, "cuenta" character, "dias" numeric, "id_moneda" integer, "fecha_entrega" timestamp without time zone, "vendido" boolean, "domicilio" text, "idmetpago" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

ult_val int :=0;

doctoinsert character(10) :='''';

BEGIN

IF EXISTS (SELECT 1 FROM "PEDIDOS" WHERE "DOCUMENTO" = docto AND "ID_EMPRESA" = id_empresa) THEN
SELECT TRIM("PREFIJO")||TRIM(to_char("VALOR",''0000000''))
FROM "INCREMENTOS" WHERE "NOMBRE_INC" = ''PEDI'' AND "ID_EMPRESA" = id_empresa  into doctoinsert;
ELSE
doctoinsert = docto;
END IF;

INSERT INTO "PEDIDOS" ("DOCUMENTO","ID_CLIENTE","ID_VENDEDOR","FECHA_PEDIDO",
"ANIO_FISCAL","ID_EMPRESA","IMPORTE","ID_SUC_PIDIO","VENDIDO","ID_MONEDA","ID_TIPO_PAGO","DIAS","FECHA_ENTREGA",
"ID_FORMA_PAGO","CUENTA","COMENTARIOS","DOMICILIO","ID_METODO_PAGO","ESTATUS") 
VALUES(doctoinsert,id_clte,id_vend,fec_pedido,anio_fisc,id_empresa,importe,idsucursal,vendido,
id_moneda,tpago,dias,fecha_entrega,fpago,cuenta,comentarios,domicilio,idmetpago,''ACTIVO'');


UPDATE "INCREMENTOS" SET "VALOR" = "VALOR" + 1 
WHERE "NOMBRE_INC" = ''PEDI'' AND "ID_EMPRESA" = id_empresa;


SELECT "last_value" 
FROM "PEDIDOS_ID_PEDIDO_seq" into ult_val;

return ult_val;

END';