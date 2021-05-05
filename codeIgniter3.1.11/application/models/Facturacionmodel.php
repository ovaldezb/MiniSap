<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturacionmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function savefactura($arrayDatFact){
		$query = 'SELECT * FROM registra_factura($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18)';
		pg_prepare($this->conn,"insert_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_fact",$arrayDatFact));
		return json_encode($result);
	}

  function save_corte_caja($arrayCorteCaja){
    $query = 'SELECT * FROM registra_corte($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14)';
    pg_prepare($this->conn,"insert_corte",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_corte",$arrayDatFact));
		return json_encode($result);
  }

  function corte_venta($idcorte,$idventa){
    $query = 'INSERT INTO "CORTE_VENTA" ("ID_CORTE","ID_VENTA") 
      VALUES($1,$2)';
    $result = pg_query($this->conn,$query);
    return $result;
  }

	function getfacturas($arrayData){
		$query = 'SELECT F."ID_FACTURA", F."DOCUMENTO",
		TO_CHAR(F."FECHA_FACTURA",\'dd-MM-yyyy\') as "FECHA_FACTURA",
		F."IMPORTE",F."SALDO", 
    C."CLAVE",
    E."ID_VENDEDOR",
		CASE WHEN C."NOMBRE" IS NULL THEN \'Ventas Mostrador\' ELSE C."NOMBRE" END  as "CLIENTE", 
		CASE WHEN E."NOMBRE" IS NULL THEN \'Ventas Mostrador\' ELSE E."NOMBRE"END as "VENDEDOR", F."ID_TIPO_PAGO",
		CASE WHEN C."DIAS_CREDITO" IS NULL THEN 0 ELSE C."DIAS_CREDITO" END as "DIAS_CREDITO",
		TO_CHAR(F."FECHA_REVISION",\'dd-MM-yyyy\') as "FECHA_REVISION",
		TO_CHAR(F."FECHA_VENCIMIENTO",\'dd-MM-yyyy\') as "FECHA_VENCIMIENTO",
    CASE WHEN FC."RFC" IS NULL THEN false ELSE true END as "FACTURADO",
    FC."FOLIO",
    TRIM(C."EMAIL") as "EMAIL",
    C."ID_CLIENTE",
    TRIM(F."ESTATUS") as "ESTATUS"
		FROM "FACTURA" as F
		LEFT JOIN "CLIENTE" as C ON C."ID_CLIENTE" = F."ID_CLIENTE"
		lEFT JOIN "VENDEDOR" as E ON E."ID_VENDEDOR" = F."ID_VENDEDOR"
    LEFT OUTER JOIN "FACTURA_CFDI" as FC ON FC."ID_FACTURA" = F."ID_FACTURA"
		WHERE F."ID_EMPRESA" = $1 
		AND F."ANIO_FISCAL" = $2
		ORDER BY F."FECHA_FACTURA" DESC';
		pg_prepare($this->conn,"select_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fact",$arrayData));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function eliminaFacturaById($idfactura,$idsucursal){
		$query = 'SELECT * FROM eliminar_factura($1,$2)';
		pg_prepare($this->conn,"elimina",$query);
		$result = pg_execute($this->conn,"elimina",array($idfactura,$idsucursal));
		return json_encode($result);
	}

  function get_prod_by_fact_validar($idfactura){
    $query = 'SELECT VP."ID_PRODUCTO",P."DESCRIPCION",TRIM(P."COD_CFDI") as "COD_CFDI" ,P."UNIDAD_SAT"
    FROM "VENTAS_PRODUCTO" as VP
    INNER JOIN "PRODUCTO" as P ON P."ID_PRODUCTO" = VP."ID_PRODUCTO"
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = VP."ID_VENTA"
    WHERE V."ID_FACTURA" = $1';
		pg_prepare($this->conn,"select_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fact",array($idfactura)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  function get_datos_for_cfdi($idfactura){
    $query = 'SELECT F."DOCUMENTO" as "FOLIO", V."ID_VENTA",F."ID_CLIENTE",TRIM(C."RFC") as "RFC",
    TRIM(C."NOMBRE") as "CLIENTE" ,
    U."CLAVE" as "USO_CFDI", TRIM(M."MET_PAGO") as "METODO_PAGO", FP."CLAVE" as "FORMA_PAGO",
    MO."CODIGO" as "MONEDA"
    FROM "FACTURA" as F
    INNER JOIN "VENTAS" as V ON V."ID_FACTURA" = F."ID_FACTURA"
    INNER JOIN "CLIENTE" as C ON C."ID_CLIENTE" = F."ID_CLIENTE"
    INNER JOIN "USO_CFDI" as U ON U."ID_CFDI" = F."ID_USO_CFDI"  
    INNER JOIN "METODO_PAGO" as M ON M."ID_MET_PAGO" = F."ID_METODO_PAGO"
    INNER JOIN "FORMA_PAGO" as FP ON FP."ID_FORMA_PAGO" = F."ID_FORMA_PAGO"
    INNER JOIN "MONEDA" as MO ON MO."ID_MONEDA" = F."ID_MONEDA"
    WHERE F."ID_FACTURA" = $1';
		pg_prepare($this->conn,"select_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fact",array($idfactura)));
		return json_encode($result[0]);
  }
}
?>
