<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Postgresmodel extends CI_model 
{
	
	private $db = "testdb"; 	
	private $user="postgres";	
	private $conn;
	
	function __construct() {
		parent::__construct();
		$this->load->library('postgresdb');
		$this->conn = $this->postgresdb->getConn();
	}
	
		function get_user_list() {		
			$query = "SELECT * FROM COMPANY";
			if(!$this->conn)
			{
				echo 'An error ocuured';
				exit;
			}
			
			$result = pg_query($this->conn, $query);
			if (!$result) {
				echo "An error occurred.\n";
				exit;
			}			
			$arr = pg_fetch_all($result);		
		return $arr;
	}
}

?>