<br><br>

<div class="container" ng-controller="myCtrlVendedores" data-ng-init="init()">
	<div class="notification">
  <h1 class="title has-text-centered">Administración de Vendedores</h1>
</div>

	<div class="box" id="barranavegacion" ng-show="!isAddOpen">
		<nav class="level">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-5">
	        	<strong>Filtro:</strong>
	      	</p>
				</div>
				<div class="level-item">
					<input name="filtrovendedor" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tblVendedores');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
				</div>
			</div>
			<div class="level-right">
				<p class="level-item" ng-show="permisos.alta">
					<a ng-click="agregaVendedor();"><span class="icon has-text-success"><i class="fas fa-plus-square" title="Agrega Vendedor"></i></span></a></p>
				<p class="level-item" ng-show="permisos.modificacion">
					<a ng-click="editaVendedor()"><span class="icon has-text-info"><i class="fas fa-edit" title="Edita Vendedor"></i></span></a></p>
				<p class="level-item" ng-show="permisos.baja">
					<a ng-click="preguntaElimnaVendedor()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Vendedor"></i></span></a></p>
			</div>
		</nav>
	</div>
	<div class="box"  style="display:{{isAddOpen ? 'block' : 'none'}}">
		<form name="myForm">
		<div class="columns">
				<div class="column is-5">
					<input ng-model="nombre" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="text" placeholder="Nombre" required>
				</div>
		</div>
		
		<div class="field is-grouped">
				<div class="control" id="submit">
						<button id="add" class="button is-info" ng-click="addVendedor();" ng-disabled="myForm.$invalid">{{msjBoton}}</button>
				</div>
				<div class="control" id="cancelar">
						<button id="cancel" class="button is-danger" ng-click="cancelVendedor();">Cancelar</button>
				</div>
		</div>
	</form>
	</div>
	<div class="table-container is-centered" id="lstvendedores" ng-show="!isAddOpen">
		<table border="1" style="width:100%">
			<tr>
				<td>
					<table style="width:100%">
						<col width="26%">
						<col width="29%">
						<thead>
							<tr style="background:LightSlateGray;">
								<td align="center" style="color:white" ng-click="orderByMe('ID_VENDEDOR')">ID VENDEDOR</td>
								<td align="center" style="color:white" ng-click="orderByMe('NOMBRE')">NOMBRE</td>
							</tr>
						</thead>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:500px; overflow:auto;">
					<table class="table is-hoverable" style="width:100%" id="tblVendedores">
						<col width="26%">
						<col width="29%">
						<tr ng-repeat="x in lstVendedor | orderBy:myOrder:sortDir" ng-click="selectRowVendedor($index,x.ID_VENDEDOR)" ng-class="{selected: x.ID_VENDEDOR === idVendedor}">
							<td align="center">{{x.ID_VENDEDOR}}</td>
							<td align="center">{{x.NOMBRE}}</td>
						</tr>
					</table>
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="{{modalBorraVend ? 'modal is-active' : 'modal' }}">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Advertencia</p>
	      <button class="delete" aria-label="close" ng-click="cerrarBorraVendedor()"></button>
	    </header>
	    <section class="modal-card-body">
	      ¿Está seguro que desea eliminar al vendedor <b>{{vendBorrar}}</b>?
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-success" ng-click="borraVendedor()">Si</button>
	      <button class="button" ng-click="cerrarBorraVendedor()">No</button>
	    </footer>
	  </div>
	</div>
</div>
