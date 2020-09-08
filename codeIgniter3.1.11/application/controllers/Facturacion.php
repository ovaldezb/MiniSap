<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Facturacion extends CI_Controller
{
	function __construct() {
		parent::__construct();
		//$this->load->model('facturacionmodel');
		$this->load->model('catalogosmodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

	function index()
	{
		if(isset($_SESSION['username']))
    	{
			$data['tipo_pagos'] = $this->catalogosmodel->get_tipo_pago();
			$data['bancos'] = $this->catalogosmodel->get_bancos();
			$data['tarjetas'] = $this->catalogosmodel->get_tarjetas();
			$data['vales'] = $this->catalogosmodel->get_vales();
			/*Datos para los vendedores */
			$data['tipo_cliente'] = $this->catalogosmodel->get_tipo_cliente();
			$data['revision'] = $this->catalogosmodel->get_dias_semana();
			$data['forma_pago'] = $this->catalogosmodel->get_forma_pago();
			$data['vendedor'] = $this->catalogosmodel->get_vendedor();
			$data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
			$this->load->view('facturar',$data);
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
    	}
	}

	


}
?>
