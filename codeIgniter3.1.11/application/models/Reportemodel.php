<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportemodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

  function get_reporte_mov_almacen($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea)
  {
    $query = 'SELECT CO."ID_PRODUCTO",TRIM(CO."CODIGO") as "CODIGO",CO."DESCRIPCION",
					CO."CANT_COMP",
					CO."IMP_TOT_COMP",
					CASE WHEN  VE."CANT_VENTA" IS NULL THEN 0 ELSE VE."CANT_VENTA"  END as "CANT_VENTA",
					CASE WHEN  VE."IMPO_TOT_VTA" IS NULL THEN 0 ELSE VE."IMPO_TOT_VTA"  END as "IMPO_TOT_VTA",
					CASE WHEN  VE."CANT_VENTA" IS NULL THEN CO."CANT_COMP" ELSE (CO."CANT_COMP" - VE."CANT_VENTA")  END as "CANT_EXIST",
					CO."PRECIO_LISTA",
					CASE WHEN  VE."CANT_VENTA" IS NULL THEN CO."CANT_COMP"*CO."PRECIO_LISTA" ELSE (CO."CANT_COMP" - VE."CANT_VENTA")*CO."PRECIO_LISTA"  END as "IMPO_EXIST"
					FROM
							(SELECT P."ID_PRODUCTO",
							P."CODIGO",
							P."DESCRIPCION",P."PRECIO_LISTA",
							SUM(CP."CANTIDAD") as "CANT_COMP",
							SUM(CP."IMPORTE_TOTAL") as "IMP_TOT_COMP"
							FROM "COMPRAS" as C
							INNER JOIN "COMPRA_PRODUCTO" as CP on C."ID_COMPRA" = CP."ID_COMPRA"
							INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = CP."ID_PRODUCTO"
							WHERE C."ID_EMPRESA" = $1
							AND C."FECHA_COMPRA" >= $2
							AND C."FECHA_COMPRA" <= $3
							AND C."ANIO_FISCAL" = $4
							AND P."ID_LINEA" = $5
							GROUP BY P."ID_PRODUCTO",P."CODIGO",P."DESCRIPCION",P."PRECIO_LISTA"
							ORDER BY P."DESCRIPCION") as CO
					LEFT JOIN
							(SELECT VP."ID_PRODUCTO",SUM(VP."CANTIDAD") as "CANT_VENTA",SUM(VP."IMPORTE") as "IMPO_TOT_VTA"
						FROM "VENTAS" as V
							INNER JOIN "VENTAS_PRODUCTO" as VP on V."ID_VENTA" = VP."ID_VENTA"
							INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							WHERE V."ID_EMPRESA" = $6
							AND V."FECHA_VENTA" >= $7
							AND V."FECHA_VENTA" <= $8
							AND V."ANIO_FISCAL" = $9
							AND P."ID_LINEA" = $10
							GROUP BY VP."ID_PRODUCTO") as VE
					ON CO."ID_PRODUCTO" = VE."ID_PRODUCTO"';
    pg_prepare($this->conn,"qry_mov_almac",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"qry_mov_almac",array($idEmpresa,$fecIni,$fecFin,$anio_fiscal,$linea,$idEmpresa,$fecIni,$fecFin,$anio_fiscal,$linea)));
    return json_encode($result,JSON_NUMERIC_CHECK);
  }

	public function get_reporte_ventas_by_empr_fy($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea,$isTopTen)
	{
		$query = 'SELECT VP."ID_PRODUCTO", P."DESCRIPCION",TRIM(P."CODIGO") as "CODIGO",
							SUM(VP."CANTIDAD") as "CANTIDAD",
							SUM(VP."IMPORTE") as "BRUTA",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) as "NETA",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) / SUM(VP."CANTIDAD") as "PRECIO_PROM",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) *100 / (SELECT SUM(VP."IMPORTE"/(1+P."IVA"/100)) as "TOTAL"
							  FROM "VENTAS_PRODUCTO" as VP
							  INNER JOIN "VENTAS" as V ON V."ID_VENTA" = VP."ID_VENTA"
							  INNER JOIN "PRODUCTO" as P ON P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							  WHERE V."ID_EMPRESA" = $1
							  AND V."ANIO_FISCAL" = $2
							  AND V."FECHA_VENTA" >= $3
							  AND V."FECHA_VENTA" <= $4
							  AND P."ID_LINEA" = $5) as "PORCENTAJE",
							P."PRECIO_COMPRA" * SUM(VP."CANTIDAD") as "COSTO",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) - (P."PRECIO_COMPRA" * SUM(VP."CANTIDAD")) as  "UTILIDAD"
							FROM "VENTAS_PRODUCTO" as VP
							INNER JOIN "VENTAS" as V on V."ID_VENTA" = VP."ID_VENTA"
							INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							WHERE V."ID_EMPRESA" = $6
							AND V."ANIO_FISCAL" = $7
							AND V."FECHA_VENTA" >= $8
							AND V."FECHA_VENTA" <= $9
							AND P."ID_LINEA" = $10
							GROUP BY VP."ID_PRODUCTO", P."DESCRIPCION", P."CODIGO", P."IVA",P."PRECIO_COMPRA" ';
			if($isTopTen){
				$query = $query . 'ORDER BY "TOTAL" LIMIT 10';
			}else{
				$query = $query . 'ORDER BY P."DESCRIPCION" ';
			}
			pg_prepare($this->conn,"qry_rep_ven",$query);
			$result = pg_fetch_all(pg_execute($this->conn,"qry_rep_ven",array($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea,$idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea)));
			return json_encode($result,JSON_NUMERIC_CHECK);
	}

	public function get_reporte_ventas_by_empr_fy_codigo_desc($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea,$expresion, $isCodigo)
	{
		$and1 = '';
		$and2 = '';
		if($isCodigo){
			$and1 = 'AND P."CODIGO" = $6 ';
			$and2 = 'AND P."CODIGO" = $12 ';
		}else{
			$and1 = 'AND P."DESCRIPCION" = $6 ';
			$and2 = 'AND P."DESCRIPCION" = $12 ';
		}
		$query = 'SELECT VP."ID_PRODUCTO", P."DESCRIPCION",TRIM(P."CODIGO") as "CODIGO",
							SUM(VP."CANTIDAD") as "CANTIDAD",
							SUM(VP."IMPORTE") as "BRUTA",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) as "NETA",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) / SUM(VP."CANTIDAD") as "PRECIO_PROM",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) *100 / (SELECT SUM(VP."IMPORTE"/(1+P."IVA"/100)) as "TOTAL"
								FROM "VENTAS_PRODUCTO" as VP
							  	INNER JOIN "VENTAS" as V ON V."ID_VENTA" = VP."ID_VENTA"
							  	INNER JOIN "PRODUCTO" as P ON P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							  	WHERE V."ID_EMPRESA" = $1
							  	AND V."ANIO_FISCAL" = $2
							  	AND V."FECHA_VENTA" >= $3
							  	AND V."FECHA_VENTA" <= $4
								AND P."ID_LINEA" = $5) as "PORCENTAJE" '
								.$and1.
							',
							P."PRECIO_COMPRA" * SUM(VP."CANTIDAD") as "COSTO",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) - (P."PRECIO_COMPRA" * SUM(VP."CANTIDAD")) as  "UTILIDAD"
							FROM "VENTAS_PRODUCTO" as VP
							INNER JOIN "VENTAS" as V on V."ID_VENTA" = VP."ID_VENTA"
							INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							WHERE V."ID_EMPRESA" = $7
							AND V."ANIO_FISCAL" = $8
							AND V."FECHA_VENTA" >= $9
							AND V."FECHA_VENTA" <= $10
							AND P."ID_LINEA" = $11 '
							.$and2.
							'GROUP BY VP."ID_PRODUCTO", P."DESCRIPCION", P."CODIGO", P."IVA",P."PRECIO_COMPRA" 
							ORDER BY P."DESCRIPCION"';
			
			pg_prepare($this->conn,"qry_rep_ven",$query);
			$result = pg_fetch_all(pg_execute($this->conn,"qry_rep_ven",array($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea,$expresion,$idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea,$expresion)));
			return json_encode($result,JSON_NUMERIC_CHECK);
	}

  function get_valor_inventario($idEmpresa){
    $query = 'SELECT X."LINEA", X."SUMA", X."SUMA"/R."TOTAL" * 100 as "PORCENTAJE"
              FROM
              (SELECT TRIM(L."NOMBRE") as "LINEA", SUM( P."PRECIO_COMPRA" * S."STOCK" ) as "SUMA" 
              FROM "PRODUCTO" as P
              INNER JOIN "PRODUCTO_SUCURSAL" as S ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
              INNER JOIN "LINEA" as L ON P."ID_LINEA" = L."ID_LINEA"
              WHERE P."ID_EMPRESA" = $1
              GROUP BY L."NOMBRE" ) as X,
              (SELECT SUM( P."PRECIO_COMPRA" * S."STOCK" ) as "TOTAL"
              FROM "PRODUCTO" as P
              INNER JOIN "PRODUCTO_SUCURSAL" as S ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
              WHERE P."ID_EMPRESA" = $2) as R';
    pg_prepare($this->conn,"valor_inventario",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"valor_inventario",array($idEmpresa,$idEmpresa)));
    return $result;
  }

  function get_ventas_aniofiscal($idEmpresa,$anioFiscal){
    $query = 'SELECT CAST(to_char(V."FECHA_VENTA",\'MM\') as integer)-1 as "MES", SUM(V."IMPORTE") as "IMPORTE"
              FROM "VENTAS" as V
              WHERE V."ID_EMPRESA" = $1
              AND V."ANIO_FISCAL"  = $2
              GROUP BY to_char(V."FECHA_VENTA",\'MM\')
              ORDER BY to_char(V."FECHA_VENTA",\'MM\')';
    pg_prepare($this->conn,"ventas_aniofiscal",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"ventas_aniofiscal",array($idEmpresa,$anioFiscal)));
    return $result;
  }

}
