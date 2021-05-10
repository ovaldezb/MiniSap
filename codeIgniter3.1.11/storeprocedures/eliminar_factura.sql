CREATE FUNCTION "eliminar_factura" ("idfactura" integer, "idsucursal" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int := 0;
y int := 0;
producto RECORD;
idventa int := 0;
prods cursor(_idventa INTEGER) 
     FOR SELECT "CANTIDAD", "ID_PRODUCTO"
         FROM "VENTAS_PRODUCTO" 
         WHERE "ID_VENTA" = _idventa;
BEGIN

SELECT "ID_VENTA" FROM "VENTAS" WHERE "ID_FACTURA" = idfactura into idventa;

  OPEN prods(idventa);
  LOOP
    FETCH prods INTO producto;
    EXIT WHEN NOT FOUND;
    UPDATE "PRODUCTO_SUCURSAL" SET "STOCK" = "STOCK" + producto."CANTIDAD" 
    WHERE "ID_PRODUCTO" = producto."ID_PRODUCTO" AND "ID_SUCURSAL" = idsucursal;
  END LOOP;
  CLOSE prods;

  WITH VP as (
  DELETE FROM "VENTAS_PRODUCTO" WHERE "ID_VENTA" = idventa RETURNING *)
  SELECT COUNT(*) FROM VP INTO y;

  WITH V as( DELETE FROM "VENTAS" WHERE "ID_VENTA" = idventa RETURNING *)
  SELECT COUNT(*) from V INTO x;
 
  UPDATE "FACTURA" SET "ESTATUS" = ''CANCELADA'' WHERE "ID_FACTURA" = idfactura;


  return x + y;

END
';