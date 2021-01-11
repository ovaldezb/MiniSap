<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('reportemodel');
    $this->load->model('catalogosmodel');
    //$this->load->library('session');
  }

  function rmovalmc()
  {
    //$data['lineas'] = $this->catalogosmodel->get_linea_by_empresa(1);
    $this->load->view('repmovalmacen');
    /*if(isset($_SESSION['username']))
    {
      $data['lineas'] = $this->catalogosmodel->get_linea_by_empresa(1);
      $this->load->view('repmovalmacen',$data);
    }else {
      $error['error'] = '';
      $this->load->view('login',$error);
    }*/
  }

  function rventas()
  {
    $this->load->view('rventas');
  }

  function movalmacen($idEmpresa,$fy,$fecIni,$fecFin,$linea)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output( $this->reportemodel->get_reporte_mov_almacen($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$linea));
  }

  function ventas($idEmpresa,$fy,$fecIni,$fecFin,$linea,$expresion,$tipo)
  {
    $reporte = null;
    switch($tipo){
      case 1:
        $reporte = $this->reportemodel->get_reporte_ventas_by_empr_fy($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$linea,false);
        break;
      case 2:
        $reporte = $this->reportemodel->get_reporte_ventas_by_empr_fy($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$linea,true);
        break;
      case 3:
        $reporte = $this->reportemodel->get_reporte_ventas_by_empr_fy_codigo_desc($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$linea,$expresion,true);
        break;
      case 4:
        $reporte = $this->reportemodel->get_reporte_ventas_by_empr_fy_codigo_desc($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$linea,$expresion,false);
        break;
    }

    return $this->output
            ->set_content_type('application/json')
            ->set_output($reporte);
  }

}
