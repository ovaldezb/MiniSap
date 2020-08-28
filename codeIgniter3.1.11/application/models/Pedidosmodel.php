<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidosmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}


	function registra_pedido($documento,$idcliente,$idvendedor,$fechapedido,$aniofiscal,$idempresa,$idtipopago,$importe,$idsucursal)
	{
		$pstmt = 'SELECT * FROM registra_pedido($1,$2,$3,$4,$5,$6,$7,$8,false)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", array($documento,$idcliente,$idvendedor,$fechapedido,$aniofiscal,$idempresa,$importe,$idsucursal)));
		return json_encode($result);
	}

	function registra_pedido_producto($idventa,$idProducto,$cantidad,$precio,$importe,$idsucursal)
	{
		$pstmt = 'SELECT * FROM pedido_producto($1,$2,$3,$4,$5,$6)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", array($idventa,$idProducto,$cantidad,$precio,$importe,$idsucursal)));
		return json_encode($result);
	}

	function getpedidobyid($idVenta){
		$query = 'SELECT * FROM "PEDIDO" WHERE "ID_PEDIDO" = $1';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idVenta)));
		return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
	}

	function get_pedidos($aniofiscal){
		$query = 'SELECT * FROM "PEDIDOS" WHERE "ANIO_FISCAL" = $1';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($aniofiscal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function getpedidodetallebyid($idVenta){
		$query = 'SELECT VP."CANTIDAD",VP."PRECIO",VP."IMPORTE",
					TRIM(P."DESCRIPCION") as "DESCRIPCION",TRIM(P."UNIDAD_MEDIDA") as "UNIDAD_MEDIDA", TRIM(P."COD_CFDI") as "COD_CFDI",
					P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
					CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
					TRIM(P."CODIGO") as "CODIGO"
				FROM "PEDIDOS_PRODUCTO" as VP
				INNER JOIN "PRODUCTO" as P ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
				INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
				WHERE VP."ID_VENTA" = $1';				
		pg_prepare($this->conn,"select_venta_prod",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idVenta)));		
		return json_decode(json_encode($result,JSON_NUMERIC_CHECK),false);
	}
}
?>
