<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedormodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

  function get_vendedores($idempresa)
  {
    $query = 'SELECT * FROM "VENDEDOR" WHERE "ID_EMPRESA" = $1 ORDER BY "NOMBRE"';
		pg_prepare($this->conn, "select_vendedor",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_vendedor",array($idempresa)));
		return json_encode($result);
  }

  function get_vendedores_by_nombre($idempresa,$nombre)
  {
    $query = 'SELECT * FROM "VENDEDOR" WHERE UPPER("NOMBRE") LIKE UPPER($1) AND "ID_EMPRESA" = $2';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($nombre,$idempresa)));
		return json_encode($result);
  }
}

?>
