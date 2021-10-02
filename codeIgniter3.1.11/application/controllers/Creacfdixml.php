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
    use SWServices\Cancelation\CancelationService as CancelationService;
    use SWServices\Authentication\AuthenticationService as AuthenticationService;
    use SWServices\Cancelation\CancelationRequest as cancelationRequest;
    use Dompdf\Dompdf;

    error_reporting(E_STRICT | E_ALL);

    

class Creacfdixml extends CI_Controller
{
    protected $params;
    function __construct() {
        parent::__construct();				
        $this->ci =& get_instance();		
        $this->load->helper('url');
        $this->ci->load->config('cfdi');
        $url = $this->ci->config->item('url');
        $this->load->model('facturamodel');
        $this->load->model('empresamodel');
        $this->load->model('clientemodel');
        $this->load->model('tpvmodel');        
        $this->load->library('crearcfdi');
        $this->load->library('xml2pdf');    
        $this->load->model('catalogosmodel');   
        /*$this->params = array(
          "url"=>"http://services.test.sw.com.mx",
          "user"=>"omar.valdez.becerril@gmail.com",
          "password"=> "omar.sw"
        ); */
        $this->params = array(
            "url"=>"http://services.sw.com.mx",
            "user"=>"omar.valdez.becerril@gmail.com",
            "password"=> "Jafra2018!@"
          );
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
        $usocfdi = $data['usocfdicodigo'];
        $serie = $data['serie'];
        $folio = trim($data['folio']);
        $moneda = $data['moneda'];
        $tipoCambio = $data['tipocambio'];
        $metodopago =  $data['metodopago'];
        $idCliente = $data['idCliente'];     
        $idfactura = $data['idfactura'];
        $formapago = $data['formapago'];
        $aniofiscal = $data['aniofiscal'];
        $regineFiscal = $data['regimenfiscal'];
        $cfdi = $this->facturamodel->getDataCFDI($idEmpresa,$idSucursal);         
        $archivoCerPem = $cfdi[0]->RUTA_PEM;
        $archivoKeyPem = $cfdi[0]->RUTA_KEY;
        $empresa = json_decode($this->empresamodel->get_empresa_by_id($idEmpresa),false);
        $venta = $this->tpvmodel->getventabyid($idVenta);
        $venta_detalle = $this->tpvmodel->getventadetallebyVentaId($idVenta);
        $descuentoTotal = 0;
        $traslados = array();
        $conceptosArray = array();
        $subTotalAcc = 0;
        $importeTotal = 0;
        $i = 0;
        $impuestoAcomulado = 0;

        foreach($venta_detalle as $vd){
            $iva = ($vd->IVA/100);
            $valorUnitario = (($vd->PRECIO)/(1+$iva));
            $descuento = $vd->CANTIDAD * $vd->PRECIO * ($vd->DESCUENTO / 100); 
            $importe = $valorUnitario * $vd->CANTIDAD;
            $importeTotal += $importe;
            $importeIva = $importe * $iva;
            $item = array('ClaveProdServ'=>$vd->COD_CFDI,
                        'NoIdentificacion'=>$vd->CODIGO,
                        'Cantidad'=>$vd->CANTIDAD,
                        'ClaveUnidad'=>$vd->UNIDAD_SAT,
                        'Unidad'=>$vd->UNIDAD_MEDIDA,
                        'Descripcion'=>$vd->DESCRIPCION,
                        'ValorUnitario'=>number_format($valorUnitario,3,'.',''),
                        'Descuento' =>Number_format($descuento,3,'.',''),
                        'Importe'=>number_format($importe,3,'.','')
                    );
            $descuentoTotal += $descuento; 
            $traslado = array('Base'=>number_format($importe,3,'.',''),'Impuesto'=>'002','TipoFactor'=>$vd->TIPOFACTOR,'TasaOCuota'=>number_format($iva,6),'Importe'=>number_format($importeIva,3,'.','')); 
            $impuestoAcomulado +=  $importeIva;
            $item['Traslados'] = $traslado;
            $conceptosArray[$i] = $item;
            $i = $i + 1;            
        }
        
        $baseArray = 
          array(
              'Serie'=>$serie,
              'Folio'=>$folio,
              'Fecha'=> str_replace(' ','T',$venta[0]->FECHA_VENTA), 
              'FormaPago'=>$formapago, 
              'NoCertificado'=>$cfdi[0]->NOCERTIFICADO,
              'Certificado'=>$cfdi[0]->CERTIFICADO,
              'SubTotal'=>number_format($importeTotal,2,'.',''),
              'Descuento'=>number_format($descuentoTotal,2,'.',''),
              'Moneda'=>$moneda,
              'TipoCambio'=>$tipoCambio,
              'Total'=>number_format($venta[0]->IMPORTE,2,'.',''),
              'TipoDeComprobante'=>'I',
              'MetodoPago'=>$metodopago,
              'LugarExpedicion'=>$empresa[0]->CP,
              'RfcEmisor'=>$cfdi[0]->RFC,
              'NombreEmisor'=>$cfdi[0]->NOMBRE,
              'RegimenFiscal'=>$regineFiscal,
              'RfcReceptor'=>$rfc,
              'NombreReceptor'=>$nombre,
              'UsoCFDI'=>$usocfdi,
              'TotalImpuestosTrasladados'=>round($impuestoAcomulado,2),
              'Traslados'=>array(array('Impuesto'=>"002", 'TipoFactor'=>"Tasa", 'TasaOCuota'=>number_format($iva,6), 'Importe'=>round($impuestoAcomulado,2)))
        );
        $sellado = $this->crearcfdi->generaXML($baseArray,$conceptosArray,$archivoCerPem,$archivoKeyPem);  
        try{
            header('Content-type: application/json');        
            $stamp = StampService::Set($this->params);
            $result = json_decode(json_encode($stamp::StampV4($sellado)),false);            
            if($result->status == "success"){
                $dataSAT = array($nombre,$rfc,$result->data->fechaTimbrado,$result->data->qrCode,$result->data->cfdi,$folio,number_format($importe,3,'.',''),$result->data->cadenaOriginalSAT,$idCliente,$idEmpresa,$idfactura,$aniofiscal);
                $this->facturamodel->saveCFDISAT($dataSAT);
                return $this->output
		        ->set_content_type('application/json')
                ->set_output(json_encode(array("status"=>$result->status,"error"=>"0")));
            }else{
                return $this->output
		        ->set_content_type('application/json')
                ->set_output(json_encode(array("status"=>"fail","error"=>$result->message,"xml"=>$sellado)));
            }
        }
        catch(Exception $e){
            header('Content-type: text/plain');
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }        
    }

    function creacfdicc(){
      $data = json_decode(file_get_contents("php://input"),true);
      $idEmpresa = $data['idempresa'];
      $idSucursal = $data['idsucursal'];
      $nombre = $data['cliente'];
      $rfc = $data['rfc'];
      $iva = $data['iva'];
      $usocfdi = $data['usocfdicodigo'];
      $serie = $data['serie'];
      $folio = trim($data['folio']);
      $moneda = $data['moneda'];
      $tipoCambio = $data['tipocambio'];
      $metodopago =  $data['metodopago'];
      $importeTotal = $data['importetotal'];
      $impuestoAcomulado = $data['ivatotal'];
      $formapago = $data['formapago'];
      $aniofiscal = $data['aniofiscal'];
      $ClaveProdServ = $data['claveprodserv'];
      $NoIdentificacion = $data['noidentificacion'];
      $ClaveUnidad = $data['claveunidad'];
      $Unidad = $data["unidad"];
      $Descripcion = $data["descripcion"];
      $fechaVenta = $data["fechaventa"];
      $idfactura = $data['idfactura'];
      $cfdi = $this->facturamodel->getDataCFDI($idEmpresa,$idSucursal);         
      $archivoCerPem = $cfdi[0]->RUTA_PEM;
      $archivoKeyPem = $cfdi[0]->RUTA_KEY;
      $empresa = json_decode($this->empresamodel->get_empresa_by_id($idEmpresa),false);
      $descuentoTotal = 0;
      $traslados = array();
      $conceptosArray = array();
      $subTotalAcc = 0;
      
      $valorUnitario = $importeTotal - $impuestoAcomulado;
      $descuento = 0; 
      
      $importeIva = $impuestoAcomulado;
      $item = array('ClaveProdServ'=>$ClaveProdServ, 
                  'NoIdentificacion'=>$NoIdentificacion,
                  'Cantidad'=>1,
                  'ClaveUnidad'=>$ClaveUnidad,
                  'Unidad'=>$Unidad,
                  'Descripcion'=>$Descripcion,
                  'ValorUnitario'=>number_format($valorUnitario,3,'.',''),
                  'Descuento' =>Number_format($descuento,3,'.',''),
                  'Importe'=>number_format($valorUnitario,3,'.','')
              );

      $traslado = array('Base'=>number_format($valorUnitario,3,'.',''),'Impuesto'=>'002','TipoFactor'=>'Tasa','TasaOCuota'=>number_format($iva,6),'Importe'=>number_format($impuestoAcomulado,2,'.','')); 
      $item['Traslados'] = $traslado;
      $conceptosArray[0] = $item;
      
      $baseArray = 
        array(
            'Serie'=>$serie,
            'Folio'=>$folio,
            'Fecha'=> str_replace(' ','T',$fechaVenta), 
            'FormaPago'=>$formapago, 
            'NoCertificado'=>$cfdi[0]->NOCERTIFICADO,
            'Certificado'=>$cfdi[0]->CERTIFICADO,
            'SubTotal'=>number_format($valorUnitario,2,'.',''),
            'Descuento'=>number_format($descuentoTotal,2,'.',''),
            'Moneda'=>$moneda,
            'TipoCambio'=>$tipoCambio,
            'Total'=>number_format($importeTotal,2,'.',''),
            'TipoDeComprobante'=>'I',
            'MetodoPago'=>$metodopago,
            'LugarExpedicion'=>$empresa[0]->CP,
            'RfcEmisor'=>$cfdi[0]->RFC,
            'NombreEmisor'=>$cfdi[0]->NOMBRE,
            'RegimenFiscal'=>$empresa[0]->REGIMEN,
            'RfcReceptor'=>$rfc,
            'NombreReceptor'=>$nombre,
            'UsoCFDI'=>$usocfdi,
            'TotalImpuestosTrasladados'=>round($impuestoAcomulado,2),
            'Traslados'=>array(array('Impuesto'=>"002", 'TipoFactor'=>"Tasa", 'TasaOCuota'=>number_format($iva,6), 'Importe'=>round($impuestoAcomulado,2)))
      );
      $sellado = $this->crearcfdi->generaXML($baseArray,$conceptosArray,$archivoCerPem,$archivoKeyPem);  
      try{
          header('Content-type: application/json');        
          $stamp = StampService::Set($this->params);
          $result = json_decode(json_encode($stamp::StampV4($sellado)),false);            
          if($result->status == "success"){
              $dataSAT = array($nombre,$rfc,$result->data->fechaTimbrado,$result->data->qrCode,$result->data->cfdi,$folio,number_format($importeTotal,3,'.',''),$result->data->cadenaOriginalSAT,0,$idEmpresa,$idfactura,$aniofiscal);
              $res = $this->facturamodel->saveCFDISATCC($dataSAT);
              return $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode(array("status"=>$result->status,"error"=>"0","lastValue"=>$res[0]->guarda_cfdicc)));
          }else{
              return $this->output
          ->set_content_type('application/json')
              ->set_output(json_encode(array("status"=>"fail","error"=>$result->message,"xml"=>$sellado)));
          }
      }
      catch(Exception $e){
          header('Content-type: text/plain');
          echo 'Caught exception: ',  $e->getMessage(), "\n";
      }        
    }
    
    function cancelafactura($idFactura,$idEmpresa,$idSucursal){
        try{
            $factura = json_decode($this->facturamodel->get_factura_by_id($idFactura));
            if($factura){
                //$auth = AuthenticationService::auth($this->params);
	            $token = "
                pojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasdpojeopopkadokasdñklasdlñasdlñksadñldsañladasdasd
                ";//$auth::Token();
                $uuid = explode('|',$factura[0]->CADENA_SAT)[3];
                $cfdi = $this->facturamodel->getDataCFDI($idEmpresa,$idSucursal);         
                $archivoCerPem = $cfdi[0]->RUTA_PEM;
                $archivoKeyPem = $cfdi[0]->RUTA_KEY;
                $paramsCancel = array(
                    "url"=>"http://services.test.sw.com.mx",
                    //"user"=>"omar.valdez.becerril@gmail.com",
                    "password"=> "omar.sw",
                    "token" => $token,
                    "uuid"=> $uuid,
                    "rfc"=> $cfdi[0]->RFC,
                    "b64Cer"=> file_get_contents($archivoCerPem),
                    "b64Key"=> file_get_contents($archivoKeyPem)
                );
                

                $cancelationService = CancelationService::Set($paramsCancel); //asignamos los valores al servicio
                $result = $cancelationService::CancelationByCSD();//usamos el servicio de cancelación
                
                //$result->messageDetail;
                return $this->output
                ->set_content_type('application/json')
                    ->set_output(json_encode(array("status"=>"success","message"=>$result->messageDetail)));
            }else{
                return $this->output
                ->set_content_type('application/json')
                    ->set_output(json_encode(array("status"=>"fail","message"=>"La factura no ha sido timbrada")));
            }
        }
        catch(Exception $e){
            return $this->output
            ->set_content_type('application/json')
                ->set_output(json_encode(array("status"=>"fail","message"=>$e->message)));
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

    function sendfacturaby($forma,$idfactura,$idCliente,$idEmpresa){
        $data = json_decode(file_get_contents("php://input"),true);
        if($data['correos'] !== null){
          $emails = substr($data['correos'], 0, -1);
        }
        $result = json_decode($this->facturamodel->get_factura_by_id($idfactura),false); 
        if($idCliente===0){
          $domicilio = 'Conocido';
          $CP = '';
        }else{
          $cliente = json_decode($this->clientemodel->get_cliente_by_id($idCliente),false);
          $domicilio = $cliente[0]->DOMICILIO;
          $CP = '00000';//$cliente[0]->CP;
        }
        $empresa = json_decode($this->empresamodel->get_empresa_by_id($idEmpresa),false);
        $formapago = json_decode($this->catalogosmodel->get_forma_pago_js(),false);
        $uso_cfdi = json_decode(json_encode($this->catalogosmodel->get_uso_cfdi()),false);
        file_put_contents(FCPATH.'img/'.$result[0]->FOLIO.'.png',base64_decode($result[0]->QR_CODE));
        $qr = FCPATH.'img/'.$result[0]->FOLIO.'.png';      
        $res = $this->xml2pdf->convierte($result[0]->CFDI,$result[0]->CADENA_SAT,$domicilio,$empresa,$formapago,$uso_cfdi,$qr,$idEmpresa,$CP);        
        $dompdf = new DOMPDF();
        $dompdf->loadHtml($res);
        $dompdf->setPaper('A4', "portrait");
        $dompdf->render();
        $today = date("Ymd_His");
        $filenamepdf = FCPATH.'pdfs/'.$result[0]->RFC.'_'.$today.'.pdf';
        $filenamexml = FCPATH.'pdfs/'.$result[0]->RFC.'_'.$today.'.xml';
        $filenamezip = FCPATH.'pdfs/'.$result[0]->FOLIO.'_'.$today.'.zip'; 
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
            }finally{
              unlink($filenamepdf);
              unlink($filenamexml);        
              unlink($filenamezip); 
              unlink($qr);
            }            
        }else{
            try{    
                $email = new PHPMailer(true);
                $email->CharSet = 'UTF-8';
                $email->SetFrom('no-reply@ready2solve.club', 'RTS'); //Name is optional
                $email->Subject   = 'Factura: '.$result[0]->FOLIO;
                $email->Body      = '<p>Adjunto se encuentra su factura en formato PDF y XML</p>';
                $lstemails = explode('|',$emails);
                for($i = 0; $i < sizeof($lstemails);$i++){
                  $email->addAddress($lstemails[$i]);
                }
             
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
            }catch(Exception $e){
                return $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array('value'=>$e->getMessage() )));
            }finally{
                unlink($filenamepdf);
                unlink($filenamexml);        
                unlink($filenamezip); 
                unlink($qr);
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