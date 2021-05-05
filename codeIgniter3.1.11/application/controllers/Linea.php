<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Linea extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('linea');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }
}
