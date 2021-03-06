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

	function get_compras($id_empresa,$anio_fiscal, $idsucursal)
	{
		$query = 'SELECT C."ID_COMPRA",to_char(C."FECHA_COMPRA",\'DD/MM/YYYY\') as "FECHA_COMPRA",
					    TRIM(C."DOCUMENTO") AS "DOCUMENTO",
					    TRIM(P."NOMBRE") as "PROVEEDOR",
              P."ID_PROVEEDOR",
					    C."IMPORTE",
					    C."SALDO",
							"DESCUENTO",
							"CLAVE_PROVEEDOR",
							"DIAS_PAGO",
							"TIPO_CAMBIO",
							"IVA",
							"TIPO_ORDENCOMPRA",
							C."ID_TIPO_PAGO" as "TIPO_PAGO",
							"ID_MONEDA" as "MONEDA",
							"CONTRA_RECIBO" as "CR",
							C."NOTAS",
							to_char(C."FECHA_REVISION",\'DD/MM/YYYY\') as "FECHA_REVISION",
							to_char(C."FECHA_PAGO",\'DD/MM/YYYY\') as "FECHA_PAGO",
							CASE "DIAS_PAGO"
							WHEN 0 THEN T."DESCRIPCION"
							ELSE T."DESCRIPCION" || \' \' ||"DIAS_PAGO" || \' días\'
							END as "FORMA_PAGO"
							FROM "COMPRAS" AS C 
              INNER JOIN "PROVEEDORES" AS P ON C."CLAVE_PROVEEDOR" = P."CLAVE"
							INNER JOIN "TIPO_PAGO" as T 	ON C."ID_TIPO_PAGO" = T."ID_TIPO_PAGO"
							WHERE C."ID_EMPRESA" = $1
							AND C."ANIO_FISCAL" = $2
              AND C."ID_SUC_COMPRO" = $3
							AND P."ID_EMPRESA" = C."ID_EMPRESA"
							ORDER BY 	C."FECHA_COMPRA" DESC';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($id_empresa,$anio_fiscal, $idsucursal)));
		return json_encode($result);
	}

	function insert_compra($datoscompra)
	{
		$query = 'SELECT * FROM inserta_compra($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20)';
		pg_prepare($this->conn,"insert_compra",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_compra",$datoscompra));
		return json_encode($result);
	}

	function insert_compra_producto($data_producto)
	{
		$query = 'SELECT * FROM inserta_comp_prod($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16)';
		pg_prepare($this->conn,"insert_compra_producto",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insert_compra_producto",$data_producto));
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
							C."NOTAS",
							"DIAS_PAGO"
							FROM "COMPRAS" AS C INNER  JOIN "PROVEEDORES" AS P
							ON C."CLAVE_PROVEEDOR" = P."CLAVE"
							WHERE "ID_COMPRA" = $1';
		pg_prepare($this->conn,"selectquery",$query);
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
							C."IMPORTE_TOTAL" as "IMPORTE",
							C."DSCTOPROD" as "DESCTO",
              P."IVA"
							FROM "COMPRA_PRODUCTO" as C INNER JOIN "PRODUCTO" as P
							ON C."ID_PRODUCTO" = P."ID_PRODUCTO"
							WHERE "ID_COMPRA" = $1
							ORDER BY P."DESCRIPCION"';
		$result = pg_prepare($this->conn,"selectquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "selectquery", array($id_compra)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function elimina_compraprod_by_id($idcompra,$idsucursal)
	{
		$pstmt = 'select * from borra_compra($1,$2)';
		pg_prepare($this->conn,"selectquery",$pstmt);
		$result = pg_fetch_all(pg_execute($this->conn, "selectquery", array($idcompra,$idsucursal)));
		return json_encode($result);
	}
}
?>