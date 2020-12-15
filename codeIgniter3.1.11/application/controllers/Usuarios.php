<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model('usuariomodel');
    $this->load->model('procesosmodel');
    $this->load->helper('url');
    $this->load->library('session');
  }

  public function index()
  {
    if(isset($_SESSION['username']))
    {
      $this->load->view('usuarios');
		}else {
			$error['error'] = '';
      $this->load->view('login',$error);
    }
  }

  /*Obtiene los datos de un usuario por su Id*/
  public function getusrbyid($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output( $this->usuariomodel->get_usuario_by_id($idusuario));
  }

  public function getprocsusr($idusuario)
  {
    $data = $this->usuariomodel->get_procesos_by_usuario($idusuario);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
  }

  function allempr()
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->get_all_empr_nuevo());
  }

  function emppermusr($usuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->get_empperm_by_usuario($usuario));
  }

  function allmoduls()
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->get_all_modulos_nuevo());
  }

  function saveusr()
  {
    $data = json_decode(file_get_contents("php://input"),true);
    $result = $this->usuariomodel->crea_usuario(
      $data['nombre'],
      $data['usrname'],
      password_hash($data['paswd'], PASSWORD_DEFAULT)
    );
    return $this->output
            ->set_content_type('application/json')
            ->set_output($result);
  }

  function updtusuario()
  {
    $data = json_decode(file_get_contents("php://input"),true);
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

  function elimmodusr($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->elimina_modulosperm_by_usuario($idusuario));
  }

  function elimemperm($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->elimina_empperm_by_usuario($idusuario));
  }

  function insrtmdls($idusuario,$idmodulo)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->inserta_modulo_by_user($idusuario,$idmodulo));
  }

  function insrtempperm($idusuario,$idempresa)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->inserta_empperm_by_user($idusuario,$idempresa));
  }

  function getusrs()
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->get_ususarios());
  }

  function getmodulsusr($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->get_modulos_by_ususario($idusuario));
  }

  function getallmodulsusr($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->get_all_modulos_by_usuario($idusuario));
  }

  function eliminausrproc($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->elimina_procesos_by_usuario($idusuario));
  }

  function insrtprocusr($idusuario,$idproceso,$p,$a,$b,$m,$c)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->inserta_proceso_by_usuario($idusuario,$idproceso,$p,$a,$b,$m,$c));
  }

  function eliminausr($idusuario)
  {
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->usuariomodel->elimina_usuario($idusuario));
  }

  function permusrproc($idusuario,$idproceso){
    return $this->output
            ->set_content_type('application/json')
            ->set_output($this->procesosmodel->get_perm_by_proc_usr($idusuario,$idproceso));
  }

}

?>
