<?php

class Uploadcatalog extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->helper(array('file', 'url'));
				$this->load->library('csvreader');
				$this->load->model('catalogosmodel');
        }

        public function index()
        {
                $this->load->view('upload_catalog0', array('error' => ' ' ));
        }

        public function load()
        {			
                $result =   $this->csvreader->parse_file('/var/www/html/codeigniter3.1.11/uploads/MedSAT.csv');
                
                $count = 0;			
                foreach($result as $item){
                        if($count != 0){
                                $this->catalogosmodel->inserta_item_medidas($item['CLAVE'],$item['DESCRIPCION'],$item['DESCAP']);
                        }
                        $count++;
                }
                $data['total'] =  $count;
                $this->load->view('upload_catalog1', $data);
        }
}
?>