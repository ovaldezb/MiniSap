<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Vendedor extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vendedormodel');
        $this->load->model('catalogosmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('vendedores');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }

    }

    public function getvendedores($idempresa, $nombre)
    {
        if (isset($_SESSION['username'])) {
            if ($nombre == 'vacio') {
                $data = $this->vendedormodel->get_vendedores($idempresa);
            } else {
                $nvoNombre = rawurldecode($nombre);
                $data = $this->vendedormodel->get_vendedores_by_nombre($idempresa, '%' . $nvoNombre . '%');
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function findvendbyid($codigo, $idempresa)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->vendedormodel->get_vend_by_id($codigo, $idempresa);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function save()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->vendedormodel->crea_vendedor(
                array($data['nombre'],
                    $data['idempresa']));
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function delete($id)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->vendedormodel->delete_vendedor($id);
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

    public function update($id)
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->vendedormodel->update_vendedor(array($data['nombre'], $id));
            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'El vendedor se actualizo correctamente')));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => $result)));
            }
        }
    }

}
