<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursalmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

  function get_sucursales()
  {
    $query='SELECT * FROM "SUCURSALES" ORDER BY "ID_SUCURSAL"';
    $result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
  }

  function create_sucursal($clave,$direccion,$responsable,$telefono,$cp,$alias,$notas,$idempresa)
  {
    $query = 'SELECT * FROM crea_sucursal($1,$2,$3,$4,$5,$6,$7,$8)';
    pg_prepare($this->conn,"insertquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insertquery",array($clave,$direccion,$responsable,$telefono,$cp,$alias,$notas,$idempresa)));
		return json_encode($result);
  }

  function get_sucursal_by_id($_id)
  {
    $query = 'SELECT * FROM "SUCURSALES" WHERE "ID_SUCURSAL" = $1';
    pg_prepare($this->conn,"getbyid",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"getbyid",array($_id)));
    return json_encode($result);
  }

  function update_sucursal($idsucursal,$clave,$direccion,$responsable,$telefono,$cp,$alias,$notas)
  {
    $query = 'UPDATE "SUCURSALES" SET
              "CLAVE" = $1,
              "DIRECCION" = $2,
              "RESPONSABLE" = $3,
              "TELEFONO" = $4,
              "CP" = $5,
              "ALIAS" = $6,
              "NOTAS" = $7
              WHERE "ID_SUCURSAL" = $8';
      pg_prepare($this->conn,"update_sucursal",$query);
      $result = pg_execute($this->conn,"update_sucursal",array($clave,$direccion,$responsable,$telefono,$cp,$alias,$notas,$idsucursal));
  		return $result;
  }

  function delete_sucursal($_id)
  {
    $query = 'DELETE FROM "SUCURSALES" WHERE "ID_SUCURSAL" = $1';
    pg_prepare($this->conn,"delete_suc",$query);
    $result = pg_execute($this->conn,"delete_suc",array($_id));
    return $result;
  }

}
