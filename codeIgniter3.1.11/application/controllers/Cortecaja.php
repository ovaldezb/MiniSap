<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cortecaja extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('cortecajamodel');
		//$this->load->helper('url');
		$this->load->library('session');
	}

  function index() {
		if(isset($_SESSION['username']))
    {
			$this->load->view('cortecaja');
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
		}
	}

  function reportemes($idempresa,$aniofiscal,$fecIni,$fecFin){
		$result = $this->cortecajamodel->reporte_by_month($idempresa,$aniofiscal,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin));
		return $this->output
		->set_content_type('application/json')
		->set_output($result);
  }

  function getoperdate($idempresa,$aniofiscal,$fecIni,$fecFin){
		$result = $this->cortecajamodel->getOperMonthByDate(array($aniofiscal,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$idempresa ));
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
	}

  function getdataopercc($idempresa,$aniofiscal,$fecIni,$fecFin){
		$result = $this->cortecajamodel->dataOperByDateCC(array($aniofiscal,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$idempresa ));
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
	}

  function getvendasbyid($idventa){
    $result = $this->cortecajamodel->get_ventas_by_id(array($idventa));
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
  }

}

?>