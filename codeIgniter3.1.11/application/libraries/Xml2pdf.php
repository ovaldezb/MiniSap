<?php 
  defined('FCPATH') OR exit('No direct script access allowed');    

  class Xml2pdf{    
    function convierte($xml,$cadena_sat,$domicilio,$empresa,$formapagoArray,$uso_cfdiArray,$qr){                
      $rows = 15;
      $xml_doc = new DOMDocument('1.0','utf-8');
      $xml_doc->loadXML($xml);
      $complemento = $xml_doc->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital', 'TimbreFiscalDigital')->item(0);
      $UUID = $complemento->getAttribute('UUID');
      $certificadoSAT = $complemento->getAttribute('NoCertificadoSAT');
      $comprobante = $xml_doc->getElementsByTagNameNS('http://www.sat.gob.mx/cfd/3', 'Comprobante')->item(0);            
      $serie = $comprobante->getAttribute('Serie');
      $folio = $comprobante->getAttribute('Folio');
      $fecha = str_replace('T', ' ', $comprobante->getAttribute('Fecha'));
      $formaPago = $comprobante->getAttribute('FormaPago');
      $certificado = $comprobante->getAttribute('NoCertificado');
      $subtotal = $comprobante->getAttribute('SubTotal');
      $descuento = $comprobante->getAttribute('Descuento');
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
      $selloCFD = $timbreFiscal->getAttribute('SelloCFD');
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
      foreach($concepto as $concep){
          $arrayConcept .= 
          "
          <tr>
              <td class='t3c1' style='border-right:2px solid black'>{$concep->getAttribute('Cantidad')}</td>
              <td class='t3c2' style='border-right:2px solid black'>{$concep->getAttribute('Unidad')}</td>
              <td class='t3c3' style='border-right:2px solid black'>{$concep->getAttribute('Descripcion')}</td>
              <td class='t3c4' style='border-right:2px solid black'>$ {$concep->getAttribute('ValorUnitario')}</td>
              <td class='t3c5' style='border-right:2px solid black'>$ {$concep->getAttribute('Descuento')}</td>
              <td class='t3c6'>$ {$concep->getAttribute('Importe')}</td>
          </tr>";
      }
      $relleno = '';
      for($i=0;$i<($rows-sizeof($concepto));$i++){
        $relleno .=
        "
        <tr> 
          <td style='border-right:2px solid black'>&nbsp;</td>
          <td style='border-right:2px solid black'>&nbsp;</td>
          <td style='border-right:2px solid black'>&nbsp;</td>
          <td style='border-right:2px solid black'>&nbsp;</td>
          <td style='border-right:2px solid black'>&nbsp;</td>
          <td >&nbsp;</td>
        </tr>
        ";
      }

      $html1 = "
        <html>
        <HEAD>
            <style>
            table {
              border-collapse: collapse;
              border-spacing: 0;
            }                            
            .header{
                color:white;
                background: DodgerBlue;
                text-align: center;
                margin: 0px;
                padding: 0px;
            }
            .headc1{ 
                font-size: 14px;
                text-align: right;
                color:blue;
                font-weight: bold;
            }
            .headc2{
                font-size: 12px;
                text-align:right;
            }
            .thEmp{ 
              font-size: 14px;
              font-weight: bold;
            }
            .t2h1{ 
                font-size: 14px;
                font-weight: bold;
            }
            .t2h2{
                font-size: 14px;
                text-align:left'
                font-weight: bold;
            }
            .t3c1{
                width: 60.55px; 
                text-align: center;
                font-size: 12px;
            }
            .t3c2{
                width: 72.2833px; 
                text-align: center;
                font-size: 12px;
            }
            .t3c3{
                width: 262.667px;
                font-size: 10px;
                text-align: center;
            }
            .t3c4{
                width: 80.0px;
                font-size: 12px;
                text-align: center;
            }
            .t3c5{
                width: 80.00px;
                font-size: 12px;
                text-align: center;
            }
            .t3c6{
              width: 80.0px;
              font-size: 12px;
              text-align: center;
          }
            .t4head{
                font-size: 12px;
                text-align: left;
                font-weight: bold;
            }
            .t4cell{
                font-size: 8px;
                text-align: left;
                padding: 0px;
                word-wrap: break-word;
            }
            
            #footer {
              width: 100%;
              height: 195px;
              left: 0px !important;
              bottom: 0px !important;
              margin: 0 auto !important;
              position: fixed;
            }
            .font12b {
              font-size:12px;
              font-weight:bold
            }
            .font10 {
              font-size:10px
            }
            </style>
        </HEAD>
        <BODY>
          <table style='width: 100%;'>
            <colgroup>
              <col width:'30%'/>
              <col width:'35%'/>
              <col width:'35%'/>
            </colgroup>
            <tbody>
              <tr>
                <td style='text-align:center' colspan='3'><h2>{$nombre}</h2></td>
              </tr>
              <tr>
                <td style='text-align:center'>
                  <img src='./img/logo.jpg' alt='' width='123' height='123' />
                </td>
                <td>
                  <table style='width:100%'>
                    <tbody>                    
                      <tr>
                        <td>RFC:</td>
                        <td class='thEmp'>{$rfc}</td>
                      </tr>
                      <tr>
                        <td  class='thEmp' colspan='2'>{$empresa[0]->DOMICILIO}</td>
                      </tr>
                      <tr>
                        <td >CP:</td>
                        <td class='thEmp' colspan='2'>{$empresa[0]->CP}</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td>
                  <table style='width: 100%' cellspacing='0' cellpadding='0'>                                
                    <tbody>
                      <tr>
                        <td class='headc1' >Factura</td>
                      </tr>  
                      <tr>
                        <td class='headc2' style='color:red'>{$folio}</td>
                      </tr>
                      <tr>
                        <td class='headc1' >Folio Fiscal</td>
                      </tr>  
                      <tr>
                        <td class='headc2'>{$UUID}</td>
                      </tr>
                      <tr>
                        <td class='headc1' >Certificado CSD</td>
                      </tr>  
                      <tr>    
                        <td class='headc2'>{$certificado}</td>
                      </tr>
                      <tr>
                        <td class='headc1' >Fecha</td>
                      </tr>  
                      <tr>
                        <td class='headc2'>{$fecha}</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
        </table>
        <hr style='color:red;background:blue'/>
        <table style='width: 100%'>
          <colgroup>
            <col width='25%' />
            <col width='25%' />
            <col width='25%' />
            <col width='25%' />
          </colgroup>
          <tbody>                    
              <tr>
                  <td class='t2h1'>RECEPTOR:</td>
                  <td class='t2h2'>{$nombre_receptor}</td>
                  <td class='t2h1'>METODO PAGO</td>
                  <td class='t2h2'>{$metodoPago}</td>
                  
              </tr>
              <tr>
                  <td class='t2h1'>RFC CLIENTE:</td>
                  <td class='t2h2'>{$rfc_receptor}</td>
                  <td class='t2h1'>FORMA PAGO </td>
                  <td class='t2h2'>{$formaPagoDesc}</td>
              </tr>
              <tr>
                  <td class='t2h1'>DIRECCION</td>
                  <td class='t2h2'>{$domicilio}</td>
                  <td class='t2h1'></td>
                  <td class='t2h2'></td>
              </tr>
          </tbody>
        </table>
        <br>
        <div style='width:100%; border:2px solid black; height:325px'>
          <table style='width:100%'>
            <tbody>
              <tr>
                  <td class='t3c1' style='background: DodgerBlue;color:white;'>Cantidad</td>
                  <td class='t3c2' style='background: DodgerBlue;color:white;'>Unidad</td>
                  <td class='t3c3' style='background: DodgerBlue;color:white;'>Descripci&oacute;n</td>
                  <td class='t3c4' style='background: DodgerBlue;color:white;'>P. Unitario</td>
                  <td class='t3c5' style='background: DodgerBlue;color:white;'>Descuento</td>
                  <td class='t3c6' style='background: DodgerBlue;color:white;'>Importe</td>
              </tr>
              {$arrayConcept}
              {$relleno}
            </tbody>
          </table>
        </div>
        <table style='width: 100%;'>
          <tbody>
            <colgroup>
              <col width='30%'/>
              <col width='40%'/>
              <col width='40%'/>
            </colgroup>
            <tr>
              <td style='text-align:left'>
                <img src='{$qr}' width='140' height='140' />
              </td>
              <td>
              <p>Uso CFDI:<p>
              <p>{$uso_cfdi_desc}</p>
              </td>
              <td style='text-align:right'>
                <table style='width:100%'>
                  <colgroup>
                    <col width='60%'/>
                    <col width='40%'/>
                  </colgroup>
                  <tr>
                    <td style='text-align: right;'><strong>Sub Total:</strong></td>
                    <td style='text-align: right;'>$ {$subtotal}</td>
                  </tr>
                  <tr>
                    <td style='text-align: right;'><strong>Descuento:</strong></td>
                    <td style='text-align: right;'>$ {$descuento}</td>
                  </tr>
                  <tr>
                    <td style='text-align: right;'><strong>IVA:</strong></td>
                    <td style='text-align: right;'>$ {$imptrans}</td>
                  </tr>
                  <tr>
                    <td style='text-align: right;'><strong>Total:</strong></td>
                    <td style='text-align: right;'>$ {$total}</td>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      
        <footer id='footer'>
          <div class='font12b'>CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACION DIGITAL DEL SAT</div>
          <div style='margin-top:-15px'>
          <p style='word-wrap: break-word;line:spacing 0px' class='font10'>{$cadena_sat}</p>
          </div>
          <div class='font12b' style=''>SELLO DIGITAL DEL CFDI</div>
          <div style='margin-top:-15px'>
          <p style='word-wrap: break-word;line:spacing 0px' class='font10'>{$selloCFD}</p>
          </div>
          <div class='font12b'>SELLO DIGITAL DEL SAT</div>
          <div style='margin-top:-15px'>
          <p style='word-wrap: break-word;line:spacing 0px' class='font10'>{$selloSAT}</p>
          </div>
      </footer>         
    </BODY>        
    </HEAD>";            
    return $html1;
    }
  }

?>