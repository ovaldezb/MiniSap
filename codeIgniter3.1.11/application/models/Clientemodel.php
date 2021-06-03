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

	function get_clientes_by_empresa($idEmpresa,$anioFiscal)
	{
		$query = 'SELECT C."ID_CLIENTE", C."NOMBRE",
    TRIM(C."CLAVE") as "CLAVE", C."RFC", C."DOMICILIO",C."ID_FORMA_PAGO",C."ID_USO_CFDI",
    CASE WHEN F."SALDO" IS NULL THEN 0 ELSE F."SALDO" END as "SALDO",
    C."DIAS_CREDITO", FP."CLAVE" as "FORMA_PAGO", C."CONTACTO",C."ID_VENDEDOR",V."NOMBRE" as "VENDEDOR",
    C."TELEFONO"
    FROM "CLIENTE" as C
    LEFT JOIN (SELECT F."ID_CLIENTE", SUM(F."SALDO") as "SALDO"  
              FROM "FACTURA" as F 
              WHERE F."ANIO_FISCAL" = $1 
              GROUP BY F."ID_CLIENTE") as F ON F."ID_CLIENTE" = C."ID_CLIENTE"
    INNER JOIN "FORMA_PAGO" as FP ON FP."ID_FORMA_PAGO" = C."ID_FORMA_PAGO"
    INNER JOIN "VENDEDOR" as V ON V."ID_VENDEDOR" = C."ID_VENDEDOR"
    WHERE C."ACTIVO" = true 
    AND C."ID_EMPRESA" = $2 
    ORDER BY "ID_CLIENTE" DESC ';
		pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($anioFiscal,$idEmpresa)));
		return json_encode($result);
	}

	function get_cliente_by_id($_idCliente)
	{
		$query = 'SELECT * FROM "CLIENTE" WHERE "ID_CLIENTE" = $1 AND "ACTIVO" = true';
		pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($_idCliente)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

  function get_cliente_by_code($codigo,$idempresa){
    $query = 'SELECT * FROM "CLIENTE" WHERE "CLAVE" = $1 AND "ID_EMPRESA"=$2 AND "ACTIVO" = true';
		pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($codigo,$idempresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

	function get_cliente_by_id_verif($_idCliente)
	{
		$query = 'SELECT TRIM(C."CLAVE") as "CLAVE",C."NOMBRE",C."DOMICILIO","CP",
		TRIM("TELEFONO") as "TELEFONO",
		"CONTACTO",TRIM("RFC") as "RFC",TRIM("CURP") as "CURP",
		TRIM(TC."DESCRIPCION") as "TIPO_CLIENTE","DIAS_CREDITO",
		TRIM(DS1."NOMBRE") as "REVISION",
		C."ID_REVISION",C."ID_VENDEDOR",C."ID_TIPO_CLIENTE",
		TRIM(DS2."NOMBRE") as "PAGOS",
		C."ID_PAGOS",C."ID_FORMA_PAGO",C."ID_USO_CFDI", TRIM(UC."CLAVE") as "CLAVE_CFDI",
		TRIM(FP."CLAVE") as "FORMA_PAGO",
		V."NOMBRE" as "VENDEDOR",
		UC."DESCRIPCION" as "CFDI",
		TRIM(C."EMAIL") AS "EMAIL",C."NOTAS"
		FROM "CLIENTE" as C
		LEFT JOIN "VENDEDOR" as V ON C."ID_VENDEDOR" = V."ID_VENDEDOR"
		LEFT JOIN "TIPO_CLIENTE" as TC ON TC."ID_TIPO_CLTE" = C."ID_TIPO_CLIENTE"
		LEFT JOIN "FORMA_PAGO" as FP ON FP."ID_FORMA_PAGO" = C."ID_FORMA_PAGO"
		LEFT JOIN "DIAS_SEMANA" as DS1 on DS1."ID_DIA" = C."ID_REVISION"
		LEFT JOIN "DIAS_SEMANA" as DS2 on DS2."ID_DIA" = C."ID_PAGOS"
		LEFT JOIN "USO_CFDI" as UC on UC."ID_CFDI" = C."ID_USO_CFDI"
		WHERE "ID_CLIENTE" = $1
		AND C."ACTIVO" = true';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($_idCliente)));
		return json_encode($result);
	}

	function get_clientes_by_nombre($idempresa,$nombre)
	{
		$query = 'SELECT C."ID_CLIENTE",C."NOMBRE",TRIM(C."CLAVE") as "CLAVE", 
				C."ID_USO_CFDI",C."RFC", C."DIAS_CREDITO",C."DOMICILIO",C."ID_FORMA_PAGO",
        C."DIAS_CREDITO", FP."CLAVE" as "FORMA_PAGO",C."CONTACTO",C."ID_VENDEDOR",V."NOMBRE" as "VENDEDOR",C."TELEFONO"
				FROM "CLIENTE" as C
        INNER JOIN "FORMA_PAGO" as FP ON FP."ID_FORMA_PAGO" = C."ID_FORMA_PAGO"
        INNER JOIN "VENDEDOR" as V ON V."ID_VENDEDOR" = C."ID_VENDEDOR"
				WHERE UPPER(C."NOMBRE")
				LIKE UPPER($1)
				AND C."ID_EMPRESA" = $2
				AND C."ACTIVO" = true';
		$result = pg_prepare($this->conn, "my_query", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "my_query", array($nombre,$idempresa)));
		return json_encode($result);
	}


	function crea_cliente($clave,$nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$notas,$diascred,$idempresa)
	 {
		$pstmt = 'SELECT * FROM crea_cliente($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18)';
		pg_prepare($this->conn,"insertquery",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "insertquery", array($clave,$nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$notas,$diascred,$idempresa)));
		return json_encode($result);
	}

	function delete_cliente($_idCliente)
	{
		$query = 'UPDATE "CLIENTE" SET "ACTIVO" = false WHERE "ID_CLIENTE" = $1';
		pg_prepare($this->conn,"deletequery1",$query);
		$result = pg_execute($this->conn,"deletequery1",array($_idCliente));
    
    $query2 = 'DELETE FROM "DOMICILIOS" WHERE "ID_CLIENTE" = $1';
		pg_prepare($this->conn,"deletequery2",$query2);
		$result2 = pg_execute($this->conn,"deletequery2",array($_idCliente));
		return $result;
	}

	function update_cliente($id_cliente,$nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$notas,$diascred)
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
		"NOTAS"= $15,
		"DIAS_CREDITO" = $16
		WHERE "ID_CLIENTE"=$17');
		$result = pg_execute($this->conn,"updatequery",array($nombre,$domicilio,$cp,$telefono, $contacto, $rfc, $curp, $id_tipo_cliente, $revision, $pagos, $id_forma_pago, $id_vendedor,$id_uso_cfdi,$email,$notas,$diascred,$id_cliente));
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

  function get_fact_by_idcliente($idcliente,$anioFiscal){
    $query = 
    'SELECT \'A\' as "TIPO",TRIM("DOCUMENTO") as "DOCUMENTO",TO_CHAR("FECHA_FACTURA",\'DD/Mon/YYYY\') as "FECHA_FACTURA" ,TO_CHAR("IMPORTE",\'999999999.99\') as "IMPORTE", \'\' as "COBRO",TO_CHAR("IMPORTE",\'999999999.99\') as "SALDO"
    FROM "FACTURA" as F
    WHERE F."ID_CLIENTE" = $1
    AND F."ANIO_FISCAL" = $2
    AND F."ESTATUS" IS NULL 
    UNION
    SELECT \'B\' as "TIPO", TRIM(F."DOCUMENTO") as "DOCUMENTO",TO_CHAR("FECHA_COBRO",\'DD/Mon/YYYY\') as "FECHA_FACTURA",\'\' as "IMPORTE",TO_CHAR(C."IMPORTE_COBRO",\'999999999.99\') as "COBRO", \'\' as "SALDO"
    FROM "COBROS" as C
    INNER JOIN "FACTURA" as F ON F."ID_FACTURA" = C."ID_FACTURA"
    WHERE F."ID_CLIENTE" = $3
    AND C."ANIO_FISCAL" = $4
    AND F."ESTATUS" IS NULL 
    ORDER BY "DOCUMENTO","TIPO", "FECHA_FACTURA" ';
		pg_prepare($this->conn,"selqry",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"selqry",array($idcliente,$anioFiscal,$idcliente,$anioFiscal)));
		return json_encode($result);
  }

  function get_id_clte_ventasmostrador($idempresa){
    $query = 'SELECT "ID_CLIENTE","CLAVE","NOMBRE"
							FROM  "CLIENTE" WHERE "CLAVE" = \'0001\' AND "ID_EMPRESA"=$1';
		pg_prepare($this->conn,"selqry",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"selqry",array($idempresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  function save_domicilio($data){
    $query = 'INSERT INTO "DOMICILIOS" ("ID_CLIENTE","LUGAR","CALLE","COLONIA","CIUDAD","LATITUD","LONGITUD","CONTACTO","CP","ACTIVO") 
    VALUES($1,$2,$3,$4,$5,$6,$7,$8,$9,true)';
		pg_prepare($this->conn,"insert_query",$query);
		$result = pg_execute($this->conn,"insert_query",$data);
		return $result;
  }

  /*function delete_domicilios($iddomicilo){
    $query = 'DELETE FROM "DOMICILIOS" WHERE "ID_DOMICILIO" = $1 ';
		pg_prepare($this->conn,"insert_query",$query);
		$result = pg_execute($this->conn,"insert_query",array($iddomicilo));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }*/

  function delete_domicilio($iddomicilo){
    $query = 'UPDATE "DOMICILIOS" SET "ACTIVO" = false WHERE "ID_DOMICILIO" = $1 ';
		pg_prepare($this->conn,"insert_query",$query);
		$result = pg_execute($this->conn,"insert_query",array($iddomicilo));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  function update_domicilio($dataDomicilios){
    $query = 'UPDATE "DOMICILIOS" SET "LUGAR" = $1 , "CALLE"=$2, "COLONIA"=$3, 
    "CIUDAD"=$4, "LATITUD"=$5, "LONGITUD" =$6, "CONTACTO"=$7, "CP"=$8 
    WHERE "ID_DOMICILIO"=$9';
    pg_prepare($this->conn,"update_query",$query);
		$result = pg_execute($this->conn,"update_query",$dataDomicilios);
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  function get_domi_by_id($idcliente){
    $query = 'SELECT "ID_DOMICILIO","ID_CLIENTE",TRIM("LUGAR") as "LUGAR", 
    "CALLE", TRIM("CIUDAD") as "CIUDAD", "COLONIA", "CONTACTO", "LATITUD", "LONGITUD", "CP" 
    FROM "DOMICILIOS" 
    WHERE "ID_CLIENTE"=$1 AND "ACTIVO" = \'t\' ';
		pg_prepare($this->conn,"selqry",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"selqry",array($idcliente)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }
}

?>
