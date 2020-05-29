<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accessmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

  function get_credenciales($user)
  {
    $query = 'SELECT "ID_USUARIO",
		TRIM("PASSWORD") as "PASSWORD", TRIM("NOMBRE") as "NOMBRE"
		FROM "USUARIO" WHERE "CLAVE_USR" = $1';
    pg_prepare($this->conn,"valida_user",$query);
    $result = pg_fetch_array(pg_execute($this->conn, "valida_user", array($user)));
		return $result;
  }

	function get_idSucursal_by_usuario($idusuario,$idempresa)
	{
		$query = 'SELECT "ID_SUCURSAL" FROM "USUARIO_EMPRESA" WHERE "ID_USUARIO" = $1 AND "ID_EMPRESA" = $2';
		pg_prepare($this->conn,"select_suc",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_suc",array($idusuario,$idempresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

}
