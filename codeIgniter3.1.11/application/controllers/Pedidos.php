<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pedidosmodel');
        $this->load->model('catalogosmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $data['tipo_pagos'] = $this->catalogosmodel->get_tipo_pago();
            $data['bancos'] = $this->catalogosmodel->get_bancos();
            $data['tarjetas'] = $this->catalogosmodel->get_tarjetas();
            $data['vales'] = $this->catalogosmodel->get_vales();
            /*Datos para los vendedores */
            $data['tipo_cliente'] = $this->catalogosmodel->get_tipo_cliente();
            $data['revision'] = $this->catalogosmodel->get_dias_semana();
            $data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
            $this->load->view('pedidos', $data);
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function registrapedido()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->pedidosmodel->registra_pedido(array(
                $data['docto'],
                $data['idcliente'],
                $data['idvendedor'],
                $data['fechapedido'],
                $data['aniofiscal'],
                $data['idempresa'],
                $data['total'],
                $data['idsucursal'],
                $data['fpago'],
                $data['tpago'],
                $data['comentarios'],
                $data['cuenta'],
                $data['dias'],
                $data['idmoneda'],
                $data['fechaentrega'],
                $data['domi'],
                $data['mpago'],
                $data['idusuario'],
            )
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function registrapedidoprod()
    {
        if (isset($_SESSION['username'])) {
            /**Se removio la sucursal, no recuerdo para que se requeria */
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->pedidosmodel->registra_pedido_producto(array(
                $data['idpedido'],
                $data['idProducto'],
                $data['cantidad'],
                $data['precio'],
                $data['importe'],
                $data['descuento'],
                $data['idcalidad'],
            ));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getpedidos($idempresa, $anioFiscal, $idsucursal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->get_pedidos($idempresa, $anioFiscal, $idsucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getpedidostotales($idempresa, $anioFiscal, $idsucursal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->get_pedidos_activos($idempresa, $anioFiscal, $idsucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getpedidobyid($idpedido)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->getpedidobyid($idpedido);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getpedidetallebyid($idpedido)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->getpedidodetallebyid($idpedido);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getpedidetallebyidexistencia($idpedido,$idsucursal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->pedido_detallebyid_existencia($idpedido,$idsucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function elimpedidobyid($idpedido)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->eliminapedido($idpedido);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function deletepedprodbyid($idpedido)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->borrapedidoproducto($idpedido);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function updatepedido($idpedido, $status, $idfactura)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->pedidosmodel->updatepedido($idpedido, $status, $idfactura);
        }
    }

    public function updatepedbyid($idpedido)
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->pedidosmodel->update_pedido_by_id(array(
                $data['idcliente'],
                $data['idvendedor'],
                $data['total'],
                $data['idmoneda'],
                $data['tpago'],
                $data['fpago'],
                $data['mpago'],
                $data['fechaentrega'],
                $data['domi'],
                $data['comentarios'],
                $idpedido,
            ));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

}
