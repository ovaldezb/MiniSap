<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('pagosmodel');
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

	function getpagos($idempresa, $anioFiscal){
		$result = $this->pagosmodel->getListaPagos(array($idempresa,$anioFiscal));
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
			$data['idcompra'],
      $data['idproveedor']
			)
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function getpagocom($idcompra){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->getpagobycompra($idcompra));
	}

	function deletepago($idpago,$idcompra,$importe){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->deletebyid($idpago,$idcompra,$importe));
	}

	function updatepago($idpago,$idcompra,$importe){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->updatebyid($idpago,$idcompra,$importe));
	}

  function getpagoid($idpago){
    return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->pagosmodel->getpagobyid($idpago));
  }

}
?>