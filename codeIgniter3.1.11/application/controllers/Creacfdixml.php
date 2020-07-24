<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
defined('FCPATH') OR exit('No direct script access allowed');
require_once FCPATH.'sw-sdk/SWSDK.php';
    use SWServices\Stamp\StampService as StampService;
        
class Creacfdixml extends CI_Controller
{
    
    function __construct() {
        parent::__construct();						
        //$this->load->helper('url');
        $this->load->model('facturamodel');
        $this->load->model('empresamodel');
        $this->load->model('tpvmodel');        
        $this->load->library('crearcfdi');
    }

    function creacfdi(){
        $data = json_decode(file_get_contents("php://input"),true);
        $idVenta = $data['idventa'];
        $idEmpresa = $data['idempresa'];
        $idSucursal = $data['idsucursal'];
        $nombre = $data['cliente'];
        $rfc = $data['rfc'];
        $usocfdi = $data['usocfdi'];
        $serie = $data['serie'];
        $folio = $data['folio'];
        $moneda = $data['moneda'];
        $tipoCambio = $data['tipocambio'];
        $metodopago =  $data['metodopago'];
        $cfdi = $this->facturamodel->getDataCFDI($idEmpresa,$idSucursal);         
        $archivoCerPem = $cfdi[0]->RUTA_PEM;
        $archivoKeyPem = $cfdi[0]->RUTA_KEY;
        $empresa = json_decode($this->empresamodel->get_empresa_by_id($idEmpresa),false);
        $venta = $this->tpvmodel->getventabyid($idVenta);
        $venta_detalle = $this->tpvmodel->getventadetallebyid($idVenta);
        $subtotal = 0;
        $traslados = array();
        $conceptosArray = array();
        
        $i = 0;
        $impuestoAcomulado = 0;
        foreach($venta_detalle as $vd){
            $iva = ($vd->IVA/100);
            $valorUnitario = ($vd->PRECIO/(1+$iva));
            $importe = $valorUnitario * $vd->CANTIDAD;
            $importeIva = $importe * $iva;
            $item = array('ClaveProdServ'=>$vd->COD_CFDI,
                        'NoIdentificacion'=>$vd->CODIGO,
                        'Cantidad'=>$vd->CANTIDAD,
                        'ClaveUnidad'=>$vd->UNIDAD_SAT,
                        'Unidad'=>$vd->UNIDAD_MEDIDA,
                        'Descripcion'=>$vd->DESCRIPCION,
                        'ValorUnitario'=>number_format($valorUnitario,3,'.',''),
                        'Importe'=>number_format($importe,3,'.','')
                    );
            $traslado = array('Base'=>number_format($importe,3,'.',''),'Impuesto'=>'002','TipoFactor'=>$vd->TIPOFACTOR,'TasaOCuota'=>number_format($iva,6),'Importe'=>number_format($importeIva,3,'.','')); 
            $impuestoAcomulado = $impuestoAcomulado + $importeIva;
            $item['Traslados'] = $traslado;
            $conceptosArray[$i] = $item;
            $i = $i + 1;            
        }
        
        $baseArray = 
            array(
                'Serie'=>$serie,
                'Folio'=>$folio,
                'Fecha'=> str_replace(' ','T',$venta[0]->FECHA_VENTA), 
                'FormaPago'=>'01', //De momento solo efectivo
                'NoCertificado'=>$cfdi[0]->NOCERTIFICADO,
                'Certificado'=>$cfdi[0]->CERTIFICADO,
                'SubTotal'=>number_format($venta[0]->IMPORTE-$impuestoAcomulado,2,'.',''),
                'Moneda'=>$moneda,
                'TipoCambio'=>$tipoCambio,
                'Total'=>number_format($venta[0]->IMPORTE,2,'.',''),
                'TipoDeComprobante'=>'I',
                'MetodoPago'=>$metodopago,
                'LugarExpedicion'=>$empresa[0]->CP,
                'RfcEmisor'=>$cfdi[0]->RFC,
                'NombreEmisor'=>$cfdi[0]->NOMBRE,
                'RegimenFiscal'=>'612', //$empresa->ID_REGIMEN //para las pruebas necesito poner 612 porque estoy usando mis datos
                'RfcReceptor'=>$rfc,
                'NombreReceptor'=>$nombre,
                'UsoCFDI'=>$usocfdi,
                'TotalImpuestosTrasladados'=>round($impuestoAcomulado,2),
                'Traslados'=>array(array('Impuesto'=>"002", 'TipoFactor'=>"Tasa", 'TasaOCuota'=>number_format($iva,6), 'Importe'=>round($impuestoAcomulado,2)))
        );
                                    
        $sellado = $this->crearcfdi->generaXML($baseArray,$conceptosArray,$archivoCerPem,$archivoKeyPem);                     
        try{
            header('Content-type: application/json');        
            $params = array(
                "url"=>"http://services.test.sw.com.mx",
                "user"=>"omar.valdez.becerril@gmail.com",
                "password"=> "omar.sw"
                );
            
            $stamp = StampService::Set($params);
            $result = json_decode(json_encode($stamp::StampV4($sellado)),false);            
            if($result->status == "success"){
                $dataSAT = array($nombre,$rfc,$result->data->fechaTimbrado,$result->data->qrCode,$result->data->cfdi);
                $this->facturamodel->saveCFDISAT($dataSAT);
                return $this->output
		        ->set_content_type('application/json')
                ->set_output(json_encode(array("status"=>$result->status,"error"=>"0")));
            }else{
                return $this->output
		        ->set_content_type('application/json')
                ->set_output(json_encode(array("status"=>"fail","error"=>$result->messageDetail,"xml"=>$sellado)));
            }
            
                
            
            }
        catch(Exception $e){
            header('Content-type: text/plain');
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }        
    }

    function getdatacfdi($idEmpresa,$idSucursal){
        $result = $this->facturamodel->getDataCFDI($idEmpresa,$idSucursal); 
        if($result) {
            return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array("status"=>"ok")));
        }
        else
        {
            return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array("status"=>"fail")));
        }
    }

    /*function sellaCFDI($xml_string, $cadena, $file_xml){
        try{        
            $xml_doc = new DOMDocument();
            $xml_doc->loadXML($xml_string);            
            shell_exec('xsltproc ../cadenaoriginal/cadenaoriginal_3_3.xslt '.$file_xml.' > cadenaoriginal.txt');            
            $params = array('cadenaOriginal'=>'cadenaoriginal.txt','archivoCerPem'=>'00001000000504470826.pem','archivoKeyPem'=>'key.pem');        
            $sello = SignService::ObtenerSello($params);
            unlink('cadenaoriginal.txt');
            $c = $xml_doc->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0); 
            $c->setAttribute('Sello', $sello->sello);
            return $xml_doc->saveXML();
        }catch(Exception $e){
            header("HTTP/1.0 500");
            die($e->getMessage());
        }
    }*/
}

?>