<?php

class Upload extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $this->load->view('upload_form', array('error' => ' '));
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function startupload($name, $idempresa)
    {
        $data['nombre'] = $name; 
        $data['idempresa'] = $idempresa;
        $this->load->view('upload_form', $data);
    }

    public function do_upload()
    {
        $dorename = 0;
        $config['upload_path'] = './uploads/' . $this->input->post('idempresa') . '/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 0;
        $config['max_width'] = 1024;
        $config['max_height'] = 1024;
        $this->load->library('upload', $config);
        /* Checar si existe la ruta, si no, la creo*/
        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
            chmod($config['upload_path'], 0777);
        }
        if (!$this->upload->do_upload('userfile')) {
            $data['error'] = array($this->upload->display_errors());
            $data['nombre'] = $this->input->post('nfname');
            $data['idempresa'] = $this->input->post('idempresa');
            $data['upload_path'] = $config['upload_path'];
            $this->load->view('upload_form', $data);
        } else {
            $data = array('upload_data' => $this->upload->data());
            if (file_exists($config['upload_path'] . $data['upload_data']['file_name'])) {
                $dorename = rename($config['upload_path'] . $data['upload_data']['file_name'], $config['upload_path'] . $this->input->post('nfname') . $data['upload_data']['file_ext']);
            }
            $data['nombre'] = $this->input->post('nfname');
            $data['idempresa'] = $this->input->post('idempresa');
            $this->load->view('upload_success', $data);
        }
    }
}
