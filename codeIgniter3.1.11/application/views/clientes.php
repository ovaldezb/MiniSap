<br><br>
<input type="hidden" id="idempresa" value="<?php echo $id_empresa ?>">
<div class="container">
	<div class="notification" align="center">
  <h1 class="title is-2">Gestión de Clientes</h1>
</div>
</div>
<div class="container" ng-controller="myCtrlClientes" data-ng-init="init()">
	<div class="box" id="barranavegacion">
		<nav class="level">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-5">
	        	<strong>Filtro:</strong>
	      	</p>
				</div>
				<div class="level-item">
					<input name="filtrocliente" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tblClientes');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
				</div>
			</div>
			<div class="level-right">
				<p class="level-item"><a ng-click="agregaCliente();"><span class="icon has-text-success"><i class="far fa-file" title="Agrega Cliente"></i></span></a></p>
				<p class="level-item"><a ng-click="editaCliente()"><span class="icon has-text-info"><i class="fas fa-edit" title="Edita Cliente"></i></span></a></p>
				<p class="level-item"><a ng-click="preguntaElimnaCliente()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimna Cliente"></i></span></a></p>
			</div>
		</nav>
	</div>
	<div class="box"  style="display:{{isAddOpen ? 'block' : 'none'}}">
		<div class="columns">
				<div class="column is-1">
					<input id="clave" name="clave" ng-model="clave" class="input is-small" type="text" placeholder="Clave" required>
				</div>
				<div class="column is-4">
					<input id="nombre" name="nombre" ng-model="nombre" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="text" placeholder="Nombre" required>
				</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Domicilio</label>
			</div>
			<div class="column is-6">
				<input id="domicilio" name="domicilio" ng-model="domicilio" class="input is-small" type="textarea" placeholder="Domicilio" required>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">C.P.</label>
			</div>
			<div class="column is-1">
				<input maxlength="5" class="input is-small" ng-model="cp" ng-keyup="validaCP()" onfocus="this.select()" type="input" placeholder="Código Postal" required>
			</div>
			<div class="column is-1">
				<label class="label">Teléfono</label>
			</div>
			<div class="column is-2">
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
			<div class="column is-2">
				<select name="id_tipo_cliente" id="id_tipo_cliente">
<?php	foreach ($tipo_cliente as $tc) { ?>
					<option value=<?php echo $tc['ID_TIPO_CLTE'] ?>><?php echo $tc['DESCRIPCION']?></option>
<?php	} ?>
				</select>
			</div>
			<div class="column is-1">
				<label class="label">Crédito (días)</label>
			</div>
			<div class="column is-1">
				<input  name="dcredito" ng-model="dcredito" class="input is-small" type="number" placeholder="Crédito">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Revisión</label>
			</div>
			<div class="column is-2">
				<select name="revision" id="revision">
<?php 		foreach($revision as $rev) {?>
					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
<?php 		}?>
				</select>
			</div>
			<div class="column is-1">
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
			<div class="column is-1">
				<label class="label">Forma de Pago</label>
			</div>
			<div class="column is-2">
				<select name="id_forma_pago" id="id_forma_pago">
<?php foreach($forma_pago as $fp) {?>
					<option value='<?php echo $fp['ID_FORMA_PAG']?>'><?php echo trim($fp['CLAVE'])?> <?php echo trim($fp['DESCRIPCION'])?></option>
<?php }?>
				</select>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Banco(s)</label>
			</div>
			<div class="column is-2">
				<button class="button is-small">Cuentas</button>
			</div>
			<div class="column is-5">
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
			<div class="column is-1">
				<label class="label">USO del CFDI</label>
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
			<div class="column is-1">
				<label class="label">Número Proveedor</label>
			</div>
			<div class="column is-2">
				<input id="num_proveedor" ng-model="num_proveedor" name="num_proveedor" class="input is-small" type="input" placeholder="Número Proveedor">
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
						<button id="add" class="button is-info" ng-click="addCliente();">{{msjBoton}}</button>
				</div>
				<div class="control" id="cancelar">
						<button id="cancel" class="button is-danger" ng-click="cancelCliente();">Cancelar</button>
				</div>
		</div>
	</div>
	<div class="table-container is-centered" id="lstclientes">
		<table border="1" style="width:100%">
			<tr>
				<td>
					<table style="width:100%">
						<col width="15%">
						<col width="25%">
						<col width="26%">
						<col width="29%">
						<thead>
							<tr style="background:LightSlateGray;">
								<td align="center" style="color:white" ng-click="orderByMe('CLAVE')">CLAVE</td>
								<td align="center" style="color:white" ng-click="orderByMe('NOMBRE')">NOMBRE</td>
								<td align="center" style="color:white" ng-click="orderByMe('RFC')">RFC</td>
								<td align="center" style="color:white" ng-click="orderByMe('CURP')">CURP</td>
							</tr>
						</thead>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:500px; overflow:auto;">
					<table class="table is-hoverable" style="width:100%" id="tblClientes">
						<col width="15%">
						<col width="25%">
						<col width="26%">
						<col width="29%">
						<tr ng-repeat="x in lstCliente | orderBy:myOrder:sortDir" ng-click="selectRowCliente(x.CLAVE,$index,x.ID_CLIENTE)" ng-class="{selected: x.CLAVE === idSelCompra}">
							<td align="center">{{x.CLAVE}}</td>
							<td align="center">{{x.NOMBRE}}</td>
							<td align="center">{{x.RFC}}</td>
							<td align="center">{{x.CURP}}</td>
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
