<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Producto extends CI_Controller
{
	function __construct() {
		parent::__construct();

		$this->load->model('catalogosmodel');
		$this->load->model('productomodel');
		$this->load->model('lineamodel');		
		$this->load->helper('url');
		$this->load->library('session');
	}

	function index() {
		if(isset($_SESSION['username']))
    {
			$data['lineas'] = $this->lineamodel->read($_SESSION['idempresa']);
			$data['monedas'] = $this->catalogosmodel->get_monedas();
			$data['iepss'] = $this->catalogosmodel->get_ieps();
			$data['umedidas'] = $this->catalogosmodel->get_unidad_medida();
			$this->load->view('producto',$data);
    }else {
      $error['error'] = '';
      $this->load->view('login',$error);
    }
	}

	function load($idempresa)
	{
		$data = $this->productomodel->get_productos($idempresa);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function save()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->productomodel->create_producto(
		$data['codigo'],
		$data['nombre'],
		$data['linea'],
		$data['unidadmedida'],
		$data['esequiv'],
		strlen($data['equivalencia'])>0?$data['equivalencia']:NULL,
		$data['codigocfdi'],
		$data['unidad_sat'],
		$data['preciolista'],
		$data['ultact'],
		$data['moneda'],
		$data['iva'],
		$data['idieps'],
		strlen($data['ieps'])>0?$data['ieps']:NULL,
		$data['espromo'],
		strlen($data['preciopromo'])>0?$data['preciopromo']:NULL,
		$data['esdescnt'],
		strlen($data['preciodescnt']) > 0 ? $data['preciodescnt']:NULL,
		strlen($data['maxstock'])  > 0 ? $data['maxstock']:NULL,
		strlen($data['minstock'])  > 0 ? $data['minstock']:NULL,
		$data['estasaexenta'],
		$data['notas'],
		$data['img'],
		$data['idempresa'],
		$data['idscursal'],
		$data['tipops']);

		if($result){
			return $this->output
			->set_content_type('application/json')
			->set_output($result);
		}else{
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>'Error')));
		}
	}

	function loadbyid($idProducto)
	{
		$data = $this->productomodel->get_producto_by_id($idProducto);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function update($idProducto)
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->productomodel->update_producto(
		$idProducto,
		$data['codigo'],
		$data['nombre'],
		$data['linea'],
		$data['unidadmedida'],
		$data['esequiv'],
		strlen($data['equivalencia'])>0?$data['equivalencia']:NULL,
		$data['codigocfdi'],
		$data['unidad_sat'],
		$data['preciolista'],
		$data['ultact'],
		$data['moneda'],
		$data['iva'],
		$data['idieps'],
		strlen($data['ieps'])>0?$data['ieps']:NULL,
		$data['espromo'],
		strlen($data['preciopromo'])>0?$data['preciopromo']:NULL,
		$data['esdescnt'],
		strlen($data['preciodescnt'])>0?$data['preciodescnt']:NULL,
		$data['maxstock'],
		$data['minstock'],
		$data['estasaexenta'],
		$data['notas'],
		$data['img']);
		if($result){
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>'OK')));
		}else{
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>'Error')));
		}
	}

	function delete($idProducto)
	{
		// base_url()
		$result = $this->productomodel->delete_producto($idProducto);
		if($result)
		{
			$res = 'OK';
			/*unlink();*/
		}
		else
		{
			$res = 'Error';
		}
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>$res)));
	}

	function items($desc)
	{
		$nvodesc = str_replace("%20"," ",$desc);
		$result = $this->catalogosmodel->get_sat_items_by_desc('%'.$nvodesc.'%');
		return $this->output
            ->set_content_type('application/json')
            ->set_output($result);

	}

	function unidadsat($desc)
	{
		$nvodesc = str_replace("%20"," ",$desc);
		$result = $this->catalogosmodel->get_unidad_sat_by_desc('%'.$nvodesc.'%');
		return $this->output
            ->set_content_type('application/json')
            ->set_output($result);

	}

	function prodbycode($codigo)
	{
		$result = $this->productomodel->get_producto_by_codigo($codigo);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($result);
	}
	/*function fileExists($codigo)
	{

	}*/
}
?>
