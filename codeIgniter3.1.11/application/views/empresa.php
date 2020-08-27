<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$size1 = 50;
?>
<div class="container" ng-controller="myCtrlEmpresa" data-ng-init="init()">
	<div class="notification" >
		<h1 class="title has-text-centered">Administración de Empresas</h1>
	</div>
	<nav class="level">
		<div class="level-left">
			<div class="level-item">
				<p class="subtitle is-5">
        			<strong>Filtro:</strong>
      			</p>
			</div>
			<div class="level-item">
				<input name="producto" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablaempresas');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
			</div>
		</div>
		<div class="level-right">
	    	<p class="level-item">
				<a ng-click="openDivAgregar()">
					<span class="icon has-text-success">
						<i title="Agregar una nueva empresa" class="fas fa-plus-square" ></i>
					</span>
				</a>
			</p>
			<p class="level-item">
				<a ng-click="update()">
					<span class="icon has-text-info">
						<i title="Editar una empresa" class="fas fa-edit" ></i>
					</span>
				</a>
			</p>
			<p class="level-item">
				<a ng-click="preguntaEliminar()">
					<span class="icon has-text-danger">
						<i title="Elimnar una empresa" class="far fa-trash-alt"></i>
					</span>
				</a>
			</p>
  		</div>
	</nav>
	<div class="box" ng-show="isDivEmpActivo">
		<form name="myForm">
		<div class="box">
			<div class="columns">
				<div class="column is-1">
					<label class="label">Nombre</label>
				</div>
				<div class="column is-6">
					<div class="control has-icons-left has-icons-right">
						<input ng-model="emp.nombre" class="input is-small" type="text" placeholder="Nombre de la empresa" required>
						<span class="icon is-small is-left">
						  <i class="far fa-building"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">Domicilio</label>
				</div>
				<div class="column is-6">
					<div class="control has-icons-left has-icons-right">
						<input ng-model="emp.domicilio" class="input is-small" placeholder="Domicilio" required>
						<span class="icon is-small is-left">
						  <i class="fas fa-home"></i>
						</span>
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">RFC</label>
				</div>
				<div class="column is-2">
					<div class="control has-icons-left">
						<input ng-model="emp.rfc" class="input is-small" type="input" placeholder="RFC" maxlength="20" required>
						<span class="icon is-small is-left">
						  <i class="fas fa-id-card-alt"></i>
						</span>
					</div>
				</div>
				<div class="column is-narrow">
				<label class="label">CP</label>
				</div>
				<div class="column is-narrow" style="width:146px;">
					<input ng-model="emp.cp" class="input is-small" type="input" placeholder="CP" maxlength="5" required>
				</div>
				<div class="column is-narrow">
					<label class="label">Ejercicio Fiscal</label>
				</div>
				<div class="column is-1">
					<input id="ef" ng-model="emp.ejercicio_fiscal" ng-keyup="validaEF()" class="input is-small" type="number" maxlength="4" required>
				</div>
			</div>
			<div class="columns">
				<div class="column is-1">
					<label class="label">Régimen</label>
				</div>
				<div class="column is-4">
					<div class="control">
						<div class="select is-small">
							<select id="regimen">
<?php 			foreach($regimenes as $regimen)
						{?>
							<option value='<?php echo $regimen['ID_REGIMEN']?>'><?php echo $regimen['CLAVE']?> <?php echo $regimen['DESCRIPCION']?></option>
<?php 			}?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>							
		<div class="box">
			<h4 class="title is-5 has-text-centered">Registro Contable</h4>
			<div class="columns ">
				<div class="column is-narrow">
					<label class="label">Dígitos por Cuenta</label>
				</div>
				<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
					<input id="dig1" ng-model="emp.dig1" class="input is-small" type="input" maxlength="1">
				</div>
				<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
					<input id="dig2" ng-model="emp.dig2" class="input is-small" type="input" maxlength="1">
				</div>
				<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
					<input id="dig3" ng-model="emp.dig3" class="input is-small" type="input" maxlength="1">
				</div>
				<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
					<input id="dig4" ng-model="emp.dig4" class="input is-small" type="input" maxlength="1">
				</div>
			</div>
			<div class="columns">
				<div class="column is-narrow">
					<label class="label">Cuenta para resultado del Ejercicio</label>
				</div>
				<div class="column is-1">
					<input id="cuenta_resultado" ng-model="emp.cuenta_resultado" ng-keyup="validaCR()" class="input is-small" type="input" maxlength="4">
				</div>
			</div>
			<div class="columns">
				<div class="column is-narrow">
					<label class="label">Resultado de Ejercicios Anteriores</label>
				</div>
				<div class="column is-1">
					<input id="resultado_anterior" ng-model="emp.resultado_anterior" ng-keyup="validaRA()" class="input is-small" type="input" maxlength="4">
				</div>
			</div>

			<div class="field is-grouped">
				<div class="control">
					<button id="add" class="button is-link" ng-click="submitForm();" ng-disabled="myForm.$invalid">{{actnBton}}</button>
				</div>
				<div class="control">
					<button  class="button is-ligth" ng-click="cancelar();">Cancelar</button>
				</div>
			</div>
		</div>
		</form>
	</div>
	<div class="container" style="border: 2px solid black" ng-show="!isDivEmpActivo">
		<table style="width:100%">
			<tr>
				<td>
					<table class="table" style="width:100%">
						<tr style="background-color:CornflowerBlue; color:Ivory;">
							<td>#</td>
							<td ng-click="orderByMe('NOMBRE')">NOMBRE</td>
							<td ng-click="orderByMe('RFC')">RFC</td>
							<td>CP</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:590px; overflow:auto;">
						<table id="tablaempresas" class="table is-hoverable" style="width:100%">
							<tr ng-repeat="x in lstEmpresas | orderBy:myOrderBy:sortDir" ng-dblclick="update()" ng-click="selectRowEmpresa(x.RFC,$index,x.ID_EMPRESA)" ng-class="{selected: x.RFC === idSelEmp}">
								<td>{{$index+1}}</td>
								<td>{{x.NOMBRE}}</td>
								<td>{{x.RFC}}</td>
								<td>{{x.CP}}</td>
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
	      Está seguro que desea eliminar esta empresa <b>{{descEmpBorrar}}</b>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-success" ng-click="eliminar()">Si</button>
	      <button class="button" ng-click="closeAvisoBorrar();">No</button>
	    </footer>
	  </div>
	</div>
</div>
