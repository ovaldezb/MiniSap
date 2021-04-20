<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Facturas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('facturacionmodel');
        $this->load->model('catalogosmodel');
        $this->load->model('tpvmodel');
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
			$this->load->view('facturas',$data);
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
    	}
	}

    public function savefactura(){
        $data = json_decode(file_get_contents("php://input"),true);
        $arraDatosFactura = array(
            $data['documento'],
            $data['ffactura'],
            $data['idcliente'],
            $data['importe'],
            $data['saldo'],
            $data['tipopago'],
            $data['frevision'],
            $data['fvencimiento'],
            $data['idvendedor'],
            $data['idempresa'],
            $data['aniofiscal'],
            $data['idsucursal'],
            $data['formapago'],
            $data['usocfdi'],
            $data['metodopago'],
            $data['contacto']
        );
       $result = $this->facturacionmodel->savefactura($arraDatosFactura);
       return $this->output
            ->set_content_type('application/json')
            ->set_output($result);
    }

    /* las ventas contienen los datos de una factura */
	function getfacturas($idEmpresa,$idAnioFiscal){
		$result = $this->facturacionmodel->getfacturas(array($idEmpresa,$idAnioFiscal));
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
    }
    
    function getfactdetbyid($idfactura){
		$result = $this->tpvmodel->getventadetallebyid($idfactura);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output(json_encode($result));
	}

	function eliminafact($idventa,$idsucursal){
		$result = $this->facturacionmodel->eliminaFacturaById($idventa,$idsucursal);
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
	}

  function validaprdfact($idfactura){
    $result = $this->facturacionmodel->get_prod_by_fact_validar($idfactura);
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
  }

  function datoscfdi($idfactura){
    $result = $this->facturacionmodel->get_datos_for_cfdi($idfactura);
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
  }

}