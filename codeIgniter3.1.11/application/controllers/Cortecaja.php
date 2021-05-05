<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cortecaja extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cortecajamodel');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('cortecaja');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function reportemes($idempresa, $aniofiscal, $fecIni, $fecFin)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->cortecajamodel->reporte_by_month($idempresa, $aniofiscal, str_replace("%20", " ", $fecIni), str_replace("%20", " ", $fecFin));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getoperdate($idempresa, $aniofiscal, $fecIni, $fecFin)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->cortecajamodel->getOperMonthByDate(array($aniofiscal, str_replace("%20", " ", $fecIni), str_replace("%20", " ", $fecFin), $idempresa));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getdataopercc($idempresa, $aniofiscal, $fecIni, $fecFin)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->cortecajamodel->dataOperByDateCC(array($aniofiscal, str_replace("%20", " ", $fecIni), str_replace("%20", " ", $fecFin), $idempresa));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getvendasbyid($idventa)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->cortecajamodel->get_ventas_by_id(array($idventa));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getdocinifin($idempresa,$aniofiscal,$fecIni,$fecFin)
    {
      if (isset($_SESSION['username'])) {
        $result = $this->cortecajamodel->get_docto_ini_fin(array($idempresa,$aniofiscal,str_replace("%20", " ", $fecIni), str_replace("%20", " ", $fecFin),$idempresa,$aniofiscal,str_replace("%20", " ", $fecIni), str_replace("%20", " ", $fecFin)));
        
        return $this->output
            ->set_content_type('application/json')
            ->set_output($result);
        
      }
    }

}
