<?php
  
class Reppagos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('reportemodel');
        $this->load->model('catalogosmodel');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('reppagos');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    function reppagos($anioFiscal,$idEmpresa,$fecIni,$fecFin){
      return $this->output
      ->set_content_type('application/json')
      ->set_output($this->reportemodel->get_reporte_pagos($anioFiscal,$idEmpresa,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin)));
    }

}
