<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {
  function __construct() {
		parent::__construct();
		$this->load->model('accessmodel');
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
    $usuario = $this->accessmodel->valida_credenciales($this->input->post('username'));
    if(password_verify($this->input->post('password'),$usuario['PASSWORD']))
    {
      $data = array(
        'username' => $this->input->post('username'),
        'idsucursal' => $usuario['ID_SUCURSAL'],
        'currently_logged_in' => 1
      );
      $this->session->set_userdata($data);
      $user['nombre'] = $usuario['NOMBRE'];
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

}
?>
