
<div class="container" ng-app="myApp" ng-controller="myCtrlDatosFactura" data-ng-init="init()">
    <div class="notification" >
		<h1 class="title has-text-centered">Gestión de datos para Facturar</h1>
    </div>
    <table style="width:70%;" class="table is-bordered is-hoverable" >
        <col width="40%">
        <col width="40%">
        <col width="20%">
              
        <tr ng-repeat="suc in lstSucCerts" style="display:{{suc.FORMA?'table-row':'none'}};">
            <td style="vertical-align:middle;">
                <p><label class="label">{{suc.ALIAS}}</label></p>
                <p><label class="label">{{suc.RESPONSABLE}}</label></p>
                <p><label class="label">{{suc.DIRECCION}}</label></p>
                <p><label class="label">{{suc.CP}}</label></p>
            </td>
            <td style="vertical-align:middle;">                
                <p><label class="label">RFC:</label> {{suc.RFC}}</p>                
                <p><label class="label">Desde:</label> {{suc.FECHA_INICIO}}</p>                
                <p><label class="label">Hasta:</label> {{suc.FECHA_FIN}}</p>                
            </td>
            <td align="center" style="vertical-align:middle;"> 
                <button class="button is-success" ng-click="enviar($index,suc.ID_SUCURSAL)">{{suc.BOTON}}</button>           
            </td>
        </tr>        
    </table>
    <div ng-show="formactive" style="transition-duration: 0.4s">
        <form name="myForm" action='<?php echo base_url();?>datosfactura/save' enctype='multipart/form-data' method="post"-->
            <input type ="hidden"  name="sucursal" value="{{factura.idsucursal}}">
            <input type ="hidden"  name="idempresa" value="{{idempresa}}">
            <div class="columns">
                <div class="column is-2"><label class="label">Archivo .cer</label></div>
                <div class="column"><input type="file" name="files[]" accept=".cer" select-ng-files ng-model="factura.cerFile"></div>            
            </div>
            <div class="columns">
                <div class="column is-2"><label class="label">Archivo .key</label></div>
                <div class="column"><input type="file" name="files[]" accept=".key" select-ng-files ng-model="factura.keyFile"></div>
            </div>
            <div class="columns">
                <div class="column is-2"><label class="label">Contraseña del Certificado</label></div>
                <div class="column"><input type="password" name="ci_pass" ng-model="factura.pass" required></div>
            </div>
        </form>
            <div class="field is-grouped">		
                <div class="control">
                    <button  class="button is-success" onclick="document.myForm.submit();" >Enviar</button><!--ng-disabled="myForm.$invalid"-->
                </div>
                <div class="control">
                    <button  class="button is-warning" ng-click="cancelar()" >Cancelar</button>
                </div>
            </div>
        
    </div>
    <?php if(isset($errorKey)){
        echo '<div class="title">'.$errorKey.'</div>';
    }
    ?>
    <?php if(isset($error)){
        foreach($error as $err){
            echo '<ul>'.$err.'</ul>';
        }
    }?>
 </div>
