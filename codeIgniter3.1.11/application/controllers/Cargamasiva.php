<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cargamasiva extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('clientemodel');
        $this->load->model('productomodel');
        $this->load->library('csvreader');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('cargamasiva');
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function cargaproveedor()
    {
        $file =  $_FILES['file']['name'];
        $config['upload_path'] = './uploads/carga/' . $_SESSION['idempresa'] . '/';
        $config['allowed_types'] = 'txt|csv';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        /* Checar si existe la ruta, si no, la creo*/
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
            chmod($config['upload_path'], 0777);
        }
        if (!$this->upload->do_upload('file')) {
            $data['error'] = array($this->upload->display_errors());
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array($this->upload->display_errors())));
        } else {
            $data = $this->upload->data();
            $inFile = $data['full_path'];
            $result = $this->csvreader->parse_file($inFile);
            $count = 0;
            foreach ($result as $item) {
                $this->productomodel->inserta_proveedor(array(trim($item['CLAVE']),
                    utf8_encode(trim($item['NOMBRE'])),
                    trim($item['DOMICILIO']),
                    trim($item['RFC']),
                    trim($item['CURP']),
                    trim($item['TELEFONO']),
                    trim($item['CORREO']),
                    trim($item['DIASCREDITO']), 
                    1,//Categoria proveedor
                    1,//Tipo Alcance Proveedor
                    $_SESSION['idempresa'],
                    true
                ));
                $count++;
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array("Total"=>$count)));
        }
    }

    public function cargacliente()
    {
        $file =  $_FILES['file']['name'];
        $config['upload_path'] = './uploads/carga/' . $_SESSION['idempresa'] . '/';
        $config['allowed_types'] = 'txt|csv';
        $config['max_size'] = 0;

        $this->load->library('upload', $config);
        /* Checar si existe la ruta, si no, la creo*/
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
            chmod($config['upload_path'], 0777);
        }
        if (!$this->upload->do_upload('file')) {
            $data['error'] = array($this->upload->display_errors());
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array($this->upload->display_errors())));
        } else {
            $data = $this->upload->data();
            $inFile = $data['full_path'];
            $result = $this->csvreader->parse_file($inFile);
            $count = 0;
            foreach ($result as $item) {
                $this->productomodel->inserta_cliente(array(trim($item['CLAVE']),
                    utf8_encode(trim($item['NOMBRE'])),
                    trim($item['DOMICILIO']),
                    trim($item['TELEFONO']),
                    trim($item['CORREO']),
                    trim($item['CURP']),
                    trim($item['RFC']), 
                    trim($item['DIASCREDITO']), 
                    $_SESSION['idempresa'],
                    true,
                    1 //FORMA_PAGO
                ));
                $count++;
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array("Total"=>$count)));
        }
    }

    public function cargaproducto()
    {
        $dorename = 0;
        $config['upload_path'] = './uploads/carga/' . $_SESSION['idempresa'] . '/';
        $config['allowed_types'] = 'txt|csv';
        $config['max_size'] = 0;
        $this->load->library('upload', $config);
        /* Checar si existe la ruta, si no, la creo*/
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
            chmod($config['upload_path'], 0777);
        }
        if (!$this->upload->do_upload('file')) {
            $data['error'] = array($this->upload->display_errors());
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array($this->upload->display_errors())));
        } else {
            $data = $this->upload->data();
            $inFile = $data['full_path'];
            $result = $this->csvreader->parse_file($inFile);
            $count = 0;
            foreach ($result as $item) {
                $unidad = "";
                switch(trim($item['MEDIDA'])){
                    case 'PZAS.':
                        $unidad = 'Pieza';
                        break;
                    case "LTS.":
                        $unidad = "Litro";
                        break;
                    case "KG":
                        $unidad = "Kilogramo";
                        break;
                    
                }
                $this->productomodel->inserta_producto(array(trim($item['CODIGO']),
                    utf8_encode(trim($item['DESCRIPCION'])),
                    intval(trim($item['EXISTENCIA'])),
                    trim($item['LINEA']),
                    $unidad,
                    0, //Precio Lista
                    1, //moneda
                    16, //Iva
                    $_SESSION['idempresa'],
                    $_SESSION['idsucursal'],
                    date('Y-m-d H:i:s')
                ));
                $count++;
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array("Total"=>$count)));
        }
    }
}
