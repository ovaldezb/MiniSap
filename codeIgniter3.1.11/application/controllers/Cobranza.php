<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cobranza extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cobranzamodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('cobranza');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function getfacturas($idempresa, $anioFiscal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->cobranzamodel->getListaFacturas(array($idempresa, $anioFiscal));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function guardacobro()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->cobranzamodel->guardacobranza(array(
                $data['fechacobro'],
                $data['importecobro'],
                $data['movimiento'],
                $data['banco'],
                $data['cheque'],
                $data['depositoen'],
                $data['poliza'],
                $data['importebase'],
                $data['idempresa'],
                $data['aniofiscal'],
                $data['idfactura'],
            )
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getcobrofac($idfactura)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->cobranzamodel->getcobranzabyfactura($idfactura));
        }
    }

    public function deletecobro($idcobro, $idfactura, $importe)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->cobranzamodel->deletebyid($idcobro, $idfactura, $importe));
        }
    }

    public function updatecobro($idcobro, $idfactura, $importe)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->cobranzamodel->updatebyid($idcobro, $idfactura, $importe));
        }
    }

    public function getcobroid($idcobro)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->cobranzamodel->getcobranzabyid($idcobro));
        }
    }

}
