<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cargamasiva extends CI_Controller
{ 
    function __construct() {
		parent::__construct();
		//$this->load->model('clientemodel');
		//$this->load->model('catalogosmodel');
		$this->load->library('csvreader');
		$this->load->helper('url');
		$this->load->library('session');
	}

	function index() {
		if(isset($_SESSION['username']))
        {
			$this->load->view('cargamasiva');
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
		}
	}

	function save(){
        $data = [];   
        $count = count($_FILES['files']['name']);
        //$pass = $this->input->post('ci_pass');
        //$id_suc = $this->input->post('sucursal');
        $idEmp = $this->input->post('idempresa');
        //$cerFile = '';
        //$keyFile = '';
        //$keyOutFile = '';
        //$pemFile = '';
        
        for($i=0;$i<$count;$i++){    
            if(!empty($_FILES['files']['name'][$i])){        
              $_FILES['file']['name'] = $_FILES['files']['name'][$i];
              $_FILES['file']['type'] = $_FILES['files']['type'][$i];
              $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
              $_FILES['file']['error'] = $_FILES['files']['error'][$i];
              $_FILES['file']['size'] = $_FILES['files']['size'][$i];

              $config['upload_path'] = './uploads/carga/'; 
              $config['allowed_types'] = array('csv');
              $config['max_size'] = 5000;
              $config['file_name'] = $_FILES['files']['name'][$i];
              $this->load->library('upload'); 
              $this->upload->initialize($config);
              if(!file_exists($config['upload_path']))
              {
                mkdir($config['upload_path'],0777,true);
                chmod($config['upload_path'],0777);
              }              
              
              if($this->upload->do_upload('file')){
                $uploadData = $this->upload->data();                
                //$filename = $uploadData['file_name']; 
                //$data['totalFiles'][] = $filename.' '.$_FILES['file']['type'];
				$inFile = $uploadData['full_path'];
				$result =   $this->csvreader->parse_file($inFile);
				$count = 0;			
				foreach($result as $item){
					if($count != 0){
						$this->catalogosmodel->inserta_producto(array($item['Código'],$item['Descripcion']));
					}
					$count++;
				}
              }else{
                $data['error'] = array($this->upload->display_errors());                
              }
            }       
        }
    }
}

?>