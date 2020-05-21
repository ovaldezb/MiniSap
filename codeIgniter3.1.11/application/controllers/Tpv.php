<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tpv extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('tpvmodel');
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
			$this->load->view('tpv',$data);
		}else {
			$error['error'] = '';
      $this->load->view('login',$error);
    }
	}

	function getitems($idEmpresa,$desc,$tipo_req)
	{
		if($desc != 'vacio'){
			$nvodesc = str_replace("%20"," ",$desc);
			$data = $this->tpvmodel->get_items($idEmpresa,'%'.$nvodesc.'%',$tipo_req);
		}else
		{
			$data = $this->tpvmodel->get_items_vacio($idEmpresa,$tipo_req);
		}
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function registraventa()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->tpvmodel->registra_venta(
			$data['documento'],
			$data['idcliente'],
			$data['idvendedor'],
			$data['fechaventa'],
			$data['aniofiscal'],
			$data['idempresa'],
			$data['idtipopago'],
			$data['pagoefectivo'],
			$data['pagotarjeta'],
			$data['pagocheques'],
			$data['pagovales'],
			$data['idtarjea'],
			$data['idbanco'],
			$data['idvales'],
			$data['importe'],
			$data['cambio'],
			$data['idsucursal']
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function registraventaprod()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->tpvmodel->registra_venta_producto(
			$data['idventa'],
			$data['idProducto'],
			$data['cantidad'],
			$data['precio'],
			$data['importe'],
			$data['idsucursal'],
			$data['tipops']
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function getitemsbyprodsuc($idProducto,$idSucursal)
	{
		$result = $this->tpvmodel->get_items_by_suc($idProducto,$idSucursal);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function getproductosforsucursal($idProducto)
	{
		$result = $this->tpvmodel->get_productos_for_each_sucursal($idProducto);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

}
?>
