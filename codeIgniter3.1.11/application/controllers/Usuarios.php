<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {
  function __construct() {
    parent::__construct();
    //$this->load->model('clientemodel');
    $this->load->helper('url');
  }

  public function index()
  {
    $this->load->view('usuarios');
  }

}

?>
