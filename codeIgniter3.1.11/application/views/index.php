<html>
<head>
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="../js/utilerias.js"></script>
	<script src="../js/jquery-3.4.1.slim.js"></script>
  <script src="../js/foopicker.js"></script>
  <link rel="stylesheet" href="../css/utils.css">
  <link rel="stylesheet" href="../css/foopicker.css">
  <title>RTS</title>
  <style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc;
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}
.accordion:after {
  content: '\002B';
  color: #777;
  font-weight: bold;
  float: right;
  margin-left: 5px;
}

.active:after {
  content: "\2212";
}
.sidepanel  {
  width: 0;
  position: fixed;
  z-index: 1;
  height: 250px;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidepanel a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidepanel a:hover {
  color: #f1f1f1;
}

.sidepanel .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
}

.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color:#444;
}
</style>

</head>
<body ng-app="myApp" ng-controller="myCtrlIndex" data-ng-init="init()">
  <input type="hidden" id="idusuario" name="idusuario" value="<?php echo $idusuario ?>">
  <input type="hidden" id="nombreusuario" name="nombreusuario" value="<?php echo $nombre ?>">
  <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a class="navbar-item" >
        <img src="../img/cercanias.png" alt="Sistema de Gesti&oacute;n Empresarial" width="40" height="28">
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
            <a ng-click="selectEmpresa()">
              <span class="icon">
                <i class="far fa-building"></i>
              </span>
            </a>
            <span>{{nombreEmpresa}}</span>
          </p>

          <p class="control">
            <span class="icon">
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
  <br><br>
  <div class="columns">
    <div class="column is-one-fifth">
      <aside class="menu" style="width:100%; height:890px; overflow:auto;">
<?php
  $modulotmp = '';
  $isFinModulo = false;
  foreach ($modproc as $key)
  {
    $modulo = $key['MODULO'];
    $ruta = $key['RUTA'];
    if($modulo != $modulotmp)
    {
      if($isFinModulo)
      {?>
        </ul>
<?php }?>
        <h2><?php echo $key['MODULO']?></h2>
        <ul class="menu-list">
          <li><a href="#!<?php echo $key['RUTA']?>"><?php echo $key['PROCESO']?></a></li>
<?php
      $isFinModulo = true;
    }else if($ruta !=null) {?>
          <li><a href="#!<?php echo $key['RUTA']?>"><?php echo $key['PROCESO']?></a></li>
<?php
    }
    $modulotmp = $modulo;?>
<?php
  } ?>
      </aside>
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
<script src="../js/facturar.js"></script>
<script src="../js/vendedores.js"></script>
<script>

/*var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    console.log(i);
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}

function openNav() {
  document.getElementById("mySidepanel").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidepanel").style.width = "0";
}*/
</script>
</body>
</html>
