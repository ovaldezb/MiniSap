<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empresa extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('empresamodel');
		$this->load->model('catalogosmodel');
		$this->load->helper('url');
	}

	function index() {
		$data['regimenes'] = $this->catalogosmodel->get_regimenes();
		$this->load->view('empresa',$data);
	}

	function load()
	{
		$data = $this->empresamodel->get_empresas();
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function save()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->empresamodel->create_empresa(
			$data['nombre'],
			$data['domicilio'],
			$data['rfc'],
			$data['ejercicio_fiscal'],
			$data['regimen'],
			$data['digxcta'],
			$data['cuenta_resultado'],
			$data['resultado_anterior']);

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

	function loadbyid($id)
	{
		$data = $this->empresamodel->get_empresa_by_id($id);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function update($id)
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->empresamodel->update_cliente($id,$data['nombre'],
		$data['domicilio'],
		$data['rfc'],
		$data['ejercicio_fiscal'],
		$data['regimen'],
		$data['digxcta'],
		$data['cuenta_resultado'],
		$data['resultado_anterior']);
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
		$result = $this->empresamodel->delete_empresa($id);
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
}
?>
