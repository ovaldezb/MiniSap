<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
    defined('FCPATH') OR exit('No direct script access allowed');
    require_once FCPATH.'sw-sdk/SWSDK.php';
    require_once APPPATH."third_party/dompdf/autoload.inc.php";
    
    require_once FCPATH.'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require_once FCPATH.'vendor/phpmailer/phpmailer/src/SMTP.php';
    require_once FCPATH.'vendor/phpmailer/phpmailer/src/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    use SWServices\Stamp\StampService as StampService;
    use Dompdf\Dompdf;

    error_reporting(E_STRICT | E_ALL);

    

class Creacfdixml extends CI_Controller
{
    
    function __construct() {
        parent::__construct();						
        $this->load->helper('url');
        $this->load->model('facturamodel');
        $this->load->model('empresamodel');
        $this->load->model('clientemodel');
        $this->load->model('tpvmodel');        
        $this->load->library('crearcfdi');
        $this->load->library('xml2pdf');    
        $this->load->model('catalogosmodel');     
    }


    function index(){
        $this->load->view('listafacturas');
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
        $idCliente = $data['idCliente'];        
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
                $dataSAT = array($nombre,$rfc,$result->data->fechaTimbrado,$result->data->qrCode,$result->data->cfdi,$folio,number_format($importe,3,'.',''),$result->data->cadenaOriginalSAT,$idCliente,$idEmpresa);
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

    function getfacturaby($forma,$idfactura,$idCliente,$idEmpresa){

        $result = json_decode($this->facturamodel->get_factura_by_id($idfactura),false); 
        $cliente = json_decode($this->clientemodel->get_cliente_by_id($idCliente),false);
        $empresa = json_decode($this->empresamodel->get_empresa_by_id($idEmpresa),false);
        $formapago = json_decode($this->catalogosmodel->get_forma_pago_js(),false);
        $uso_cfdi = json_decode(json_encode($this->catalogosmodel->get_uso_cfdi()),false);
        $cadenaSat = 'Aqui va la cadena SAT desde Controller';
        file_put_contents($result[0]->FOLIO.'.png',base64_decode($result[0]->QR_CODE));
        $url = FCPATH.$result[0]->FOLIO.'.png';        
        $res = $this->xml2pdf->convierte($result[0]->CFDI,$cliente,$empresa,$formapago,$uso_cfdi,$cadenaSat,$url);        
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($res);
        $dompdf->setPaper('A4', "portrait");
        $dompdf->render();
        $filenamepdf = FCPATH.'pdfs/'.$result[0]->RFC.'.pdf';
        $filenamexml = FCPATH.'pdfs/'.$result[0]->RFC.'.xml';
        $filenamezip = FCPATH.'pdfs/'.$result[0]->FOLIO.'.zip'; 
        $output = $dompdf->output();
        file_put_contents($filenamepdf, $output); //$dompdf->output()
        $xml_doc = new DOMDocument('1.0','utf-8');
        $xml_doc->loadXML($result[0]->CFDI);      
        $xml_doc->formatOutput = TRUE;  
        $xml_doc->preserveWhiteSpace = false;
        $xml_doc->save($filenamexml);

        $zip = new ZipArchive();
        $zip->open($filenamezip, ZIPARCHIVE::CREATE); 
        $zip->addFile($filenamepdf,basename($filenamepdf)); 
        $zip->addFile($filenamexml,basename($filenamexml)); 
        $zip->close();     
        if($forma==1){ /* 1 para Attach, 2 para Email*/
            try{
                ob_clean();   
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="'.basename($filenamezip).'"');
                header('Content-Length: ' . filesize($filenamezip));
                header("Pragma: no-cache");
                header("Expires: 0");
                flush();
                readfile($filenamezip);             
            }catch(\Exception $e){
                unlink($filenamepdf);
                unlink($filenamexml);        
                unlink($filenamezip);
            }            
        }else{
            try{
                $email = new PHPMailer(true);
                $email->CharSet = 'UTF-8';
                $email->SetFrom('no-reply@rts-soft.net', 'RTS'); //Name is optional
                $email->Subject   = 'Factura: '.$result[0]->FOLIO;
                $email->Body      = '<p>Adjunto se encuentra su factura en formato PDF y XML</p>';
                $email->addAddress( 'omar.valdez.becerril@gmail.com','Omar Valdez' );
                $email->addAddress( 'omar.valdez@protonmail.com','Omar Valdez' );                
                $email->IsHTML(true); 
                $file_to_attach = $filenamezip; 
                $email->AddAttachment( $file_to_attach , basename($filenamezip));            
                if(!$email->send()) {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('value'=>'error')));
                } else {
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('value'=>'OK')));
                }
            }catch(\Exception $e){
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('value'=>$e)));
            }finally{
                unlink($filenamepdf);
                unlink($filenamexml);        
                unlink($filenamezip); 
            }
        }               
    }
 
    function sendemail($idfactura,$idCliente,$idEmpresa){
        
    }

    function getfacbydate($fecIni,$fecFin){
        return $this->output
            ->set_content_type('application/json')
            ->set_output($this->facturamodel->get_facturas_by_dates($fecIni));
        
    }
}

?>