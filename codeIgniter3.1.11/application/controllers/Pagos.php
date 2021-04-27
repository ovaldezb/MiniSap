<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pagos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pagosmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('pagos');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function getpagos($idempresa, $anioFiscal)
    {
      if (isset($_SESSION['username'])) {
          $result = $this->pagosmodel->getListaPagos(array($idempresa, $anioFiscal));
          return $this->output
              ->set_content_type('application/json')
              ->set_output($result);
      }
    }

    public function guardapago()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->pagosmodel->guardapago(array(
                $data['fechapago'],
                $data['importepago'],
                $data['movimiento'],
                $data['banco'],
                $data['cheque'],
                $data['depositoen'],
                $data['poliza'],
                $data['importebase'],
                $data['idempresa'],
                $data['aniofiscal'],
                $data['idcompra'],
                $data['idproveedor'],
            )
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getpagocom($idcompra)
    {
      if (isset($_SESSION['username'])) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output($this->pagosmodel->getpagobycompra($idcompra));
      }
    }

    public function deletepago($idpago, $idcompra, $importe)
    {
      if (isset($_SESSION['username'])) {
        return $this->output
            ->set_content_type('application/json')
            ->set_output($this->pagosmodel->deletebyid($idpago, $idcompra, $importe));
      }
    }

    public function updatepago($idpago, $idcompra, $importe)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->pagosmodel->updatebyid($idpago, $idcompra, $importe));
        }
    }

    public function getpagoid($idpago)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->pagosmodel->getpagobyid($idpago));
        }
    }

}
