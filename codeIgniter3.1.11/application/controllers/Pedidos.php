<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('pedidosmodel');
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
			//$data['vendedor'] = $this->catalogosmodel->get_vendedor();
			$data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
			$this->load->view('pedidos',$data);
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
    	}
	}

	function registrapedido()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->pedidosmodel->registra_pedido(array(
			$data['docto'],
			$data['idcliente'],
			$data['idvendedor'],
			$data['fechapedido'],
			$data['aniofiscal'],
			$data['idempresa'],
			$data['total'],
			$data['idsucursal'],
			$data['fpago'],
			$data['tpago'],
			$data['contacto'],
			$data['cuenta'],
			$data['dias'],
			$data['idmoneda'],
			$data['fechaentrega']
			)
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function registrapedidoprod()
	{
    /**Se removio la sucursal, no recuerdo para que se requeria */
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->pedidosmodel->registra_pedido_producto(array(
			$data['idpedido'],
			$data['idProducto'],
			$data['cantidad'],
			$data['precio'],
			$data['importe'],
      $data['descuento']
    ));
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function getpedidos($idempresa,$anioFiscal){
		$result = $this->pedidosmodel->get_pedidos($idempresa,$anioFiscal);
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

  function getpedidostotales($idempresa,$anioFiscal){
		$result = $this->pedidosmodel->get_pedidos_activos($idempresa,$anioFiscal);
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

	function getpedidobyid($idpedido){
		$result = $this->pedidosmodel->getpedidobyid($idpedido);
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

	function getpedidetallebyid($idpedido){
		$result = $this->pedidosmodel->getpedidodetallebyid($idpedido);
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

	function elimpedidobyid($idpedido){
		$result = $this->pedidosmodel->eliminapedido($idpedido);
		return $this->output
				->set_content_type('application/json')
				->set_output($result);
	}

	function updatepedido($idpedido,$status){
		$result = $this->pedidosmodel->updatepedido($idpedido,$status);
	}


}
?>
