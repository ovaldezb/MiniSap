<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuariomodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_usuario_by_id($idusuario)
	{
		$query = 'SELECT TRIM("NOMBRE") as "NOMBRE",TRIM("CLAVE_USR") as "CLAVE_USR","ID_SUCURSAL" FROM "USUARIO" WHERE "ID_USUARIO"=$1';
		pg_prepare($this->conn,"select_usrbyid",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_usrbyid",array($idusuario)));
		return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
	}

  function get_procesos_by_usuario($idusuario)
  {
    $query = 'SELECT X."ID_MODULO",X."NOMBRE",X."ID_PROCESO",M."NOMBRE" as "MODULO",
							CASE UP."P" WHEN true THEN \'true\' ELSE \'false\' END as "P",
							CASE UP."A" WHEN true THEN \'true\' ELSE \'false\' END as "A",
							CASE UP."B" WHEN true THEN \'true\' ELSE \'false\' END as "B",
							CASE UP."M" WHEN true THEN \'true\' ELSE \'false\' END as "M",
							CASE UP."C" WHEN true THEN \'true\' ELSE \'false\' END as "C"
							FROM
							(
								SELECT U."ID_MODULO" as "ID_MODULO", U."ID_USUARIO",
											P."NOMBRE" as "NOMBRE",
											P."ID_PROCESO" as "ID_PROCESO"
								FROM "USUARIO_MODULO" as U
								INNER JOIN "PROCESOS" as P
								ON U."ID_MODULO" = P."ID_MODULO"
								WHERE U."ID_USUARIO" = $1
							) as X
							LEFT JOIN "USUARIO_PROCESO" as UP
							ON X."ID_PROCESO" = UP."ID_PROCESO" AND X."ID_USUARIO" = UP."ID_USUARIO"
							INNER JOIN "MODULOS" as M ON M."ID_MODULO" = X."ID_MODULO"
							ORDER BY M."ID_MODULO"';
		pg_prepare($this->conn,"select_procusr",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_procusr",array($idusuario)));
		return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
  }

	function get_all_modulos_by_usuario($idusuario)
	{
		$query = 'SELECT M."ID_MODULO",M."NOMBRE",
							CASE U."PERMITIDO" WHEN true THEN \'true\' ELSE \'false\' END as "PERMITIDO"
							FROM  "MODULOS" as M
							LEFT JOIN
							(SELECT "ID_USUARIO","ID_MODULO","PERMITIDO"
							FROM "USUARIO_MODULO"
							WHERE "ID_USUARIO" = $1) as U
							ON U."ID_MODULO" = M."ID_MODULO"';
		pg_prepare($this->conn,"select_allmdlsuser",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_allmdlsuser",array($idusuario)));
		return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
	}

	function get_all_empr_nuevo()
	{
		$query = 'SELECT "ID_EMPRESA", "NOMBRE", \'false\' as "PERMITIDO"
						FROM "EMPRESA" WHERE "ACTIVO" = true';
		$result = pg_fetch_all(pg_query($this->conn,$query));
		return json_encode($result);
	}

	function get_empperm_by_usuario($usuario)
	{
		$query = 'SELECT E."ID_EMPRESA",TRIM(E."NOMBRE") as "NOMBRE",
							CASE U."PERMITIDO" WHEN true THEN \'true\' ELSE \'false\' END as "PERMITIDO"
							FROM  "EMPRESA" as E
							LEFT JOIN
							(SELECT "ID_EMPRESA","PERMITIDO"
							FROM "USUARIO_EMPRESA"
							WHERE "ID_USUARIO" = $1) as U
							ON E."ID_EMPRESA" = U."ID_EMPRESA"
							WHERE "ACTIVO" = true';
		pg_prepare($this->conn,"select_useremp",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_useremp",array($usuario)));
		return json_encode($result);
	}

	function get_all_modulos_nuevo()
	{
		$query = 'SELECT "ID_MODULO", "NOMBRE", \'false\' as "PERMITIDO"
						FROM "MODULOS" ';
		$result = pg_fetch_all(pg_query($this->conn,$query));
		return json_encode($result);
	}

	function crea_usuario($nombre,$usrname,$paswd,$idsucursal)
	{
		$query = 'SELECT * FROM crea_usuario($1,$2,$3,$4)';
		pg_prepare($this->conn,"creaquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "creaquery", array($nombre,$usrname,$paswd,$idsucursal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function inserta_modulo_by_user($idusuario,$idmodulo)
	{
		$query = 'INSERT INTO "USUARIO_MODULO" ("ID_USUARIO","ID_MODULO","PERMITIDO")
							VALUES($1,$2,$3)';
		pg_prepare($this->conn,"insert_modulo",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "insert_modulo", array($idusuario,$idmodulo,true)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function inserta_empperm_by_user($idusuario,$idempresa)
	{
		/*$query = 'INSERT INTO "USUARIO_EMPRESA" ("ID_USUARIO","ID_EMPRESA","PERMITIDO")
							VALUES($1,$2,$3)';*/
		$query = 'SELECT * FROM insert_emp_perm($1,$2)';
		pg_prepare($this->conn,"insert_empperm",$query);
		$result = pg_execute($this->conn, "insert_empperm", array($idusuario,$idempresa));
		return json_encode($result);
	}

	function get_ususarios()
	{
		$query = 'SELECT "ID_USUARIO","NOMBRE","CLAVE_USR","ID_SUCURSAL" FROM "USUARIO" WHERE "ACTIVO" = true ORDER BY "ID_USUARIO"';
		$result = pg_fetch_all(pg_query($this->conn,$query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	/*Obtiene unicamente los modulos a los que tiene acceso el usuario*/
	function get_modulos_by_ususario($idusuario)
	{
		$query = 'SELECT U."ID_MODULO", M."NOMBRE"
							FROM "USUARIO_MODULO" as U INNER JOIN "MODULOS" as M ON U."ID_MODULO" = M."ID_MODULO"
							WHERE U."ID_USUARIO" = $1';
		pg_prepare($this->conn,"selectusrmdl",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "selectusrmdl", array($idusuario)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function update_usuario($idusuario,$nombre,$usrname,$paswd,$updtpwd)
	{
		$query = 'SELECT * FROM actualiza_usuario($1,$2,$3,$4,$5)';
		pg_prepare($this->conn,"updt_user",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "updt_user", array($idusuario,$nombre,$usrname,$paswd,$updtpwd)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function elimina_modulosperm_by_usuario($idusuario)
	{
		$query = 'DELETE FROM "USUARIO_MODULO" WHERE "ID_USUARIO" = $1';
		pg_prepare($this->conn,"del_modperm_user",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "del_modperm_user", array($idusuario)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function elimina_empperm_by_usuario($idusuario)
	{
		$query = 'DELETE FROM "USUARIO_EMPRESA" WHERE "ID_USUARIO" = $1';
		pg_prepare($this->conn,"del_empperm_user",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "del_empperm_user", array($idusuario)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function elimina_procesos_by_usuario($idusuario)
	{
		$query = 'DELETE FROM "USUARIO_PROCESO" WHERE "ID_USUARIO" = $1';
		pg_prepare($this->conn,"del_proc_by_user",$query);
		$result = pg_execute($this->conn, "del_proc_by_user", array($idusuario));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function inserta_proceso_by_usuario($idusuario,$idproceso,$p,$a,$b,$m,$c)
	{
		$query = 'INSERT INTO "USUARIO_PROCESO" ("ID_USUARIO","ID_PROCESO","P","A","B","M","C")
							VALUES($1,$2,$3,$4,$5,$6,$7)';
		pg_prepare($this->conn,"insert_proc_usr",$query);
		$result = pg_execute($this->conn, "insert_proc_usr", array($idusuario,$idproceso,$p,$a,$b,$m,$c));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function elimina_usuario($idusuario)
	{
		$query = 'SELECT * FROM eliminar_usuario($1)';
		pg_prepare($this->conn,"del_user",$query);
		$result = pg_execute($this->conn, "del_user", array($idusuario));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

}

?>
