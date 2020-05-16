<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {
  function __construct() {
		parent::__construct();
		$this->load->model('accessmodel');
    $this->load->model('procesosmodel');
    $this->load->library('session');
		$this->load->helper('url');
	}

  public function index()
  {
    $error['error'] = '';
    $this->load->view('login',$error);
  }

  function inicio()
  {
    if(isset($_SESSION['username']))
    {
      $this->load->view('inicio');
    }else {
      $error['error'] = '';
      $this->load->view('login',$error);
    }
  }

  public function login()
  {
    $usuario = $this->accessmodel->get_credenciales($this->input->post('username'));
    if(password_verify($this->input->post('password'),$usuario['PASSWORD']))
    {
      $data = array(
        'username' => $this->input->post('username'),
        'idsucursal' => $usuario['ID_SUCURSAL'],
        'idusuario' => $usuario['ID_USUARIO']
      );
      $this->session->set_userdata($data);
      $user['nombre'] = $usuario['NOMBRE'];
      $user['idusuario'] = $usuario['ID_USUARIO'];
      $user['modproc'] = $this->procesosmodel->get_modulos_procesos_by_usuario($usuario['ID_USUARIO']);
      $this->load->view('index',$user);
    }else {
      $error['error'] = 'Usuario o contraseña no coinciden';
      $this->load->view('login',$error);
    }
  }

  public function logout()
  {
    $this->session->sess_destroy();
    $this->load->view('logout');
  }

  public function setempfy($idempresa,$fy)
  {
    $data = array(
      'idempresa' => $idempresa,
      'aniofiscal' => $fy
    );
    $this->session->set_userdata($data);
  }

}
?>
