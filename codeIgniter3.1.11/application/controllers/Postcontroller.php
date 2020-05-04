<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postcontroller extends CI_Controller 
{
	function __construct() {
		parent::__construct();
		$this->load->model('postgresmodel');
	}

	function index() {
		$data['company'] = $this->postgresmodel->get_user_list();		
		$this->load->view('company', $data);
	}
}

?>