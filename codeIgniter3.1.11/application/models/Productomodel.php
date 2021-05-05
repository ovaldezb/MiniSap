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
				GROUP BY P."DESCRIPCION",P."ID_PRODUCTO", P."CODIGO",P."PRECIO_LISTA"
				ORDER BY P."DESCRIPCION"';
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

	function get_producto_by_codigo($codigo, $idempresa,$idsucursal)
	{
		$query = 'SELECT P."DESCRIPCION", P."PRECIO_LISTA",P."UNIDAD_MEDIDA","IMAGEN","IVA", PS."STOCK",
    P."ID_PRODUCTO","CODIGO","ES_PROMO","ES_DESCUENTO","PRECIO_DESCUENTO","PRECIO_PROMO","TIPO_PS" 
    FROM "PRODUCTO" as P
    LEFT OUTER JOIN "PRODUCTO_SUCURSAL" as PS ON PS."ID_PRODUCTO" = P."ID_PRODUCTO"
    WHERE "CODIGO" = $1
    AND "ID_EMPRESA"=$2
    AND PS."ID_SUCURSAL" = $3';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($codigo, $idempresa, $idsucursal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

  function get_producto_detalle_by_codigo($idproducto){
    $query = 
    'SELECT \'COMPRA\' as "TIPO", C."DOCUMENTO", CP."CANTIDAD", CP."CANTIDAD" as "MOV", TO_CHAR("FECHA_COMPRA",\'DD/Mon/YYYY\') as "FECHA"
    FROM "COMPRAS" as C
    INNER JOIN "COMPRA_PRODUCTO" as CP ON CP."ID_COMPRA" = C."ID_COMPRA"
    WHERE "ID_PRODUCTO" = $1
    UNION
    SELECT \'VENTA\' as "TIPO", V."DOCUMENTO",VP."CANTIDAD",VP."CANTIDAD" * -1 as "MOV", TO_CHAR(V."FECHA_VENTA",\'DD/Mon/YYYY\') as "FECHA"
    FROM "VENTAS" as V
    INNER JOIN "VENTAS_PRODUCTO" as VP ON VP."ID_VENTA" = V."ID_VENTA"
    WHERE VP."ID_PRODUCTO" = $2 
    ORDER BY "FECHA"';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($idproducto,$idproducto)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

	function update_producto($id_producto,$codigo,$nombre,$linea,$unidadmedida,$esequiv,$equivalencia,$codigocfdi,$unidad,$preciolista,$ultact,$moneda,$iva,$id_ieps,$ieps,$enpromo,$preciopromo,$esdescnt,$preciodescnt,$maxstock,$minstock,$estasaexenta,$notas,$img)
	{
		$query = 'UPDATE "PRODUCTO" SET
				"CODIGO" =$1,
				"DESCRIPCION" =$2,
				"ID_LINEA" =$3,
				"UNIDAD_MEDIDA" =$4,
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

	//Inserta productos de forma masiva
	function inserta_producto($producto){
		$query = 'SELECT * FROM inserta_producto($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14)';
		pg_query($this->conn, "DEALLOCATE ALL"); 
		pg_prepare($this->conn,"insert_prod",$query);
		$result = pg_execute($this->conn,"insert_prod",$producto);
		return $result;
	}

	function inserta_cliente($cliente){
		$query = 'INSERT INTO "CLIENTE" ("CLAVE",
										"NOMBRE",
										"DOMICILIO",
										"TELEFONO",
										"EMAIL",
										"CURP",
										"RFC",
										"DIAS_CREDITO",
										"ID_EMPRESA",
										"ACTIVO",
										"ID_FORMA_PAGO") 
				VALUES($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)';
		pg_query($this->conn, "DEALLOCATE ALL"); 
		pg_prepare($this->conn,"insert_clt",$query);
		$result = pg_execute($this->conn,"insert_clt",$cliente);
		return $result;
	}

	function inserta_proveedor($proveedor){
		$query = 'INSERT INTO "PROVEEDORES" ("CLAVE",
											"NOMBRE",
											"DOMICILIO",
											"RFC",
											"CURP",
											"TELEFONO",
											"EMAIL",
											"DIAS_CRED",
											"ID_CATEGORIA_PROV",
											"ID_TIPO_ALC_PROV",
											"ID_EMPRESA",
											"ACTIVO") 
				VALUES($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12)';
		pg_query($this->conn, "DEALLOCATE ALL"); 
		pg_prepare($this->conn,"inserta_prov",$query);
		$result = pg_execute($this->conn,"inserta_prov",$proveedor);
		return $result;
	}
}
?>
