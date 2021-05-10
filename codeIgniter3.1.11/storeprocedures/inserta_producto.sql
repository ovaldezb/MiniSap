CREATE FUNCTION "inserta_producto" ("codigo" text, "descripcion" text, "stock" integer, "linea" character, "unidadmedida" character, "precioventa" double precision, "preciocosto" double precision, "moneda" integer, "iva" double precision, "idempresa" integer, "idsucursal" integer, "ultact" timestamp without time zone, "codigosat" character, "unidadsat" character) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
idlinea int :=0;
x int :=0;
clave_usr char(10) :='''';
BEGIN
IF EXISTS (SELECT 1 FROM "LINEA" WHERE "NOMBRE" = linea AND "ID_EMPRESA" = idempresa) THEN
  SELECT "ID_LINEA" FROM "LINEA" WHERE "NOMBRE" = linea AND "ID_EMPRESA" = idempresa into idlinea;
ELSE
  INSERT INTO "LINEA" ("NOMBRE","ID_EMPRESA","ACTIVO") 
   VALUES(linea,idempresa,true);
  SELECT "last_value" FROM "VENTAS_ID_VENTA_seq" into idlinea;
END IF;

INSERT INTO "PRODUCTO"("CODIGO", "DESCRIPCION", "ID_LINEA", 
"UNIDAD_MEDIDA", "PRECIO_LISTA", "PRECIO_COMPRA","ULTIMA_ACTUALIZACION",
"ID_IEPS","ES_PROMO", "TASA_EXENTA","NOTAS","ES_EQUIVALENTE","ES_DESCUENTO",
"ID_MONEDA", "IVA","ID_EMPRESA",
"TIPO_PS","ACTIVO","COD_CFDI","UNIDAD_SAT")
VALUES (codigo,descripcion,idlinea,
unidadmedida,precioventa,preciocosto,ultact,
1,false,false,'''',false,false,
moneda,iva,idempresa,
''P'',true,codigosat,unidadsat);

SELECT "last_value" FROM "PRODUCTO_ID_PRODUCTO_seq" into x;

INSERT INTO "PRODUCTO_SUCURSAL" ("ID_PRODUCTO","ID_SUCURSAL","STOCK")
VALUES (x,idsucursal,stock);

return x;
END
';