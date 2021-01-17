<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controlinvmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}


    function get_inventario($idEmpresa,$anio_fiscal,$tipoMov,$tipoES,$fechaIni,$fechaFin,$caja,$codigoProducto){
        $tipoMovT = $tipoMov=='tod' ? ' ' : 'AND "MOV"=\''.$tipoMov.'\' ';
        if($tipoES=='t'){
            $tipoEST = ' ';
        }else if($tipoES=='e'){
            $tipoEST = 'AND "IN" > 0 ';
        }else{
            $tipoEST ='AND "OUT" > 0 ';
        }
        $cajaT = $caja == '' ? '' : 'AND "CAJA"='.$caja.' ';
        
        $codigoProductoT = $codigoProducto == '' ? '' : 'AND "CODIGO"=\''.$codigoProducto.'\' ';
        $query = 'SELECT "ID", TO_CHAR("FECHA",\'dd-MM-yyyy\') as "FECHA", TRIM("DOCUMENTO") as "DOCUMENTO",
                "CAJA",TRIM("MOV") as "MOV","IN","OUT","PREC_UNIT","IMPORTE","CODIGO" 
                FROM "INVENTARIO"
                WHERE "ID_EMPRESA" = $1 
                AND "ANIO_FISCAL" = $2
                AND "FECHA" >= $3 
                AND "FECHA" <= $4 '
                .$tipoMovT
                .$tipoEST
                .$cajaT
                .$codigoProductoT
                .' ORDER BY "FECHA" DESC';
        
        $result = pg_prepare($this->conn, "selectquery", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "selectquery", array($idEmpresa,$anio_fiscal,$fechaIni,$fechaFin)));
		return json_encode($result,JSON_NUMERIC_CHECK);
    }

    function save_inventario($data)
    {
        $query = 'SELECT * FROM inserta_movimiento($1, $2, $3, $4, $5, $6, $7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17)';
		pg_prepare($this->conn, "insertamov", $query);
		$result =  pg_fetch_all(pg_execute($this->conn, "insertamov", $data));
		return json_encode($result);
    }

}
?>