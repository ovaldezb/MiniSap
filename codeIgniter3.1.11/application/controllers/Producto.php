<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Producto extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('catalogosmodel');
        $this->load->model('productomodel');
        $this->load->model('lineamodel');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index()
    {
        if (isset($_SESSION['username'])) {
            $data['lineas'] = $this->lineamodel->read($_SESSION['idempresa']);
            $data['monedas'] = $this->catalogosmodel->get_monedas();
            $data['iepss'] = $this->catalogosmodel->get_ieps();
            $data['umedidas'] = $this->catalogosmodel->get_unidad_medida();
            $this->load->view('producto', $data);
        } else {
            $error['error'] = '';
            $this->load->view('login', $error);
        }
    }

    public function load($idempresa)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->productomodel->get_productos($idempresa);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function save()
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->productomodel->create_producto(
                $data['codigo'],
                $data['nombre'],
                $data['linea'],
                $data['unidadmedida'],
                $data['esequiv'],
                strlen($data['equivalencia']) > 0 ? $data['equivalencia'] : null,
                $data['codigocfdi'],
                $data['unidad_sat'],
                $data['preciolista'],
                $data['ultact'],
                $data['moneda'],
                $data['iva'],
                $data['idieps'],
                strlen($data['ieps']) > 0 ? $data['ieps'] : null,
                $data['espromo'],
                strlen($data['preciopromo']) > 0 ? $data['preciopromo'] : null,
                $data['esdescnt'],
                strlen($data['preciodescnt']) > 0 ? $data['preciodescnt'] : null,
                strlen($data['maxstock']) > 0 ? $data['maxstock'] : null,
                strlen($data['minstock']) > 0 ? $data['minstock'] : null,
                $data['estasaexenta'],
                $data['notas'],
                $data['img'],
                $data['idempresa'],
                $data['idscursal'],
                $data['tipops']);

            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output($result);
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'Error')));
            }
        }
    }

    public function loadbyid($idProducto)
    {
        if (isset($_SESSION['username'])) {
            $data = $this->productomodel->get_producto_by_id($idProducto);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($data);
        }
    }

    public function update($idProducto)
    {
        if (isset($_SESSION['username'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $this->productomodel->update_producto(
                $idProducto,
                $data['codigo'],
                $data['nombre'],
                $data['linea'],
                $data['unidadmedida'],
                $data['esequiv'],
                strlen($data['equivalencia']) > 0 ? $data['equivalencia'] : null,
                $data['codigocfdi'],
                $data['unidad_sat'],
                $data['preciolista'],
                $data['ultact'],
                $data['moneda'],
                $data['iva'],
                $data['idieps'],
                strlen($data['ieps']) > 0 ? $data['ieps'] : null,
                $data['espromo'],
                strlen($data['preciopromo']) > 0 ? $data['preciopromo'] : null,
                $data['esdescnt'],
                strlen($data['preciodescnt']) > 0 ? $data['preciodescnt'] : null,
                $data['maxstock'],
                $data['minstock'],
                $data['estasaexenta'],
                $data['notas'],
                $data['img']);
            if ($result) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'OK')));
            } else {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array('value' => 'Error')));
            }
        }
    }

    public function delete($idProducto)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->productomodel->delete_producto($idProducto);
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

    public function items($desc)
    {
        if (isset($_SESSION['username'])) {
            $nvodesc = str_replace("%20", " ", $desc);
            $result = $this->catalogosmodel->get_sat_items_by_desc('%' . $nvodesc . '%');
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function satitembycode($clave)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->catalogosmodel->get_sat_item_by_code($clave);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function unidadsat($desc)
    {
        if (isset($_SESSION['username'])) {
            $nvodesc = str_replace("%20", " ", $desc);
            $result = $this->catalogosmodel->get_unidad_sat_by_desc('%' . $nvodesc . '%');
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function satunidadbycode($clave)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->catalogosmodel->get_unidad_by_code($clave);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function prodbycode($codigo, $idempresa, $idsucursal)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->productomodel->get_producto_by_codigo($codigo, $idempresa, $idsucursal);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

    public function proddetailid($idproducto)
    {
        if (isset($_SESSION['username'])) {
            $result = $this->productomodel->get_producto_detalle_by_codigo($idproducto);
            return $this->output
                ->set_content_type('application/json')
                ->set_output($result);
        }
    }

}
