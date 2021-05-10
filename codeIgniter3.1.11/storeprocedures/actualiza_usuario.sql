CREATE FUNCTION "actualiza_usuario" ("idusuario" integer, "nombre" character, "usrname" character, "paswd" character, "updtpwd" boolean) RETURNS int4 LANGUAGE plpgsql AS '
DECLARE

x char(20) :='''';

clave_usr char(10) :='''';

BEGIN

SELECT TRIM("CLAVE_USR") FROM "USUARIO" WHERE "ID_USUARIO" = idusuario into clave_usr;

IF clave_usr = usrname THEN

  IF updtpwd = true THEN

      UPDATE "USUARIO" SET "NOMBRE" = nombre, "PASSWORD" = paswd WHERE "ID_USUARIO" = idusuario;

      x := ''1'';

  ELSE

      UPDATE "USUARIO" SET "NOMBRE" = nombre WHERE "ID_USUARIO" = idusuario;

     x := ''2'';

  END IF;  

ELSE

  IF EXISTS(SELECT 1 FROM "USUARIO" WHERE "CLAVE_USR" = usrname ) THEN

    x := ''-1'';

  ELSE

    IF updtpwd = true THEN

      UPDATE "USUARIO" SET "NOMBRE" = nombre, "CLAVE_USR" = usrname,"PASSWORD" = paswd WHERE "ID_USUARIO" = idusuario;

      x := ''3'';

    ELSE

      UPDATE "USUARIO" SET "NOMBRE" = ''nombre'', "CLAVE_USR" = usrname WHERE "ID_USUARIO" = idusuario;

      x := 41;

    END IF;

  END IF;

END IF;

return x;

END

';