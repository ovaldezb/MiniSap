<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('controlinvmodel');
		$this->load->model('catalogosmodel');
		$this->load->library('session');
    }
    
    function index() {
		if(isset($_SESSION['username']))
    {
			//$data['tipo_cliente'] = $this->catalogosmodel->get_tipo_cliente();
			//$data['revision'] = $this->catalogosmodel->get_dias_semana();
			//$data['forma_pago'] = $this->catalogosmodel->get_forma_pago();
			//$data['vendedor'] = $this->catalogosmodel->get_vendedor();
			//$data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
			$this->load->view('controlinventario');
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
		}
	}

	function getinventario(){
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->controlinvmodel->get_inventario(		
			$data['idempresa'],
			$data['aniofiscal'],
			$data['tipoMov'],
			$data['tipoES'],
			$data['fechaIni'],
			$data['fechaFin'],
			$data['caja'],
			$data['codigoProducto']);
			return $this->output
			->set_content_type('application/json')
			->set_output($result);
	}

	function save(){
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->controlinvmodel->save_inventario(array(
			$data['aniofiscal'],
			$data['caja'],
			$data['documento'],
			$data['fecha'],
			$data['codigo'],
			$data['idproducto'],
			$data['idcliente'],
			$data['idempresa'],
			$data['idmoneda'],
			$data['idproveedor'],
			$data['idsucursal'],
			$data['idusuario'],
			$data['importe'],
			$data['in'],
			$data['out'],
			$data['preciounit'],
			$data['tipoMov']
		));
		return $this->output
			->set_content_type('application/json')
			->set_output($result);
	}

	function delmov($idMov){
		$result = $this->controlinvmodel->del_movinv($idMov);
		return $this->output
			->set_content_type('application/json')
			->set_output($result);
	}
}

?>