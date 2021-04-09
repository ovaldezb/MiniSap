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


	function registra_pedido($pedido_data)
	{
		$pstmt = 'SELECT * FROM registra_pedido($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,false)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", $pedido_data));
		return json_encode($result);
	}

	function registra_pedido_producto($idventa,$idProducto,$cantidad,$precio,$importe,$idsucursal)
	{
		$pstmt = 'SELECT * FROM pedido_producto($1,$2,$3,$4,$5,$6)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", array($idventa,$idProducto,$cantidad,$precio,$importe,$idsucursal)));
		return json_encode($result);
	}

	function get_pedidos($idempresa,$aniofiscal){
		$query = 'SELECT P."ID_PEDIDO", P."DOCUMENTO", C."NOMBRE" AS "CLIENTE", C."CLAVE", V."NOMBRE" AS "VENDEDOR", 
		P."FECHA_PEDIDO", P."IMPORTE", P."VENDIDO" 
		FROM "PEDIDOS" AS P
		INNER JOIN "CLIENTE" AS C ON C."ID_CLIENTE" = P."ID_CLIENTE"
		INNER JOIN "VENDEDOR" AS V ON V."ID_VENDEDOR" = P."ID_VENDEDOR"
		WHERE P."ID_EMPRESA" = $1
		AND P."ANIO_FISCAL" = $2
		ORDER BY P."DOCUMENTO"';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idempresa,$aniofiscal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function getpedidobyid($idPedido){
		$query = 'SELECT TRIM(P."DOCUMENTO") as "DOCUMENTO", C."NOMBRE" AS "CLIENTE", 
		TRIM(C."CLAVE") as "CLAVE", V."NOMBRE" AS "VENDEDOR", V."ID_VENDEDOR",
		P."FECHA_PEDIDO", P."IMPORTE", P."VENDIDO", P."CONTACTO", P."CUENTA",to_char(P."FECHA_ENTREGA",\'DD/MM/YYYY\') as "FECHA_ENTREGA",
		P."ID_MONEDA",P."ID_TIPO_PAGO",P."DIAS",P."ID_FORMA_PAGO" 
		FROM "PEDIDOS" AS P
		INNER JOIN "CLIENTE" AS C ON C."ID_CLIENTE" = P."ID_CLIENTE"
		INNER JOIN "VENDEDOR" AS V ON V."ID_VENDEDOR" = P."ID_VENDEDOR"
		WHERE P."ID_PEDIDO" = $1';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idPedido)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function getpedidodetallebyid($idPedido){
		$query = 'SELECT VP."CANTIDAD",VP."PRECIO" as "PRECIO_LISTA",VP."IMPORTE",
					TRIM(P."DESCRIPCION") as "DESCRIPCION",TRIM(P."UNIDAD_MEDIDA") as "UNIDAD", TRIM(P."COD_CFDI") as "COD_CFDI",
					P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
					CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
					TRIM(P."CODIGO") as "CODIGO"
				FROM "PEDIDO_PRODUCTO" as VP
				INNER JOIN "PRODUCTO" as P ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
				INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
				WHERE VP."ID_PEDIDO" = $1';				
		pg_prepare($this->conn,"select_venta_prod",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idPedido)));		
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function eliminapedido($idPedido){
		$query ='DELETE FROM "PEDIDOS" WHERE "ID_PEDIDO"=$1';
		pg_prepare($this->conn,"del_pedido",$query);
		$result = pg_execute($this->conn,"del_pedido",array($idPedido));
		
		$query ='DELETE FROM "PEDIDO_PRODUCTO" WHERE "ID_PEDIDO"=$1';
		pg_prepare($this->conn,"del_pedido_det",$query);
		$result = pg_execute($this->conn,"del_pedido_det",array($idPedido));
		
		return json_encode($result);
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
    V."ID_VENTA"
		FROM "FACTURA" as F
		LEFT JOIN "CLIENTE" as C ON C."ID_CLIENTE" = F."ID_CLIENTE"
		lEFT JOIN "VENDEDOR" as E ON E."ID_VENDEDOR" = F."ID_VENDEDOR"
    LEFT JOIN "VENTAS" as V ON V."ID_FACTURA" = F."ID_FACTURA"
		WHERE F."ID_EMPRESA" = $1 
		AND F."ANIO_FISCAL" = $2
		ORDER BY F."FECHA_FACTURA" DESC';
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
