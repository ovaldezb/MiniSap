<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('reportemodel');
    $this->load->model('catalogosmodel');
    $this->load->library('session');
  }

  function rmovalmc()
  {
    $this->load->view('repmovalmacen'); 
  }

  function rventas()
  {
    $this->load->view('rventas');
  }

  function movalmacen($idEmpresa,$fy,$fecIni,$fecFin,$linea)
  {
    if (isset($_SESSION['username'])) {
      if($linea === 0){
        return $this->output
          ->set_content_type('application/json')
          ->set_output( $this->reportemodel->get_reporte_mov_almacen_by_line($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin),$linea));
      }else{
        return $this->output
          ->set_content_type('application/json')
          ->set_output( $this->reportemodel->get_reporte_mov_almacen($idEmpresa,$fy,str_replace("%20"," ",$fecIni),str_replace("%20"," ",$fecFin)));
      }
    
    }
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

  /** Aqui van a ir las llamadas a las consultas que se van a mostrar en el Dashboard */

  /** Valor del Inventario */
  function valinvent($idEmpresa,$idscursal){
    return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->reportemodel->get_valor_inventario($idEmpresa,$idscursal)));
  }

  function ventasfy($idEmpresa, $anioFiscal){
    return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->reportemodel->get_ventas_aniofiscal($idEmpresa,$anioFiscal)));
  }

  function ctsxcob($idEmpresa,$anioFiscal){
    return $this->output
    ->set_content_type('application/json')
    ->set_output($this->reportemodel->get_cuentas_x_cobrar($idEmpresa,$anioFiscal));
  }

  function ctsxpag($idEmpresa,$anioFiscal){
    return $this->output
    ->set_content_type('application/json')
    ->set_output($this->reportemodel->get_cuentas_x_pagar($idEmpresa,$anioFiscal));
  }

}
?>