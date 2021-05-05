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
    $query = 
    'SELECT CF."FECHA",CF."OPERACIONES",
    CASE WHEN CT."CCANCEL" IS NULL THEN 0 ELSE CT."CCANCEL" END as "CANCELADOS", CF."IMPORTE"
    FROM
      (SELECT TO_CHAR("FECHA_VENTA",\'DD-MM-YYYY\') as "FECHA", COUNT("FECHA_VENTA") as "OPERACIONES",SUM("IMPORTE") as "IMPORTE"
        FROM "VENTAS" 
        WHERE "ID_EMPRESA" = $1
        AND "ANIO_FISCAL" = $2
        AND "FECHA_VENTA" >= $3
        AND "FECHA_VENTA"<= $4
        AND "CANCELADO" = false
        GROUP BY "FECHA" ) as CF
    LEFT JOIN (SELECT TO_CHAR("FECHA_VENTA",\'DD-MM-YYYY\') as "FECHA", COUNT("FECHA_VENTA") as "CCANCEL"
        FROM "VENTAS" 
        WHERE "ID_EMPRESA" = $5
        AND "ANIO_FISCAL" = $6
        AND "FECHA_VENTA" >= $7
        AND "FECHA_VENTA"<= $8
        AND "CANCELADO" = true
        GROUP BY "FECHA" ) as CT ON CT."FECHA" = CF."FECHA"
    ORDER BY CF."FECHA"
    ;';
		pg_prepare($this->conn, "sel_month", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "sel_month", array($idempresa,$aniofiscal,$fecini,$fecfin,$idempresa,$aniofiscal,$fecini,$fecfin)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

  function getOperMonthByDate($arrayDates){
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
  }

  public function dataOperByDateCC($arrayDates){
		$query1 = 'SELECT V."DOCUMENTO", VP."COUNT",V."ID_TIPO_PAGO", V."IMPORTE",
					CASE WHEN V."ID_FACTURA" IS NULL THEN 0 ELSE V."ID_FACTURA" END as "ID_FACTURA",
					V."ID_VENTA"
					FROM "VENTAS" as V
					INNER JOIN (SELECT "ID_VENTA", COUNT(*) AS "COUNT" FROM "VENTAS_PRODUCTO" GROUP BY "ID_VENTA")
					AS VP ON VP."ID_VENTA" = V."ID_VENTA"
					WHERE V."ANIO_FISCAL" = $1
					AND V."FECHA_VENTA" >= $2 
					AND V."FECHA_VENTA" <= $3
					AND V."ID_EMPRESA" = $4
          AND V."CANCELADO" = \'false\'
					ORDER BY V."ID_VENTA"';
		pg_prepare($this->conn,"qry1",$query1);
		$result1 = pg_fetch_all(pg_execute($this->conn,"qry1",$arrayDates));

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

		$query3 = 'SELECT SUM("IMPORTE"), "ID_TIPO_PAGO" 
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
          $result4 = array($result3[0], array("sum"=>"0","ID_TIPO_PAGO"=>"2"));
        }else{
          $result4 = array(array("sum"=>"0","ID_TIPO_PAGO"=>"1"),$result3[0]);
        }
      }else{
        $result4 = $result3;
      }
    }else{
      $result4 = array(array("sum"=>"0","ID_TIPO_PAGO"=>"1"),array("sum"=>"0","ID_TIPO_PAGO"=>"2"));
    }

    $query4 = 'SELECT COUNT(*) as "TOTAL" FROM "VENTAS"
          WHERE "ANIO_FISCAL" = $1
					AND "FECHA_VENTA" >= $2 
					AND "FECHA_VENTA" <= $3
					AND "ID_EMPRESA" = $4
          AND "CANCELADO" = \'true\'';
    pg_prepare($this->conn,"qry4",$query4);
    $result5 = pg_fetch_all(pg_execute($this->conn,"qry4",$arrayDates));

		return json_encode(array("ventas"=>$result1,"pagos"=>$result2,"tipopago"=>$result4,"cancelados"=>$result5[0]),JSON_NUMERIC_CHECK);
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
    $query = '(SELECT TRIM("DOCUMENTO") as "DOCUMENTO", TO_CHAR("FECHA_VENTA",\'HH24:mm:ss\') as"FECHA_VENTA"
    FROM "VENTAS"
    WHERE "ID_EMPRESA" = $1
    AND "ANIO_FISCAL" = $2
    AND "FECHA_VENTA" >= $3
    AND "FECHA_VENTA" <= $4
    ORDER BY "FECHA_VENTA"
    LIMIT 1)
    UNION
    (SELECT TRIM("DOCUMENTO") as "DOCUMENTO",TO_CHAR("FECHA_VENTA",\'HH24:mm:ss\') as "FECHA_VENTA"
    FROM "VENTAS"
    WHERE "ID_EMPRESA" = $5
    AND "ANIO_FISCAL" = $6
    AND "FECHA_VENTA" >= $7
    AND "FECHA_VENTA" <= $8
    ORDER BY "FECHA_VENTA" DESC
    LIMIT 1)';
		pg_prepare($this->conn,"qry1",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"qry1",$data));
    return json_encode($result);
  }

}
?>