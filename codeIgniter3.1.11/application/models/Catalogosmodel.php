<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalogosmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}

	function get_tipo_cliente()
	{
		$query = 'SELECT "ID_TIPO_CLTE", TRIM("DESCRIPCION") as "DESCRIPCION" FROM "TIPO_CLIENTE" ORDER BY "ID_TIPO_CLTE"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return ($result);
	}

	function get_dias_semana()
	{
		$query = 'SELECT * FROM "DIAS_SEMANA"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return ($result);
	}

	function get_forma_pago() {
		$query = 'SELECT * FROM "FORMA_PAGO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return ($result);
	}

	

	function get_forma_pago_js() {
		$query = 'SELECT * FROM "FORMA_PAGO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
	}

	function get_vendedor()
	{
		$query = 'SELECT * FROM "VENDEDOR"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return ($result);
	}

	function get_uso_cfdi()
	{
		$query = 'SELECT * FROM "USO_CFDI"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return ($result);
	}

	function get_uso_cfdi_js()
	{
		$query = 'SELECT * FROM "USO_CFDI"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_regimenes()
	{
		$query = 'SELECT * FROM "REGIMEN"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	function get_tipo_prov()
	{
		$query = 'SELECT * FROM "TIPO_PROVEEDOR"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	/*function get_tipo_pago()
	{
		$query = 'SELECT * FROM "TIPO_PAGO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}*/

	function get_tipo_pago()
	{
		$query = 'SELECT * FROM "TIPO_PAGO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_alcance_prov()
	{
		$query = 'SELECT * FROM "TIPO_ALCANCE_PROV"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	function get_bancos()
	{
		$query = 'SELECT * FROM "BANCO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_linea()
	{
		$query = 'SELECT * FROM "LINEA"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	function get_monedas()
	{
		$query = 'SELECT * FROM "MONEDA"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	function get_moneda_json()
	{
		$query = 'SELECT "ID_MONEDA",TRIM("NOMBRE") as "NOMBRE", "CODIGO" FROM "MONEDA"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_metodo_pago(){
		$query = 'SELECT * FROM "METODO_PAGO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_ieps()
	{
		$query = 'SELECT * FROM "IEPS"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}


	function inserta_item_catalogo($clave,$descripcion,$nivel,$tipo,$desc_mayus)
	{
		$nombre = pg_escape_string($descripcion);
		$nombre_mayus = pg_escape_string($desc_mayus);

		$query = "INSERT INTO \"CATALOGO_SAT\" (\"CLAVE\", \"DESCRIPCION\", \"NIVEL\", \"TIPO\", \"DESC_MAYUS\")
				  VALUES ('$clave','$nombre','$nivel','$tipo','$nombre_mayus')";
		$result = pg_query($this->conn,$query);
		return $result;
	}

	function inserta_item_medidas($clave,$descripcion,$desc_mayus)
	{
		$nombre = pg_escape_string($descripcion);
		$nombre_mayus = pg_escape_string($desc_mayus);

		$query = "INSERT INTO \"MEDIDAS_SAT\" (\"CLAVE\", \"DESCRIPCION\", \"DESC_MAYUS\")
				  VALUES ('$clave','$nombre','$nombre_mayus')";
		$result = pg_query($this->conn,$query);
		return $result;
	}

	function get_sat_items_by_desc($desc)
	{
		$query = "SELECT * FROM \"CATALOGO_SAT\" WHERE \"DESC_MAYUS\" LIKE UPPER('$desc') ";
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
	}

	function get_unidad_sat_by_desc($desc)
	{
		$query = "SELECT * FROM \"MEDIDAS_SAT\" WHERE \"DESC_MAYUS\" LIKE UPPER('$desc')" ;
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result);
	}

	function get_unidad_medida()
	{
		$query = 'SELECT * FROM "UNIDAD_MEDIDA" ';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return ($result);
	}

	function get_tarjetas()
	{
		$query = 'SELECT * FROM "TARJETAS" ';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_vales()
	{
		$query = 'SELECT * FROM "VALES" ';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_incremento_by_name($nombre,$idempresa,$longitud)
	{
		$query = 'SELECT TRIM("PREFIJO")||LPAD(trim(to_char("VALOR",\'9999999999\')),$1,\'0\') as "VALOR"
							FROM "INCREMENTOS" WHERE "NOMBRE_INC" = $2 AND "ID_EMPRESA" = $3';
		pg_prepare($this->conn,"myincrement",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"myincrement", array($longitud,$nombre,$idempresa)));
		return json_encode($result);
	}

	function get_linea_by_empresa($idEmpresa)
	{
		$query = 'SELECT "ID_LINEA",TRIM("NOMBRE") as "NOMBRE" FROM "LINEA" WHERE "ID_EMPRESA" = $1';
		pg_prepare($this->conn,"qry_linea_emp",$query);
		$result = pg_fetch_all(pg_execute($this->conn,"qry_linea_emp",array($idEmpresa)));
		return json_encode($result,JSON_NUMERIC_CHECK);
	}

	function get_areas(){
		$query = 'SELECT * FROM "AREA"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	function get_titulos(){
		$query = 'SELECT * FROM "TITULO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

	function get_puestos(){
		$query = 'SELECT * FROM "PUESTO"';
		$result = pg_fetch_all(pg_query($this->conn, $query));
		return $result;
	}

}
?>
