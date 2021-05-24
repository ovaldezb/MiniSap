<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transferenciamodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}


  public function save_transfer($arrayData){
    $query = 'SELECT * FROM registra_transferencia($1,$2,$3,$4,$5,$6,$7,$8)';
    pg_prepare($this->conn,"insert_transpaso",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"insert_transpaso",$arrayData));
    return json_encode($result[0]);
  }

  public function get_transf_by_emp_fy($idempresa, $fiscalyear, $idsucursal){
    $query = 'SELECT S."ALIAS" as "ORIGEN",S1."ALIAS" as "DESTINO", P."DESCRIPCION",U."CLAVE_USR",T."CANTIDAD",T."FECHA_TRANSPASO"
    FROM "TRANSPASO" AS T
    INNER JOIN "SUCURSALES" AS S ON S."ID_SUCURSAL" = T."ID_SUCURSAL_ORIGEN" 
    INNER JOIN "SUCURSALES" AS S1 ON S1."ID_SUCURSAL" = T."ID_SUCURSAL_DESTINO" 
    INNER JOIN "PRODUCTO" AS P ON P."ID_PRODUCTO" = T."ID_PRODUCTO"
    INNER JOIN "USUARIO" AS U ON U."ID_USUARIO" = T."ID_USUARIO"
    WHERE T."ID_EMPRESA" = $1
    AND T."ANIO_FISCAL" = $2 
    AND T."ID_SUCURSAL_ORIGEN" = $3
    ORDER BY T."FECHA_TRANSPASO" DESC';
    pg_prepare($this->conn,"select_transfer",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_transfer",array($idempresa, $fiscalyear, $idsucursal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

}
?>