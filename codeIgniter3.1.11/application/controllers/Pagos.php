<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('pagosmodel');
		//$this->load->model('catalogosmodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

    function index() {
		if(isset($_SESSION['username']))
    	{
            $this->load->view('pagos');
        }else{
            $error['error'] = '';
      		$this->load->view('login',$error);
        }
    }

	function getfacturas($idempresa, $anioFiscal){
		$result = $this->pagosmodel->getListaFacturas(array($idempresa,$anioFiscal));
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

	function guardapago(){
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->pagosmodel->guardapago(array(
			$data['fechapago'],
			$data['importepago'],
			$data['movimiento'],
			$data['banco'],
			$data['cheque'],
			$data['depositoen'],
			$data['poliza'],
			$data['importebase'],
			$data['idempresa'],
			$data['aniofiscal'],
			$data['idfactura']
			)
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function getpagofac($idfactura){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->getpagobyfactura($idfactura));
	}

	function deletepago($idpago,$idfactura,$importe){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->deletebyid($idpago,$idfactura,$importe));
		
	}

  function getpagoid($idpago){
    return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->getpagobyid($idpago));
  }

}
?>