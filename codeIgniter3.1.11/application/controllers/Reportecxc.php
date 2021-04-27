<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportecxc extends CI_Controller
{
	public function __construct()
    {
        parent::__construct();
        $this->load->model('reportemodel');
        $this->load->library('session');
    }

  public function index()
  {
    if (isset($_SESSION['username'])) {
        $this->load->view('reportecxc');
    } else {
        $error['error'] = '';
        $this->load->view('login', $error);
    }
  }

  public function getrepcxc($idempresa,$anioFiscal){
    if (isset($_SESSION['username'])) {
      $result = $this->reportemodel->get_reporte_cxc($idempresa,$anioFiscal);
      return $this->output
          ->set_content_type('application/json')
          ->set_output($result);

  }
  }
}
?>