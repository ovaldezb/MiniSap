<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Comprasmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_compras($id_empresa,$anio_fiscal)
	{
		$query = 'SELECT C."ID_COMPRA",to_char(C."FECHA_COMPRA",\'DD/MM/YYYY\') as "FECHA_COMPRA",
							TRIM(C."DOCUMENTO") AS "DOCUMENTO",
							TRIM(P."NOMBRE") as "PROVEEDOR",
							to_char(C."IMPORTE",\'L999,999,999.00\') as "IMPORTE",
							CASE "DIAS_PAGO"
							WHEN 0 THEN \'$             0.00\'
							ELSE to_char("IMPORTE",\'L999,999,999.00\')
							END as "SALDO",
							"DESCUENTO",
							"CLAVE_PROVEEDOR",
							"DIAS_PAGO",
							"TIPO_CAMBIO",
							"IVA",
							"TIPO_ORDENCOMPRA",
							C."ID_TIPO_PAGO" as "TIPO_PAGO",
							"ID_MONEDA" as "MONEDA",
							"CONTRA_RECIBO" as "CR",
							to_char(C."FECHA_REVISION",\'DD/MM/YYYY\') as "FECHA_REVISION",
							to_char(C."FECHA_PAGO",\'DD/MM/YYYY\') as "FECHA_PAGO",
							CASE "DIAS_PAGO"
							WHEN 0 THEN T."DESCRIPCION"
							ELSE T."DESCRIPCION" || \' \' ||"DIAS_PAGO" || \' días\'
							END as "FORMA_PAGO"
							FROM "COMPRAS" AS C INNER JOIN "PROVEEDORES" AS P
							ON C."CLAVE_PROVEEDOR" = P."CLAVE"
							INNER JOIN "TIPO_PAGO" as T
							ON C."ID_TIPO_PAGO" = T."ID_TIPO_PAGO"
							WHERE C."ID_EMPRESA" = $1
							AND C."ANIO_FISCAL" = $2
							ORDER BY 	C."FECHA_COMPRA" DESC';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($id_empresa,$anio_fiscal)));
		return json_encode($result);
	}

	function insert_compra($documento,$claveprov,$fec_comp,$tipo_pago,$moneda,$tipo_cambio,$contra_rec,$fec_pago,$fec_rev,$id_empresa,$docprev,$diascred,$importe,$iva,$anio_fiscal,$descuento,$idsucursal,$idproveedor)
	{
		$query = 'SELECT * FROM inserta_compra($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18)';
		pg_prepare($this->conn,"insert_compra",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_compra",array($documento,$claveprov,$fec_comp,$tipo_pago,$moneda,$tipo_cambio,$contra_rec,$fec_pago,$fec_rev,$id_empresa,$docprev,$diascred,$importe,$iva,$anio_fiscal,$descuento,$idsucursal,$idproveedor)));
		return json_encode($result);
	}

	function insert_compra_producto($idcompra,$idproducto,$cantidad,$unidad_medida,$precio_unit,$importe_total,$dsctoprod,$idsucursal)
	{
		$query = 'SELECT * FROM inserta_comp_prod($1,$2,$3,$4,$5,$6,$7,$8)';
		pg_prepare($this->conn,"insert_compra_producto",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_compra_producto",array($idcompra,$idproducto,$cantidad,$unidad_medida,$precio_unit,$importe_total,$dsctoprod,$idsucursal)));
		return json_encode($result);
	}

	function get_compras_by_id($id_compra)
	{
		$query = 'SELECT TRIM(C."DOCUMENTO") as "DOCUMENTO" , "CLAVE_PROVEEDOR", TRIM(P."NOMBRE") as "PROVEEDOR",
							to_char(C."FECHA_COMPRA",\'DD-MM-YYYY\') as "FECHA_COMPRA", "ID_TIPO_PAGO","ID_MONEDA","TIPO_CAMBIO",
							"CONTRA_RECIBO",
							to_char("FECHA_PAGO",\'DD-MM-YYYY\') as "FECHA_PAGO",
							"DESCUENTO",
							"IVA",
							"TIPO_ORDENCOMPRA",
							"DIAS_PAGO"
							FROM "COMPRAS" AS C INNER  JOIN "PROVEEDORES" AS P
							ON C."CLAVE_PROVEEDOR" = P."CLAVE"
							WHERE "ID_COMPRA" = $1';
		$result = pg_prepare($this->conn,"selectquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "selectquery", array($id_compra)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_compra_producto_by_id($id_compra)
	{
		$query = 'SELECT TRIM(P."CODIGO") as "CODIGO",
							P."DESCRIPCION",
							C."CANTIDAD",
							TRIM(C."UNIDAD") as "UNIDAD",
							C."PRECIO_UNITARIO" as "PRECIO",
							to_char(C."IMPORTE_TOTAL",\'999,999,999.00\') as "IMPORTE",
							C."DSCTOPROD" as "DESCTO"
							FROM "COMPRA_PRODUCTO" as C INNER JOIN "PRODUCTO" as P
							ON C."ID_PRODUCTO" = P."ID_PRODUCTO"
							WHERE "ID_COMPRA" = $1
							ORDER BY P."DESCRIPCION"';
		$result = pg_prepare($this->conn,"selectquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "selectquery", array($id_compra)));
		return json_encode($result);
	}

	function elimina_compraprod_by_id($idcompra,$idsucursal)
	{
		$pstmt = 'select * from borra_compra($1,$2)';
		pg_prepare($this->conn,"selectquery",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "selectquery", array($idcompra,$idsucursal)));
		return json_encode($result);
	}
}
