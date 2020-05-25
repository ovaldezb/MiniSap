<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor extends CI_Controller
{
	function __construct() {
		parent::__construct();
    $this->load->model('vendedormodel');
		$this->load->helper('url');
	}

  function index()
  {

  }

  function getvendedores($idempresa,$nombre)
  {
    if($nombre == 'vacio')
		{
			$data = $this->vendedormodel->get_vendedores($idempresa);
		}else{
			$nvoNombre = rawurldecode($nombre);
			$data = $this->vendedormodel->get_vendedores_by_nombre($idempresa,'%'.$nvoNombre.'%');
		}
		return $this->output
						->set_content_type('application/json')
						->set_output($data);
  }

}
?>
