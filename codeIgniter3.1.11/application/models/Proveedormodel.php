<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedormodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_proveedores_by_empresa($idEmpresa,$aniofiscal)
	{
		$query = 'SELECT P."ID_PROVEEDOR", P."CLAVE", 
              TRIM(P."NOMBRE") as "NOMBRE", P."RFC", 
              CASE WHEN S."SALDO" IS NULL THEN 0 ELSE S."SALDO" END AS "SALDO"
        FROM "PROVEEDORES" as P 
        LEFT OUTER JOIN (SELECT C."ID_PROVEEDOR", SUM(C."SALDO") as "SALDO"
        FROM "COMPRAS" as C
        WHERE "ANIO_FISCAL" = $1
        GROUP BY C."ID_PROVEEDOR" ) as S ON S."ID_PROVEEDOR" = P."ID_PROVEEDOR"
        WHERE "ACTIVO" = true 
        AND "ID_EMPRESA" = $2';
		pg_prepare($this->conn,"select_prov",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"select_prov",array($aniofiscal,$idEmpresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function create_proveedor($clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas,$idempresa)
	{
		$query = 'SELECT * FROM crea_proveedor($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16)';
		pg_prepare($this->conn,"insertquery",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"insertquery",array($clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas,$idempresa)));
		return json_encode($result);
	}

	function get_proveedor_by_id($_id)
	{
		$query = 'SELECT * FROM "PROVEEDORES" WHERE "ID_PROVEEDOR" = $1 AND "ACTIVO" = true';
		pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($_id)));
		return json_encode($result);
	}

	function get_proveedor_by_clave($_clave)
	{
		$query = 'SELECT "ID_PROVEEDOR","NOMBRE","CLAVE" FROM "PROVEEDORES" WHERE "CLAVE" = $1 AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($_clave)));
		return json_encode($result);
	}

	function update_proveedor($id_prov,$clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas)
	{
		$query ='UPDATE "PROVEEDORES" SET
				"CLAVE" = $1,
				"NOMBRE" = $2,
				"DOMICILIO" = $3,
				"CP" = $4,
				"TELEFONO" = $5,
				"CONTACTO" = $6,
				"RFC" = $7,
				"CURP" = $8,
				"ID_CATEGORIA_PROV" = $9,
				"DIAS_CRED" = $10,
				"ID_TIPO_ALC_PROV" = $11,
				"ID_BANCO" = $12,
				"CUENTA" = $13,
				"EMAIL" = $14,
				"NOTAS" = $15
				WHERE "ID_PROVEEDOR" = $16';
		pg_prepare($this->conn,"updatequery",$query);
		$result = pg_execute($this->conn,"updatequery",array($clave,$nombre,$domicilio,$cp,$telefono,$contacto,$rfc,$curp,$id_tipo_prov,$dias_cred,$id_tipo_alc_prov,$id_banco,$cuenta,$email,$notas,$id_prov));
		return $result;
	}

	function delete_proveedor($_id)
	{
		//$query = 'DELETE FROM "PROVEEDORES" WHERE "ID_PROVEEDOR" = $1';
		$query = 'UPDATE "PROVEEDORES" SET "ACTIVO" = false WHERE "ID_PROVEEDOR" = $1';
		$result = pg_prepare($this->conn,"deletequery",$query);
		$result = pg_execute($this->conn,"deletequery",array($_id));
		return $result;
	}

	function get_proveedor_by_desc($idEmpresa,$desc)
	{
		$query = 'SELECT * FROM "PROVEEDORES"
		WHERE UPPER("NOMBRE") like UPPER($1)
		AND "ID_EMPRESA" = $2
		AND "ACTIVO" = true';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($desc,$idEmpresa)));
		return json_encode($result);
	}

  function get_compras_by_proveedor($idproveedor,$aniofiscal){
    $query = 
    'SELECT \'A\' as "TIPO",TRIM("DOCUMENTO") as "DOCUMENTO", TO_CHAR("FECHA_COMPRA",\'DD/Mon/YYYY\') as "FECHA_COMPRA","IMPORTE",0 as "PAGO", "SALDO" 
    FROM "COMPRAS" 
    WHERE "ID_PROVEEDOR" = $1
    AND "ANIO_FISCAL" = $2
    UNION
    SELECT \'B\' as "TIPO",C."DOCUMENTO", TO_CHAR(P."FECHA_PAGO",\'DD/Mon/YYYY\') as "FECHA_COMPRA",0 as "IMPORTE", "IMPORTE_PAGO" as "PAGO", 0 as "SALDO"
    FROM "PAGOS" as P
    INNER JOIN "COMPRAS" as C on C."ID_COMPRA" = P."ID_COMPRA"
    WHERE P."ID_PROVEEDOR" = $3
    AND P."ANIO_FISCAL" = $4
    ORDER BY "DOCUMENTO","TIPO","FECHA_COMPRA"';
		$result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($idproveedor,$aniofiscal,$idproveedor,$aniofiscal)));
		return json_encode($result,JSON_NUMERIC_CHECK);
  }

}
?>
