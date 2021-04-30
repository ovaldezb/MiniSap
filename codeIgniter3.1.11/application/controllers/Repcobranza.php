<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Repcobranza extends CI_Controller
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
            $this->load->view('repcobranza');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    function repcobranza($anioFiscal,$idEmpresa,$fecIni,$fecFin){
      return $this->output
      ->set_content_type('application/json')
      ->set_output($this->reportemodel->get_reporte_cobranza($anioFiscal,$idEmpresa,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin)));
    }
}
