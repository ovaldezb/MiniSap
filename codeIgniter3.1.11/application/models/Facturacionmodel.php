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
		$query = 'SELECT * FROM registra_factura($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16)';
		pg_prepare($this->conn,"insert_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_fact",$arrayDatFact));
		return json_encode($result);
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
    V."ID_VENTA",
    CASE WHEN FC."RFC" IS NULL THEN false ELSE true END as "FACTURADO",
    FC."FOLIO",
    TRIM(C."EMAIL") as "EMAIL",
    C."ID_CLIENTE"
		FROM "FACTURA" as F
		LEFT JOIN "CLIENTE" as C ON C."ID_CLIENTE" = F."ID_CLIENTE"
		lEFT JOIN "VENDEDOR" as E ON E."ID_VENDEDOR" = F."ID_VENDEDOR"
    LEFT JOIN "VENTAS" as V ON V."ID_FACTURA" = F."ID_FACTURA"
    LEFT OUTER JOIN "FACTURA_CFDI" as FC ON FC."ID_FACTURA" = F."ID_FACTURA"
		WHERE F."ID_EMPRESA" = $1 
		AND F."ANIO_FISCAL" = $2
		ORDER BY F."FECHA_FACTURA" DESC';
		pg_prepare($this->conn,"select_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fact",$arrayData));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function eliminaFacturaById($idventa,$idsucursal){
		$query = 'SELECT * FROM eliminar_factura($1,$2)';
		pg_prepare($this->conn,"elimina",$query);
		$result = pg_execute($this->conn,"elimina",array($idventa,$idsucursal));
		return json_encode($result);
	}
}
?>
