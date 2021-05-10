CREATE FUNCTION "inserta_movimiento" ("aniofiscal" integer, "caja" integer, "documento" character, "fecha" timestamp without time zone, "codigo" character, "idproducto" integer, "idcliente" integer, "idempresa" integer, "idmoneda" integer, "idproveedor" integer, "idsucursal" integer, "idusuario" integer, "importe" double precision, "input" double precision, "output" double precision, "preciounit" double precision, "tipomov" character) RETURNS bpchar LANGUAGE plpgsql AS '
DECLARE
estatus char(10) :='''';
BEGIN
  IF EXISTS (SELECT 1 FROM "PRODUCTO_SUCURSAL" WHERE "ID_PRODUCTO" = idproducto AND "ID_SUCURSAL" = idsucursal) THEN
    IF input > 0 THEN
      UPDATE "PRODUCTO_SUCURSAL" SET "STOCK" = "STOCK" + input 
      WHERE "ID_PRODUCTO" = idproducto AND "ID_SUCURSAL" = idsucursal;
    ELSEIF output > 0 THEN
      UPDATE "PRODUCTO_SUCURSAL" SET "STOCK" = "STOCK" - output 
      WHERE "ID_PRODUCTO" = idproducto AND "ID_SUCURSAL" = idsucursal;
    END IF;
    INSERT INTO "INVENTARIO" ("FECHA","DOCUMENTO","MOV","IN","OUT","PREC_UNIT","IMPORTE","CODIGO","ID_EMPRESA","ANIO_FISCAL","CAJA","ID_CLIENTE","ID_PROVEEDOR","ID_USUARIO","ID_MONEDA","ID_SUCURSAL")
    VALUES (fecha,documento,tipomov,input,output,preciounit,importe,codigo,idempresa,aniofiscal,caja,idcliente,idproveedor,idusuario,idmoneda,idsucursal);
    estatus=''success'';
  ELSE
    estatus=''error'';
  END IF;

return estatus;
END


';