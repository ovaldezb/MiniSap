<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagos extends CI_Controller
{
    function __construct() {
		parent::__construct();
		$this->load->model('pagosmodel');
		//$this->load->model('catalogosmodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

    function index() {
		if(isset($_SESSION['username']))
    	{
            $this->load->view('pagos');
        }else{
            $error['error'] = '';
      		$this->load->view('login',$error);
        }
    }

}
?>