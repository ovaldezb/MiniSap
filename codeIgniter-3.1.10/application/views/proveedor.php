<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>ABC Proveedores</title>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.6.2/css/bulma.min.css">
	<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="<?php echo base_url(); ?>js/proveedor.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-3.4.1.slim.js"></script>
	<script src="<?php echo base_url(); ?>js/utilerias.js"></script>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/utils.css">
</head>

<body>
	<input type="hidden" id="idempresa" value="<?php echo $id_empresa ?>">
	<div class="container" ng-app="myProveedor" ng-controller="myCtrlProveedor" data-ng-init="init()">
		<div class="notification" >
		<h1 class="title has-text-centered">Administración de Proveedores</h1>
		</div>
		<nav class="level">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-5">
						<strong>Filtro:</strong>
					</p>
				</div>
				<div class="level-item">
					<input name="producto" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablaprovedores');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
				</div>
			</div>
			<div class="level-right">
				<p class="level-item">
					<a ng-click="openDivAgregar()">
						<span class="icon has-text-success">
							<i title="Agrega un nuevo Proveedor" class="fas fa-plus-square" ></i>
						</span>
					</a>
				</p>
				<p class="level-item">
					<a ng-click="update()">
						<span class="icon has-text-info">
							<i title="Edita un Proveedor" class="fas fa-edit" ></i>
						</span>
					</a>
				</p>
				<p class="level-item">
					<a ng-click="preguntaEliminar()">
						<span class="icon has-text-danger">
							<i title="Elimna un Proveedor" class="far fa-trash-alt"></i>
						</span>
					</a>
				</p>
			</div>
		</nav>
		<div class="box" style="display:{{isDivProvActivo ? 'block':'none'}}">
			<form name="myForm">
			<div class="columns">
				<div class="column is-1">
					<input id="clave" ng-model="clave" class="input is-small" type="text" placeholder="CLAVE" required>
				</div>
				<div class="column is-5">
					<input id="nombre" ng-model="nombre" class="input is-small" type="text" placeholder="NOMBRE" onKeyUp="this.value=this.value.toUpperCase()" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">Domicilio:</label>
				</div>
				<div class="column is-6">
					<textarea type="text" ng-model="domicilio" name="domicilio" class="input is-small"  ng-required="true"></textarea>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">CP:</label>
				</div>
				<div class="column is-1">
					<input id="cp" ng-model="cp" class="input is-small" value="" type="text" placeholder="CP" maxlength="5" required>
				</div>
				<div class="column is-1">
					<label class="label">Teléfono:</label>
				</div>
				<div class="column is-4">
					<input id="telefono" ng-model="telefono" class="input is-small" type="text" placeholder="TELEFONO" maxlength="10" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-narrow">
					<label class="label">Contacto:</label>
				</div>
				<div class="column is-6">
					<input id="contacto" ng-model="contacto" class="input is-small" type="input" placeholder="Contacto" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">RFC:</label>
				</div>
				<div class="column is-2">
					 <input id="rfc" ng-model="rfc" class="input is-small" type="text" placeholder="RFC" maxlength="20" onKeyUp="this.value=this.value.toUpperCase()" required>
				</div>
				<div class="column is-1">
					<label class="label">CURP:</label>
				</div>
				<div class="column is-3">
					<input id="curp" ng-model="curp" class="input is-small" type="text" placeholder="CURP" maxlength="20" onKeyUp="this.value=this.value.toUpperCase()" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">Proveedor:</label>
				</div>
				<div class="column is-2">
					<div class="control">
						<div class="select is-small">
							<select name="id_tipo_prov" id="id_tipo_prov" required>
<?php foreach ($proveedores as $proveedor) { ?>
							<option value=<?php echo $proveedor['ID_TIPO_PROV'] ?>><?php echo $proveedor['DESCRIPCION']?></option>
<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="column is-narrow">
					<label class="label">Crédito: (días)</label>
				</div>
				<div class="column is-1">
					<input id="dias_cred" ng-model="dias_cred" type="number" class="input is-small" maxlength="4" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1"><label class="label">Tipo:</label></div>
				<div class="column is-4">
					<div class="control">
						<div class="select is-small">
							<select name="id_tipo_alc_prov" id="id_tipo_alc_prov">
<?php 	foreach ($alcanceprov as $ac) { ?>
							<option value=<?php echo $ac['ID_ALC_PROV'] ?>><?php echo $ac['DESCRIPCION']?></option>
<?php	} ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">Banco:</label>
				</div>
				<div class="column is-2">
					<div class="control">
						<div class="select is-small">
					        <select name="banco" id="banco">
<?php 	foreach ($bancos as $banco) { ?>
							<option value=<?php echo $banco['ID_BANCO'] ?>><?php echo $banco['DESCRIPCION']?></option>
<?php	} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="column is-1">
					<label class="label">Cuenta:</label>
				</div>
				<div class="column is-3">
					<input name="cuenta" ng-model="cuenta" type="number" class="input is-small" maxlength="20" placeholder="Cuenta" required>
				</div>
			</div>

			<div class="columns">
				<div class="column is-1">
					<label class="label">Email:</label>
				</div>
				<div class="column is-3">
					<input ng-model="email" name="email" class="input is-small" type="email" placeholder="Email" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">Observaciones:</label>
				</div>
			</div>
			<div class="columns is-gapless is-multiline is-mobile">
				<div class="column is-6">
					<textarea ng-model="notas" class="textarea"></textarea>
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

		<div style="border: 2px solid black">
			<table style="width:100%">
				<tr>
					<td>
						<table style="width:100%">
							<col width="30%">
							<col width="40%">
							<col width="30%">
							<tr style="background-color:CornflowerBlue; color:Ivory;">
								<td ng-click="orderByMe('CLAVE')" align="right">CLAVE</td>
								<td ng-click="orderByMe('NOMBRE')" align="center">NOMBRE</td>
								<td ng-click="orderByMe('RFC')" align="center">RFC</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:100%; height:590px; overflow:auto;">
							<table id="tablaprovedores" class="table is-hoverable" style="width:100%">
								<col width="20%">
								<col width="50%">
								<col width="30%">
								<tr ng-repeat="x in lstProveedor | orderBy:myOrderBy:sortDir" ng-click="selectRowProveedor(x.RFC,$index,x.ID_PROVEEDOR)" ng-class="{selected: x.RFC === idSelProv}">
									<td>{{x.CLAVE}}</td>
									<td>{{x.NOMBRE.trim()}}</td>
									<td>{{x.RFC}}</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</div>
		<div class="{{isAvsoBrrarActv ? 'modal is-active' : 'modal'}}" id="avisoborrar">
		  <div class="modal-background"></div>
		  <div class="modal-card">
		    <header class="modal-card-head">
		      <p class="modal-card-title">Advertencia</p>
		      <button class="delete" aria-label="close" ng-click="closeAvisoBorrar();"></button>
		    </header>
		    <section class="modal-card-body">
		      Está seguro que desea eliminar el proveedor <b>{{descProvBorrar}}</b>
		    </section>
		    <footer class="modal-card-foot">
		      <button class="button is-success" ng-click="eliminar()">Si</button>
		      <button class="button" ng-click="closeAvisoBorrar();">No</button>
		    </footer>
		  </div>
		</div>
	</div>
	<div id="message"></div>
</body>
</html>
