CREATE FUNCTION "venta_producto" ("idventa" integer, "idproducto" integer, "cantidad" integer, "precio" double precision, "importe" double precision, "idsucursal" integer, "tipo_ps" character, "documento" character, "caja" integer, "idempresa" integer, "aniofiscal" integer, "idcliente" integer, "idproveedor" integer, "idusuario" integer, "idmoneda" integer, "descuento" double precision) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int :=0;
codigo char(15) :='''';
qty_stock int := 0; 
BEGIN

SELECT "STOCK" FROM "PRODUCTO_SUCURSAL" 
WHERE "ID_PRODUCTO" = idProducto AND "ID_SUCURSAL" = idsucursal into qty_stock;


INSERT INTO "VENTAS_PRODUCTO" 
("ID_VENTA","ID_PRODUCTO","CANTIDAD","PRECIO","IMPORTE","DESCUENTO","CANTIDAD_STOCK") 
VALUES(idventa,idproducto,cantidad,precio,importe,descuento,qty_stock-cantidad);

IF(tipo_ps = ''P'') THEN
WITH P as (
UPDATE "PRODUCTO_SUCURSAL" 
SET "STOCK" = "STOCK" - cantidad 
WHERE "ID_PRODUCTO" = idProducto AND "ID_SUCURSAL" = idsucursal RETURNING *)
SELECT COUNT(*) FROM P INTO x;

ELSE
x = 0;
END IF;

SELECT "CODIGO" FROM "PRODUCTO" WHERE "ID_PRODUCTO" = idproducto into codigo;

INSERT INTO "INVENTARIO" ("FECHA","DOCUMENTO","MOV","IN","OUT","PREC_UNIT","IMPORTE","CODIGO","ID_EMPRESA","ANIO_FISCAL","CAJA","ID_CLIENTE","ID_PROVEEDOR","ID_USUARIO","ID_MONEDA","ID_SUCURSAL")
VALUES (CURRENT_TIMESTAMP,documento,''VEN'',0,cantidad,precio,importe,codigo,idempresa,aniofiscal,caja,idcliente,idproveedor,idusuario,idmoneda,idsucursal);
    
return x;

END



';