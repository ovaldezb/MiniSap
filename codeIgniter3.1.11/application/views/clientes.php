
<div class="container" ng-controller="myCtrlClientes" data-ng-init="init()">
	<div class="notification">
  <h1 class="title has-text-centered">Administración de Clientes</h1>
</div>
	<div class="box" id="barranavegacion" ng-show="!isAddOpen">
		<nav class="level">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-5"><strong>Filtro:</strong></p>
				</div>
				<div class="level-item">
					<input name="filtrocliente" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tblClientes');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
				</div>
			</div>
			<div class="level-right">
				<p class="level-item" ng-show="permisos.alta">
					<a ng-click="agregaCliente();"><span class="icon has-text-success"><i class="fas fa-plus-square" title="Agrega Cliente"></i></span></a></p>
				<p class="level-item" ng-show="permisos.modificacion">
					<a ng-click="editaCliente()"><span class="icon has-text-info"><i class="fas fa-edit" title="Edita Cliente"></i></span></a></p>
				<p class="level-item" ng-show="permisos.baja">
					<a ng-click="preguntaElimnaCliente()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimna Cliente"></i></span></a></p>
			</div>
		</nav>
	</div>
	<div class="box"  style="display:{{isAddOpen ? 'block' : 'none'}}">
		<form name="myForm">
		<div class="columns">
				<div class="column is-1">
					<input id="clave" name="clave" ng-model="clave" class="input is-small" type="text" placeholder="Clave" required>
				</div>
				<div class="column is-4">
					<input ng-model="nombre" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="text" placeholder="Nombre" required>
				</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Domicilio</label>
			</div>
			<div class="column is-4">
				<textarea name="domicilio" id="domicilio" ng-model="domicilio" class="input is-small" placeholder="Domicilio" required></textarea>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">C.P.</label>
			</div>
			<div class="column is-narrow" style="width:114px">
				<input maxlength="5" class="input is-small" ng-model="cp" ng-keyup="validaCP()" onfocus="this.select()" type="input" placeholder="Código Postal" required>
			</div>
			<div class="column is-narrow" style="width:70px">
			</div>
			<div class="column is-narrow" style="width:80px">
				<label class="label">Teléfono</label>
			</div>
			<div class="column is-narrow" style="width:115px">
				<input ng-model="telefono" class="input is-small" type="input" placeholder="Teléfono" maxlength="10" required>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Contacto</label>
			</div>
			<div class="column is-2">
				<input ng-model="contacto" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="input" placeholder="Contacto">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">RFC</label>
			</div>
			<div class="column is-2">
				<input maxlength="20" ng-model="rfc" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="input" placeholder="RFC">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">CURP</label>
			</div>
			<div class="column is-2">
				<input maxlength="18" ng-model="curp" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="input" placeholder="CURP">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Cliente</label>
			</div>
			<div class="column is-narrow" style="width:205px">
				<select name="id_tipo_cliente" id="id_tipo_cliente">
<?php	foreach ($tipo_cliente as $tc) { ?>
					<option value=<?php echo $tc['ID_TIPO_CLTE'] ?>><?php echo $tc['DESCRIPCION']?></option>
<?php	} ?>
				</select>
			</div>
			<div class="column is-narrow">
				<label class="label">Días de Crédito</label>
			</div>
			<div class="column is-narrow" style="width:100px">
				<input  name="dcredito" ng-model="dcredito" class="input is-small" type="number" maxlength="3" placeholder="Crédito">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Revisión</label>
			</div>
			<div class="column is-narrow" style="width:257px">
				<select name="revision" id="revision">
<?php 		foreach($revision as $rev) {?>
					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
<?php 		}?>
				</select>
			</div>
			<div class="column is-narrow">
				<label class="label">Pagos</label>
			</div>
			<div class="column is-2">
				<select id="pagos">
<?php 		foreach($revision as $rev) {?>
					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
<?php 		}?>
				</select>
			</div>
		</div>
		<div class="columns">
			<div class="column is-narrow">
				<label class="label">Forma de Pago</label>
			</div>
			<div class="column is-2">
				<select name="id_forma_pago" id="id_forma_pago">
<?php foreach($forma_pago as $fp) {?>
					<option value="<?php echo $fp['ID_FORMA_PAGO']?>"><?php echo trim($fp['CLAVE'])?> <?php echo trim($fp['DESCRIPCION'])?></option>
<?php }?>
				</select>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Banco(s)</label>
			</div>
			<div class="column is-narrow">
				<button class="button is-small">Cuentas</button>
			</div>
			<div class="column is-3">
				<label class="label">Datos necesarios para transferencias crédito o PPD con CFDI 3.3</label>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Vendedor</label>
			</div>
			<div class="column is-2">
				<select name="id_vendedor" id="id_vendedor">
<?php	foreach($vendedor as $vend) {?>
					<option value='<?php echo $vend['ID_VENDEDOR']?>'><?php echo trim($vend['NOMBRE'])?></option>
<?php	}?>
				</select>
			</div>
		</div>
		<div class="columns">
			<div class="column is-narrow">
				<label class="label">Uso del CFDI</label>
			</div>
			<div class="column is-2">
				<select name="id_uso_cfdi" id="id_uso_cfdi">
<?php 		foreach($uso_cfdi as $ucfdi) {?>
					<option value='<?php echo $ucfdi['ID_CFDI']?>'><?php echo trim($ucfdi['CLAVE'])?> <?php echo trim($ucfdi['DESCRIPCION'])?></option>
<?php 		}?>
				</select>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Email</label>
			</div>
			<div class="column is-2">
				<input id="email" name="email" ng-model="email" class="input is-small" type="input" placeholder="Email">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Observaciones</label>
			</div>
		</div>
		<div class="columns">
			<div class="column is-8">
				<textarea id="notas" ng-model="notas" name="notas" class="input"></textarea>
			</div>
		</div>
		<div class="field is-grouped">
				<div class="control" id="submit">
						<button id="add" class="button is-info" ng-click="addCliente();" ng-disabled="myForm.$invalid">{{msjBoton}}</button>
				</div>
				<div class="control" id="cancelar">
						<button id="cancel" class="button is-danger" ng-click="cancelCliente();">Cancelar</button>
				</div>
		</div>
	</form>
	</div>
	<div class="container" style="border:2px solid black; width:99%; margin:0 auto" id="lstclientes" ng-show="!isAddOpen">
		<table style="width:100%">
			<tr>
				<td>
					<table class="table is-bordered" style="width:100%">
						<col width="15%">
						<col width="25%">
						<col width="26%">
						<col width="29%">
						<tr class="tbl-header">
							<td style="text-align:center" ng-click="orderByMe('CLAVE')">CLAVE</td>
							<td style="text-align:center" ng-click="orderByMe('NOMBRE')">NOMBRE</td>
							<td style="text-align:center" ng-click="orderByMe('RFC')">RFC</td>
							<td style="text-align:center" ng-click="orderByMe('SALDO')">SALDO</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:500px; overflow:auto;">
					<table class="table is-bordered is-hoverable" style="width:100%" id="tblClientes">
						<col width="15%">
						<col width="25%">
						<col width="26%">
						<col width="29%">
						<tr ng-repeat="x in lstCliente | orderBy:myOrder:sortDir" ng-click="selectRowCliente(x.CLAVE,$index,x.ID_CLIENTE)" ng-class="{selected: x.CLAVE === idSelCompra}">
							<td style="text-align:center">{{x.CLAVE}}</td>
							<td style="text-align:center">{{x.NOMBRE}}</td>
							<td style="text-align:center">{{x.RFC}}</td>
							<td style="text-align:center">{{x.SALDO | currency}}</td>
						</tr>
					</table>
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="{{modalBorraClte ? 'modal is-active' : 'modal' }}">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Advertencia</p>
	      <button class="delete" aria-label="close" ng-click="cerrarBorraCliente()"></button>
	    </header>
	    <section class="modal-card-body">
	      ¿Está seguro que desea eliminar al cliente <b>{{clteBorrar}}</b>?
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-success" ng-click="borraCliente()">Si</button>
	      <button class="button" ng-click="cerrarBorraCliente()">No</button>
	    </footer>
	  </div>
	</div>
</div>
