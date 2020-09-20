<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {
  function __construct() {
		parent::__construct();
		$this->load->model('accessmodel');
    $this->load->model('procesosmodel');
    $this->load->model('sucursalmodel');
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

  function modproc(){
    return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->procesosmodel->get_modulos_procesos_by_usuario($usuario['ID_USUARIO'])));
  }

  public function logout()
  {
    $this->session->sess_destroy();
    $this->load->view('logout');
  }

  public function setempfy($idempresa,$fy)
  {
    if(isset($_SESSION['idempresa'])){
      unset(
          $_SESSION['idempresa'],
          $_SESSION['aniofiscal'],
          $_SESSION['idsucursal']
      );
    }
    $sucursal = $this->accessmodel->get_idSucursal_by_usuario($_SESSION['idusuario'],$idempresa);
    $suc_dec = json_decode($sucursal,true);
    $data = array(
      'idempresa' => $idempresa,
      'aniofiscal' => $fy,
      'idsucursal' => $suc_dec[0]['ID_SUCURSAL']
    );
    $this->session->set_userdata($data);
    return  $this->output
            ->set_content_type('application/json')
            ->set_output($sucursal);
  }

  public function getdata()
  {
    if(isset( $_SESSION['idempresa'])){
      $idEmpresa = $_SESSION['idempresa'];
      $data = array('value'=>'OK','idempresa'=>$_SESSION['idempresa'],'id_empr_codigo'=>str_pad('E',7-mb_strlen($idEmpresa),'0').$idEmpresa,'aniofiscal'=>$_SESSION['aniofiscal'],'idsucursal'=>$_SESSION['idsucursal'],'idusuario'=>$_SESSION['idusuario']);
    }else {
      $data = array('value'=>'ERROR');
    }
    return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
  }

}
?>
