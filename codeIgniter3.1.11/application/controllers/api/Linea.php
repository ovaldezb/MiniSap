<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
    require(APPPATH.'/libraries/REST_Controller.php');
    use Restserver\Libraries\REST_Controller;
    class Linea extends REST_Controller
    {
           public function __construct() {
                   parent::__construct();
                   $this->load->model('lineamodel');
           }    

          public function index_get(){
                $idEmpresa = $this->uri->segment(3);
                $idLinea = $this->uri->segment(4);
                if($idLinea==null){
                    $r = $this->lineamodel->read($idEmpresa);
                }else{
                    $r = $this->lineamodel->readById($idEmpresa,$idLinea);
                }               
               $this->response($r,REST_Controller::HTTP_OK); 
           }
           public function index_put(){
               $id = $this->uri->segment(3);               
                $r = $this->lineamodel->update($id,$this->put());
                $this->response($r); 
           }
           public function index_post(){
               $data = array('nombre' => $this->post('NOMBRE'),
               'idempresa' => $this->post('ID_EMPRESA'));
               $r = $this->lineamodel->insert($data);
               $this->response(['linea insertada'],REST_Controller::HTTP_OK); 
           }
           public function index_delete(){
               $id = $this->uri->segment(3);
               $r = $this->lineamodel->delete($id);
               $this->response($r); 
           }
        
    }