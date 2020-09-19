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
							TRIM("UNIDAD_MEDIDA") as "UNIDAD_MEDIDA",
							SUM(S."STOCK") as "STOCK",
							"IMAGEN","ES_PROMO","ES_DESCUENTO","PRECIO_PROMO","PRECIO_DESCUENTO","TIPO_PS"
							FROM "PRODUCTO" as P INNER JOIN  "PRODUCTO_SUCURSAL" as S
							ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
							WHERE UPPER("DESCRIPCION") LIKE  UPPER($1)'
							. $condition .
							'AND P."ACTIVO" = true
							AND P."ID_EMPRESA" = $2
							GROUP BY P."ID_PRODUCTO",
											"DESCRIPCION","CODIGO","PRECIO_LISTA",
											"PRECIO_COMPRA", "IVA",
											"UNIDAD_MEDIDA","IMAGEN",
											"ES_PROMO","ES_DESCUENTO","PRECIO_PROMO","PRECIO_DESCUENTO","TIPO_PS"
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
		$pstmt = 'SELECT * FROM registra_venta($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", $arrayVenta));
		return json_encode($result);
	}

	function registra_venta_producto($idventa,$idProducto,$cantidad,$precio,$importe,$idsucursal,$tipo_ps)
	{
		$pstmt = 'SELECT * FROM venta_producto($1,$2,$3,$4,$5,$6,$7)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", array($idventa,$idProducto,$cantidad,$precio,$importe,$idsucursal,$tipo_ps)));
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

	function getventadetallebyid($idVenta){
		$query = 'SELECT VP."CANTIDAD",VP."PRECIO",VP."IMPORTE",
					TRIM(P."DESCRIPCION") as "DESCRIPCION",TRIM(P."UNIDAD_MEDIDA") as "UNIDAD_MEDIDA", TRIM(P."COD_CFDI") as "COD_CFDI",
					P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
					CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
					TRIM(P."CODIGO") as "CODIGO"
				FROM "VENTAS_PRODUCTO" as VP
				INNER JOIN "PRODUCTO" as P ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
				INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
				WHERE VP."ID_VENTA" = $1';				
		pg_prepare($this->conn,"select_venta_prod",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idVenta)));		
		return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
	}

	public function dataOperByDate($arrayDates){
		$query1 = 'SELECT V."DOCUMENTO", VP."COUNT",V."ID_TIPO_PAGO", V."IMPORTE",
					CASE WHEN V."ID_FACTURA" IS NULL THEN 0 ELSE V."ID_FACTURA" END as "ID_FACTURA",
					V."ID_VENTA"
					FROM "VENTAS" as V
					INNER JOIN (SELECT "ID_VENTA", COUNT(*) AS "COUNT" FROM "VENTAS_PRODUCTO" GROUP BY "ID_VENTA")
					AS VP ON VP."ID_VENTA" = V."ID_VENTA"
					WHERE V."ANIO_FISCAL" = $1
					AND V."FECHA_VENTA" >= $2 
					AND V."FECHA_VENTA" <= $3
					AND V."FACTURADO" = \'false\'
					ORDER BY V."ID_VENTA"';
		pg_prepare($this->conn,"qry1",$query1);
		$result1 = pg_fetch_all(pg_execute($this->conn,"qry1",$arrayDates));

		$query2 = 'SELECT SUM("PAG_EFECTIVO") - SUM("CAMBIO") as "EFECTIVO",SUM("PAG_TARJETA") as "TARJETA",
					SUM("PAG_CHEQUE") as "CHEQUE",SUM("PAG_VALES") as "VALES" 
					FROM "VENTAS"
					WHERE "ANIO_FISCAL"=$1
					AND "FECHA_VENTA" >= $2 
					AND "FECHA_VENTA" <= $3
					AND "ID_TIPO_PAGO" = 1
					GROUP BY "ID_TIPO_PAGO"';
		pg_prepare($this->conn,"qry2",$query2);
		$result2 = pg_fetch_all(pg_execute($this->conn,"qry2",$arrayDates));

		$query3 = 'SELECT SUM("IMPORTE"), "ID_TIPO_PAGO" 
					FROM "VENTAS"
					WHERE "ANIO_FISCAL"=$1
					AND "FECHA_VENTA" >= $2 
					AND "FECHA_VENTA" <= $3
					GROUP BY "ID_TIPO_PAGO"';
		pg_prepare($this->conn,"qry3",$query3);
		$result3 = pg_fetch_all(pg_execute($this->conn,"qry3",$arrayDates));

		return json_encode(array("ventas"=>$result1,"pagos"=>$result2,"tipopago"=>$result3),JSON_NUMERIC_CHECK);
	}

	function updateventatrue($idfactura,$idventa){
		$query = 'UPDATE "VENTAS" SET "FACTURADO" = true, "ID_FACTURA" = $1 
		WHERE "ID_VENTA" = $2';
		pg_prepare($this->conn,"updtventatrue",$query);
		$result = pg_execute($this->conn,"updtventatrue",array($idfactura,$idventa));
		return json_encode(array("res"=>$result));
	}
}
?>
