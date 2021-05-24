<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Compras extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('comprasmodel');
        $this->load->model('catalogosmodel');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $data['tipopago'] = $this->catalogosmodel->get_tipo_pago();
            $data['monedas'] = $this->catalogosmodel->get_monedas();
            $this->load->view('compras', $data);
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function registracompra()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->comprasmodel->insert_compra(array(
                $data['documento'],
                $data['claveprov'],
                $data['feccompra'],
                $data['tipopago'],
                $data['moneda'],
                $data['tipocambio'],
                $data['contrarec'],
                $data['fecpago'],
                $data['fecrevision'],
                $data['idempresa'],
                $data['docprev'],
                $data['diascred'],
                $data['importe'],
                $data['iva'],
                $data['aniofiscal'],
                $data['descuento'],
                $data['idsucursal'],
                $data['idproveedor'],
                $data['notas'],
                $data['saldo'])
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }

    }

    public function regcompraprdcto()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->comprasmodel->insert_compra_producto(array(
                $data['idcompra'],
                $data['idproducto'],
                $data['cantidad'],
                $data['unidadmedida'],
                $data['preciounitario'],
                $data['importetotal'],
                $data['dsctoprod'],
                $data['idsucursal'],
                $data['documento'],
                $data['caja'],
                $data['idempresa'],
                $data['aniofiscal'],
                $data['idcliente'],
                $data['idproveedor'],
                $data['idusuario'],
                $data['idmoneda'],
            ));
            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'OK')));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'Error ' . $result)));
            }
        }
    }

    public function getcompras($id_empresa, $anio_fiscal, $idsucursal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->comprasmodel->get_compras($id_empresa, $anio_fiscal, $idsucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getcomprabyid($idcompra)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->comprasmodel->get_compras_by_id($idcompra);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getcomprodbyid($idcompra)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->comprasmodel->get_compra_producto_by_id($idcompra);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function elimcompraprodid($idcompra, $idsucursal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->comprasmodel->elimina_compraprod_by_id($idcompra, $idsucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

}
