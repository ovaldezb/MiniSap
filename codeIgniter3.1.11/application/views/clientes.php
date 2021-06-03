
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
					<a ng-click="borraCliente()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimna Cliente"></i></span></a></p>
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
        <div class="field has-addons">
          <p class="control is-expanded has-icons-left">
				    <textarea name="domicilio" id="domicilio" ng-model="domicilio" class="input is-small" placeholder="Domicilio" required></textarea>
          </p>
          <p class="control">
            <a class="button is-info is-small" ng-click="agregaDomEntrega()">
              <i class="fas fa-truck"></i>
            </a>
          </p>
        </div>
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
				<input maxlength="20" ng-model="rfc" onKeyUp="this.value = this.value.toUpperCase();" class="input is-small" type="input" placeholder="RFC" required>
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
        <div class="select is-small">
				<select name="id_tipo_cliente" id="id_tipo_cliente">
<?php	foreach ($tipo_cliente as $tc) { ?>
					<option value=<?php echo $tc['ID_TIPO_CLTE'] ?>><?php echo $tc['DESCRIPCION']?></option>
<?php	} ?>
				</select>
        </div>
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
      <div class="select is-small">
				<select name="revision" id="revision">
<?php 		foreach($revision as $rev) {?>
					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
<?php 		}?>
				</select>
        </div>
			</div>
			<div class="column is-narrow">
				<label class="label">Pagos</label>
			</div>
			<div class="column is-2">
        <div class="select is-small">
				<select id="pagos">
<?php 		foreach($revision as $rev) {?>
					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
<?php 		}?>
				</select>
        </div>
			</div>
		</div>
		<div class="columns">
			<div class="column is-narrow">
				<label class="label">Forma de Pago</label>
			</div>
			<div class="column is-2">
        <div class="select is-small">
          <select ng-model="id_forma_pago" ng-options="x.ID_FORMA_PAGO as x.CLAVE+' '+x.DESCRIPCION for x in lstFormpago"></select>
        </div>
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
			<div class="column is-4">
        <div class="select is-small">
        <select ng-model="idVendedor" ng-options="x.ID_VENDEDOR as x.NOMBRE for x in lstVendedor" ng-style="vendedor_style" ng-change="selectVendedor()"></select>	
        </div>
			</div>
		</div>
		<div class="columns">
			<div class="column is-narrow">
				<label class="label">Uso del CFDI</label>
			</div>
			<div class="column is-4">
        <div class="select is-small">
        <select ng-model="id_uso_cfdi" ng-options="x.ID_CFDI as x.CLAVE +' '+x.DESCRIPCION for x in lstUsoCFDI" ng-style="cfdi_style" ng-change="selectCFDI()"></select>
        </div>
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
						<col width="34%">
						<col width="26%">
						<col width="20%">
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
					<div style="width:100%; height:450px; overflow:auto;">
					<table class="table is-bordered is-hoverable" style="width:100%" id="tblClientes">
						<col width="15%">
						<col width="34%">
						<col width="26%">
						<col width="20%">
						<tr ng-repeat="x in lstCliente | orderBy:myOrder:sortDir" ng-click="selectRowCliente(x.CLAVE,$index,x.ID_CLIENTE)" ng-dblclick="muestraDetalle()" ng-class="{selected: x.CLAVE === idSelCompra}">
							<td class="font12" style="text-align:center">{{x.CLAVE}}</td>
							<td class="font12" style="text-align:left">{{x.NOMBRE}}</td>
							<td class="font12" style="text-align:center">{{x.RFC}}</td>
							<td class="font12" style="text-align:right">{{x.SALDO | currency}}</td>
						</tr>
					</table>
				</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="{{modalDetalleClte ? 'modal is-active' : 'modal' }}">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Detalle del Cliente: {{cliente}}</p>
	      <button class="delete" aria-label="close" ng-click="cerrarBorraCliente()"></button>
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
            <th style="text-align:center">Cobro</th>
            <th style="text-align:center">Saldo</th>
          </tr>
          </thead>
        </table>
        <div style="height:200px;overflow:auto;margin-top:-25px">
          <table style="width:100%;" class="table is-bordered">
            <colgroup>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
              <col width="20%"/>
            </colgroup>
            <tr ng-repeat="x in lstFactDtl">
              <td style="text-align:center">{{x.DOCUMENTO}}</td>
              <td>{{x.FECHA_FACTURA}}</td>
              <td style="text-align:right">{{x.IMPORTE | currency}}</td>
              <td style="text-align:right">{{x.COBRO | currency}}</td>
              <td style="text-align:right">{{x.SALDO | currency}}</td>
            </tr>
            <tr>
              <td colspan="2" style="text-align:right">Total:</td>
              <td style="text-align:right">{{totalImporFact | currency}}</td>
              <td style="text-align:right">{{totalImporCobr | currency}}</td>
              <td style="text-align:right">{{totalSaldo | currency}}</td>
            </tr>
          </table>
        </div>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button" ng-click="cerrarBorraCliente()">Cerrar</button>
	    </footer>
	  </div>
	</div>

  <div class="modal is-active" ng-show="addDomEntrega">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Domicilios de Entrega</p>
	      <button class="delete" aria-label="close" ng-click="clseDomEntrega()"></button>
	    </header>
	    <section class="modal-card-body">
	      <table style="width:70%" border="1">
          <colgroup>
            <col width="90%" />
            <col width="10%" />
          </colgroup>
          <tr>
            <td>
              <table class="table" style="width:100%">
                <tbody>
                  <tr class="tbl-header">
                    <td>#</td>
                    <td>Lugar de Entrega</td>
                  </tr>
                </tbody>
              </table>
              <div style="width:100%;overflow:auto;height:100px;margin-top:-24px">
                <table class="table" style="width:100%">
                  <tbody>
                    <tr ng-repeat="x in lstDomicilios" ng-click="selectRowDom($index)" ng-class="{selected: $index === idxRowDom}">
                      <td>{{$index+1}}</td>
                      <td>{{x.LUGAR}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </td>
            <td>
              <table class="table">
                <tr>
                  <td>
                    <a ng-click="agregaDom()">
                      <span class="icon has-text-success">
                        <i class="fas fa-plus-square" title="Agrega un nuevo Domicilio"></i>
                      </span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>
                    <a ng-click="eliminaDom()">
                      <span class="icon has-text-success">
                        <i class="fas fa-trash-alt" title="Elimina un Domicilio"></i>
                      </span>
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <form name="frmDomicilio">
              <table class="table" style="width:100%">
                <colgroup>
                  <col width="25%" />
                  <col width="25%" />
                  <col width="25%" />
                  <col width="25%" />
                </colgroup>
                <tbody>
                  <tr>
                    <td>Lugar</td>
                    <td colspan="3">
                      <input type="text" class="input is-small" ng-model="domicilios.LUGAR" ng-disabled="cltedsbl" required>
                    </td>
                  </tr>
                  <tr>
                    <td>Calle</td>
                    <td colspan="3">
                      <input type="text" class="input is-small" ng-model="domicilios.CALLE" ng-disabled="cltedsbl" required></td>
                  </tr>
                  <tr>
                    <td>Colonia</td>
                    <td colspan="3">
                      <input type="text" class="input is-small" ng-model="domicilios.COLONIA" ng-disabled="cltedsbl" required></td>
                  </tr>
                  <tr>
                    <td>CP</td>
                    <td>
                      <input type="number" class="input is-small" ng-model="domicilios.CP" maxlength="5" minlength="5"  ng-disabled="cltedsbl" required>
                    </td>
                    <td colspan="2">&nbsp;</td>
                  </tr>
                  <tr>
                    <td>Ciudad</td>
                    <td colspan="3">
                      <input type="text" class="input is-small" ng-model="domicilios.CIUDAD" ng-disabled="cltedsbl" required></td>
                  </tr>
                  <tr>
                    <td>Latitud</td>
                    <td><input type="text" class="input is-small" ng-model="domicilios.LATITUD" ng-disabled="cltedsbl"></td>
                    <td>Longitud</td>
                    <td><input type="text" class="input is-small" ng-model="domicilios.LONGITUD" ng-disabled="cltedsbl"></td>
                  </tr>
                  <tr>
                    <td>Contacto</td>
                    <td colspan="3"><input type="text" class="input is-small" ng-model="domicilios.CONTACTO" ng-disabled="cltedsbl"></td>
                  </tr>
                  <tr>
                    <td colspan="2" style="text-align:right"><button class="button is-info" ng-disabled="cltedsbl || frmDomicilio.$invalid" ng-click="guardaDom()">{{btnName}}</button></td>
                    <td colspan="2" style="text-align:right"><button class="button is-danger" ng-disabled="cltedsbl" ng-click="limpiaDom()">Limpiar</button></td>
                  </tr>
                </tbody>
              </table>
              </form>
            </td>
          </tr>
        </table>
        
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-warning" ng-click="clseDomEntrega()">{{btnClose}}</button>
	    </footer>
	  </div>
	</div>

</div>
