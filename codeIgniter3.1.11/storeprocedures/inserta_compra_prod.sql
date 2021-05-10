CREATE FUNCTION "inserta_comp_prod" ("idcompra" integer, "idproducto" integer, "cantidad" double precision, "unidad_medida" character, "precio_unit" double precision, "importe_total" double precision, "dsctoprod" double precision, "idsucursal" integer, "documento" character, "caja" integer, "idempresa" integer, "aniofiscal" integer, "idcliente" integer, "idproveedor" integer, "idusuario" integer, "idmoneda" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int :=0;
codigo char(15) :='''';
qty_stock int :=0;
BEGIN

SELECT "STOCK" FROM "PRODUCTO_SUCURSAL" WHERE "ID_PRODUCTO" = idproducto AND "ID_SUCURSAL" = idsucursal into qty_stock;

INSERT INTO "COMPRA_PRODUCTO" ("ID_COMPRA","ID_PRODUCTO","CANTIDAD","UNIDAD","PRECIO_UNITARIO","IMPORTE_TOTAL","DSCTOPROD","CANTIDAD_STOCK")
VALUES (idcompra,idproducto,cantidad,unidad_medida,precio_unit,importe_total,dsctoprod,qty_stock + cantidad);

IF EXISTS (SELECT 1 FROM "PRODUCTO_SUCURSAL" WHERE "ID_PRODUCTO" = idproducto AND "ID_SUCURSAL" = idsucursal) THEN
  WITH U as (
  UPDATE "PRODUCTO_SUCURSAL" SET "STOCK" = "STOCK" + cantidad 
  WHERE "ID_PRODUCTO" = idproducto 
  AND "ID_SUCURSAL" = idsucursal RETURNING *)
  SELECT COUNT(*) FROM U INTO x;
  
ELSE
  WITH I as (
  INSERT INTO "PRODUCTO_SUCURSAL" ("ID_PRODUCTO","ID_SUCURSAL","STOCK")
  VALUES(idproducto,idsucursal,cantidad) RETURNING *)
  SELECT COUNT(*) FROM I INTO x;
END IF;

UPDATE "PRODUCTO" SET "PRECIO_COMPRA" = precio_unit 
WHERE "ID_PRODUCTO" = idproducto;

SELECT "CODIGO" FROM "PRODUCTO" WHERE "ID_PRODUCTO" = idproducto into codigo;

INSERT INTO "INVENTARIO" ("FECHA","DOCUMENTO","MOV","IN","OUT","PREC_UNIT","IMPORTE","CODIGO","ID_EMPRESA",
             "ANIO_FISCAL","CAJA","ID_CLIENTE","ID_PROVEEDOR","ID_USUARIO","ID_MONEDA","ID_SUCURSAL")
VALUES(CURRENT_TIMESTAMP,documento,''COM'',cantidad,0,precio_unit,cantidad*precio_unit,codigo,idempresa,aniofiscal,caja,
        idcliente,idproveedor,idusuario,idmoneda,idsucursal);

return x;

END';