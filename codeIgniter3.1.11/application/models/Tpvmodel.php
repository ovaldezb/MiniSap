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

	function registra_venta($documento,$idcliente,$idvendedor,$fechaventa,$aniofiscal,$idempresa,$idtipopago,
													$pagoefectivo,$pagotarjeta,$pagocheques,$pagovales,$idtarjea,$idbanco,$idvales,$importe,$cambio,$idsucursal)
	{
		$pstmt = 'SELECT * FROM registra_venta($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", array($documento,$idcliente,$idvendedor,$fechaventa,$aniofiscal,$idempresa,$idtipopago,
																																		$pagoefectivo,$pagotarjeta,$pagocheques,$pagovales,$idtarjea,$idbanco,$idvales,$importe,$cambio,$idsucursal)));
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

	function getfacturas($arrayData){
		$query = 'SELECT V."ID_VENTA", V."DOCUMENTO",V."CODIGO_VENDEDOR",
		V."FECHA_VENTA",V."IMPORTE", C."CLAVE", C."NOMBRE" as "CLIENTE", E."NOMBRE" as "VENDEDOR", V."CVE_FORMA_PAGO"
		FROM "VENTAS" as V
		INNER JOIN "CLIENTE" as C ON C."ID_CLIENTE" = V."CODIGO_CLIENTE"
		INNER JOIN "VENDEDOR" as E ON E."ID_VENDEDOR" = V."CODIGO_VENDEDOR"
		WHERE V."ID_EMPRESA" = $1 AND V."ANIO_FISCAL" = $2';
		pg_prepare($this->conn,"select_fact",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_fact",$arrayData));
		return json_encode($result);
	}

	function eliminaFacturaById($idventa,$idsucursal){
		$query = 'SELECT * FROM eliminar_factura($1,$2)';
		pg_prepare($this->conn,"elimina",$query);
		$result = pg_execute($this->conn,"elimina",array($idventa,$idsucursal));
		return json_encode($result);
	}
}
?>
