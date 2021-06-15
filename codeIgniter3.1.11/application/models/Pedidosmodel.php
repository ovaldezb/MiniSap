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


	function registra_pedido($pedido_data)
	{
		$pstmt = 'SELECT * FROM registra_pedido($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,false,$16,$17,$18)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", $pedido_data));
		return json_encode($result);
	}

	function registra_pedido_producto($pedido_data)
	{
		$pstmt = 'INSERT INTO "PEDIDO_PRODUCTO" 
    ("ID_PEDIDO","ID_PRODUCTO","CANTIDAD","PRECIO","IMPORTE","DESCUENTO","ID_CALIDAD_MADERA") 
    VALUES($1,$2,$3,$4,$5,$6,$7)';
		pg_prepare($this->conn,"prstmt",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "prstmt", $pedido_data));
		return json_encode($result);
	}

	function get_pedidos($idempresa,$aniofiscal,$idsucrsal){
		$query = 'SELECT P."ID_PEDIDO", 
			P."DOCUMENTO", 
			C."NOMBRE" AS "CLIENTE", 
			C."CLAVE", V."NOMBRE" AS "VENDEDOR", 
			P."FECHA_PEDIDO", 
			P."IMPORTE", 
			P."VENDIDO"
		FROM "PEDIDOS" AS P
		INNER JOIN "CLIENTE" AS C ON C."ID_CLIENTE" = P."ID_CLIENTE"
		INNER JOIN "VENDEDOR" AS V ON V."ID_VENDEDOR" = P."ID_VENDEDOR"
		WHERE P."ID_EMPRESA" = $1
		AND P."ANIO_FISCAL" = $2
    AND P."ID_SUC_PIDIO" = $3
    AND P."VENDIDO" = false
		ORDER BY P."DOCUMENTO" DESC';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idempresa,$aniofiscal,$idsucrsal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

  function get_pedidos_activos($idempresa,$aniofiscal, $idsucrsal){
		$query = 'SELECT P."ID_PEDIDO", 
			P."DOCUMENTO", 
			C."NOMBRE" AS "CLIENTE", 
			C."CLAVE", V."NOMBRE" AS "VENDEDOR", 
			TO_CHAR(P."FECHA_PEDIDO",\'DD-Mon-YYYY HH24:MM\') as "FECHA_PEDIDO", 
			P."IMPORTE", 
			P."VENDIDO",
      TRIM(P."ESTATUS") as "ESTATUS",
      TRIM(U."CLAVE_USR") as "CLAVE_USR"
		FROM "PEDIDOS" AS P
		INNER JOIN "CLIENTE" AS C ON C."ID_CLIENTE" = P."ID_CLIENTE"
		INNER JOIN "VENDEDOR" AS V ON V."ID_VENDEDOR" = P."ID_VENDEDOR"
    LEFT JOIN "USUARIO" as U ON U."ID_USUARIO" = P."ID_USUARIO"
		WHERE P."ID_EMPRESA" = $1
		AND P."ANIO_FISCAL" = $2
    AND P."ID_SUC_PIDIO" = $3
		ORDER BY P."FECHA_PEDIDO" DESC';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idempresa,$aniofiscal, $idsucrsal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function getpedidobyid($idPedido){
		$query = 'SELECT TRIM(P."DOCUMENTO") as "DOCUMENTO", 
			C."NOMBRE" AS "CLIENTE", 
			TRIM(C."CLAVE") as "CLAVE", 
			V."NOMBRE" AS "VENDEDOR", 
			V."ID_VENDEDOR",
			P."FECHA_PEDIDO", 
			P."IMPORTE", 
			P."VENDIDO", P."COMENTARIOS", P."CUENTA",to_char(P."FECHA_ENTREGA",\'DD/MM/YYYY\') as "FECHA_ENTREGA",
			P."ID_MONEDA",
			P."ID_TIPO_PAGO",
			P."DIAS",
			P."ID_FORMA_PAGO", 
			P."ID_DOMICILIO",
			P."ID_CLIENTE",
      C."DOMICILIO" as "CLI_DOMICILIO",
      C."TELEFONO",
      C."RFC",
      P."ID_METODO_PAGO",
      C."ID_USO_CFDI",
      C."CONTACTO",
      TRIM(P."ESTATUS") as "ESTATUS"
		FROM "PEDIDOS" AS P
		INNER JOIN "CLIENTE" AS C ON C."ID_CLIENTE" = P."ID_CLIENTE"
		INNER JOIN "VENDEDOR" AS V ON V."ID_VENDEDOR" = P."ID_VENDEDOR"
		WHERE P."ID_PEDIDO" = $1 ';
		pg_prepare($this->conn,"select_venta",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta",array($idPedido)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

  function getpedidodetallebyid($idPedido){
		$query = 'SELECT VP."CANTIDAD",VP."PRECIO" as "PRECIO_LISTA",
				VP."IMPORTE",
        VP."DESCUENTO",
        \'true\' as "ESDSCTO",
				CASE WHEN M."DESCRIPCION" IS NULL THEN TRIM(P."DESCRIPCION") ELSE TRIM(P."DESCRIPCION")|| \' [\' || M."DESCRIPCION"||\']\' END as "DESCRIPCION",
				TRIM(P."UNIDAD_MEDIDA") as "UNIDAD_MEDIDA", 
				TRIM(P."COD_CFDI") as "COD_CFDI",
				P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
				CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
				TRIM(P."CODIGO") as "CODIGO",
				VP."ID_PRODUCTO" as "ID_PRODUCTO",
				P."TIPO_PS",
        TRIM(L."NOMBRE") as "LINEA",
        VP."ID_CALIDAD_MADERA"
				FROM "PRODUCTO" as P
				INNER JOIN "PEDIDO_PRODUCTO" as VP ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
				INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
        INNER JOIN "LINEA" as L ON L."ID_LINEA" = P."ID_LINEA"
        LEFT OUTER JOIN "CALIDAD_MADERA" as M ON M."ID_CALIDAD_MADERA" = VP."ID_CALIDAD_MADERA"
				WHERE VP."ID_PEDIDO" = $1 
        AND L."ID_EMPRESA" = P."ID_EMPRESA"';				
		pg_prepare($this->conn,"select_venta_prod",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idPedido)));		
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function pedido_detallebyid_existencia($idPedido,$idsucrsal){
		$query = 'SELECT VP."CANTIDAD",VP."PRECIO" as "PRECIO_LISTA",
				VP."IMPORTE",
        VP."DESCUENTO",
        \'true\' as "ESDSCTO",
				CASE WHEN M."DESCRIPCION" IS NULL THEN TRIM(P."DESCRIPCION") ELSE TRIM(P."DESCRIPCION")||\' [\'||M."DESCRIPCION"||\']\' END as "DESCRIPCION",
				TRIM(P."UNIDAD_MEDIDA") as "UNIDAD_MEDIDA", 
				TRIM(P."COD_CFDI") as "COD_CFDI",
				P."IVA",TRIM(P."UNIDAD_SAT") as "UNIDAD_SAT",
				CASE WHEN P."IEPS" IS NULL THEN \'0\' ELSE P."IEPS" END as "IEPS",TRIM(I."NOMBRE") as "TIPOFACTOR",
				TRIM(P."CODIGO") as "CODIGO",
				VP."ID_PRODUCTO" as "ID_PRODUCTO",
				P."TIPO_PS",
        PS."STOCK",
        TRIM(L."NOMBRE") as "LINEA",
        VP."ID_CALIDAD_MADERA"
				FROM "PEDIDO_PRODUCTO" as VP
				INNER JOIN "PRODUCTO" as P ON VP."ID_PRODUCTO" = P."ID_PRODUCTO"
        INNER JOIN "PRODUCTO_SUCURSAL" as PS ON PS."ID_PRODUCTO" = VP."ID_PRODUCTO"
				INNER JOIN "IEPS" as I on P."ID_IEPS" = I."ID_IEPS"
        INNER JOIN "LINEA" as L ON L."ID_LINEA" = P."ID_LINEA"
        LEFT OUTER JOIN "CALIDAD_MADERA" as M ON M."ID_CALIDAD_MADERA" = VP."ID_CALIDAD_MADERA"
				WHERE VP."ID_PEDIDO" = $1 
        AND PS."ID_SUCURSAL" = $2
        AND L."ID_EMPRESA" = P."ID_EMPRESA" ';				
		pg_prepare($this->conn,"select_venta_prod",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_venta_prod",array($idPedido,$idsucrsal)));		
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function eliminapedido($idPedido){
		$query ='UPDATE "PEDIDOS" SET "ESTATUS" = \'CANCELADO\' WHERE "ID_PEDIDO"=$1';
		pg_prepare($this->conn,"del_pedido",$query);
		$result = pg_execute($this->conn,"del_pedido",array($idPedido));
		return json_encode($result);
	}

  function borrapedidoproducto($idPedido){
    $query ='DELETE FROM "PEDIDO_PRODUCTO" WHERE "ID_PEDIDO"=$1';
		pg_prepare($this->conn,"del_pedido_det",$query);
		$result = pg_execute($this->conn,"del_pedido_det",array($idPedido));
		return json_encode($result);
  }

	function updatepedido($idPedido,$status, $idfactura){
		$query = 'UPDATE "PEDIDOS" SET "VENDIDO"=$1, "ID_FACTURA"=$2 WHERE "ID_PEDIDO"=$3';
		pg_prepare($this->conn,"upd_ped",$query);
		$result = pg_execute($this->conn,"upd_ped",array($status,$idfactura,$idPedido));
		return json_encode($result);
	}

  //actualiza todo el pedido
  function update_pedido_by_id($dataPedido){
    $query = 'UPDATE "PEDIDOS" SET 
    "ID_CLIENTE" =$1, 
    "ID_VENDEDOR"=$2, 
    "IMPORTE"=$3, 
    "ID_MONEDA" = $4,
    "ID_TIPO_PAGO"=$5, 
    "ID_FORMA_PAGO"=$6, 
    "ID_METODO_PAGO"=$7,
    "FECHA_ENTREGA" = $8,
    "ID_DOMICILIO" = $9,
    "COMENTARIOS" = $10
    WHERE "ID_PEDIDO" = $11';
    pg_prepare($this->conn,"upd_ped",$query);
		$result = pg_execute($this->conn,"upd_ped",$dataPedido);
		return json_encode($result);
  }
}
?>
