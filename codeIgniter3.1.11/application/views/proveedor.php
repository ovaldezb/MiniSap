<div class="container" ng-controller="myCtrlProveedor" data-ng-init="init()">
	<div class="notification" >
	<h1 class="title has-text-centered is-4">Administración de Proveedores</h1>
	</div>
	<div class="box" ng-show="!isDivProvActivo">
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
			<p class="level-item" ng-show="permisos.alta">
				<a ng-click="openDivAgregar()"><span class="icon has-text-success"><i title="Agrega un nuevo Proveedor" class="fas fa-plus-square" ></i></span></a></p>
			<p class="level-item" ng-show="permisos.modificacion">
				<a ng-click="update()"><span class="icon has-text-info"><i title="Edita un Proveedor" class="fas fa-edit" ></i></span></a></p>
			<p class="level-item">
				<a ng-click="eliminar()" ng-show="permisos.baja">
					<span class="icon has-text-danger">
						<i title="Elimna un Proveedor" class="far fa-trash-alt"></i>
					</span>
				</a>
			</p>
		</div>
	</nav>
</div>
	<div class="box" style="display:{{isDivProvActivo ? 'block':'none'}}">
		<form name="myForm">
		<div class="columns">
			<div class="column is-1">
				<input id="clave" ng-model="clave" class="input is-small" type="text" placeholder="CLAVE" required>
			</div>
			<div class="column is-4">
				<input id="nombre" ng-model="nombre" class="input is-small" type="text" placeholder="NOMBRE DE LA EMPRESA" onKeyUp="this.value=this.value.toUpperCase()" required>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Domicilio:</label>
			</div>
			<div class="column is-4">
				<textarea type="text" ng-model="domicilio" name="domicilio" class="input is-small"  placeholder="DOMICILIO" ng-required="true"></textarea>
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">CP:</label>
			</div>
			<div class="column is-1">
				<input id="cp" ng-model="cp" class="input is-small" value="" type="text" placeholder="CP" maxlength="5" required>
			</div>
			<div class="column is-narrow" style="width:100px">
			</div>
			<div class="column is-narrow" style="width:75px">
				<label class="label">Teléfono:</label>
			</div>
			<div class="column is-narrow" style="width:110px">
				<input id="telefono" ng-model="telefono" class="input is-small" type="text" placeholder="TELEFONO" maxlength="10" required>
			</div>
		</div>
		<div class="columns">
			<div class="column is-narrow" style="width:95px">
				<label class="label">Contacto:</label>
			</div>
			<div class="column is-4">
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
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">CURP:</label>
			</div>
			<div class="column is-2">
				<input id="curp" ng-model="curp" class="input is-small" type="text" placeholder="CURP" maxlength="20" onKeyUp="this.value=this.value.toUpperCase()">
			</div>
		</div>
		<div class="columns">
			<div class="column is-1">
				<label class="label">Proveedor:</label>
			</div>
			<div class="column is-narrow" style="width:175px">
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
			<div class="column is-narrow" style="width:150px">
				<label class="label">Días de crédito:</label>
			</div>
			<div class="column is-1" style="margin-left:-15px;">
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
			<div class="column is-narrow" style="width:190px">
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
			<div class="column is-narrow" style="width:70px">
				<label class="label">Cuenta:</label>
			</div>
			<div class="column is-narrow" style="width:150px">
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
		<div class="columns">
			<div class="column is-5">
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

	<div style="border: 2px solid black;width:80%;margin:0 auto;" ng-show="!isDivProvActivo">
		<table style="width:100%">
			<tr>
				<td>
					<table style="width:100%" border="1">
            <colgroup>
              <col width="10%">
						  <col width="45%">
						  <col width="25%">
              <col width="20%">
            </colgroup>
						<tr style="background-color:CornflowerBlue; color:Ivory;">
							<td ng-click="orderByMe('CLAVE')" style="text-align:left">CLAVE</td>
							<td ng-click="orderByMe('NOMBRE')" style="text-align:center">NOMBRE</td>
							<td ng-click="orderByMe('RFC')" style="text-align:center">RFC</td>
              <td ng-click="orderByMe('RFC')" style="text-align:center">SALDO</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:450px; overflow:auto;">
						<table id="tablaprovedores" class="table is-hoverable" style="width:100%">
              <colgroup>
							  <col width="10%">
							  <col width="45%">
							  <col width="25%">
                <col width="20%">
              </colgroup>
							<tr ng-repeat="x in lstProveedor" ng-click="selectRowProveedor($index,x.ID_PROVEEDOR)" ng-dblclick="muestraDetalle()" ng-class="{selected: x.ID_PROVEEDOR === idProveedor}">
								<td class="font12">{{x.CLAVE}}</td>
								<td class="font12">{{x.NOMBRE.trim()}}</td>
								<td class="font12" style="text-align:center">{{x.RFC}}</td>
                <td class="font12" style="text-align:right">{{x.SALDO | currency}}</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="{{modalDetalleProv ? 'modal is-active' : 'modal'}}" id="avisoborrar">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Detalle del Proveedor: <span class="font12" style="text-align:left;">{{proveedor}}</span></p>
	      <button class="delete" aria-label="close" ng-click="closeAvisoBorrar();"></button>
	    </header>
	    <section class="modal-card-body">
	      <table style="width:100%" class="table is-bordered">
          <colgroup>
          <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
          </colgroup>
          <thead>
            <tr style="background-color:#7ca9e8">
              <th style="text-align:center">Documento</th>
              <th style="text-align:center">Fecha</th>
              <th style="text-align:center">Importe</th>
              <th style="text-align:center">Pagos</th>
              <th style="text-align:center">Saldo</th>
            </tr>
          </thead>
        </table>
        <div style="height:200px;overflow:auto;margin-top:-25px">
          <table style="width:100%" class="table is-bordered">
            <colgroup>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
            </colgroup>
            <tbody>
              <tr ng-repeat="x in lstFactProv">
                <td>{{x.DOCUMENTO}}</td>
                <td>{{x.FECHA_COMPRA}}</td>
                <td style="text-align:right">{{x.IMPORTE | currency}}</td>
                <td style="text-align:right">{{x.PAGO | currency}}</td>
                <td style="text-align:right">{{x.SALDO | currency}}</td>
              </tr>
              <tr>
                <td style="text-align:right;" colspan="2">Total:</td>
                <td style="text-align:right">{{totalImporFact | currency}}</td>
                <td style="text-align:right">{{totalImporPago | currency}}</td>
                <td style="text-align:right">{{totalSaldoFact | currency}}</td>
              </tr>
            </tbody>
          </table>
        </div>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button" ng-click="closeAvisoBorrar();">Cerrar</button>
	    </footer>
	  </div>
	</div>
</div>
