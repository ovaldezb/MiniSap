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

  function get_reporte_mov_almacen_by_line($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea)
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

  function get_reporte_mov_almacen($idEmpresa,$anio_fiscal,$fecIni,$fecFin)
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
							GROUP BY P."ID_PRODUCTO",P."CODIGO",P."DESCRIPCION",P."PRECIO_LISTA"
							ORDER BY P."DESCRIPCION") as CO
					LEFT JOIN
							(SELECT VP."ID_PRODUCTO",SUM(VP."CANTIDAD") as "CANT_VENTA",SUM(VP."IMPORTE") as "IMPO_TOT_VTA"
						  FROM "VENTAS" as V
							INNER JOIN "VENTAS_PRODUCTO" as VP on V."ID_VENTA" = VP."ID_VENTA"
							INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							WHERE V."ID_EMPRESA" = $5
							AND V."FECHA_VENTA" >= $6
							AND V."FECHA_VENTA" <= $7
							AND V."ANIO_FISCAL" = $8
							GROUP BY VP."ID_PRODUCTO") as VE
					ON CO."ID_PRODUCTO" = VE."ID_PRODUCTO"';
    
    pg_prepare($this->conn,"qry_mov_almac",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"qry_mov_almac",array($idEmpresa,$fecIni,$fecFin,$anio_fiscal,$idEmpresa,$fecIni,$fecFin,$anio_fiscal)));
    return json_encode($result,JSON_NUMERIC_CHECK);
  }

	public function get_reporte_ventas_by_empr_fy($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$linea,$isTopTen)
	{
    $queryLinea1 = $linea == 0 ? ' 0 = $5 ) ' : ' P."ID_LINEA" = $5) ';
    $queryLinea2 = $linea == 0 ? ' 0 = $10' : ' P."ID_LINEA" = $10 ';
		$query = 'SELECT CASE WHEN M."DESCRIPCION" IS NULL THEN TRIM(P."DESCRIPCION") ELSE TRIM(P."DESCRIPCION")|| \' [\' || M."DESCRIPCION"||\']\' END as "DESCRIPCION",
              CASE WHEN M."DESCRIPCION" IS NULL THEN \'\' ELSE M."DESCRIPCION" END as "CALIDAD",
              TRIM(P."CODIGO") as "CODIGO",
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
                AND V."CANCELADO" = \'f\'
                AND '.$queryLinea1. 'as "PORCENTAJE",
							P."PRECIO_COMPRA" * SUM(VP."CANTIDAD") as "COSTO",
							SUM(VP."IMPORTE") / (1+(P."IVA")/100) - (P."PRECIO_COMPRA" * SUM(VP."CANTIDAD")) as  "UTILIDAD"
							FROM "VENTAS_PRODUCTO" as VP
							INNER JOIN "VENTAS" as V on V."ID_VENTA" = VP."ID_VENTA"
							INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
							LEFT OUTER JOIN "CALIDAD_MADERA" as M ON M."ID_CALIDAD_MADERA" = VP."ID_CALIDAD_MADERA"
              WHERE V."ID_EMPRESA" = $6
							AND V."ANIO_FISCAL" = $7
							AND V."FECHA_VENTA" >= $8
							AND V."FECHA_VENTA" <= $9
              AND V."CANCELADO" = \'f\'
							AND '.$queryLinea2.'
							GROUP BY M."DESCRIPCION",VP."ID_PRODUCTO", P."DESCRIPCION", P."CODIGO", P."IVA",P."PRECIO_COMPRA" ';
			if($isTopTen){
				$query = $query . 'ORDER BY "NETA" DESC LIMIT 10';
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
      $query1 = 'SELECT P."DESCRIPCION",TRIM(P."CODIGO") as "CODIGO",
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
          AND P."CODIGO" = $5 )  as "PORCENTAJE",
      P."PRECIO_COMPRA" * SUM(VP."CANTIDAD") as "COSTO",
      SUM(VP."IMPORTE") / (1+(P."IVA")/100) - (P."PRECIO_COMPRA" * SUM(VP."CANTIDAD")) as  "UTILIDAD"
      FROM "VENTAS_PRODUCTO" as VP
      INNER JOIN "VENTAS" as V on V."ID_VENTA" = VP."ID_VENTA"
      INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
      WHERE V."ID_EMPRESA" = $6
      AND V."ANIO_FISCAL" = $7
      AND V."FECHA_VENTA" >= $8
      AND V."FECHA_VENTA" <= $9 
      AND P."CODIGO" = $10
      GROUP BY VP."ID_PRODUCTO", P."DESCRIPCION", P."CODIGO", P."IVA",P."PRECIO_COMPRA" 
      ORDER BY P."DESCRIPCION"';
      pg_prepare($this->conn,"qry_rep_ven",$query1);
			$result = pg_fetch_all(pg_execute($this->conn,"qry_rep_ven",array($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$expresion,$idEmpresa,$anio_fiscal,$fecIni,$fecFin,$expresion)));
			return json_encode($result,JSON_NUMERIC_CHECK);
		}else{
			$and1 = '';
			$and2 = ' ';
      $query2 = 'SELECT P."DESCRIPCION",TRIM(P."CODIGO") as "CODIGO",
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
          AND UPPER(P."DESCRIPCION") like UPPER(\'%'.$expresion.'%\')  )  as "PORCENTAJE",
      P."PRECIO_COMPRA" * SUM(VP."CANTIDAD") as "COSTO",
      SUM(VP."IMPORTE") / (1+(P."IVA")/100) - (P."PRECIO_COMPRA" * SUM(VP."CANTIDAD")) as  "UTILIDAD"
      FROM "VENTAS_PRODUCTO" as VP
      INNER JOIN "VENTAS" as V on V."ID_VENTA" = VP."ID_VENTA"
      INNER JOIN "PRODUCTO" as P on P."ID_PRODUCTO" = VP."ID_PRODUCTO"
      WHERE V."ID_EMPRESA" = $5
      AND V."ANIO_FISCAL" = $6
      AND V."FECHA_VENTA" >= $7
      AND V."FECHA_VENTA" <= $8 
      AND UPPER(P."DESCRIPCION") like UPPER(\'%'.$expresion.'%\')
      GROUP BY VP."ID_PRODUCTO", P."DESCRIPCION", P."CODIGO", P."IVA",P."PRECIO_COMPRA" 
      ORDER BY P."DESCRIPCION"';
      pg_prepare($this->conn,"qry_rep_ven",$query2);
			$result = pg_fetch_all(pg_execute($this->conn,"qry_rep_ven",array($idEmpresa,$anio_fiscal,$fecIni,$fecFin,$idEmpresa,$anio_fiscal,$fecIni,$fecFin)));
			return json_encode($result,JSON_NUMERIC_CHECK);
		}
		
			
			
	}

  function get_valor_inventario($idEmpresa,$idsucursal){
    $query = 'SELECT X."LINEA", X."SUMA", \'$\'||TRIM(TO_CHAR(X."SUMA",\'999,999,999.99\')) as "SUMACURR", X."SUMA"/R."TOTAL" * 100 as "PORCENTAJE"
              FROM
              (SELECT TRIM(L."NOMBRE") as "LINEA", SUM( P."PRECIO_COMPRA" * S."STOCK" ) as "SUMA" 
              FROM "PRODUCTO" as P
              INNER JOIN "PRODUCTO_SUCURSAL" as S ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
              INNER JOIN "LINEA" as L ON P."ID_LINEA" = L."ID_LINEA"
              WHERE P."ID_EMPRESA" = $1
              AND S."ID_SUCURSAL" = $2
              GROUP BY L."NOMBRE" ) as X,
              (SELECT SUM( P."PRECIO_COMPRA" * S."STOCK" ) as "TOTAL"
              FROM "PRODUCTO" as P
              INNER JOIN "PRODUCTO_SUCURSAL" as S ON P."ID_PRODUCTO" = S."ID_PRODUCTO"
              WHERE P."ID_EMPRESA" = $3
              AND S."ID_SUCURSAL" = $4) as R';
    pg_prepare($this->conn,"valor_inventario",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"valor_inventario",array($idEmpresa,$idsucursal,$idEmpresa,$idsucursal)));
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

  function get_cuentas_x_cobrar($idEmpresa,$anioFiscal){
    $query = 
    'SELECT X."SALDO" as "SALDO",\'$\'||TRIM(TO_CHAR(X."SALDO",\'999,999,999.99\')) as "SALDOCURR", X."NOMBRE" as "CLIENTE", X."SALDO"/Z."TOTAL" * 100 as "PORCENTAJE"
    FROM 
    (SELECT SUM(F."SALDO") as "SALDO", C."NOMBRE" 
     FROM "FACTURA" as F
     INNER JOIN "CLIENTE" as C ON C."ID_CLIENTE"  = F."ID_CLIENTE" 
     WHERE F."ID_EMPRESA" = $1
     AND F."ANIO_FISCAL" = $2
     AND "SALDO" <> 0
     GROUP BY C."NOMBRE" ) as X,
    (SELECT SUM(Y."SALDO") as "TOTAL" 
     FROM "FACTURA" as Y WHERE Y."ID_EMPRESA" = $3 
     AND Y."ANIO_FISCAL" = $4 ) as Z';
    pg_prepare($this->conn,"cuentasxcobrar",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"cuentasxcobrar",array($idEmpresa,$anioFiscal,$idEmpresa,$anioFiscal)));
    return json_encode($result, JSON_NUMERIC_CHECK);
  }

  function get_cuentas_x_pagar($idEmpresa,$anioFiscal){
    $query = 
    'SELECT X."SALDO", \'$\'||TRIM(TO_CHAR(X."SALDO",\'999,999,999.99\')) as "SALDOCURR",X."SALDO" / Y."TOTAL" as "PORCENTAJE", X."NOMBRE"
    FROM
    (SELECT SUM("SALDO") as "SALDO",TRIM("NOMBRE") as "NOMBRE"
    FROM "COMPRAS" as C
    INNER JOIN "PROVEEDORES" as P ON P."ID_PROVEEDOR" = C."ID_PROVEEDOR"
    WHERE C."ID_EMPRESA" = $1
    AND C."ANIO_FISCAL" = $2
    GROUP BY "NOMBRE") as X,
    (SELECT SUM("SALDO") as "TOTAL" FROM "COMPRAS"
    WHERE "ID_EMPRESA" = $3
    AND "ANIO_FISCAL" = $4 ) as Y';
    pg_prepare($this->conn,"cuentasxcobrar",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"cuentasxcobrar",array($idEmpresa,$anioFiscal,$idEmpresa,$anioFiscal)));
    return json_encode($result, JSON_NUMERIC_CHECK);
  }


  function get_reporte_cxc($idEmpresa,$anioFiscal){
    $query = 'SELECT F."ID_CLIENTE",C."NOMBRE",C."CLAVE", SUM(F."30_DIAS") as "TR_DIAS",SUM(F."60_DIAS") as "SE_DIAS",SUM(F."90_DIAS") as "NO_DIAS",SUM(F."MAYOR_90_DIAS") as "MAYOR_90_DIAS"
    FROM (
    SELECT "ID_CLIENTE",
    CASE WHEN (current_date - 30)<= "FECHA_VENCIMIENTO"  THEN SUM("SALDO") ELSE 0 END as "30_DIAS",
    CASE WHEN (current_date - 30) > "FECHA_VENCIMIENTO"  AND (current_date - 60) <= "FECHA_VENCIMIENTO" THEN SUM("SALDO") ELSE 0 END as "60_DIAS",
    CASE WHEN (current_date - 60) > "FECHA_VENCIMIENTO"  AND (current_date - 90) <= "FECHA_VENCIMIENTO" THEN SUM("SALDO") ELSE 0 END as "90_DIAS",
    CASE WHEN (current_date - 90) > "FECHA_VENCIMIENTO"  THEN SUM("SALDO") ELSE 0 END as "MAYOR_90_DIAS"
    FROM "FACTURA"
    WHERE "ID_EMPRESA" = $1
    AND "ANIO_FISCAL" = $2
    AND "ID_TIPO_PAGO" = 2
    GROUP BY "ID_CLIENTE","FECHA_VENCIMIENTO") as F
    INNER JOIN "CLIENTE" as C ON C."ID_CLIENTE" = F."ID_CLIENTE"
    GROUP BY F."ID_CLIENTE",C."NOMBRE",C."CLAVE"';
    pg_prepare($this->conn,"reportecxc",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"reportecxc",array($idEmpresa,$anioFiscal)));
    return json_encode($result, JSON_NUMERIC_CHECK);
  }

  function get_reporte_cobranza($anioFiscal,$idEmpresa, $fecIni,$fecFin){
    $query = 'SELECT F."DOCUMENTO" as "DOCTO", TO_CHAR("FECHA_COBRO",\'DD-MM-YYYY\') as "FECHA_COBRO","IMPORTE_COBRO" as "ABONO",CL."NOMBRE",
    TRIM(FP."CLAVE") as "FP",MP."MET_PAGO" as "MP", CO."ID_MOVIMIENTO" as "ID_FP"
    FROM "COBROS" as CO
    INNER JOIN "FORMA_PAGO" as FP ON FP."ID_FORMA_PAGO" = CO."ID_MOVIMIENTO"
    INNER JOIN "FACTURA" as F ON F."ID_FACTURA" = CO."ID_FACTURA"
    INNER JOIN "CLIENTE" as CL ON CL."ID_CLIENTE" = F."ID_CLIENTE"
    INNER JOIN "METODO_PAGO" as MP ON MP."ID_MET_PAGO" = F."ID_METODO_PAGO"
    AND CO."ANIO_FISCAL" = $1
    AND CO."ID_EMPRESA" = $2
    AND "FECHA_COBRO" >= $3
    AND "FECHA_COBRO" <= $4
    ORDER BY "FECHA_COBRO"';
    pg_prepare($this->conn,"cobranzaa",$query);
    $result = pg_fetch_all(pg_execute($this->conn,"cobranzaa",array($anioFiscal,$idEmpresa,$fecIni,$fecFin)));
    return json_encode($result);
  }

}
?>