<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor extends CI_Controller
{
	function __construct() {
		parent::__construct();

		$this->load->model('catalogosmodel');
		$this->load->model('proveedormodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

	function index() {
		if(isset($_SESSION['username']))
    {
			$data['proveedores'] = $this->catalogosmodel->get_tipo_prov();
			$data['alcanceprov'] = $this->catalogosmodel->get_alcance_prov();
			$data['bancos'] = $this->catalogosmodel->get_bancos();
			$this->load->view('proveedor',$data);
		}else {
			$error['error'] = '';
			$this->load->view('login',$error);
    }
	}

	function loadByEmpresa($idEmpresa,$aniofiscal)
	{
		$data = $this->proveedormodel->get_proveedores_by_empresa($idEmpresa,$aniofiscal);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function save()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->proveedormodel->create_proveedor(
		$data['clave'],
		$data['nombre'],
		$data['domicilio'],
		$data['cp'],
		$data['telefono'],
		$data['contacto'],
		$data['rfc'],
		$data['curp'],
		$data['id_tipo_prov'],
		$data['dias_cred'],
		$data['id_tipo_alc_prov'],
		$data['banco'],
		$data['cuenta'],
		$data['email'],
		$data['notas'],
		$data['idempresa']);

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

	function loadbyid($id_proveedor)
	{
		$data = $this->proveedormodel->get_proveedor_by_id($id_proveedor);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function update($id_proveedor)
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->proveedormodel->update_proveedor(
		$id_proveedor,
		$data['clave'],
		$data['nombre'],
		$data['domicilio'],
		$data['cp'],
		$data['telefono'],
		$data['contacto'],
		$data['rfc'],
		$data['curp'],
		$data['id_tipo_prov'],
		$data['dias_cred'],
		$data['id_tipo_alc_prov'],
		$data['banco'],
		$data['cuenta'],
		$data['email'],
		$data['notas']);
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

	function delete($id)
	{
		$result = $this->proveedormodel->delete_proveedor($id);
		if($result)
		{
			$res = 'OK';
		}
		else
		{
			$res = 'Error';
		}
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>$res)));
	}

	function getproveedores($idEmpresa,$aniofiscal,$desc)
	{
		if($desc != 'vacio'){
			$data = $this->proveedormodel->get_proveedor_by_desc($idEmpresa,$desc.'%');
		}else
		{
			$data = $this->proveedormodel->get_proveedores_by_empresa($idEmpresa,$aniofiscal);
		}

		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function getprvdorclave($_clave)
	{
		$data = $this->proveedormodel->get_proveedor_by_clave($_clave);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

  function comprasprov($idproveedor,$aniofiscal){
    $data = $this->proveedormodel->get_compras_by_proveedor($idproveedor,$aniofiscal);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
  }

}
?>
