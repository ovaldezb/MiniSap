<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cortecajamodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

  function reporte_by_month($idempresa,$aniofiscal,$fecini,$fecfin,$idscursal){
    $query = 'SELECT TO_CHAR(C."FECHA_CORTE",\'DD-mon-YYYY HH24:MI\') as "FECHA", 
      C."OPERACIONES", C."CANCELADAS" as "CANCELADOS", C."IMPORTE", C."ID_CORTE",
      F."DOCUMENTO",
      TRIM(U."CLAVE_USR") as "CLAVE_USR", U."NOMBRE" 
      FROM "CORTE_CAJA" as C
      LEFT JOIN "FACTURA" as F ON F."ID_FACTURA" = C."ID_FACTURA"
      LEFT JOIN "USUARIO" as U ON U."ID_USUARIO" = C."ID_USUARIO"
      WHERE C."ID_EMPRESA" = $1
      AND C."ANIO_FISCAL" = $2
      AND C."FECHA_CORTE" >= $3
      AND C."FECHA_CORTE"<= $4 
      AND C."ID_SUCURSAL" = $5
      ORDER BY "FECHA"';
		pg_prepare($this->conn, "sel_month", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "sel_month", array($idempresa,$aniofiscal,$fecini,$fecfin,$idscursal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  public function dataOperByIDCC($arrayIdCC){
		
    $query1 = 'SELECT CV."ID_VENTA",V."DOCUMENTO",VP."COUNT",V."ID_TIPO_PAGO", 
    CASE WHEN V."CANCELADO" = \'t\' THEN 0 ELSE V."IMPORTE" END AS "IMPORTE",
    V."CANCELADO",
    CASE WHEN V."PAG_EFECTIVO" > 0 THEN \'EF\' ELSE
      (CASE WHEN V."PAG_TARJETA" > 0 THEN \'TA\' ELSE
        (CASE WHEN V."PAG_CHEQUE" > 0 THEN \'CH\' ELSE 
          (CASE WHEN V."PAG_VALES" > 0 THEN \'VA\' ELSE \'CR\'END)
           END)
        END)
    END as "TIPO_PAGO",
    U."CLAVE_USR"
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    INNER JOIN (SELECT "ID_VENTA", COUNT(*) AS "COUNT" FROM "VENTAS_PRODUCTO" GROUP BY "ID_VENTA")  AS VP ON VP."ID_VENTA" = V."ID_VENTA"
    LEFT OUTER JOIN "USUARIO" as U ON U."ID_USUARIO" = V."ID_USUARIO"
    WHERE "ID_CORTE" = $1
    ORDER BY CV."ID_VENTA"';
		pg_prepare($this->conn,"qry1",$query1);
		$result1 = pg_fetch_all(pg_execute($this->conn,"qry1",$arrayIdCC));

    $query2 = 'SELECT  SUM(V."PAG_EFECTIVO") - SUM("CAMBIO") as "EFECTIVO",SUM("PAG_TARJETA") as "TARJETA",
    SUM("PAG_CHEQUE") as "CHEQUE",SUM("PAG_VALES") as "VALES" 
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    WHERE "ID_CORTE" = $1
    AND V."CANCELADO" = \'f\'
    GROUP BY V."ID_TIPO_PAGO"';
		pg_prepare($this->conn,"qry2",$query2);
		$result2 = pg_fetch_all(pg_execute($this->conn,"qry2",$arrayIdCC));
    if($result2 == false){
      $result2 = (array("EFECTIVO"=>0,"TARJETA"=>0,"CHEQUE"=>0,"VALES"=>0));
    }


    $query3 = 'SELECT SUM("IMPORTE") as "SUMA", "ID_TIPO_PAGO"
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    WHERE "ID_CORTE" = $1
    AND V."CANCELADO" = \'f\'
    GROUP BY V."ID_TIPO_PAGO"';
		pg_prepare($this->conn,"qry3",$query3);
    
		$result3 = pg_fetch_all(pg_execute($this->conn,"qry3",$arrayIdCC));
    if($result3){
      if(sizeof($result3) == 1){
        if($result3[0]["ID_TIPO_PAGO"] == "1"){
          $result4 = array($result3[0], array("SUMA"=>"0","ID_TIPO_PAGO"=>"2"));
        }else{
          $result4 = array(array("SUMA"=>"0","ID_TIPO_PAGO"=>"1"),$result3[0]);
        }
      }else{
        $result4 = $result3;
      }
    }else{
      $result4 = array(array("SUMA"=>"0","ID_TIPO_PAGO"=>"1"),array("SUMA"=>"0","ID_TIPO_PAGO"=>"2"));
    }

    $query4 = 'SELECT COUNT(*) as "TOTAL"
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    WHERE "ID_CORTE" = $1
    AND V."CANCELADO" = \'t\'
    GROUP BY V."ID_TIPO_PAGO"';
    pg_prepare($this->conn,"qry4",$query4);
    $result5 = pg_fetch_all(pg_execute($this->conn,"qry4",$arrayIdCC));
    if(!$result5){
      $cancelados = 0;
    }else{
      $cancelados = $result5[0]["TOTAL"];
    }

		return json_encode(array("ventas"=>$result1,"pagos"=>$result2[0],"tipopago"=>$result4,"cancelados"=>$cancelados),JSON_NUMERIC_CHECK);
	}

  function get_ventas_by_id($idventadata){
    $query1 = 'SELECT * FROM "VENTAS" WHERE "ID_VENTA" = $1';
    pg_prepare($this->conn,"qry1",$query1);
    $result1 = pg_fetch_all(pg_execute($this->conn,"qry1",$idventadata));

    $query2 = 'SELECT P."DESCRIPCION",V."CANTIDAD",V."DESCUENTO",V."IMPORTE",V."PRECIO",P."IVA"  
    FROM "VENTAS_PRODUCTO" as V
    INNER JOIN "PRODUCTO" as P ON P."ID_PRODUCTO" = V."ID_PRODUCTO"
    WHERE "ID_VENTA" = $1';
    pg_prepare($this->conn,"qry2",$query2);
    $result2 = pg_fetch_all(pg_execute($this->conn,"qry2",$idventadata));

    return json_encode(array("venta"=>$result1[0],"detalle"=>$result2), JSON_NUMERIC_CHECK);
  }


  function get_docto_ini_fin($data){
    $query = '(SELECT TRIM(V."DOCUMENTO") as "DOCUMENTO", TO_CHAR(V."FECHA_VENTA",\'HH24:mm:ss\') as"FECHA_VENTA"
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    WHERE "ID_CORTE" = $1
    ORDER BY CV."ID_VENTA"
    LIMIT 1)
    UNION
    (SELECT TRIM(V."DOCUMENTO") as "DOCUMENTO", TO_CHAR(V."FECHA_VENTA",\'HH24:mm:ss\') as"FECHA_VENTA"
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    WHERE "ID_CORTE" = $2
    ORDER BY CV."ID_VENTA" DESC
    LIMIT 1)';
		pg_prepare($this->conn,"qry1",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"qry1",$data));
    return json_encode($result);
  }

  function get_cortecaja_no_timbrada($arrayCCNT){
    $query = 'SELECT * FROM "CORTE_CAJA" WHERE "ID_EMPRESA"=$1 AND "ID_SUCURSAL"=$2 AND "ANIO_FISCAL"=$3 AND "TIMBRADA"= \'f\' ORDER BY "FECHA_CORTE" DESC';
    pg_prepare($this->conn,"qry1",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"qry1",$arrayCCNT));
    return json_encode($result,JSON_NUMERIC_CHECK);
  }

  function updt_cortecaja_timbrada($idcorte,$idfactura,$idcfdi){
    $query = 'UPDATE "CORTE_CAJA" SET "TIMBRADA" = \'true\' WHERE "ID_CORTE" = $1';
    pg_prepare($this->conn,"upatequery",$query);
		$result = pg_execute($this->conn,"upatequery",array($idcorte));
    
    $query1 = 'INSERT INTO "CFDI_FACTURAS" ("ID_CFDI","ID_FACTURA") VALUES($1,$2)';
    pg_prepare($this->conn,"insertquery",$query1);
		$result = pg_execute($this->conn,"insertquery",array($idcfdi,$idfactura));
    return json_encode(array("msg"=>"ok"));
  }

}
?>