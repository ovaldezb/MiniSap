<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresamodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_empresas()
	{
		$query = 'SELECT * FROM "EMPRESA" ORDER BY "NOMBRE"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
	}

	function create_empresa($nombre, $domicilio, $rfc, $ejercicio_fiscal, $id_regimen, $digxcuenta, $cta_res, $res_ant)
	{
		$query = 'SELECT * FROM crea_empresa($1, $2, $3, $4, $5, $6, $7,$8)';
		/*$result = pg_prepare($this->conn,"insertquery",
				'INSERT INTO "EMPRESA" ("NOMBRE", "DOMICILIO", "RFC", "ID_REGIMEN", "DIGITO_X_CUENTA", "CUENTA_RESULTADO", "RESULTADO_ANTERIOR")
				  VALUES ($1, $2, $3, $4, $5, $6, $7)');
		$result = pg_execute($this->conn,"insertquery",array($nombre, $domicilio, $rfc, $id_regimen, $digxcuenta, $cta_res, $res_ant));
		$result1 = pg_prepare($this->conn,"insertejefis",'INSERT INTO "EMP_EJER_FISC" ("ID_EMPRESA", "EJER_FISC") SELECT "ID_EMPRESA",$1 FROM "EMPRESA" ORDER BY "ID_EMPRESA" DESC LIMIT 1');
		$result1 = pg_execute($this->conn,"insertejefis",array($ejercicio_fiscal));*/
		pg_prepare($this->conn, "creaempresa", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "creaempresa", array($nombre,$domicilio,$rfc,$ejercicio_fiscal,$id_regimen,$digxcuenta,$cta_res,$res_ant)));
		return json_encode($result);
	}

	function get_empresa_by_id($_id)
	{
		$query = 'SELECT TRIM(A."NOMBRE") NOMBRE, A."DOMICILIO", TRIM(A."RFC") RFC,
				A."ID_REGIMEN", A."DIGITO_X_CUENTA", A."CUENTA_RESULTADO", A."RESULTADO_ANTERIOR", B."EJER_FISC"
				FROM "EMPRESA" A, "EMP_EJER_FISC" B
				WHERE A."ID_EMPRESA" = $1 AND A."ID_EMPRESA" = B."ID_EMPRESA" ORDER BY A."ID_EMPRESA" LIMIT 1';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($_id)));
		return json_encode($result);
	}

	function update_cliente($id_empresa,$nombre, $domicilio, $rfc, $ejercicio_fiscal, $id_regimen, $digxcuenta, $cta_res, $res_ant)
	{
		$result = pg_prepare($this->conn,"updatequery",'UPDATE "EMPRESA" SET
				"NOMBRE" = $1,
				"DOMICILIO" = $2,
				"RFC" = $3,
				"ID_REGIMEN" = $4,
				"DIGITO_X_CUENTA" = $5,
				"CUENTA_RESULTADO" = $6,
				"RESULTADO_ANTERIOR" = $7
				WHERE "ID_EMPRESA" = $8');

		$result = pg_execute($this->conn,"updatequery",array($nombre, $domicilio,$rfc,$id_regimen, $digxcuenta, $cta_res, $res_ant,$id_empresa));
		$result = pg_prepare($this->conn,"updateempejerfis",'UPDATE "EMP_EJER_FISC" SET "EJER_FISC" = $1 WHERE "ID_EMPRESA" = $2');
		$result = pg_execute($this->conn,"updateempejerfis",array($ejercicio_fiscal,$id_empresa));
		return $result;
	}

	function delete_empresa($_id)
	{
		$query = "DELETE FROM \"EMPRESA\" WHERE \"ID_EMPRESA\" = $1";
		$result = pg_prepare($this->conn,"deletequery",$query);
		$result = pg_execute($this->conn,"deletequery",array($_id));
		return $result;
	}
}

?>
