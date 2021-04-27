<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Usuarios extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usuariomodel');
        $this->load->model('procesosmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('usuarios');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function getusrbyid($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_usuario_by_id($idusuario));
        }
    }

    public function getprocsusr($idusuario)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->usuariomodel->get_procesos_by_usuario($idusuario);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function allempr()
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_all_empr_nuevo());
        }
    }

    public function emppermusr($usuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_empperm_by_usuario($usuario));
        }
    }

    public function allmoduls()
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_all_modulos_nuevo());
        }
    }

    public function saveusr()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->usuariomodel->crea_usuario(
                $data['nombre'],
                $data['usrname'],
                password_hash($data['paswd'], PASSWORD_DEFAULT)
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function updtusuario()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->usuariomodel->update_usuario(
                $data['idusuario'],
                $data['nombre'],
                $data['usrname'],
                password_hash($data['paswd'], PASSWORD_BCRYPT),
                $data['updtpwd']
            );
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function elimmodusr($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->elimina_modulosperm_by_usuario($idusuario));
        }
    }

    public function elimemperm($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->elimina_empperm_by_usuario($idusuario));
        }
    }

    public function insrtmdls($idusuario, $idmodulo)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->inserta_modulo_by_user($idusuario, $idmodulo));
        }
    }

    public function insrtempperm($idusuario, $idempresa)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->inserta_empperm_by_user($idusuario, $idempresa));
        }
    }

    public function getusrs()
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_ususarios());
        }
    }

    public function getmodulsusr($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_modulos_by_ususario($idusuario));
        }
    }

    public function getallmodulsusr($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->get_all_modulos_by_usuario($idusuario));
        }
    }

    public function eliminausrproc($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->elimina_procesos_by_usuario($idusuario));
        }
    }

    public function insrtprocusr($idusuario, $idproceso, $p, $a, $b, $m, $c)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->inserta_proceso_by_usuario($idusuario, $idproceso, $p, $a, $b, $m, $c));
        }
    }

    public function eliminausr($idusuario)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->usuariomodel->elimina_usuario($idusuario));
        }
    }

    public function permusrproc($idusuario, $idproceso)
    {
        if (isset($_SESSION['username'])) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output($this->procesosmodel->get_perm_by_proc_usr($idusuario, $idproceso));
        }
    }

}
