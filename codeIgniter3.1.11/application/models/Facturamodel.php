<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturamodel extends CI_model
{
    private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
    }
    
    function saveCFDI($arrayCFDI){
        $query = 'INSERT INTO "DATOS_CFDI" VALUES($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12)';
        pg_prepare($this->conn,"inserta_cfdi",$query);
        $res = pg_execute($this->conn,"inserta_cfdi",$arrayCFDI);
        return $res;
    }

    function getMatrixCertByEmp($idempresa){
        $query = 'SELECT "NOMBRE","RFC","FECHA_INICIO","FECHA_FIN",TRIM("OU") as "OU" FROM "DATOS_CFDI" WHERE "ACTIVO" = true AND "ID_EMPRESA" = $1 AND "ID_SUCURSAL" = 0' ;
        pg_prepare($this->conn,"select_cfdi",$query);
        $result = pg_fetch_all(pg_execute($this->conn,"select_cfdi",array($idempresa)));
        return json_encode($result,JSON_NUMERIC_CHECK);
    }

    function getSucCertsByEmp($idempresa){
        $query = 'SELECT S."ID_SUCURSAL",S."CLAVE",S."DIRECCION",S."RESPONSABLE",S."ALIAS",S."CP",
                CASE WHEN  D."NOMBRE" IS NULL THEN \'\' ELSE D."NOMBRE"  END as "NOMBRE",
                CASE WHEN  D."RFC" IS NULL THEN \'\' ELSE D."RFC"  END as "RFC",
                D."FECHA_INICIO",
                D."FECHA_FIN"
                FROM 
                    (SELECT "ID_SUCURSAL","CLAVE","DIRECCION","RESPONSABLE","ALIAS","CP"
                    FROM "SUCURSALES" WHERE "ID_EMPRESA" = $1 ) as S
                LEFT JOIN 
                    (SELECT "ID_SUCURSAL","NOMBRE","RFC","FECHA_INICIO","FECHA_FIN" 
                    FROM "DATOS_CFDI" WHERE "ACTIVO" = true) as D
                    ON S."ID_SUCURSAL" = D."ID_SUCURSAL"' ;
        pg_prepare($this->conn,"select_cfdi_suc",$query);
        $result = pg_fetch_all(pg_execute($this->conn,"select_cfdi_suc",array($idempresa)));
        return json_encode($result,JSON_NUMERIC_CHECK);
    }

    function getDataCFDI($idEmpresa,$idSucursal){
        $query = 'SELECT * 
                    FROM "DATOS_CFDI" 
                    WHERE "ID_SUCURSAL" = $1 
                    AND "ID_EMPRESA" = $2';        
        pg_prepare($this->conn,"select_cfdi_data",$query);
        $result = pg_fetch_all(pg_execute($this->conn,"select_cfdi_data",array($idSucursal,$idEmpresa)));        
        return json_decode(json_encode($result),false);
    }

    function saveCFDISAT($arrayCFDISAT){
        $query = 'INSERT INTO "FACTURA_CFDI" ("CLIENTE","RFC","FECHA_TIMBRADO","QR_CODE","CFDI","FOLIO","IMPORTE","CADENA_SAT","ID_CLIENTE","ID_EMPRESA","CANCELADO","ID_FACTURA") VALUES($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,false,$11)';
        pg_prepare($this->conn,"inserta_cfdi",$query);
        $res = pg_execute($this->conn,"inserta_cfdi",$arrayCFDISAT);
        return $res;
    }

    function get_facturas_by_dates($fechas){
        $query = 'SELECT "ID_FACTURA","CLIENTE","RFC","FECHA_TIMBRADO","FOLIO","ID_CLIENTE","ID_EMPRESA" FROM "FACTURA_CFDI" WHERE "CANCELADO" = false';
        pg_prepare($this->conn,"select_facturas_date",$query);
        $result = pg_fetch_all(pg_execute($this->conn,"select_facturas_date",array()));        
        return json_encode($result);
    }

    function get_factura_by_id($idfactura){
        $query = 'SELECT * FROM "FACTURA_CFDI" WHERE "ID_FACTURA" = $1';
        pg_prepare($this->conn,"select_facturas_id",$query);
        $result = pg_fetch_all(pg_execute($this->conn,"select_facturas_id",array($idfactura)));        
        return json_encode($result);
    }
   

}

?>