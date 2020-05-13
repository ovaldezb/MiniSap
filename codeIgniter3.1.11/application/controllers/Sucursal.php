<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sucursal extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('sucursalmodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

  function index()
  {
		if(isset($_SESSION['username']))
    {
			/*Esta variable se debe recibir del lugar donde se invoque este servicio*/
			$data['id_empresa'] = '1';
			$this->load->view('sucursal',$data);
		}else {
			$error['error'] = '';
      $this->load->view('login',$error);
    }
  }

  function load()
  {
    $data = $this->sucursalmodel->get_sucursales();
    return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
  }

  function loadbyid($_id)
  {
    $data = $this->sucursalmodel->get_sucursal_by_id($_id);
    return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
  }

  function save()
  {
    $data = json_decode(file_get_contents("php://input"),true);
    $result = $this->sucursalmodel->create_sucursal(
    $data['clave'],
    $data['direccion'],
    $data['responsable'],
    $data['telefono'],
    $data['cp'],
    $data['alias'],
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

  function update($id_sucursal)
  {
    $data = json_decode(file_get_contents("php://input"),true);
    $result = $this->sucursalmodel->update_sucursal(
    $id_sucursal,
    $data['clave'],
    $data['direccion'],
    $data['responsable'],
    $data['telefono'],
    $data['cp'],
    $data['alias'],
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

  function delete($id_sucursal)
  {
    $result = $this->sucursalmodel->delete_sucursal($id_sucursal);
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

}
