<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transferencia extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transferenciamodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
          $this->load->view('transferencia');
        }
    }

    public function savetransfer(){
      $data = json_decode(file_get_contents("php://input"),true);
      $result = $this->transferenciamodel->save_transfer(array(
        $data['idsucorigen'],
        $data['idsucdestino'],
        $data['cantidad'],
        $data['idproducto'],
        $data['idusuario'],
        $data['idempresa'],
        $data['fechatransfer'],
        $data['aniofiscal'],
        
        )
      );
      return $this->output
             ->set_content_type('application/json')
             ->set_output($result);
    }

    public function gettransfer($idempresa, $fiscalyear, $idsucursal){
      $result = $this->transferenciamodel->get_transf_by_emp_fy($idempresa, $fiscalyear, $idsucursal);
		  return $this->output
					 ->set_content_type('application/json')
					 ->set_output($result);
    }
}
?>