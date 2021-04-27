<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datosfactura extends CI_Controller
{
	function __construct() {
        parent::__construct();						
        $this->load->helper('url');
        $this->load->model('facturamodel');
	}

    function index(){
        $this->load->view('datosfactura');
    }

    function getmatrixcert($idempresa){
        $result = $this->facturamodel->getMatrixCertByEmp($idempresa);        
		return $this->output
		->set_content_type('application/json')
		->set_output($result);
    }

    function getsuccerts($idempresa){
        $result = $this->facturamodel->getSucCertsByEmp($idempresa);        
		return $this->output
		->set_content_type('application/json')
		->set_output($result);
    }

    function save(){
      $data = [];   
      $count = count($_FILES['files']['name']);
      $pass = $this->input->post('ci_pass');
      $id_suc = $this->input->post('sucursal');
      $idEmp = $this->input->post('idempresa');
      $cerFile = '';
      $keyFile = '';
      $keyOutFile = '';
      $pemFile = '';
        
      for($i=0;$i<$count;$i++){    
        if(!empty($_FILES['files']['name'][$i])){        
          $_FILES['file']['name'] = $_FILES['files']['name'][$i];
          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
          $_FILES['file']['size'] = $_FILES['files']['size'][$i];

          $config['upload_path'] = './uploads/cfdi/'; 
          $config['allowed_types'] = array('cer','key');
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
            $filename = $uploadData['file_name']; 
            $data['totalFiles'][] = $filename.' '.$_FILES['file']['type'];
            $inFile = $uploadData['full_path'];
            if($uploadData['file_ext'] == '.key'){         
                $outFile = str_replace('.key','_key.pem',$inFile);                               
                shell_exec("openssl pkcs8 -inform DER -in {$inFile} -passin pass:{$pass} -out {$outFile}");
                $keyOutFile = $outFile;
                $keyFile = $inFile;
            }else if($uploadData['file_ext'] == '.cer'){                    
                $outFile = str_replace('.cer','.pem',$uploadData['full_path']);
                shell_exec("openssl x509 -inform DER -in {$inFile} -out {$outFile}");
                $cerFile = $inFile;
                $pemFile = $outFile;
            }
          }else{
            $data['error'] = array($this->upload->display_errors());                
          }
        }       
      }
      if(file_exists($keyOutFile) && filesize($keyOutFile) > 0 ){
          $dataCFDI = getDataCFDI($pemFile,$cerFile,$keyOutFile,$id_suc,$idEmp);
          $this->facturamodel->saveCFDI(explode('|',$dataCFDI));
          $data['pass'] = $dataCFDI;
          $this->load->view('datosfacturasave',$data);
      }else{
          unlink($keyFile);
          unlink($cerFile);
          unlink($pemFile);
          unlink($keyOutFile);
          $data['errorKey'] = 'Hubo un error, los archivos no son los correctos y/o la contraseña no coincide';
          $this->load->view('datosfactura',$data);
      } 
    }
}

function getDataCFDI($filePem_,$fileCer_,$keyOutFile_,$id_suc_,$idEmp_){
    $res = shell_exec("openssl x509 -in {$filePem_} -serial -noout");
    $parse_res = str_replace('serial=','',$res);
    $serialNumber = '';
    for($i=1;$i<strlen($parse_res);$i=$i+2){
        $serialNumber = $serialNumber.''.$parse_res[$i];
    }         
    $res = shell_exec("openssl x509 -in {$filePem_} -startdate -noout");
    $validFrom = str_replace('notBefore=','',$res);       
    $res = shell_exec("openssl x509 -in {$filePem_} -enddate -noout");
    $validTo = str_replace('notAfter=','',$res);
    $file = file_get_contents($filePem_);
    $Certificado = openssl_x509_parse($file);    
    $rfc = explode('[/.-]', $Certificado['subject']['x500UniqueIdentifier'])[0];
    $razon = $Certificado['subject']['name'];
    $ou = $Certificado['subject']['OU'];   
    $b4cer = shell_exec("openssl enc -in {$fileCer_} -a -A");
    return $razon.'|'.$rfc.'|'.date_format(date_create($validFrom),"Y-m-d H:i:s").'|'.date_format(date_create($validTo),"Y-m-d H:i:s").'|'.$serialNumber.'|'.$b4cer.'|'.$filePem_.'|'.$keyOutFile_.'|'.$ou.'|'.$id_suc_.'|'.$idEmp_.'|true';    
}