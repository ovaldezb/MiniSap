<?php 
    defined('FCPATH') OR exit('No direct script access allowed');    

    class Xml2pdf{
        
        function convierte($xml,$cliente,$empresa,$formapagoArray,$uso_cfdiArray,$cadenaSAT,$path){                
            $xml_doc = new DOMDocument('1.0','utf-8');
            $xml_doc->loadXML($xml);
            $complemento = $xml_doc->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital', 'TimbreFiscalDigital')->item(0);
            $UUID = $complemento->getAttribute('UUID');
            $certificadoSAT = $complemento->getAttribute('NoCertificadoSAT');
            $comprobante = $xml_doc->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);            
            $serie = $comprobante->getAttribute('Serie');
            $folio = $comprobante->getAttribute('Folio');
            $fecha = $comprobante->getAttribute('Fecha');
            $formaPago = $comprobante->getAttribute('FormaPago');
            $certificado = $comprobante->getAttribute('NoCertificado');
            $subtotal = $comprobante->getAttribute('SubTotal');
            $total = $comprobante->getAttribute('Total');
            $metodoPago = $comprobante->getAttribute('MetodoPago');
            $formaPago = $comprobante->getAttribute('FormaPago');
            $emisor = $xml_doc->getElementsByTagName('Emisor')[0];          
            $rfc = $emisor->getAttribute('Rfc');    
            $nombre = $emisor->getAttribute('Nombre');
            $receptor = $xml_doc->getElementsByTagName('Receptor')[0];
            $nombre_receptor = $receptor->getAttribute('Nombre');
            $rfc_receptor = $receptor->getAttribute('Rfc');
            $uso_cfdi = $receptor->getAttribute('UsoCFDI');
            $conceptos = $xml_doc->getElementsByTagName('Conceptos')[0]; 
            $concepto = $xml_doc->getElementsByTagName('Concepto');
            $impuestos = $xml_doc->getElementsByTagName('Traslado');
            $sizeImp = sizeof($impuestos);
            $imptrans = $impuestos[$sizeImp-1]->getAttribute('Importe');
            $timbreFiscal = $xml_doc->getElementsByTagName('TimbreFiscalDigital')[0];
            $selloCFDI = $timbreFiscal->getAttribute('SelloCFD');
            $selloSAT = $timbreFiscal->getAttribute('SelloSAT');    
            $formaPagoDesc = '';
            foreach($formapagoArray as $fp){                
                if($fp->CLAVE == $formaPago ){
                    $formaPagoDesc = $fp->DESCRIPCION;
                    break;
                }
            }
            $uso_cfdi_desc ='';
            foreach($uso_cfdiArray as $uc){
                if($uc->CLAVE == $uso_cfdi){
                    $uso_cfdi_desc = $uc->DESCRIPCION;
                    break;
                }
            }

            $arrayConcept = '';
            $col1 = '20%';
            $col2 = '80%';
            foreach($concepto as $concep){
                $arrayConcept .= 
                "
                <tr>
                    <td class='t3c1'>{$concep->getAttribute('Cantidad')}</td>
                    <td class='t3c2'>{$concep->getAttribute('ClaveUnidad')}</td>
                    <td class='t3c3'>{$concep->getAttribute('Descripcion')}</td>
                    <td class='t3c4'>$ {$concep->getAttribute('ValorUnitario')}</td>
                    <td class='t3c5'>$ {$concep->getAttribute('Importe')}</td>
                </tr>";
            }
            $html1 = "
            <html>
            <HEAD>
                <style>
                table {
                border-collapse: collapse;
                }                            
                
                .header{
                    color:white;
                    background: DodgerBlue;
                    text-align: center;
                    margin: 0px;
                    padding: 0px;
                }
                .headc1{
                    width: 20px; 
                    font-size: 12px;
                }
                .headc2{
                    width: 240px; 
                    font-size: 12px;
                    text-align:left'
                }
                .t2h1{
                    width: 40px; 
                    font-size: 12px;
                }
                .t2h2{
                    width: 200px; 
                    font-size: 12px;
                    text-align:left'
                }
                .t3c1{
                    width: 80.55px; 
                    text-align: center;
                    font-size: 12px;
                }
                .t3c2{
                    width: 78.2833px; 
                    text-align: center;
                    font-size: 12px;
                }
                .t3c3{
                    width: 262.667px;
                    font-size: 12px;
                }
                .t3c4{
                    width: 105.833px;
                    font-size: 12px;
                    text-align: right;
                }
                .t3c5{
                    width: 114.667px;
                    font-size: 12px;
                    text-align: right;
                }.
                .t4head{
                    font-size: 12px;
                    text-align: left;
                    font-weight: bold;
                }
                .t4cell{
                    font-size: 10px;
                    text-align: left;
                    word-wrap: break-word;
                }
                td {                                 
                    word-wrap: break-word; 
                } 
                </style>
            </HEAD>
            <BODY>
            <table style='width: 600px;'>
                <tbody>
                    <tr>
                        <td style='width:100px'>
                            <img src='./img/cercanias.png' alt='' width='123' height='123' />
                        </td>
                        <td style='width:180px;'>&nbsp;</td>
                        <td style='width:350px;' align='right'>
                            <table style='width: 350px' cellspacing='0' cellpadding='0'>                                
                                <tbody>
                                    <tr>
                                        <td class='header' style='width: 350px;' colspan='2'>Factura</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Folio Fiscal:</td>
                                        <td class='headc2'>{$UUID}</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Fecha:</td>
                                        <td class='headc2'>{$fecha}</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Certificado CSD:</td>
                                        <td class='headc2'>{$certificado}</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Certificado SAT:</td>
                                        <td class='headc2'>{$certificadoSAT}</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Folio Interno:</td>
                                        <td class='headc2'>{$folio}</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Forma Pago:</td>
                                        <td class='headc2'>{$formaPagoDesc}</td>
                                    </tr>
                                    <tr>
                                        <td class='headc1'>Método de Pago:</td>
                                        <td class='headc2'>{$metodoPago}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr/>
            <table style='width:100%'>
                <tr>
                    <td class='header'>Datos del Emisor</td>
                </tr>            
            </table>
            <table>
                <tbody>                    
                    <tr>
                        <td class='t2h1'>Nombre:</td>
                        <td class='t2h2'>{$nombre}</td>
                    </tr>
                    <tr>
                        <td class='t2h1'>RFC:</td>
                        <td class='t2h2'>{$rfc}</td>
                    </tr>
                    <tr>
                        <td class='t2h1'>Direcci&oacute;n:</td>
                        <td class='t2h2'>{$empresa[0]->DOMICILIO}</td>
                    </tr>
                    <tr>
                        <td class='t2h1'>CP:</td>
                        <td class='t2h2'>{$empresa[0]->CP}</td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <table style='width:100%'>
                <tr>
                    <td class='header'>Datos del Receptor</td>
                </tr>            
            </table>
            <table>
                <tbody>                    
                    <tr>
                        <td class='t2h1'>Nombre:</td>
                        <td class='t2h2'>{$nombre_receptor}</td>
                    </tr>
                    <tr>
                        <td class='t2h1'>RFC:</td>
                        <td class='t2h2'>{$rfc_receptor}</td>
                    </tr>
                    <tr>
                        <td class='t2h1'>Direcci&oacute;n</td>
                        <td class='t2h2'>{$cliente[0]->DOMICILIO}</td>
                    </tr>
                    <tr>
                        <td class='t2h1'>Uso CFDI:</td>
                        <td class='t2h2'>{$uso_cfdi_desc}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table style='width:100%'>
                <tbody>
                    <tr>
                        <td class='t3c1' style='background: DodgerBlue;color:white;'>Cantidad</td>
                        <td class='t3c2' style='background: DodgerBlue;color:white;'>Unidad</td>
                        <td class='t3c3' style='background: DodgerBlue;color:white;'>Descripci&oacute;n</td>
                        <td class='t3c4' style='background: DodgerBlue;color:white;'>P. Unitario</td>
                        <td class='t3c4' style='background: DodgerBlue;color:white;'>Importe</td>
                    </tr>
                    {$arrayConcept}                    
                    <tr>
                    <td style='width: 80.55px; text-align: center;'>&nbsp;</td>
                    <td style='width: 78.2833px; text-align: center;'>&nbsp;</td>
                    <td style='width: 262.667px;'>&nbsp;</td>
                    <td style='width: 105.833px; text-align: right;'>&nbsp;</td>
                    <td style='width: 114.667px; text-align: right;'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td style='width: 80.55px; text-align: center;'>&nbsp;</td>
                    <td style='width: 78.2833px; text-align: center;'>&nbsp;</td>
                    <td style='width: 262.667px;'>&nbsp;</td>
                    <td style='width: 105.833px; text-align: right;'>&nbsp;</td>
                    <td style='width: 114.667px; text-align: right;'>&nbsp;</td>
                    </tr>
                    <tr>
                    <td style='width: 80.55px; text-align: center;'>&nbsp;</td>
                    <td style='width: 78.2833px; text-align: center;'>&nbsp;</td>
                    <td style='width: 262.667px;'>&nbsp;</td>
                    <td style='width: 105.833px; text-align: right;'>&nbsp;</td>
                    <td style='width: 114.667px; text-align: right;'>                    
                    </td>
                    </tr>
                </tbody>
            </table>
            <table style='width: 100%;' border='1'>
                <tbody>
                    <tr>
                        <td style='width: 150px;'>
                            <table>
                                <tbody>
                                    <tr>
                                        <td style='width: 52px;' rowspan='3'><img src='./0000031.png' alt='' width='140' height='140' />&nbsp;&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style='width: 511px;'>
                            <table style='width: 100%;'>
                                <tbody>
                                    <tr>
                                        <td style='width: 135px;'>&nbsp;</td>
                                        <td style='width: 102px;'>&nbsp;</td>
                                        <td style='width: 130px; text-align: right;'><strong>Sub Total:</strong></td>
                                        <td style='width: 112px; text-align: right;'>&nbsp;$137.26</td>
                                    </tr>
                                    <tr>
                                        <td style='width: 135px; text-align: right;'>&nbsp;</td>
                                        <td style='width: 102px;'>&nbsp;</td>
                                        <td style='width: 130px; text-align: right;'><strong>IVA:</strong></td>
                                        <td style='width: 112px; text-align: right;'>$21.96</td>
                                    </tr>
                                    <tr>
                                        <td style='width: 135px;'>&nbsp;</td>
                                        <td style='width: 102px; text-align: right;'>&nbsp;</td>
                                        <td style='width: 130px; text-align: right;'><strong>Total:</strong></td>
                                        <td style='width: 112px; text-align: right;'>$152.29</td>
                                    </tr>                                                        
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <table style='width: 100%;'>
                <tbody>
                    <tr>
                        <td class='t4head'>CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT</td>
                    </tr>
                    <tr>
                        <td class='t4cell'>||1.1|baf349fb-0652-4caf-b756-ff5841b7d4b5|2017-05-11T18:27:19|AAA010101AAA|YHvkKPCGUhxHRoqk8vAnNeiHVNo5KaGYa3EBU1yMOiiTNnUASQJZxFkNbn52RUMtnepI1IAXDh7FlqCm5Vjofh3vLSJFCl8A+KUYO/GRoiYXOqwPpIhBMs9JPDXnshQzgDeL4NCd6/dSuQj3hdCVZCPgUnyYjRaFUBtqfJKTuIyP3n1o0QHq9pNvQTe+I6pumMcZoK2cWsFcgj3gZ++qO/SeV8bcWpXWGVQ43dvMCggI/z3q6sMTli6TcqoLYjS/aXmtKcPXE7Lay9uEGUNXlRaNDeGFyhtRh4ABGcFzIUuOVu1aPoq5s9wX81CaYx7hgTHFg74vNVGmxbTUwMbDSg==|20001000000300022323||</td>
                    </tr>
                    <tr>
                        <td class='t4head'>SELLO DIGITAL DEL CFDI</td>
                    </tr>
                    <tr>
                        <td class='t4cell'>Rd5/vHgpCeGWCDOMQWYXoM16QjCTw+rYPmmO97cTcpmbxPFx5CcleYEJpP45kYv40zP4lCuPnDdh4PtNzK7tqavjZJN6ZOem+Zr3sGXSxpn8HIu6kKflhhrK6HME0XZyuhNHUBHNKCR+kve8x3Oz29c1jDRxxM3L2zpMAgLLVbwiYUHTcanmnjY69n59MdEQGfhg/YF2b4Eyx397SkFA92uvVV1U+dCW0NfxRae66P+yfGhnHGLjUC2LuLsDmJ26zDtlnIfrkJPiFxlakPh1UZDG/AQWgOtWx5OQejBndQuT9dMsReCJ8ocDGsEqCG3C90SqDwrLZGkJ72RP8KQS0w==</td>
                    </tr>
                    <tr>
                        <td class='t4head'>SELLO DIGITAL DEL SAT<br /></td>
                    </tr>
                    <tr>
                        <td class='t4cell'>tHF1ZW4TzxSsv7A8EG0t82jCiDsuViQKeB7sbV8/K7Clv9N6zfygh3DJQ03Pa27m8NMFZEGxQRDAR2sA4R8VCxhDplzqA/nORdXhNcJqh2NaNW6FzPiftRrTDcZmU1NGjRnMz5EjqJrF0Lmoml1oXyyllrbdAlRyHxPZntqoi/a4VbjhZUuTT+9WMM1OGGBhjOhZl+4Xziq1VU9CQ1QJ08YJj7xmCeaoQVtRjIl6BxI4Dnbluc/C2cPC9h6iwPAz1U547F41gr2VEZlN5ddTKnorabDpLnxVlZoN0xRulL9dqp8YCDoNAEnCJEBahFxbsOgvahcmaunoMvZGuvHNtA==</td>
                    </tr>
                </tbody>
            </table>            
        </BODY>        
    </HEAD>";            
        return $html1;
        }
    }

?>