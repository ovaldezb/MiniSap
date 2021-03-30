<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model('clientemodel');
		$this->load->model('catalogosmodel');
		$this->load->helper('url');
		$this->load->library('session');
	}

	function index() {
		if(isset($_SESSION['username']))
    	{
			$data['tipo_cliente'] = $this->catalogosmodel->get_tipo_cliente();
			$data['revision'] = $this->catalogosmodel->get_dias_semana();
			$data['forma_pago'] = $this->catalogosmodel->get_forma_pago();
			$data['vendedor'] = $this->catalogosmodel->get_vendedor();
			$data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
			$this->load->view('clientes',$data);
		}else {
			$error['error'] = '';
      		$this->load->view('login',$error);
		}
	}

	function loadByEmpresa($idEmpresa,$anioFiscal)
	{
		$data = $this->clientemodel->get_clientes_by_empresa($idEmpresa,$anioFiscal);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function loadbyid($id)
	{
		$data = $this->clientemodel->get_cliente_by_id($id);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function loadbyidverfi($id)
	{
		$data = $this->clientemodel->get_cliente_by_id_verif($id);
		return $this->output
            ->set_content_type('application/json')
            ->set_output($data);
	}

	function loadbynombre($idempresa,$nombre)
	{
		if($nombre == 'vacio')
		{
			$data = $this->clientemodel->get_clientes_by_empresa($idempresa);
		}else{
			$nvonombre = str_replace("%20"," ",$nombre);
			$data = $this->clientemodel->get_clientes_by_nombre($idempresa,'%'.$nvonombre.'%');
		}

		return $this->output
						->set_content_type('application/json')
						->set_output($data);
	}


	function save()
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->clientemodel->crea_cliente($data['clave'],
		$data['nombre'],
		$data['domicilio'],
		$data['cp'],
		$data['telefono'],
		$data['contacto'],
		$data['rfc'],
		$data['curp'],
		$data['id_tipo_cliente'],
		$data['revision'],
		$data['pagos'],
		$data['id_forma_pago'],
		$data['id_vendedor'],
		$data['id_uso_cfdi'],
		$data['email'],
		$data['notas'],
		$data['dcredito'],
		$data['idempresa']);
		return $this->output
		->set_content_type('application/json')
		->set_output($result);
	}

	function delete($id)
	{
		$result = $this->clientemodel->delete_cliente($id);
		if($result)
		{
			$res = 'OK';
		}
		else
		{
			$res = 'Error';
		}
		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>$res)));
	}

	function update($id)
	{
		$data = json_decode(file_get_contents("php://input"),true);
		$result = $this->clientemodel->update_cliente($id,
		$data['nombre'],
		$data['domicilio'],
		$data['cp'],
		$data['telefono'],
		$data['contacto'],
		$data['rfc'],
		$data['curp'],
		$data['id_tipo_cliente'],
		$data['revision'],
		$data['pagos'],
		$data['id_forma_pago'],
		$data['id_vendedor'],
		$data['id_uso_cfdi'],
		$data['email'],
		$data['notas'],
		$data['dcredito']);
		if($result){
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>'El cliente se actualizo correctamente')));
		}else{
			return $this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('value'=>'Error')));
		}
	}

	function nextclteid($valor)
	{
		$result = $this->clientemodel->get_next_clte_id($valor);
		return $this->output
		->set_content_type('application/json')
		->set_output($result);
	}

}

?>
