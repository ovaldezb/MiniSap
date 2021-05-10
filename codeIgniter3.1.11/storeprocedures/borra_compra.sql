CREATE FUNCTION "borra_compra" ("idcompra" integer, "idsucursal" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

x int := 0;
y int := 0;
producto RECORD;
prods cursor(_idcompra INTEGER) 
     FOR SELECT "CANTIDAD", "ID_PRODUCTO"
         FROM "COMPRA_PRODUCTO" 
         WHERE "ID_COMPRA" = _idcompra; 

BEGIN
OPEN prods(idcompra);

LOOP

  FETCH prods INTO producto;
  EXIT WHEN NOT FOUND;
  UPDATE "PRODUCTO_SUCURSAL" SET "STOCK" = "STOCK" - producto."CANTIDAD" 
  WHERE "ID_PRODUCTO" = producto."ID_PRODUCTO" AND "ID_SUCURSAL" = idsucursal;

END LOOP;
CLOSE prods;

WITH CP as (
DELETE FROM "COMPRA_PRODUCTO" WHERE "ID_COMPRA" = idcompra RETURNING *)
SELECT COUNT(*) FROM CP INTO y;

WITH D as( DELETE FROM "COMPRAS" WHERE "ID_COMPRA" = idcompra RETURNING *)
SELECT COUNT(*) from D INTO x;

return x + y;

END';