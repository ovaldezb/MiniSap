<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lineamodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
    }
	
	
    function read($idEmpresa){
        $query = 'SELECT "ID_LINEA","ID_EMPRESA",TRIM("NOMBRE") as "NOMBRE" FROM "LINEA" WHERE "ID_EMPRESA" = $1 AND "ACTIVO" = true ORDER BY "ID_LINEA"';
		pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($idEmpresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);		
	}
	
	function readById($idEmpresa,$idLinea){
        $query = 'SELECT "ID_LINEA","ID_EMPRESA",TRIM("NOMBRE") as "NOMBRE" FROM "LINEA" WHERE "ID_EMPRESA" = $1 AND "ID_LINEA"=$2 AND "ACTIVO" = true';
		pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($idEmpresa,$idLinea)));
		return $result;		
	}

	function insert($data){
		$query = 'INSERT INTO "LINEA" ("NOMBRE","ID_EMPRESA","ACTIVO") VALUES($1,$2,true)';
		pg_prepare($this->conn,"insert_compra",$query);
		$result = pg_execute($this->conn,"insert_compra",array($data["nombre"],$data["idempresa"]));		
		return $result;
	}

	function update($id,$data){
		$query = 'UPDATE "LINEA" SET "NOMBRE" = $1 WHERE "ID_LINEA" = $2';
		pg_prepare($this->conn,"update_linea",$query);
		$result = pg_execute($this->conn,"update_linea",array($data["NOMBRE"],$id));		
		return $result;
	}

	function delete($id){
		$query = 'UPDATE "LINEA" SET "ACTIVO" = false WHERE "ID_LINEA" = $1';
		pg_prepare($this->conn,"delete_linea",$query);
		$result = pg_execute($this->conn,"delete_linea",array($id));		
		return $result;
	}

}