CREATE FUNCTION "crea_cliente" ("clave" character, "nombre" text, "domicilio" character, "cp" character, "telefono" character, "contacto" text, "rfc" character, "curp" character, "id_tipo_cliente" integer, "revision" integer, "pagos" integer, "id_forma_pago" integer, "id_vendedor" integer, "id_uso_cfdi" integer, "email" character, "notas" text, "diascred" integer, "id_empresa" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
ret int :=0;
clveinsert char(10) :='''';
BEGIN

IF EXISTS (SELECT 1 FROM "CLIENTE" WHERE "CLAVE" = clave AND "ID_EMPRESA" = id_empresa ) THEN
SELECT TRIM("PREFIJO")||TRIM(to_char("VALOR",''0000''))
FROM "INCREMENTOS" WHERE "NOMBRE_INC" = ''CLTE'' AND "ID_EMPRESA" = id_empresa into clveinsert ;
ELSE
clveinsert = clave;
END IF;

INSERT INTO "CLIENTE" ("CLAVE","NOMBRE","DOMICILIO","CP",
"TELEFONO","CONTACTO","RFC","CURP","ID_TIPO_CLIENTE",
"ID_REVISION","ID_PAGOS","ID_FORMA_PAGO","ID_VENDEDOR",
"ID_USO_CFDI","EMAIL", "NOTAS","DIAS_CREDITO","ACTIVO","ID_EMPRESA") 
VALUES(clveinsert,nombre,domicilio,cp,telefono,contacto,rfc,curp,
id_tipo_cliente,revision,pagos,id_forma_pago,id_vendedor,
id_uso_cfdi,email,notas,diascred,true,id_empresa);


UPDATE "INCREMENTOS" SET "VALOR" = "VALOR" + 1 
WHERE "NOMBRE_INC" = ''CLTE'' AND "ID_EMPRESA" = id_empresa;

SELECT "last_value" FROM "CLIENTE_ID_CLIENTE_seq" into ret;

return ret;

END 
';