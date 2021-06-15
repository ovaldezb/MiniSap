<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tpvmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_items($idEmpresa,$desc,$tipo_req)
	{
		if($tipo_req == 'C')
		{
			$condition = 'AND "TIPO_PS" = \'P\' ';
		}else {
			$condition = ' ';
		}

		$query = 'SELECT P."ID_PRODUCTO",
							"DESCRIPCION",
							TRIM("CODIGO") as "CODIGO",
							to_char("PRECIO_LISTA",\'L999,999,999.00\') as "PREC_LISTA_DISP",
							"PRECIO_LISTA",
							to_char("PRECIO_COMPRA",\'L999,999,999.00\') AS "PRECIO_COMPRA_DISP",
							"PRECIO_COMPRA",
							"IVA",
              TRIM(L."NOMBRE") as "LINEA",
							TRIM("UNIDAD_MEDIDA") as "UNIDAD_MEDIDA",
							SUM(S."STOCK") as "STOCK",
							"IMAGEN","ES_PROMO","ES_DESCUENTO","PRECIO_PROMO","PRECIO_DESCUENTO","TIPO_PS"
							FROM "PRODUCTO" as P 
              INNER JOIN  "PRODUCTO_SUCURSAL" as S ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
              INNER JOIN "LINEA" as L ON L."ID_LINEA" = P."ID_LINEA"
							WHERE UPPER("DESCRIPCION") LIKE  UPPER($1)'
							. $condition .
							'AND P."ACTIVO" = true
							AND P."ID_EMPRESA" = $2
							GROUP BY P."ID_PRODUCTO",
											"DESCRIPCION","CODIGO","PRECIO_LISTA",
											"PRECIO_COMPRA", "IVA",
											"UNIDAD_MEDIDA","IMAGEN",
											"ES_PROMO","ES_DESCUENTO","PRECIO_PROMO","PRECIO_DESCUENTO","TIPO_PS",
                      L."NOMBRE"
							ORDER BY "DESCRIPCION"';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($desc,$idEmpresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_items_vacio($idEmpresa,$tipo_req)
	{
		if($tipo_req == 'C')
		{
				$condition = 'WHERE "TIPO_PS" = \'P\' ';
		}else {
			$condition = ' ';
		}
		$query = 'SELECT P."ID_PRODUCTO","DESCRIPCION",TRIM("CODIGO") as "CODIGO",to_char("PRECIO_LISTA",\'L999,999,999.00\') as "PREC_LISTA_DISP",
							"PRECIO_LISTA",
							to_char("PRECIO_COMPRA",\'L999,999,999.00\') AS "PRECIO_COMPRA_DISP",
							"PRECIO_COMPRA","IVA",
							TRIM("UNIDAD_MEDIDA") as "UNIDAD_MEDIDA",
							SUM(S."STOCK") as "STOCK",
							"IMAGEN","ES_PROMO","ES_DESCUENTO","PRECIO_PROMO","PRECIO_DESCUENTO","TIPO_PS"
							FROM "PRODUCTO" as P INNER JOIN  "PRODUCTO_SUCURSAL" as S
							ON P."ID_PRODUCTO" = S."ID_PRODUCTO" '
							.$condition.
							'AND P."ACTIVO" = true
							AND P."ID_EMPRESA" = $1
							GROUP BY P."ID_PRODUCTO","DESCRIPCION","CODIGO",
											"PRECIO_LISTA","PRECIO_COMPRA", "IVA",
											"UNIDAD_MEDIDA","IMAGEN",
											"ES_PROMO",
											"ES_DESCUENTO",
											"PRECIO_PROMO",
											"PRECIO_DESCUENTO","TIPO_PS"
							ORDER BY "DESCRIPCION"';
		pg_prepare($this->conn,"select_prod_vacio",$query);
		$result =  pg_fetch_all(pg_execute($this->conn,"select_prod_vacio",array($idEmpresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function registra_venta($arrayVenta)
	{
		$pstmt = 'SELECT * FROM registra_venta($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,$22)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", $arrayVenta));
		return json_encode($result);
	}

	function registra_venta_producto($data)
	{
		$pstmt = 'SELECT * FROM venta_producto($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", $data));
		return json_encode($result);
	}

	function get_items_by_suc($idProducto,$idSucursal)
	{
		$query = 'SELECT P."TIPO_PS", "STOCK"
		FROM "PRODUCTO" as P INNER JOIN "PRODUCTO_SUCURSAL" As PS
		ON P."ID_PRODUCTO" = PS."ID_PRODUCTO"
		WHERE P."ID_PRODUCTO" = $1 AND "ID_SUCURSAL" = $2 AND P."ACTIVO" = true';
		pg_prepare($this->conn,"select_stock",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "select_stock", array($idProducto,$idSucursal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_productos_for_each_sucursal($idProducto)
	{
		$query = 'SELECT A."STOCK",TRIM(B."ALIAS") as "ALIAS", B."DIRECCION"
		FROM "PRODUCTO_SUCURSAL" as A INNER JOIN "SUCURSALES" as B
		ON A."ID_SUCURSAL" = B."ID_SUCURSAL" WHERE A."ID_PRODUCTO" = $1';
		pg_prepare($this->conn,"select_stock",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "select_stock", array($idProducto)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function getventabyid($idVenta){
		$query = 'SELECT * FROM "VENTAS" WHERE "ID_VENTA" = $1';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idVenta)));
		return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
	}

  function get_data_impr_ticket($idVenta){
    $query1 = 'SELECT "DOCUMENTO","PAG_EFECTIVO","PAG_TARJETA","PAG_CHEQUE","PAG_VALES",C."NOMBRE","ID_TIPO_PAGO" 
    FROM "VENTAS" as V
    INNER JOIN "CLIENTE" as C ON V."CODIGO_CLIENTE" = C."ID_CLIENTE"
    WHERE "ID_VENTA" = $1';
    pg_prepare($this->conn,"select_venta",$query1);
		$result1 = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idVenta)));

    $query2 = 'SELECT "CANTIDAD",P."DESCRIPCION","IMPORTE",P."PRECIO_LISTA",P."ES_DESCUENTO" as "ESDSCTO", 
    P."PRECIO_DESCUENTO" as "DESCUENTO", P."IVA"
    FROM "VENTAS_PRODUCTO" as V
    INNER JOIN "PRODUCTO" as P ON P."ID_PRODUCTO" = V."ID_PRODUCTO"
    WHERE "ID_VENTA" = $1';
    pg_prepare($this->conn,"select_venta_prod",$query2);
		$result2 = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idVenta)));

    return json_encode(array("datos"=>$result1[0],"ventas"=>$result2),JSON_NUMERIC_CHECK);
  }


	function getventadetallebyid($idFactura){
		$query = 'SELECT VP."CANTIDAD",VP."PRECIO" as "PRECIO_LISTA",VP."IMPORTE", VP."DESCUENTO" as "DESCUENTO",
              CASE WHEN VP."DESCUENTO" > 0 THEN \'t\' ELSE \'f\' END as "ESDSCTO",
              CASE WHEN M."DESCRIPCION" IS NULL THEN TRIM(P."DESCRIPCION") ELSE TRIM(P."DESCRIPCION")|| \' [\' || M."DESCRIPCION"||\']\' END as "DESCRIPCION",
              TRIM(P."UNIDAD_MEDIDA") as "UNIDAD_MEDIDA", TRIM(P."COD_CFDI") as "COD_CFDI",
              P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
              CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
              TRIM(P."CODIGO") as "CODIGO"
              FROM "VENTAS_PRODUCTO" as VP
              INNER JOIN "VENTAS" as V ON V."ID_VENTA" = VP."ID_VENTA"
              INNER JOIN "PRODUCTO" as P ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
              INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
              LEFT OUTER JOIN "CALIDAD_MADERA" as M ON M."ID_CALIDAD_MADERA" = VP."ID_CALIDAD_MADERA"          
              WHERE V."ID_FACTURA" = $1';				
    
    
		pg_prepare($this->conn,"select_venta_prod",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idFactura)));		
		return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
	}

  function getfact_prod_id($idFactura){
    $query = 'SELECT "NOMBRE"||\'-\'||"CLAVE" as "DESCRIPCION", 1 as "CANTIDAD", \'CC\' as "UNIDAD_MEDIDA", \'f\' as "ESDSCTO", "IMPORTE" as "PRECIO_LISTA", 0 as "DESCUENTO", "IMPORTE", 0 as "IVA"  
    FROM "FACTURA_PRODUCTO" WHERE "ID_FACTURA" = $1 ORDER BY "CLAVE"';				
    pg_prepare($this->conn,"select_factura_prod",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"select_factura_prod",array($idFactura)));		
    return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
  }

  function getventadetallebyVentaId($idFactura){
    $query = 'SELECT VP."CANTIDAD",VP."PRECIO" as "PRECIO",VP."IMPORTE", VP."DESCUENTO" as "DESCUENTO",
              TRIM(P."DESCRIPCION") as "DESCRIPCION",TRIM(P."UNIDAD_MEDIDA") as "UNIDAD_MEDIDA", TRIM(P."COD_CFDI") as "COD_CFDI",
              P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
              CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
              TRIM(P."CODIGO") as "CODIGO"
              FROM "VENTAS_PRODUCTO" as VP
              INNER JOIN "VENTAS" as V ON V."ID_VENTA" = VP."ID_VENTA"
              INNER JOIN "PRODUCTO" as P ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
              INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
              WHERE VP."ID_VENTA" = $1';				
    pg_prepare($this->conn,"select_venta_prod",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idFactura)));		
    return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
  }

	public function dataOperByDate($arrayDates){
		$query1 = 'SELECT V."DOCUMENTO", 
          CASE WHEN VP."COUNT" IS NULL THEN 0 ELSE VP."COUNT" END,
          V."ID_TIPO_PAGO", V."IMPORTE",
					CASE WHEN V."ID_FACTURA" IS NULL THEN 0 ELSE V."ID_FACTURA" END as "ID_FACTURA",
					V."ID_VENTA",V."CANCELADO",V."CORTECAJA", V."FACTURADO",V."ID_FACTURA",V."IVA",
          CASE WHEN V."PAG_EFECTIVO" > 0 THEN \'EF\' ELSE
            (CASE WHEN V."PAG_TARJETA" > 0 THEN \'TA\' ELSE
              (CASE WHEN V."PAG_CHEQUE" > 0 THEN \'CH\' ELSE 
                (CASE WHEN V."PAG_VALES" > 0 THEN \'VA\' END)
              END)
             END)
          END as "TIPO_PAGO"
					FROM "VENTAS" as V
					LEFT JOIN (SELECT "ID_VENTA", COUNT(*) AS "COUNT" FROM "VENTAS_PRODUCTO" GROUP BY "ID_VENTA")
					AS VP ON VP."ID_VENTA" = V."ID_VENTA"
					WHERE V."ANIO_FISCAL" = $1
					AND V."FECHA_VENTA" >= $2 
					AND V."FECHA_VENTA" <= $3
					AND V."ID_EMPRESA" = $4
					AND V."CORTECAJA" = \'false\'
          AND V."ID_VALES" IS NOT NULL
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
          AND "CORTECAJA" = \'false\'
          AND "CANCELADO" = \'false\'
          AND "ID_VALES" IS NOT NULL
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
          AND "CORTECAJA" = \'false\'
          AND "CANCELADO" = \'false\'
          AND "ID_VALES" IS NOT NULL
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
          AND "ID_VALES" IS NOT NULL
          AND "CORTECAJA" = \'false\'
          AND "CANCELADO" = \'true\'';
    pg_prepare($this->conn,"qry4",$query4);
    $result5 = pg_fetch_all(pg_execute($this->conn,"qry4",$arrayDates));

		return json_encode(array("ventas"=>$result1,"pagos"=>$result2,"tipopago"=>$result4,"cancelados"=>$result5[0]),JSON_NUMERIC_CHECK);
	}

	function updateventatrue($idventa){
		$query = 'UPDATE "VENTAS" SET "CORTECAJA" = true
		WHERE "ID_VENTA" = $1';
		pg_prepare($this->conn,"updtventatrue",$query);
		$result = pg_execute($this->conn,"updtventatrue",array($idventa));
		return json_encode(array("res"=>$result));
	}

  function delete_venta_by_id($idventa){
    $query = 'SELECT * FROM cancela_venta($1)';
		pg_prepare($this->conn,"cancela_venta",$query);
		$result = pg_execute($this->conn,"cancela_venta",array($idventa));
		return json_encode($result);
  }

  function save_corte_caja($arrayCorteCaja){
    $query = 'SELECT * FROM registra_corte($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17)';
    pg_prepare($this->conn,"insert_corte",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_corte",$arrayCorteCaja));
		return json_encode($result);
  }

  function corte_venta($idcorte,$idventa){
    $query = 'INSERT INTO "CORTE_VENTA" ("ID_CORTE","ID_VENTA") 
      VALUES($1,$2)';
    pg_prepare($this->conn,"insert_corte_venta",$query);
    $result = pg_execute($this->conn,"insert_corte_venta",array($idcorte,$idventa));
		return json_encode(array("res"=>$result));
  }

  function save_producto_factura($arrayDataFact){
    $query = 'INSERT INTO "FACTURA_PRODUCTO" ("ID_FACTURA","CLAVE","NOMBRE","IMPORTE","IVA") VALUES($1,$2,$3,$4,$5)';
    pg_prepare($this->conn,"insert_producto_fact",$query);
    $result = pg_execute($this->conn,"insert_producto_fact",$arrayDataFact);
    return json_encode(array("res"=>"Ok"));
  }

  
  function update_factura_producto($arrayDataFact){
    $query = 'UPDATE "CORTE_CAJA" SET "ID_FACTURA"=$1 WHERE "ID_CORTE" = $2';
    pg_prepare($this->conn,"insert_producto_fact",$query);
    $result = pg_execute($this->conn,"insert_producto_fact",$arrayDataFact);
    return json_encode(array("res"=>"Ok"));
  }
}
?>
