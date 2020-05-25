<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedormodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_proveedores_by_empresa($idEmpresa)
	{
		$query = 'SELECT * FROM "PROVEEDORES" WHERE "ACTIVO" = true AND "ID_EMPRESA" = $1';
		pg_prepare($this->conn,"select_prov",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_prov",array($idEmpresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function create_proveedor($clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas,$idempresa)
	{
		$query = 'SELECT * FROM crea_proveedor($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16)';
		pg_prepare($this->conn,"insertquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insertquery",array($clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas,$idempresa)));
		return json_encode($result);
	}

	function get_proveedor_by_id($_id)
	{
		$query = 'SELECT * FROM "PROVEEDORES" WHERE "ID_PROVEEDOR" = $1 AND "ACTIVO" = true';
		pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($_id)));
		return json_encode($result);
	}

	function get_proveedor_by_clave($_clave)
	{
		$query = 'SELECT "NOMBRE","CLAVE" FROM "PROVEEDORES" WHERE "CLAVE" = $1 AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($_clave)));
		return json_encode($result);
	}

	function update_proveedor($id_prov,$clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas)
	{
		$query ='UPDATE "PROVEEDORES" SET
				"CLAVE" = $1,
				"NOMBRE" = $2,
				"DOMICILIO" = $3,
				"CP" = $4,
				"TELEFONO" = $5,
				"CONTACTO" = $6,
				"RFC" = $7,
				"CURP" = $8,
				"ID_CATEGORIA_PROV" = $9,
				"DIAS_CRED" = $10,
				"ID_TIPO_ALC_PROV" = $11,
				"ID_BANCO" = $12,
				"CUENTA" = $13,
				"EMAIL" = $14,
				"NOTAS" = $15
				WHERE "ID_PROVEEDOR" = $16';
		pg_prepare($this->conn,"updatequery",$query);
		$result = pg_execute($this->conn,"updatequery",array($clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas,$id_prov));
		return $result;
	}

	function delete_proveedor($_id)
	{
		//$query = 'DELETE FROM "PROVEEDORES" WHERE "ID_PROVEEDOR" = $1';
		$query = 'UPDATE "PROVEEDORES" SET "ACTIVO" = false WHERE "ID_PROVEEDOR" = $1';
		$result = pg_prepare($this->conn,"deletequery",$query);
		$result = pg_execute($this->conn,"deletequery",array($_id));
		return $result;
	}

	function get_proveedor_by_desc($idEmpresa,$desc)
	{
		$query = 'SELECT * FROM "PROVEEDORES"
		WHERE UPPER("NOMBRE") like UPPER($1)
		AND "ID_EMPRESA" = $2
		AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($desc,$idEmpresa)));
		return json_encode($result);
	}

}
?>
