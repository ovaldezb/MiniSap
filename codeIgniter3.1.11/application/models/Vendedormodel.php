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

	function get_vendedores($idempresa){
		$query = 'SELECT * FROM "VENDEDOR" WHERE "ID_EMPRESA" = $1 ORDER BY "NOMBRE"';
		pg_prepare($this->conn, "select_vendedor",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_vendedor",array($idempresa)));
		return json_encode($result); //,JSON_NUMERIC_CHECK
	}

	function get_vendedores_by_nombre($idempresa,$nombre){
		$query = 'SELECT * FROM "VENDEDOR" WHERE UPPER("NOMBRE") LIKE UPPER($1) AND "ID_EMPRESA" = $2';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($nombre,$idempresa)));
		return json_encode($result);
	}

	function crea_vendedor($nombre,$idempresa,$idarea,$idpuesto,$idtitulo)
	{
	   $pstmt = 'SELECT * FROM crea_vendedor($1,$2,$3,$4,$5)';
	   pg_prepare($this->conn,"insertquery",$pstmt);
	   $result = pg_fetch_all(pg_execute($this->conn, "insertquery", array($nombre,$idempresa,$idarea,$idpuesto,$idtitulo)));
	   return json_encode($result);
	}
	   
	function update_vendedor($id_vendedor,$nombre,$idarea,$idpuesto,$idtitulo)
	{
		$result = pg_prepare($this->conn,"updatequery",'UPDATE "CLIENTE" SET
		"NOMBRE"=$1,
		"ID_AREA"=$2,
		"ID_PUESTO"=$3,
		"ID_TITULO"=$4
		WHERE "ID_VENDEDOR"=$5');
		$result = pg_execute($this->conn,"updatequery",array($nombre,$idarea,$idpuesto,$idtitulo,$id_vendedor));
		return $result;
	}

	function delete_vendedor($_idVendedor)
	{
		$query = 'UPDATE "VENDEDOR" SET "ACTIVO" = false WHERE "ID_VENDEDOR" = $1';
		$result = pg_prepare($this->conn,"deletequery",$query);
		$result = pg_execute($this->conn,"deletequery",array($_idVendedor));
		return $result;
	}
	
}

?>
