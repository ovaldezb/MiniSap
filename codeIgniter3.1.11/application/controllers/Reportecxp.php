<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportecxp extends CI_Controller
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
        $this->load->view('reportecxp');
    } else {
        $error['error'] = '';
        $this->load->view('login', $error);
    }
  }

  public function getrepcxp($idempresa, $aniofiscal){

  }
}
?>