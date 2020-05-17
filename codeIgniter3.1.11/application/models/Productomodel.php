<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productomodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_productos($idempresa)
	{
		$query = 'SELECT P."ID_PRODUCTO", P."DESCRIPCION",
							TRIM(P."CODIGO") AS "CODIGO",
							P."PRECIO_LISTA", SUM(S."STOCK") as "STOCK"
							FROM "PRODUCTO" as P
							INNER JOIN "PRODUCTO_SUCURSAL" as S
							ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
							WHERE P."ID_EMPRESA" = $1 AND P."ACTIVO" = true
							GROUP BY P."DESCRIPCION",P."ID_PRODUCTO", P."CODIGO",P."PRECIO_LISTA"							';
		pg_prepare($this->conn, "selproducto",$query);
		$result = pg_fetch_all(pg_execute($this->conn, "selproducto",array($idempresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function create_producto($codigo,$nombre,$linea,$unidadmedida,$esequiv,$equivalencia,$codigocfdi,$unidad,$preciolista,$ultact,$moneda,$iva,$id_ieps,$ieps,$enpromo,$preciopromo,$esdescnt,$preciodescnt,$maxstock,$minstock,$estasaexenta,$notas,$img,$idempresa,$idsucursal,$tipops)
	{
		$query = 'SELECT * FROM crea_producto($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,$22,$23,$24,$25,$26)';
		pg_prepare($this->conn,"prstmt", $query );
		$result = pg_fetch_all(pg_execute($this->conn,"prstmt",array($codigo,$nombre,$linea,$unidadmedida,$esequiv,$equivalencia,$codigocfdi,$unidad,$preciolista,$ultact,$moneda,$iva,$id_ieps,$ieps,$enpromo,$preciopromo,$esdescnt,$preciodescnt,$maxstock,$minstock,$estasaexenta,$notas,$img,$idempresa,$idsucursal,$tipops)));
		return json_encode($result);
	}

	function get_producto_by_id($_id)
	{
		$query = 'SELECT * FROM "PRODUCTO" WHERE "ID_PRODUCTO" = $1';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($_id)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_producto_by_codigo($codigo)
	{
		$query = 'SELECT * FROM "PRODUCTO" WHERE "CODIGO" = $1';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($codigo)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function update_producto($id_producto,$codigo,$nombre,$linea,$unidadmedida,$esequiv,$equivalencia,$codigocfdi,$unidad,$preciolista,$ultact,$moneda,$iva,$id_ieps,$ieps,$enpromo,$preciopromo,$esdescnt,$preciodescnt,$maxstock,$minstock,$estasaexenta,$notas,$img)
	{
		$query = 'UPDATE "PRODUCTO" SET
				"CODIGO" =$1,
				"DESCRIPCION" =$2,
				"ID_LINEA" =$3,
				"UNIDAD_MEDIDAD" =$4,
				"ES_EQUIVALENTE" =$5,
				"EQUIVALENCIA" =$6,
				"COD_CFDI" =$7,
				"UNIDAD_SAT" =$8,
				"PRECIO_LISTA" =$9,
				"ULTIMA_ACTUALIZACION" =$10,
				"ID_MONEDA" =$11,
				"IVA" =$12,
				"ID_IEPS" =$13,
				"IEPS" = $14,
				"ES_PROMO" = $15,
				"PRECIO_PROMO" = $16,
				"ES_DESCUENTO" = $17,
				"PRECIO_DESCUENTO" = $18,
				"MAX_STOCK" = $19,
				"MIN_STOCk" = $20,
				"TASA_EXENTA" = $21,
				"NOTAS" = $22,
				"IMAGEN" = $23
				WHERE "ID_PRODUCTO" = $24';
		pg_prepare($this->conn,"updatequery",$query);
		$result = pg_execute($this->conn,"updatequery",array($codigo,$nombre,$linea,$unidadmedida,$esequiv,$equivalencia,$codigocfdi,$unidad,$preciolista,$ultact,$moneda,$iva,$id_ieps,$ieps,$enpromo,$preciopromo,$esdescnt,$preciodescnt,$maxstock,$minstock,$estasaexenta,$notas,$img,$id_producto));
		return $result;
	}

	function delete_producto($_id)
	{
		$query = 'UPDATE "PRODUCTO" SET "ACTIVO" = false WHERE "ID_PRODUCTO" = $1';
		pg_prepare($this->conn,"deletequery",$query);
		$result = pg_execute($this->conn,"deletequery",array($_id));
		return $result;
	}
}
?>
