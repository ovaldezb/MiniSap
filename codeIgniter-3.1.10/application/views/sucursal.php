<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<html lang="es">
<head>
	<meta charset="utf-8">
	<title>ABC Productos</title>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-3.4.1.slim.js"></script>
	<script src="<?php echo base_url(); ?>js/sucursal.js"></script>
	<script src="<?php echo base_url(); ?>js/utilerias.js"></script>
  <style>
	.selected{background-color: #fd9;}
	input.ng-invalid {
    background-color:Snow;
	}
	input.ng-valid {
	    background-color:Aquamarine;
	}
	textarea.ng-valid {
    color: green;
    background-color: Aquamarine;
  }
  textarea.ng-invalid {
    background-color: Snow;
  }
	</style>
</head>

<body>
  <input type="hidden" id="idempresa" value="<?php echo $id_empresa ?>">
  <div class="container" ng-app="mySucursal" ng-controller="myCtrlSucursal" data-ng-init="init()">
    <div class="notification" >
		<h1 class="title has-text-centered">Administración de Sucursales</h1>
		</div>
		<nav class="level">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-5">
						<strong>Filtro:</strong>
					</p>
				</div>
				<div class="level-item">
					<input name="sucursal" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablaprovedores');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
				</div>
			</div>
			<div class="level-right">
				<p class="level-item">
					<a ng-click="openDivAgregar()">
						<span class="icon has-text-success">
							<i title="Agrega una nueva Sucursal" class="fas fa-plus-square" ></i>
						</span>
					</a>
				</p>
				<p class="level-item">
					<a ng-click="update()">
						<span class="icon has-text-info">
							<i title="Edita una Sucursal" class="fas fa-edit" ></i>
						</span>
					</a>
				</p>
				<p class="level-item">
					<a ng-click="preguntaEliminar()">
						<span class="icon has-text-danger">
							<i title="Elimna una Sucursal" class="far fa-trash-alt"></i>
						</span>
					</a>
				</p>
			</div>
		</nav>
    <div class="box" style="display:{{isDivSucActivo ? 'block':'none'}}" >
      <form name="myForm">
        <div class="columns">
          <div class="column is-1">
            <label class="label">Clave:</label>
          </div>
          <div class="column is-1">
            <input type="text" name="clave" ng-model="clave" class="input is-small" required>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-1">
            <label class="label">Dirección:</label>
          </div>
          <div class="column is-4">
            <textarea class="textarea" ng-model="direccion" name="direccion" required></textarea>
          </div>
        </div>
        <div class="columns">
          <div class="column is-1">
            <label class="label">CP:</label>
          </div>
          <div class="column is-2">
            <input type="text" name="cp" ng-model="cp" class="input is-small" required maxlength="5">
          </div>
        </div>
        <div class="columns">
          <div class="column is-narrow" style="width:100px">
            <label class="label">Responsable:</label>
          </div>
          <div class="column is-2">
            <input type="text" name="responsable" ng-model="responsable" class="input is-small" required>
          </div>
        </div>
        <div class="columns">
          <div class="column is-1">
            <label class="label">Teléfono:</label>
          </div>
          <div class="column is-2">
            <input type="text" name="telefono" ng-model="telefono" class="input is-small" required maxlength="10">
          </div>
        </div>
        <div class="columns">
          <div class="column is-1">
            <label class="label">Alias:</label>
          </div>
          <div class="column is-2">
            <input type="text" name="alias" ng-model="alias" class="input is-small" required>
          </div>
        </div>
        <div class="columns">
          <div class="column is-1">
            <label class="label">Comentarios:</label>
          </div>
        </div>
        <div class="columns is-gapless is-multiline is-mobile">
          <div class="column is-5">
            <textarea class="textarea" name="notas" ng-model="notas"></textarea>
          </div>
        </div>
        <div class="field is-grouped">
  			  <p class="control">
  				<button  ng-click="submitForm();" class="button is-primary" ng-disabled="myForm.$invalid">{{btnAccion}}</button>
  			  </p>
  			  <p class="control">
  				<button type="button" ng-click="cancelar()" class="button is-light">Cancelar</button>
  			  </p>
  			</div>
      </form>
    </div>
    <div id="message"></div>
    <div class="table-container" style="border: 2px solid black">
      <table style="width:100%">
        <tr>
          <td>
            <table style="width:100%">
              <col width="20%">
              <col width="40%">
              <col width="30%">
              <col width="10%">
              <tr style="background-color:CornflowerBlue; color:Ivory;">
                <td align="center"><a ng-click="orderByMe('CLAVE')">CLAVE</a></td>
                <td align="center"><a ng-click="orderByMe('DIRECCION')">DIRECCIÓN</a></td>
                <td align="center"><a ng-click="orderByMe('RESPONSABLE')">RESPONSABLE</a></td>
                <td align="center"><a ng-click="orderByMe('CP')">CP</a></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <div style="width:100%; height:590px; overflow:auto;">
              <table class="table is-hoverable" style="width:100%">
                <col width="20%">
                <col width="40%">
                <col width="30%">
                <col width="10%">
                <tr ng-repeat="x in lstSucursal | orderBy:myOrderBy:sortDir" ng-click="selectRowSucursal(x.CLAVE,$index,x.ID_SUCURSAL)" ng-class="{selected: x.CLAVE === idSelSuc}">
                  <td align="center">{{x.CLAVE.trim()}}</td>
                  <td align="center">{{x.DIRECCION}}</td>
                  <td align="center">{{x.RESPONSABLE.trim()}}</td>
                  <td align="center">{{x.CP}}</td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div class="{{isAvsoBrrarActv ? 'modal is-active' : 'modal'}}" >
		  <div class="modal-background"></div>
		  <div class="modal-card">
		    <header class="modal-card-head">
		      <p class="modal-card-title">Advertencia</p>
		      <button class="delete" aria-label="close" ng-click="closeAvisoBorrar();"></button>
		    </header>
		    <section class="modal-card-body">
		      Está seguro que desea eliminar la Sucursal de <b>{{descSucBorrar}}</b>
		    </section>
		    <footer class="modal-card-foot">
		      <button class="button is-success" ng-click="eliminar()">Si</button>
		      <button class="button" ng-click="closeAvisoBorrar();">No</button>
		    </footer>
		  </div>
		</div>
  </div>
</body>
</html>
