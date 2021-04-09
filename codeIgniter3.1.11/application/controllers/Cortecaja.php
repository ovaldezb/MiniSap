<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cortecaja extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('cortecajamodel');
		//$this->load->helper('url');
		$this->load->library('session');
	}

  function index() {
		if(isset($_SESSION['username']))
    {
			$this->load->view('cortecaja');
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
		}
	}

}

?>