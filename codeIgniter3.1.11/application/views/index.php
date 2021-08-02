<html>
<head>
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
  <script src="../js/sweetalert.min.js"></script>
  <script src="../js/FileSaver.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-chart.js/0.10.2/angular-chart.js"></script>
	<script src="../js/utilerias.js"></script>
  <script src="../js/foopicker.js"></script>
  <script src="../js/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../css/utils.css">
  <link rel="stylesheet" href="../css/foopicker.css">
  <title>RTS</title>
</head>
<body ng-app="myApp" ng-controller="myCtrlIndex" data-ng-init="init()">
  <input type="hidden" id="idusuario" name="idusuario" value="<?php echo $idusuario ?>">
  <input type="hidden" id="nombreusuario" name="nombreusuario" value="<?php echo $nombre ?>">
  <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" href="./login#!/">
        <img src="../img/logo.jpg" alt="Sistema de Gesti&oacute;n Empresarial" width="40" height="28">
      </a>
    </div>
    <div class="navbar-end">
      <div class="navbar-item">
        <div class="field is-grouped">
          <p class="control">
            <span class="icon">
              <i class="fas fa-user"></i>
            </span>
            <span><?php echo $nombre?></span>
          </p>
          <p class="control">
            <span class="icon">
              <a ng-click="selectEmpresa()" style="color: #184ddd" disabled>
              <i class="far fa-building"></i>
              </a>
            </span>
            <span>{{nombreEmpresa}}</span>
          </p>

          <p class="control">
            <span class="icon" ng-show="!isCurrentYear">
              <a ng-click="addFY()" style="color: #184ddd">
                <i class="far fa-calendar-alt"></i>
              </a>
            </span>
            <span class="icon" ng-show="isCurrentYear">
                <i class="far fa-calendar-alt"></i>
            </span>
            <span>{{anioFiscal}}</span>
          </p>
          <p class="control">
            <span class="icon">
              <i class="fas fa-store-alt"></i>
            </span>
              <select ng-change="cambiaSucursal()" ng-model="sucursalEmpresa" ng-options="x.ID_SUCURSAL as x.ALIAS for x in lstSucursal">
              </select>
            <span>
            </span>
          </p>
          <p class="control">
              <a class="button is-primary" href="#!clss">
                <span class="icon">
                  <i class="fas fa-door-open"></i>
                </span>
                <span>Salir</span>
              </a>
            </p>
        </div>
      </div>
    </div>
  </nav>
  
  <div class="columns" style="margin-top:50px">
    <div class="column is-narrow" style="width:300px">
      <div class="contenedor-menu">
        <ul class="menus">
<?php
  $modulotmp = '';
  $isFinModulo = false;
  if($modproc){
    foreach ($modproc as $key)
    {
    $modulo = $key['MODULO'];
    $ruta = $key['RUTA'];
    $idproc = $key['ID_PROCESO'];
    if($modulo != $modulotmp)
    {
      if($isFinModulo)
      {?>
          </li>
        </ul>
<?php }?>
        <li>
          <a><i class="icono izquierda <?php echo $key['ICONO']?>"></i><?php echo $key['MODULO']?><i class="icono derecha fas fa-chevron-down"></i></a>
          <ul >
            <li><a href="#!<?php echo $key['RUTA']?>/<?php echo $idproc ?>"><?php echo $key['PROCESO']?></a></li>
<?php
      $isFinModulo = true;
    }else if($ruta !=null) {?>
          <li><a href="#!<?php echo $key['RUTA']?>/<?php echo $idproc ?>"><?php echo $key['PROCESO']?></a></li>
<?php
    }
    $modulotmp = $modulo;?>
<?php
    }
 }else{ ?>
    <h2>Usuario sin permisos, favor de contactar al administrador</h2>
<?php } ?>
        </ul>
      </div>
    </div>
    <div class="column">
      <div ng-view></div>
    </div>
  </div>
  <div class="{{isEmpPermActiv ? 'modal is-active': 'modal'}}">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Seleccione una empresa</p>
        <button class="delete" aria-label="close" ng-click="cerrarSelectEmpr()"></button>
      </header>
      <section class="modal-card-body">
        <table style="width:100%">
          <tr>
            <td>
              <table style="width:100%">
                <tr>
                  <td align="center"><label class="label">Nombre/Razón Social</label></td>
                </tr>
              </table>
            </td>
            <td>
              <table style="width:100%" class="table">
                <tr>
                  <td align="center"><label class="label">Año Fiscal</label></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td>
              <div style="width:100%; height:300px; overflow:auto;">
                <table style="width:100%" class="table">
                  <tr ng-repeat="x in lstEmprPerm" ng-click="selectEmpPerm(x.ID_EMPRESA,$index)" ng-class="{selected: x.ID_EMPRESA === idEmpresaSelected}">
                    <td>{{x.NOMBRE}}</td>
                  </tr>
                </table>
              </div>
            </td>
            <td>
              <div style="width:100%; height:300px; overflow:auto;">
                <table style="width:100%" class="table">
                  <tr ng-repeat="x in lstFYEmpr" ng-click="selectFYEmp(x.EJER_FISC,$index)" ng-class="{selected: x.EJER_FISC === idSelFYEmp}">
                    <td>{{x.EJER_FISC}}</td>
                  </tr>
                </table>
              </div>
            </td>
          </tr>
        </table>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" ng-click="guardarEmpPerm()">Aceptar</button>
        <button class="button" ng-click="cerrarSelectEmpr()">Cancelar</button>
      </footer>
    </div>
  </div>
<script src="../js/index.js"></script>
<script src="../js/clientes.js"></script>
<script src="../js/empresa.js"></script>
<script src="../js/producto.js"></script>
<script src="../js/proveedor.js"></script>
<script src="../js/sucursal.js"></script>
<script src="../js/compras.js"></script>
<script src="../js/tpv.js"></script>
<script src="../js/pedidos.js"></script>
<script src="../js/usuarios.js"></script>
<script src="../js/logout.js"></script>
<script src="../js/repmovalm.js"></script>
<script src="../js/rventas.js"></script>
<script src="../js/linea.js"></script>
<script src="../js/datosfactura.js"></script>
<script src="../js/facturas.js"></script>
<script src="../js/vendedores.js"></script>
<script src="../js/cargamasiva.js"></script>
<script src="../js/controlinventario.js"></script>
<script src="../js/cobranza.js"></script>
<script src="../js/pagos.js"></script>
<script src="../js/inicio.js"></script>
<script src="../js/cortecaja.js"></script>
<!--script src="../js/reportecxc.js"></script-->
<!--script src="../js/reportecxp.js"></script-->
<script src="../js/repcobranza.js"></script>
<script src="../js/reppagos.js"></script>
<script src="../js/transferencia.js"></script>
<script>
  $(document).ready(function(){
    $('.menus li:has(ul)').click(function(e){
      if($(this).hasClass('activado')){
        $(this).removeClass('activado');
        $(this).children('ul').slideUp();
      }else{
        $('.menus li ul').slideUp();
        $('.menus li').removeClass('activado');
        $(this).addClass('activado');
        $(this).children('ul').slideDown();
      }
	  });
  });
</script>
</body>
</html>
