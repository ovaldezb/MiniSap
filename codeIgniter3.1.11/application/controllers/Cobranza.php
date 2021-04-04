<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cobranza extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('cobranzamodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

    function index() {
		if(isset($_SESSION['username']))
    	{
            $this->load->view('cobranza');
        }else{
            $error['error'] = '';
      		$this->load->view('login',$error);
        }
    }

	function getfacturas($idempresa, $anioFiscal){
		$result = $this->cobranzamodel->getListaFacturas(array($idempresa,$anioFiscal));
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

	function guardacobro(){
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->cobranzamodel->guardacobranza(array(
			$data['fechacobro'],
			$data['importecobro'],
			$data['movimiento'],
			$data['banco'],
			$data['cheque'],
			$data['depositoen'],
			$data['poliza'],
			$data['importebase'],
			$data['idempresa'],
			$data['aniofiscal'],
			$data['idfactura'],
      $data['idcliente']
			)
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function getcobrofac($idfactura){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->cobranzamodel->getcobranzabyfactura($idfactura));
	}

	function deletecobro($idcobro,$idfactura,$importe){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->cobranzamodel->deletebyid($idcobro,$idfactura,$importe));
	}

  function updatecobro($idcobro,$idfactura,$importe){
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->cobranzamodel->updatebyid($idcobro,$idfactura,$importe));
	}

  function getcobroid($idcobro){
    return $this->output
					 ->set_content_type('application/json')
					 ->set_output($this->cobranzamodel->getcobranzabyid($idcobro));
  }

}
?>