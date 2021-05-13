CREATE FUNCTION "registra_factura" ("docto" character, "ffactura" timestamp without time zone, "idcliente" integer, "importe" double precision, "saldo" double precision, "idtipopago" integer, "frevision" timestamp without time zone, "fvencimiento" timestamp without time zone, "idvendedor" integer, "idempresa" integer, "aniofiscal" integer, "idsucursal" integer, "idformapago" integer, "idusocfdi" integer, "idmetodopago" integer, "contacto" text, "idmoneda" smallint, "idpedido" integer, "cliente" text, "idusuario" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

ult_val int :=0;

doctoinsert character(10) :='''';

BEGIN

IF EXISTS (SELECT 1 FROM "FACTURA" WHERE "DOCUMENTO" = docto AND "ID_EMPRESA" = idempresa) THEN
SELECT TRIM("PREFIJO")||TRIM(to_char("VALOR",''0000000''))
FROM "INCREMENTOS" WHERE "NOMBRE_INC" = ''FACT'' AND "ID_EMPRESA" = idempresa  into doctoinsert;
ELSE
doctoinsert = docto;
END IF;

INSERT INTO "FACTURA" ("DOCUMENTO","FECHA_FACTURA","ID_CLIENTE","IMPORTE","SALDO","ID_TIPO_PAGO",
"FECHA_REVISION","FECHA_VENCIMIENTO",
"ID_VENDEDOR","ID_EMPRESA",
"ANIO_FISCAL","ID_SUCURSAL", 
"ID_FORMA_PAGO","ID_USO_CFDI",
"ID_METODO_PAGO","CONTACTO",
"ID_MONEDA","ID_PEDIDO","CLIENTE","ID_USUARIO") 
VALUES(doctoinsert,ffactura,idcliente,importe,saldo,idtipopago,frevision,
fvencimiento,idvendedor,idempresa,aniofiscal,idsucursal,idformapago,
idusocfdi,idmetodopago,contacto,idmoneda,idpedido,cliente,idusuario);


UPDATE "INCREMENTOS" SET "VALOR" = "VALOR" + 1 
WHERE "NOMBRE_INC" = ''FACT'' AND "ID_EMPRESA" = idempresa;


SELECT "last_value" 
FROM "FACTURA_ID_FACTURA_seq" into ult_val;

return ult_val;

END';