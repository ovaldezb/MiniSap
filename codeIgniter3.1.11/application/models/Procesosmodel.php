<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procesosmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

  function get_modulos_procesos_by_usuario($idusuario)
  {
    $query = 'SELECT M."NOMBRE" as "MODULO",PR."NOMBRE" as "PROCESO",TRIM(PR."RUTA") as "RUTA"
              FROM "PROCESOS" as PR
              INNER JOIN "USUARIO_PROCESO" as UP ON PR."ID_PROCESO" = UP."ID_PROCESO"
              INNER JOIN "MODULOS" as M ON M."ID_MODULO" = PR."ID_MODULO"
              WHERE UP."ID_USUARIO" = $1
              AND PR."RUTA" IS NOT NULL ';
    pg_prepare($this->conn,"sel_mod_proc_user",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"sel_mod_proc_user",array($idusuario)));
		return $result;
  }

}
