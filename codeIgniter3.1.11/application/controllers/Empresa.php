<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Empresa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('empresamodel');
        $this->load->model('catalogosmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $data['regimenes'] = $this->catalogosmodel->get_regimenes();
            $this->load->view('empresa', $data);
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function load()
    {
        if (isset($_SESSION['username'])) {
            $data = $this->empresamodel->get_empresas();
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function save()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->empresamodel->create_empresa(
                $data['nombre'],
                $data['domicilio'],
                $data['rfc'],
                $data['cp'],
                $data['ejercicio_fiscal'],
                $data['regimen'],
                $data['digxcta'],
                $data['cuenta_resultado'],
                $data['resultado_anterior'],
                $data['telefono'],
                $data['email'],
                $data['redessociales'],
                $data['mensaje']);

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

    public function loadbyid($id)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->empresamodel->get_empresa_by_id($id);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function update($id)
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->empresamodel->update_empresa($id, $data['nombre'],
                $data['domicilio'],
                $data['rfc'],
                $data['cp'],
                $data['ejercicio_fiscal'],
                $data['regimen'],
                $data['digxcta'],
                $data['cuenta_resultado'],
                $data['resultado_anterior'],
                $data['telefono'],
                $data['email'],
                $data['redessociales'],
                $data['mensaje']
            );
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

    public function delete($idempresa)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->empresamodel->delete_empresa($idempresa);
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

    public function getemppermbyusr($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->empresamodel->get_emp_perm_by_id($idusuario));
        }
    }

    public function getfybyemp($idempresa)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->empresamodel->get_fy_by_emp($idempresa));
        }
    }

}
