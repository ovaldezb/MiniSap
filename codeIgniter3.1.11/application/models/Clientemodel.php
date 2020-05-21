<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientemodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_user_json()
	{
		$query = 'SELECT * FROM "CLIENTE" WHERE "ACTIVO" = true ORDER BY "CLAVE"';
		if($this->conn)
		{
			$result = pg_fetch_all(pg_query($this->conn, $query));
		}
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	/*function get_last_user()
	{
		$query = 'SELECT * FROM "CLIENTE" ORDER BY "ID_CLIENTE" DESC LIMIT 1';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
	}*/

	function get_cliente_by_id($_id)
	{
		$query = 'SELECT * FROM "CLIENTE" WHERE "ID_CLIENTE" = $1 AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($_id)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_cliente_by_id_verif($_id)
	{
		$query = 'SELECT TRIM(C."CLAVE") as "CLAVE",C."NOMBRE",C."DOMICILIO","CP",TRIM("TELEFONO") as "TELEFONO",
		"CONTACTO",TRIM("RFC") as "RFC",TRIM("CURP") as "CURP",
		TRIM(TC."DESCRIPCION") as "TIPO_CLIENTE","DIAS_CREDITO",
		TRIM(DS1."NOMBRE") as "REVISION",
		C."ID_REVISION",C."ID_VENDEDOR",C."ID_TIPO_CLIENTE",
		TRIM(DS2."NOMBRE") as "PAGOS",
		C."ID_PAGOS",C."ID_FORMA_PAGO",C."ID_USO_CFDI",
		TRIM(FP."DESCRIPCION") as "FORMA_PAGO",
		V."NOMBRE" as "VENDEDOR",
		UC."DESCRIPCION" as "CFDI",
		C."EMAIL",C."NUM_PROVEEDOR",C."NOTAS"
		FROM "CLIENTE" as C
		INNER JOIN "VENDEDOR" as V ON C."ID_VENDEDOR" = V."ID_VENDEDOR"
		INNER JOIN "TIPO_CLIENTE" as TC ON TC."ID_TIPO_CLTE" = C."ID_TIPO_CLIENTE"
		INNER JOIN "FORMA_PAGO" as FP ON FP."ID_FORMA_PAG" = C."ID_FORMA_PAGO"
		INNER JOIN "DIAS_SEMANA" as DS1 on DS1."ID_DIA" = C."ID_REVISION"
		INNER JOIN "DIAS_SEMANA" as DS2 on DS2."ID_DIA" = C."ID_PAGOS"
		INNER JOIN "USO_CFDI" as UC on UC."ID_CFDI" = C."ID_USO_CFDI"
		WHERE "ID_CLIENTE" = $1
		AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($_id)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_clientes_by_nombre($nombre)
	{
		$query = 'SELECT * FROM "CLIENTE" WHERE UPPER("NOMBRE") LIKE UPPER($1) AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($nombre)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}


	function crea_cliente($clave,$nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$num_proveedor,$notas,$diascred,$idempresa)
	 {
		$pstmt = 'SELECT * FROM crea_cliente($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19);';
		pg_prepare($this->conn,"insertquery",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "insertquery", array($clave,$nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$num_proveedor,$notas,$diascred,$idempresa)));
		return json_encode($result);
	}

	function delete_cliente($_id)
	{
		$query = 'UPDATE "CLIENTE" SET "ACTIVO" = false WHERE "ID_CLIENTE" = $1';
		$result = pg_prepare($this->conn,"deletequery",$query);
		$result = pg_execute($this->conn,"deletequery",array($_id));
		return $result;
	}

	function update_cliente($id_cliente,$nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$num_proveedor,$notas,$diascred)
	{
		$result = pg_prepare($this->conn,"updatequery",'UPDATE "CLIENTE" SET
		"NOMBRE"=$1,
		"DOMICILIO"=$2,
		"CP"=$3,
		"TELEFONO"=$4,
		"CONTACTO"=$5,
		"RFC"=$6,
		"CURP"=$7,
		"ID_TIPO_CLIENTE"=$8,
		"ID_REVISION"=$9,
		"ID_PAGOS"=$10,
		"ID_FORMA_PAGO"=$11,
		"ID_VENDEDOR"=$12,
		"ID_USO_CFDI"=$13,
		"EMAIL"=$14,
		"NUM_PROVEEDOR"=$15,
		"NOTAS"= $16,
		"DIAS_CREDITO" = $17
		WHERE "ID_CLIENTE"=$18');
		$result = pg_execute($this->conn,"updatequery",array($nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$num_proveedor,$notas,$diascred,$id_cliente));
		return $result;
	}

	function get_next_clte_id($valor)
	{
		$query = 'SELECT to_char("last_value"+$1,\'00000\') as "NEXT_CLTE_ID"
							FROM "CLIENTE_ID_CLIENTE_seq"';
		pg_prepare($this->conn,"selqry",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"selqry",array($valor)));
		return json_encode($result);
	}
}

?>
