<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*require '/var/www/html/vendor/autoload.php';*/
/**
* Author: https://www.roytuts.com
*/

class Postgresdb {

	private $conn;

	function __construct() {
		$this->ci =& get_instance();
		$this->ci->load->config('postgresdb');

		$host = $this->ci->config->item('host');
		$port = $this->ci->config->item('port');
		$username = $this->ci->config->item('username');
		$password = $this->ci->config->item('password');
		$db = $this->ci->config->item('db');
		$authenticate = $this->ci->config->item('authenticate');

		try {
			if($authenticate === TRUE) {
				$this->ci->conn = pg_connect("dbname=$db user=$username");
			} else {
				$this->ci->conn = pg_connect("host=$host dbname=$db user=$username password=$password port=$port");
			}
		} catch(Exception $ex) {
			show_error('Couldn\'t connect to mongodb: ' . $ex->getMessage(), 500);
		}
	}

	function getConn() {
		return $this->ci->conn;
	}

}

?>
