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
    $query = 'SELECT "ID_USUARIO","ID_SUCURSAL",TRIM("PASSWORD") as "PASSWORD", TRIM("NOMBRE") as "NOMBRE" FROM "USUARIO" WHERE "CLAVE_USR" = $1';
    pg_prepare($this->conn,"valida_user",$query);
    $result = pg_fetch_array(pg_execute($this->conn, "valida_user", array($user)));
		return $result;
  }

}
