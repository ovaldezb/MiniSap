<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access extends CI_Controller {
  function __construct() {
		parent::__construct();
		//$this->load->model('clientemodel');
		$this->load->helper('url');
	}

  public function index()
  {
    $this->load->view('inicio');
  }

  public function signin()
  {
    $this->load->view('registro');
  }

  public function signin_validation()
  {
      $this->load->library('form_validation');
      $this->form_validation->set_rules('username', 'Username', 'required|trim');
      $this->form_validation->set_rules('password', 'Password', 'required|trim');
      $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]');
      //$this->form_validation->set_message('is_unique', 'username already exists');
    if ($this->form_validation->run())
    {
      $this->load->view('principal');
    }
    else
    {
      $this->load->view('registro');
      /*$data = json_encode(array("Valor"=>"OK"));
      return $this->output
              ->set_content_type('application/json')
              ->set_output($data);*/
    }
  }

  function createPassword($password_string){
		$options = array(
			'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
			'cost' => 12,
		  );
		  $password_hash = password_hash($password_string, PASSWORD_BCRYPT, $options);
      return $password_hash;
	}

  function inicio()
  {
    $this->load->view('index');
  }

}
?>
