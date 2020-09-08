<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('vendedormodel');
		$this->load->model('catalogosmodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

	function index(){
		if(isset($_SESSION['username'])){
			//$data['area'] = $this->catalogosmodel->get_areas();
			//$data['titulo'] = $this->catalogosmodel->get_titulos();
			//$data['puesto'] = $this->catalogosmodel->get_puestos();
			$this->load->view('vendedores');
		}
		else {
			$error['error'] = '';
			$this->load->view('login',$error);
		}

	}

	function getvendedores($idempresa,$nombre){
		if($nombre == 'vacio'){
			$data = $this->vendedormodel->get_vendedores($idempresa);
		}
		else{
			$nvoNombre = rawurldecode($nombre);
			$data = $this->vendedormodel->get_vendedores_by_nombre($idempresa,'%'.$nvoNombre.'%');
		}
		return $this->output
			->set_content_type('application/json')
			->set_output($data);
	}	

	function save(){
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->vendedormodel->crea_vendedor(
			array($data['nombre'],
			//$data['id_area'],
			//$data['id_puesto'],
			//$data['id_titulo'],
			$data['idempresa']));
		return $this->output
		->set_content_type('application/json')
		->set_output($result);
	}

	function delete($id){
		$result = $this->vendedormodel->delete_vendedor($id);
		if($result){
			$res = 'OK';
		}
		else{
			$res = 'Error';
		}
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>$res)));
	}

	function update($id){
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->vendedormodel->update_vendedor(array($data['nombre'],$id));
		if($result){
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>'El vendedor se actualizo correctamente')));
		}
		else{
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>$result)));
		}
	}

}
?>
