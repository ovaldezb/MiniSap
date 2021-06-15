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
		$query = 'SELECT * FROM "EMPRESA" WHERE "ACTIVO" = true ORDER BY "NOMBRE"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
	}

	function create_empresa($nombre, $domicilio, $rfc, $cp,$ejercicio_fiscal, $id_regimen, $digxcuenta, $cta_res, $res_ant)
	{
		$query = 'SELECT * FROM crea_empresa($1, $2, $3, $4, $5, $6, $7,$8,$9)';
		pg_prepare($this->conn, "creaempresa", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "creaempresa", array($nombre,$domicilio,$rfc,$cp,$ejercicio_fiscal,$id_regimen,$digxcuenta,$cta_res,$res_ant)));
		return json_encode($result);
	}

	function get_empresa_by_id($_id)
	{
		$query = 'SELECT TRIM(A."NOMBRE") as "NOMBRE", A."DOMICILIO", TRIM(A."RFC") as "RFC", A."CP",
				A."ID_REGIMEN", A."DIGITO_X_CUENTA", A."CUENTA_RESULTADO", A."RESULTADO_ANTERIOR", B."EJER_FISC",
        TRIM(R."CLAVE") as "REGIMEN"
				FROM "EMPRESA" A
        INNER JOIN "EMP_EJER_FISC" as B ON A."ID_EMPRESA" = B."ID_EMPRESA"
        INNER JOIN "REGIMEN" as R ON R."ID_REGIMEN" = A."ID_REGIMEN"
				WHERE A."ID_EMPRESA" = $1 ORDER BY A."ID_EMPRESA" LIMIT 1';
		pg_prepare($this->conn, "my_query1", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query1", array($_id)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function update_empresa($id_empresa,$nombre, $domicilio, $rfc,$cp, $ejercicio_fiscal, $id_regimen, $digxcuenta, $cta_res, $res_ant)
	{
		$result = pg_prepare($this->conn,"updatequery",'UPDATE "EMPRESA" SET
				"NOMBRE" = $1,
				"DOMICILIO" = $2,
				"RFC" = $3,
				"CP" = $4,
				"ID_REGIMEN" = $5,
				"DIGITO_X_CUENTA" = $6,
				"CUENTA_RESULTADO" = $7,
				"RESULTADO_ANTERIOR" = $8
				WHERE "ID_EMPRESA" = $9');

		$result = pg_execute($this->conn,"updatequery",array($nombre, $domicilio,$rfc,$cp,$id_regimen, $digxcuenta, $cta_res, $res_ant,$id_empresa));
		//pg_prepare($this->conn,"updateempejerfis",'UPDATE "EMP_EJER_FISC" SET "EJER_FISC" = $1 WHERE "ID_EMPRESA" = $2');
		//$result = pg_execute($this->conn,"updateempejerfis",array($ejercicio_fiscal,$id_empresa));
		return $result;
	}

	function delete_empresa($_idempresa)
	{
		$query = 'UPDATE "EMPRESA" SET "ACTIVO"=false WHERE "ID_EMPRESA" = $1';
		pg_prepare($this->conn,"updatequery",$query);
		pg_execute($this->conn,"updatequery",array($_idempresa));

    $query1 = 'DELETE FROM "INCREMENTOS" WHERE "ID_EMPRESA" = $1';
		pg_prepare($this->conn,"deletequery",$query1);
		$result = pg_execute($this->conn,"deletequery",array($_idempresa));
		return $result;
	}

	function get_emp_perm_by_id($idusuario)
	{
		$query = 'SELECT UE."ID_EMPRESA", TRIM(E."NOMBRE") as "NOMBRE"
							FROM "USUARIO_EMPRESA" as UE
							INNER JOIN "EMPRESA" E ON E."ID_EMPRESA" = UE."ID_EMPRESA"
							WHERE UE."ID_USUARIO" = $1 ORDER BY E."NOMBRE"';
		pg_prepare($this->conn,"select_empperm",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_empperm",array($idusuario)));
		return json_encode($result);
	}

	function get_fy_by_emp($idempresa)
	{
		$query = 'SELECT "EJER_FISC"
		  				FROM "EMP_EJER_FISC"
							WHERE "ID_EMPRESA" = $1 ORDER BY "EJER_FISC" ';
		pg_prepare($this->conn,"select_fy_emp",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fy_emp",array($idempresa)));
		return json_encode($result);
	}

  function get_regimen_by_emp(){
    $query = 'SELECT "EJER_FISC"
		  				FROM "EMP_EJER_FISC"
							WHERE "ID_EMPRESA" = $1 ';
		pg_prepare($this->conn,"select_fy_emp",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fy_emp",array($idempresa)));
		return json_encode($result);
  }

}

?>
