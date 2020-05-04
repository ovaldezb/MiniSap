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
    $data = $this->catalogosmodel->get_incremento_by_name($nombre,$idempresa,$longitud);
    return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
  }
}
?>
