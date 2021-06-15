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
			/*Datos para los vendedores */
			$data['tipo_cliente'] = $this->catalogosmodel->get_tipo_cliente();
			$data['revision'] = $this->catalogosmodel->get_dias_semana();
			//$data['forma_pago'] = $this->catalogosmodel->get_forma_pago();
			//$data['vendedor'] = $this->catalogosmodel->get_vendedor();
			//$data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
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
		$result = $this->tpvmodel->registra_venta(array(
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
			$data['idtarjeta'],
			$data['idbanco'],
			$data['idvales'],
			$data['importe'],
			$data['cambio'],
			$data['idsucursal'],
			$data['facturado'],
			$data['idfactura'],
      $data['origen'],
      $data['iva'],
      $data['idusuario'])
		);
		return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
	}

	function registraventaprod()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->tpvmodel->registra_venta_producto(array(
			$data['idventa'],
			$data['idProducto'],
			$data['cantidad'],
			$data['precio'],
			$data['importe'],
			$data['idsucursal'],
			$data['tipops'],
			$data['documento'],
			$data['caja'],
			$data['idempresa'],
			$data['aniofiscal'],
			$data['idcliente'],
			$data['idproveedor'],
			$data['idusuario'],
			$data['idmoneda'],
      $data['descuento'],
      $data['idcalidad'],
			)
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

	function getdataoper($idempresa,$aniofiscal,$fecIni,$fecFin){
		$result = $this->tpvmodel->dataOperByDate(array($aniofiscal,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$idempresa ));
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
	}

	function updventa($idventa){
		$result = $this->tpvmodel->updateventatrue($idventa);
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
	}

  function datoimprtkt($idventa){
    $result = $this->tpvmodel->get_data_impr_ticket($idventa);
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
  }

  function deloperacion($idventa){
    $result = $this->tpvmodel->delete_venta_by_id($idventa);
		return $this->output
					->set_content_type('application/json')
					->set_output($result);
  }

  public function savecortecaja(){
    if (isset($_SESSION['username'])) {
      $data = json_decode(file_get_contents("php://input"), true);
      $arraDatosFactura = array(
          $data['documento'],
          $data['fcorte'],
          $data['cliente'],
          $data['producto'],
          $data['importe'],
          $data['vendedor'],
          $data['tipopago'],
          $data['formapago'],
          $data['metodopago'],
          $data['idempresa'],
          $data['aniofiscal'],
          $data['idsucursal'],
          $data['usocfdi'],
          $data['moneda'],
          $data['operaciones'],
          $data['canceladas'],
          $data['idusuario'],
      );
      $result = $this->tpvmodel->save_corte_caja($arraDatosFactura);
      return $this->output
          ->set_content_type('application/json')
          ->set_output($result);
    }
  }

  public function corteventa($idcorte,$idventa){
    if (isset($_SESSION['username'])) {
      $result = $this->tpvmodel->corte_venta($idcorte,$idventa);
      return $this->output
          ->set_content_type('application/json')
          ->set_output($result);
  }
  }


  public function saveproductofact(){
    if (isset($_SESSION['username'])) {
      $data = json_decode(file_get_contents("php://input"), true);
      $arraDatosFactura = array(
          $data['idfactura'],
          $data['clave'],
          $data['nombre'],
          $data['importe'],
          $data['iva']
      );
      $result = $this->tpvmodel->save_producto_factura($arraDatosFactura);
      return $this->output
          ->set_content_type('application/json')
          ->set_output($result);
    }
  }

  public function updtfaccte($idfactura,$idcorte){
    if (isset($_SESSION['username'])) {
      $data = json_decode(file_get_contents("php://input"), true);
      $arraDatosFactura = array($idfactura,$idcorte);
      $result = $this->tpvmodel->update_factura_producto($arraDatosFactura);
      return $this->output
          ->set_content_type('application/json')
          ->set_output($result);
    }
  }

}
?>
