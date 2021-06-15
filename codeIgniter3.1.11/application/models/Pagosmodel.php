<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagosmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
    }

	public function getListaPagos($arrayData){
		$query = 'SELECT "ID_FACTURA",TRIM("DOCUMENTO") as "DOCUMENTO",
				CASE WHEN M."MET_PAGO" IS NULL THEN \'\' ELSE M."MET_PAGO" END as "METODO_PAGO",
				TO_CHAR("FECHA_FACTURA",\'dd-MM-YYYY\') as "FECHA_FACTURA",
				C."NOMBRE" as "CLIENTE",TRIM(C."CLAVE") as "CLAVE",
				CASE WHEN F."FECHA_VENCIMIENTO" IS NULL THEN \'\' ELSE TO_CHAR("FECHA_VENCIMIENTO",\'dd-MM-YYYY\') END as "FECHA_VENCIMIENTO",
				"IMPORTE","SALDO",T."DESCRIPCION" as "FORMA_PAGO",
				CASE WHEN C."DIAS_CREDITO" IS NULL THEN 0 ELSE C."DIAS_CREDITO" END as "DIAS_CREDITO",
				CASE WHEN V."NOMBRE" IS NULL THEN \'\' ELSE V."NOMBRE" END as "VENDEDOR" 
			FROM "FACTURA" as F 
				INNER JOIN "CLIENTE" as C ON F."ID_CLIENTE" = C."ID_CLIENTE"
				LEFT OUTER JOIN "VENDEDOR" as V on F."ID_VENDEDOR" = V."ID_VENDEDOR"
				LEFT OUTER JOIN "METODO_PAGO" as M on F."ID_METODO_PAGO" = M."ID_MET_PAGO"
				INNER JOIN "TIPO_PAGO"as T ON F."ID_TIPO_PAGO" = T."ID_TIPO_PAGO"
			AND F."ID_EMPRESA" = $1
			AND F."ANIO_FISCAL" = $2
			ORDER BY F."ID_FACTURA"';
		pg_prepare($this->conn,"select_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fact",$arrayData));
		return json_encode($result);
	}

	public function guardapago($datospago){
		$query = 'SELECT * FROM registra_pago($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12)';
		pg_prepare($this->conn,"inserta_pago",$query);
		$result = pg_execute($this->conn,"inserta_pago",$datospago);
		return json_encode($result);
	}

	public function getpagobycompra($idcompra){
		$query = 'SELECT "ID_PAGO",
				TO_CHAR("FECHA_PAGO",\'dd/MM/YYYY\') as "FECHA_PAGO",
				"IMPORTE_PAGO" 
				FROM "PAGOS" WHERE "ID_COMPRA"=$1';
		pg_prepare($this->conn,"select_pago",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_pago",array($idcompra)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}


	public function getpagobyid($idpago){
		$query = 'SELECT "ANIO_FISCAL","CHEQUE","DEPOSITO",TO_CHAR("FECHA_PAGO",\'dd/MM/yyyy\') as "FECHA_PAGO","ID_BANCO","IMPORTE_BASE","IMPORTE_PAGO","POLIZA" FROM "PAGOS" WHERE "ID_PAGO" = $1';
		pg_prepare($this->conn,"select_pago",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_pago",array($idpago)));
		return json_encode($result);
	}

  public function updatebyid($idpago,$idcompra,$importe){
    $query = 'UPDATE "PAGOS" SET "IMPORTE_PAGO" = "IMPORTE_PAGO" + $1 WHERE "ID_PAGO"=$2';
		pg_prepare($this->conn,"updt_pago",$query);
		pg_execute($this->conn,"updt_pago",array($importe,$idpago));

    $query1 = 'UPDATE "COMPRAS" SET "SALDO" = "SALDO" - $1 WHERE "ID_COMPRA"=$2';
		pg_prepare($this->conn,"del_pago",$query1);
		pg_execute($this->conn,"del_pago",array($importe,$idcompra));
		return json_encode(array("msg"=>"ok"));
  }

	public function deletebyid($idpago,$idcompra,$importe){
		$query = 'UPDATE "COMPRAS" SET "SALDO" = "SALDO" + $1 WHERE "ID_COMPRA"=$2';
		pg_prepare($this->conn,"updt_fact",$query);
		pg_execute($this->conn,"updt_fact",array($importe,$idcompra));
		$query1 = 'DELETE FROM "PAGOS" WHERE "ID_PAGO"=$1';
		pg_prepare($this->conn,"del_pago",$query1);
		pg_execute($this->conn,"del_pago",array($idpago));
		return json_encode(array("msg"=>"ok"));
	}
}

?>