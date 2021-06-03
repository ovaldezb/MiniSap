<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cliente extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('clientemodel');
        $this->load->model('catalogosmodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $data['tipo_cliente'] = $this->catalogosmodel->get_tipo_cliente();
            $data['revision'] = $this->catalogosmodel->get_dias_semana();
            //$data['uso_cfdi'] = $this->catalogosmodel->get_uso_cfdi();
            $this->load->view('clientes', $data);
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function loadByEmpresa($idEmpresa, $anioFiscal)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->clientemodel->get_clientes_by_empresa($idEmpresa, $anioFiscal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function loadbyid($id)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->clientemodel->get_cliente_by_id($id);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function loadbyidverfi($id)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->clientemodel->get_cliente_by_id_verif($id);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);

        }
    }

    public function loadbynombre($idempresa, $anioFiscal, $nombre)
    {
        if (isset($_SESSION['username'])) {
            if ($nombre == 'vacio') {
                $data = $this->clientemodel->get_clientes_by_empresa($idempresa, $anioFiscal);
            } else {
                $nvonombre = str_replace("%20", " ", $nombre);
                $data = $this->clientemodel->get_clientes_by_nombre($idempresa, '%' . $nvonombre . '%');
            }

            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function findbycode($codigo, $idempresa)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->clientemodel->get_cliente_by_code($codigo, $idempresa);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function save()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
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
    }

    public function deleteclte($id)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->clientemodel->delete_cliente($id);
            if ($result) {
                $res = 'OK';
            } else {
                $res = 'Error';
            }
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array('value' => $res)));
        }
    }

    public function updatecliente($id)
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
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
            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'El cliente se actualizo correctamente')));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'Error')));
            }
        }
    }

    public function nextclteid($valor)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->clientemodel->get_next_clte_id($valor);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function factcliente($idcliente, $anioFiscal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->clientemodel->get_fact_by_idcliente($idcliente, $anioFiscal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getidvtsmostr($idempresa)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->clientemodel->get_id_clte_ventasmostrador($idempresa);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function savedomi()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->clientemodel->save_domicilio(array(
              $data['ID_CLIENTE'],
              $data['LUGAR'],
              $data['CALLE'],
              $data['COLONIA'],
              $data['CIUDAD'],
              $data['LATITUD'],
              $data['LONGITUD'],
              $data['CONTACTO'],
              $data['CP'],
            ));
        }
    }

    public function updatedomi($idDomicilio){
      if (isset($_SESSION['username'])) {
        $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->clientemodel->update_domicilio(array(
              $data['LUGAR'],
              $data['CALLE'],
              $data['COLONIA'],
              $data['CIUDAD'],
              $data['LATITUD'],
              $data['LONGITUD'],
              $data['CONTACTO'],
              $data['CP'],
              $idDomicilio
            ));
        //$result = $this->clientemodel->update_domicilio($data);
        return $this->output
            ->set_content_type('application/json')
            ->set_output($result);
    }
    }

    public function deletedomi($idDomicilio)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->clientemodel->delete_domicilio($idDomicilio);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function getdomis($idcliente)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->clientemodel->get_domi_by_id($idcliente);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

}
