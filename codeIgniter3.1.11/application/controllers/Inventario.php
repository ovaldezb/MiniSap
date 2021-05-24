<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('controlinvmodel');
        $this->load->model('catalogosmodel');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('controlinventario');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function getinventario()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->controlinvmodel->get_inventario(
                $data['idempresa'],
                $data['aniofiscal'],
                $data['tipoMov'],
                $data['tipoES'],
                $data['fechaIni'],
                $data['fechaFin'],
                $data['caja'],
                $data['codigoProducto'],
                $data['idsucursal']);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function saveinventario()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->controlinvmodel->save_inventario(array(
                $data['aniofiscal'],
                $data['caja'],
                $data['documento'],
                $data['fecha'],
                $data['codigo'],
                $data['idproducto'],
                $data['idcliente'],
                $data['idempresa'],
                $data['idmoneda'],
                $data['idproveedor'],
                $data['idsucursal'],
                $data['idusuario'],
                $data['importe'],
                $data['in'],
                $data['out'],
                $data['preciounit'],
                $data['tipoMov'],
            ));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function delmov($idMov)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->controlinvmodel->del_movinv($idMov);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }
}
