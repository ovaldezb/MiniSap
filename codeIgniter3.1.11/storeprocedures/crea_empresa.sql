CREATE FUNCTION "crea_empresa" ("nombre" character, "domicilio" text, "rfc" character, "cp" numeric, "ejercicio_fiscal" character, "id_regimen" integer, "digxcuenta" character, "cta_res" integer, "res_ant" integer) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE
x int :=0;

BEGIN

INSERT INTO "EMPRESA" 
("NOMBRE", "DOMICILIO","RFC","CP","ID_REGIMEN","DIGITO_X_CUENTA",
"CUENTA_RESULTADO", "RESULTADO_ANTERIOR","ACTIVO") 
VALUES(nombre,domicilio,rfc,cp,id_regimen,digxcuenta,cta_res,res_ant,true);


SELECT "last_value" FROM "EMPRESA_ID_EMPRESA_seq" into x;
INSERT INTO "EMP_EJER_FISC" 
("ID_EMPRESA", "EJER_FISC") 
VALUES(x,ejercicio_fiscal);

INSERT INTO "INCREMENTOS" ("ID_EMPRESA","NOMBRE_INC","VALOR","PREFIJO") VALUES(x,''TPVS'',1,''1'');
INSERT INTO "INCREMENTOS" ("ID_EMPRESA","NOMBRE_INC","VALOR","PREFIJO") VALUES(x,''CLTE'',1,''C'');
INSERT INTO "INCREMENTOS" ("ID_EMPRESA","NOMBRE_INC","VALOR","PREFIJO") VALUES(x,''PROV'',1,''P'');
INSERT INTO "INCREMENTOS" ("ID_EMPRESA","NOMBRE_INC","VALOR","PREFIJO") VALUES(x,''CMPR'',1,'''');
INSERT INTO "INCREMENTOS" ("ID_EMPRESA","NOMBRE_INC","VALOR","PREFIJO") VALUES(x,''FACT'',1,'''');
INSERT INTO "INCREMENTOS" ("ID_EMPRESA","NOMBRE_INC","VALOR","PREFIJO") VALUES(x,''PEDI'',1,'''');
INSERT INTO "SUCURSALES" ("CLAVE","ALIAS","ID_EMPRESA","ACTIVO") VALUES(''001'',''MATRIZ'',x,true);
INSERT INTO "CLIENTE" ("CLAVE","NOMBRE","ID_FORMA_PAGO","ID_USO_CFDI","ID_EMPRESA","ACTIVO")
VALUES(''0001'',''VENTAS MOSTRADOR'',0,0,x,true);
return x;
END





';