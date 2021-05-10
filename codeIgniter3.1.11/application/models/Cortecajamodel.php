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

  function reporte_by_month($idempresa,$aniofiscal,$fecini,$fecfin){
    $query = 'SELECT TO_CHAR("FECHA_CORTE",\'DD-mon-YYYY HH24:MI\') as "FECHA", "OPERACIONES", "CANCELADAS" as "CANCELADOS", "IMPORTE", "ID_CORTE" 
      FROM "CORTE_CAJA"
      WHERE "ID_EMPRESA" = $1
      AND "ANIO_FISCAL" = $2
      AND "FECHA_CORTE" >= $3
      AND "FECHA_CORTE"<= $4 
      ORDER BY "FECHA"';
		pg_prepare($this->conn, "sel_month", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "sel_month", array($idempresa,$aniofiscal,$fecini,$fecfin)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  /*function getOperMonthByDate($arrayDates){
    $query2 = 'SELECT SUM("PAG_EFECTIVO") - SUM("CAMBIO") as "EFECTIVO",SUM("PAG_TARJETA") as "TARJETA",
              SUM("PAG_CHEQUE") as "CHEQUE",SUM("PAG_VALES") as "VALES" 
              FROM "VENTAS"
              WHERE "ANIO_FISCAL"=$1
              AND "FECHA_VENTA" >= $2 
              AND "FECHA_VENTA" <= $3
              AND "ID_EMPRESA" = $4
              AND "ID_TIPO_PAGO" = 1
              AND "CANCELADO" = \'false\'
              GROUP BY "ID_TIPO_PAGO"';
    pg_prepare($this->conn,"qry2",$query2);
    $result2 = pg_fetch_all(pg_execute($this->conn,"qry2",$arrayDates));
    if($result2 == false){
    $result2 = array(array("EFECTIVO"=>0,"TARJETA"=>0,"CHEQUE"=>0,"VALES"=>0));
    }

    $query3 = 'SELECT SUM("IMPORTE") as "SUMA", "ID_TIPO_PAGO" 
        FROM "VENTAS"
        WHERE "ANIO_FISCAL"=$1					
        AND "FECHA_VENTA" >= $2 
        AND "FECHA_VENTA" <= $3
        AND "ID_EMPRESA" = $4
        AND "CANCELADO" = \'false\'
        GROUP BY "ID_TIPO_PAGO"';
    pg_prepare($this->conn,"qry3",$query3);

    $result3 = pg_fetch_all(pg_execute($this->conn,"qry3",$arrayDates));
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
    return json_encode(array("pagos"=>$result2[0],"tipopago"=>$result4),JSON_NUMERIC_CHECK);
  }*/

  public function dataOperByIDCC($arrayIdCC){
		
    $query1 = 'SELECT CV."ID_VENTA",V."DOCUMENTO",VP."COUNT",V."ID_TIPO_PAGO", 
    CASE WHEN V."CANCELADO" = \'t\' THEN 0 ELSE V."IMPORTE" END AS "IMPORTE",
    V."CANCELADO"
    FROM "CORTE_VENTA" as CV 
    INNER JOIN "VENTAS" as V ON V."ID_VENTA" = CV."ID_VENTA"
    INNER JOIN (SELECT "ID_VENTA", COUNT(*) AS "COUNT" FROM "VENTAS_PRODUCTO" GROUP BY "ID_VENTA")
    AS VP ON VP."ID_VENTA" = V."ID_VENTA"
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

}
?>