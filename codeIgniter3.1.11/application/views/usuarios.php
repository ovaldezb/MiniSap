<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="<?php echo base_url(); ?>js/utilerias.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-3.4.1.slim.js"></script>
  <script src="<?php echo base_url(); ?>js/usuarios.js"></script>
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/utils.css">
</head>
<body onload="addListener()">
  <div class="container" ng-app="myApp" ng-controller="myCtrlUsuarios" data-ng-init="init()">
    <div class="notification">
			<h1 class="title is-2 has-text-centered">Gestión de Usuarios</h1>
		</div>
    <div class="box" id="barranavegacion">
      <nav class="level">
				<div class="level-left">
					<p class="level-item"><span class="icon has-text-success"><i class="far fa-file" onclick="agregarUsuario();" title="Nuevo Usuario"></i></span></p>
					<p class="level-item"><a ng-click="preguntaelimcomp()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Eliminar Usuarios"></i></span></a></p>
					<p class="level-item"><a ng-click="despliegaCompra()"><span class="icon has-text-info"><i class="fas fa-folder-open" title="Visualizar Usuarios"></i></span></a></p>
				</div>
			</nav>
    </div>
    <div id="maindisplay" class="box">
      <div class="columns">
        <div class="column modulos " title="Aquí se listan los módulos disponibles">
          <p><label><input type="radio" name="modulos" value="T">&nbsp;Todos<label></p>
        </div>
        <div class="column">
          <div class="columns">
            <div class="column usuarios">
              <table style="width:100%" border="1">
                <tr>
                  <td>
                    <table style="width:100%">
                      <tr>
                        <td>Clave</td>
                        <td>Nombre</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:100%; height:100px; overflow:auto;">
                      <table style="width:100%">
                        <tr>
                          <td></td>
                          <td></td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
          <div class="columns">
            <div class="column permisos">
              <table style="width:100%" border="1">
                <tr>
                  <td>
                    <table style="width:100%">
                      <col width="50%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <tr>
                        <td>Proceso</td>
                        <td align="center">P</td>
                        <td align="center">A</td>
                        <td align="center">B</td>
                        <td align="center">M</td>
                        <td align="center">C</td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div style="width:100%; height:100px; overflow:auto;">
                      <table style="width:100%;" border="1" id="permisos">
                        <col width="50%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <col width="10%">
                        <tr>
                          <td>Punto de Venta1</td>
                          <td align="center" style="vertical-align:middle; background-color:red;" ></td>
                          <td align="center" style="vertical-align:middle"></td>
                          <td align="center" style="vertical-align:middle"><input type="checkbox"></td>
                          <td align="center" style="vertical-align:middle"><input type="checkbox"></td>
                          <td align="center" style="vertical-align:middle"><input type="checkbox"></td>
                        </tr>
                      </table>
                    </div>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</head>
