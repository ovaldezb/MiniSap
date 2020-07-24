<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utils extends CI_Controller
{
  function __construct() {
		parent::__construct();
		$this->load->model('catalogosmodel');
	}

  function incremento($nombre,$idempresa,$longitud)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->catalogosmodel->get_incremento_by_name($nombre,$idempresa,$longitud));
  }

  function lineaempr($idEmpresa)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->catalogosmodel->get_linea_by_empresa($idEmpresa));
  }

  function getmoneda(){
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->catalogosmodel->get_moneda_json());
  }

  function getmetpag(){
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->catalogosmodel->get_metodo_pago());
  }

  function getformpag(){
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->catalogosmodel->get_forma_pago_js());
  }

  function getusocfdi(){
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->catalogosmodel->get_uso_cfdi_js());
  }

}
?>
