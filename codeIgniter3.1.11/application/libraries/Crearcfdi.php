<?php //if (!defined('BASEPATH')) exit('No direct script access allowed');
        defined('FCPATH') OR exit('No direct script access allowed');
        require_once FCPATH.'sw-sdk/SWSDK.php';        
        use SWServices\Toolkit\SignService as SignService;
class Crearcfdi {

    var $xml_base_file = FCPATH.'cfdi/basico.xml';

    function generaXML($baseArray,$conceptosArray,$archivoCerPem,$archivoKeyPem){
        $path = FCPATH;
        $xml_doc = new DOMDocument('1.0','utf-8');
        $xml_doc->loadXML(file_get_contents($this->xml_base_file));      
        $xml_doc->formatOutput = TRUE;  
        $xml_doc->preserveWhiteSpace = false;
        $comprobante = $xml_doc->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);
        $comprobante->setAttribute('Serie',$baseArray['Serie']);
        $comprobante->setAttribute('Folio',$baseArray['Folio']);
        $comprobante->setAttribute('Fecha',$baseArray['Fecha']);
        $comprobante->setAttribute('FormaPago',$baseArray['FormaPago']);
        $comprobante->setAttribute('NoCertificado',$baseArray['NoCertificado']);
        $comprobante->setAttribute('Certificado',$baseArray['Certificado']);
        $comprobante->setAttribute('SubTotal',$baseArray['SubTotal']);
        $comprobante->setAttribute('Moneda',$baseArray['Moneda']);
        $comprobante->setAttribute('TipoCambio',$baseArray['TipoCambio']);
        $comprobante->setAttribute('Descuento',$baseArray['Descuento']);
        $comprobante->setAttribute('Total',$baseArray['Total']);
        $comprobante->setAttribute('TipoDeComprobante',$baseArray['TipoDeComprobante']);
        $comprobante->setAttribute('MetodoPago',$baseArray['MetodoPago']);
        $comprobante->setAttribute('LugarExpedicion',$baseArray['LugarExpedicion']);

        $emisor = $xml_doc->getElementsByTagName('Emisor')[0];          
        $emisor->setAttribute('Rfc', $baseArray['RfcEmisor']);    
        $emisor->setAttribute('Nombre', $baseArray['NombreEmisor']);
        $emisor->setAttribute('RegimenFiscal', $baseArray['RegimenFiscal']);
        
        $receptor = $xml_doc->getElementsByTagName('Receptor')[0];
        $receptor->setAttribute('Rfc', $baseArray['RfcReceptor']);    
        $receptor->setAttribute('Nombre', $baseArray['NombreReceptor']);
        $receptor->setAttribute('UsoCFDI', $baseArray['UsoCFDI']);

        $conceptos = $xml_doc->getElementsByTagName('Conceptos')[0];   
        
        foreach($conceptosArray as $conceptoElement){
            $concepto =$xml_doc->createElement('cfdi:Concepto');
            $concepto->setAttribute('ClaveProdServ',$conceptoElement['ClaveProdServ']);
            $concepto->setAttribute('NoIdentificacion',$conceptoElement['NoIdentificacion']);
            $concepto->setAttribute('Cantidad',$conceptoElement['Cantidad']);
            $concepto->setAttribute('ClaveUnidad',$conceptoElement['ClaveUnidad']);
            $concepto->setAttribute('Unidad',$conceptoElement['Unidad']);
            $concepto->setAttribute('Descripcion',$conceptoElement['Descripcion']);
            $concepto->setAttribute('ValorUnitario',$conceptoElement['ValorUnitario']);
            $concepto->setAttribute('Descuento',$conceptoElement['Descuento']);        
            $concepto->setAttribute('Importe',$conceptoElement['Importe']);        
            $concepto_nodo = $conceptos->appendChild($concepto);
            $impuestos = $xml_doc->createElement('cfdi:Impuestos');
            $impuestos_nodo = $concepto_nodo->appendChild($impuestos); 
            $translados = $xml_doc->createElement('cfdi:Traslados');
            $translados_nodo = $impuestos_nodo->appendChild($translados);
            $transladoArray = $conceptoElement['Traslados'];
            $translado = $xml_doc->createElement('cfdi:Traslado');
            $translado->setAttribute('Base',$transladoArray['Base']);
            $translado->setAttribute('Impuesto',$transladoArray['Impuesto']);
            $translado->setAttribute('TipoFactor',$transladoArray['TipoFactor']);
            $translado->setAttribute('TasaOCuota',$transladoArray['TasaOCuota']);
            $translado->setAttribute('Importe',$transladoArray['Importe']);
            $translados_nodo->appendChild($translado);
        }

        $impuestosTotal = $xml_doc->getElementsByTagName('Impuestos')[0];
        $impuestosTotal->setAttribute('TotalImpuestosTrasladados',$baseArray['TotalImpuestosTrasladados']);
        $translado_total = $xml_doc->getElementsByTagName('Traslados')[0];
        $transladoArray = $baseArray['Traslados'];
        foreach($transladoArray as $transladoElement){
            $translado_valor = $xml_doc->createElement('cfdi:Traslado');        
            $translado_valor->setAttribute('Impuesto', $transladoElement['Impuesto']);
            $translado_valor->setAttribute('TipoFactor',$transladoElement['TipoFactor']);
            $translado_valor->setAttribute('TasaOCuota',$transladoElement['TasaOCuota']);
            $translado_valor->setAttribute('Importe',$transladoElement['Importe']);
            $translado_total->appendChild($translado_valor);
        }
        $path_file = $path.'cfdi/basico_1.xml';                        
        $xml_doc->save($path_file);        
        $path_co = $path.'cfdi/cadenaoriginal.txt';        
        shell_exec("xsltproc {$path}cadenaoriginal/cadenaoriginal_3_3.xslt {$path_file} > {$path_co}");
        $params = array('cadenaOriginal'=>$path_co,'archivoCerPem'=>$archivoCerPem,'archivoKeyPem'=>$archivoKeyPem);
        $sello = SignService::ObtenerSello($params);        
        unlink($path_co);

        $xml_doc_sellado = new DOMDocument('1.0','utf-8');
        $xml_doc_sellado->loadXML(file_get_contents($path_file));      
        $xml_doc_sellado->formatOutput = TRUE;  
        $xml_doc_sellado->preserveWhiteSpace = false;
        $comprob_sello = $xml_doc_sellado->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);
        $comprob_sello->setAttribute('Sello',$sello->sello);
        unlink($path_file);
        return $xml_doc_sellado->saveXML(); 
    }
}

?>