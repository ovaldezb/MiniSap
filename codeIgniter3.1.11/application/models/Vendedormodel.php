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

  function get_vendedores()
  {
    $query = 'SELECT * FROM "VENDEDOR" ORDER BY "NOMBRE"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
  }

  function get_vendedores_by_nombre($nombre)
  {
    $query = 'SELECT * FROM "VENDEDOR" WHERE UPPER("NOMBRE") LIKE UPPER($1)';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($nombre)));
		return json_encode($result);
  }
}

?>
