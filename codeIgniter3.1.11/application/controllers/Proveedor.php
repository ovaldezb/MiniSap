<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Proveedor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('catalogosmodel');
        $this->load->model('proveedormodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $data['proveedores'] = $this->catalogosmodel->get_tipo_prov();
            $data['alcanceprov'] = $this->catalogosmodel->get_alcance_prov();
            $data['bancos'] = $this->catalogosmodel->get_bancos();
            $this->load->view('proveedor', $data);
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function loadByEmpresa($idEmpresa, $aniofiscal)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->proveedormodel->get_proveedores_by_empresa($idEmpresa, $aniofiscal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function save()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->proveedormodel->create_proveedor(
                $data['clave'],
                $data['nombre'],
                $data['domicilio'],
                $data['cp'],
                $data['telefono'],
                $data['contacto'],
                $data['rfc'],
                $data['curp'],
                $data['id_tipo_prov'],
                $data['dias_cred'],
                $data['id_tipo_alc_prov'],
                $data['banco'],
                $data['cuenta'],
                $data['email'],
                $data['notas'],
                $data['idempresa']);

            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($result);
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'Error')));
            }
        }
    }

    public function loadbyid($id_proveedor)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->proveedormodel->get_proveedor_by_id($id_proveedor);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function update($id_proveedor)
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->proveedormodel->update_proveedor(
                $id_proveedor,
                $data['clave'],
                $data['nombre'],
                $data['domicilio'],
                $data['cp'],
                $data['telefono'],
                $data['contacto'],
                $data['rfc'],
                $data['curp'],
                $data['id_tipo_prov'],
                $data['dias_cred'],
                $data['id_tipo_alc_prov'],
                $data['banco'],
                $data['cuenta'],
                $data['email'],
                $data['notas']);
            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'OK')));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'Error')));
            }
        }
    }

    public function delete($id)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->proveedormodel->delete_proveedor($id);
            if ($result) {
                $res = 'OK';
            } else {
                $res = 'Error';
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('value' => $res)));
        }
    }

    public function getproveedores($idEmpresa, $aniofiscal, $desc)
    {
        if (isset($_SESSION['username'])) {
            if ($desc != 'vacio') {
                $data = $this->proveedormodel->get_proveedor_by_desc($idEmpresa, $desc . '%');
            } else {
                $data = $this->proveedormodel->get_proveedores_by_empresa($idEmpresa, $aniofiscal);
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function getprvdorclave($_clave)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->proveedormodel->get_proveedor_by_clave($_clave);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function comprasprov($idproveedor, $aniofiscal)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->proveedormodel->get_compras_by_proveedor($idproveedor, $aniofiscal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

}
