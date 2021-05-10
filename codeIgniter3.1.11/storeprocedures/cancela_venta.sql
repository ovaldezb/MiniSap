CREATE FUNCTION "cancela_venta" ("idventa" integer) RETURNS bool LANGUAGE plpgsql AS '
DECLARE
producto RECORD;
id_sucursal int := 0;
prods cursor(_idventa INTEGER) 
     FOR SELECT "CANTIDAD", "ID_PRODUCTO"
         FROM "VENTAS_PRODUCTO" 
         WHERE "ID_VENTA" = _idventa;


BEGIN

SELECT "ID_SUC_VENDIO" FROM "VENTAS" WHERE "ID_VENTA" = idventa into id_sucursal;

OPEN prods(idventa);
  LOOP
    FETCH prods INTO producto;
    EXIT WHEN NOT FOUND;
    UPDATE "PRODUCTO_SUCURSAL" SET "STOCK" = "STOCK" + producto."CANTIDAD" 
    WHERE "ID_PRODUCTO" = producto."ID_PRODUCTO" AND "ID_SUCURSAL" = id_sucursal;
  END LOOP;
  CLOSE prods;

UPDATE "VENTAS" SET "CANCELADO" = true WHERE "ID_VENTA" = idventa;
return true;
END';