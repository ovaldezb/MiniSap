<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Factura extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('facturacionmodel');
        $this->load->model('tpvmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('factura');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
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
            $data['metodopago']
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

}
