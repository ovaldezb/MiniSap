CREATE FUNCTION "crea_producto" ("codigo" character, "nombre" text, "linea" integer, "unidadmedida" character, "esequiv" boolean, "equivalencia" text, "codigocfdi" character, "unidad_sat" character, "preciolista" double precision, "ultact" timestamp without time zone, "moneda" integer, "iva" double precision, "id_ieps" integer, "ieps" double precision, "enpromo" boolean, "preciopromo" double precision, "esdescnt" boolean, "preciodescnt" double precision, "maxstock" integer, "minstock" integer, "estasaexenta" boolean, "notas" text, "img" text, "idempresa" integer, "idsucursal" integer, "tipo_ps" character) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int :=0;
BEGIN

INSERT INTO "PRODUCTO" ("CODIGO", "DESCRIPCION", "ID_LINEA", "UNIDAD_MEDIDA", "ES_EQUIVALENTE", "EQUIVALENCIA",
"COD_CFDI", "UNIDAD_SAT", "PRECIO_LISTA", "ULTIMA_ACTUALIZACION", "ID_MONEDA", "IVA", "ID_IEPS", "IEPS",	
"ES_PROMO", "PRECIO_PROMO", "ES_DESCUENTO", "PRECIO_DESCUENTO", "MAX_STOCK", 
"MIN_STOCk", "TASA_EXENTA", "NOTAS", "IMAGEN","ID_EMPRESA","TIPO_PS","ACTIVO")
VALUES (codigo,nombre,linea,unidadmedida,esequiv,equivalencia,codigocfdi,
unidad_sat,preciolista,ultact,moneda,iva,id_ieps,ieps,enpromo,preciopromo,esdescnt,
preciodescnt,maxstock,minstock,estasaexenta,notas,img,idempresa,tipo_ps,true);

SELECT "last_value" FROM "PRODUCTO_ID_PRODUCTO_seq" into x;

INSERT INTO "PRODUCTO_SUCURSAL" ("ID_PRODUCTO","ID_SUCURSAL","STOCK")
VALUES (x,idsucursal,0);

return x;

END



';