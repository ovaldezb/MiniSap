<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pagosmodel extends CI_model
{
	private $conn;

	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
    }
}

?>