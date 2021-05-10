CREATE FUNCTION "crea_proveedor" ("clave" character, "nombre" character, "domicilio" text, "cp" character, "telefono" character, "contacto" character, "rfc" character, "curp" character, "id_tipo_prov" integer, "dias_cred" integer, "id_tipo_alc_prov" integer, "id_banco" integer, "cuenta" character, "email" character, "notas" text, "idempresa" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

x int :=0;

cveprovinsert character(10) :='''';

BEGIN

IF EXISTS (SELECT 1 FROM "PROVEEDORES" WHERE "CLAVE" = clave AND "ID_EMPRESA" = idempresa) THEN



SELECT TRIM("PREFIJO")||TRIM(to_char("VALOR",''0000''))

FROM "INCREMENTOS" WHERE "NOMBRE_INC" = ''PROV'' AND "ID_EMPRESA" = idempresa  into cveprovinsert ;



ELSE



cveprovinsert = clave;



END IF;



INSERT INTO "PROVEEDORES" 

("CLAVE","NOMBRE", "DOMICILIO", "CP",

"TELEFONO", "CONTACTO",

"RFC", "CURP", "ID_CATEGORIA_PROV", 

"DIAS_CRED", "ID_TIPO_ALC_PROV", 

"ID_BANCO", "CUENTA", "EMAIL", 

"NOTAS","ID_EMPRESA","ACTIVO")

VALUES (cveprovinsert,nombre,domicilio,cp,telefono,contacto,rfc,curp,id_tipo_prov,dias_cred,id_tipo_alc_prov,id_banco,cuenta,email,notas,idempresa,true);



UPDATE "INCREMENTOS" SET "VALOR" = "VALOR" + 1 

WHERE "NOMBRE_INC" = ''PROV'' AND "ID_EMPRESA" = idempresa;



SELECT "last_value" FROM "PROVEEDORES_ID_PROVEEDOR_seq" into x;



return x;



END



';