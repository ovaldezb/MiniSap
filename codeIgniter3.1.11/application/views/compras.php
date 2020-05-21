<br><br>

<input type="hidden" id="updtTable" value="F">
<div class="container" ng-controller="myCtrlCompras" data-ng-init="init()">
	<div class="notification">
		<h1 class="title is-2 has-text-centered">Compra de Producto</h1>
	</div>
	<div class="box" id="barranavegacion">
		<nav class="level">
			<div class="level-left">
				<p class="level-item"><a ng-click="agregarcompra();"><span class="icon has-text-success"><i class="far fa-file" title="Nueva Compra"></i></span></a></p>
				<p class="level-item"><a ng-click="preguntaelimcomp()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Compra"></i></span></a></p>
				<p class="level-item"><a ng-click="despliegaCompra()"><span class="icon has-text-info"><i class="fas fa-folder-open" title="Visualiza Compra"></i></span></a></p>
				<p class="level-item"><span class="icon has-text-info"><i class="fas fa-print" title="Imprime Compras"></i></span></p>
			</div>
		</nav>
	</div>
	<div class="box" style="display:{{isAgrgaCompra ? 'block' : 'none'}}">
	<form name="myForm">
	<div class="columns">
		<div class="column">
			<div class="box">
				<div class="columns">
					<div class="column is-narrow" style="width:100px">
						<label class="label">Proveedor:<label>
					</div>
					<div class="column is-1">
						<input type="text" class="input is-small" ng-model="docprev">
					</div>
					<div class="column is-2">
						<input type="text" class="input is-small" ng-model="claveprov" ng-keyup="buscaprovbyclave($event)" placeholder="Clave del proveedor">
					</div>
					<div class="column is-6">
						<input type="text" class="input is-small"  ng-model="proveedor" ng-keyup="buscaprovbynombre($event)" placeholder="Nombre del proveedor">
					</div>
				</div>
				<div class="table-container" style="display:none" id="buscaprov">
					<table style="width:100%">
						<tr>
							<td>
								<table class="table" style="width:100%" border="1">
									<col width="50%">
									<col width="25%">
									<col width="25%">
									<tr>
										<th>Nombre/Razón Social</th>
										<th align="center">Clave</th>
										<th align="center">CP</th>
									</tr>
								</table>
							</td>
							<td>
								<a aria-label="like">
								<span class="icon has-text-danger">
									<i onclick="closeDivSearchProv()" class="fas fa-times-circle"></i>
								</span>
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<div style="width:100%; height:200px; overflow:auto;">
								<table class="table is-hoverable"  style="width:100%;" id="proveedores">
									<col width="60%">
									<col width="20%">
									<col width="20%">
									<tr ng-repeat="x in lstaprovee" ng-click="selectProvee($index)">
										<td>{{x.NOMBRE.trim()}}</td>
										<td>{{x.CLAVE.trim()}}</td>
										<td align="center">{{x.CP.trim()}}</td>
									</tr>
								</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="columns">
					<div class="column is-narrow" style="width:100px">
						<label class="label">Documento:<label>
					</div>
					<div class="column is-1">
						<input type="text" class="input is-small" ng-model="numdoc" required>
					</div>
					<div class="column is-narrow">
						<div class="control">
							<div class="select is-small">
								<select name="tipopago" id="tipopago" onchange="cambiotp()">
<?php 	foreach ($tipopago as $tp) { ?>
									<option value=<?php echo $tp['ID_TIPO_PAGO'] ?>><?php echo $tp['DESCRIPCION']?></option>
<?php	} ?>
								</select>
							</div>
						</div>
					</div>
					<div class="column is-narrow" style="width:90px">
						<input type="text" class="input is-small" id="diascred"  onkeyup="sumadias()" ng-model="diascred" disabled>
					</div>
					<div class="column is-narrow is-vleft">
						<label class="label">d&iacute;as</label>
					</div>
					<div class="column is-narrow">
						<label class="label">C.R.</label>
					</div>
					<div class="column is-narrow">
						<input type="text" class="input is-small" id="contrarecibo" ng-model="contrarecibo">
					</div>
					<div class="column is-1">
						<label class="label">Pago:</label>
					</div>
					<div class="column is-narrow" style="width:110px">
						<input type="text" class="input is-small" id="fechapago" value="">
					</div>
				</div>
				<div class="columns">
					<div class="column is-narrow" style="width:100px">
						<label class="label">Fecha:<label>
					</div>
					<div class="column is-2" style="width:110px">
						<input type="text" class="input is-small" id="fechacompra" value="" disabled>
					</div>
					<div class="column is-narrow">
						<div class="control">
							<div class="select is-small">
								<select name="tipo" id="moneda">
<?php 	foreach ($monedas as $moneda) { ?>
									<option value=<?php echo $moneda['ID_MONEDA'] ?>><?php echo $moneda['NOMBRE']?></option>
<?php	} ?>
								</select>
							</div>
						</div>
					</div>
					<div class="column is-narrow" style="width:100px">
						<input type="text" class="input is-small" id="tipocambio" value="" ng-model="tipocambio" ng-blur="validaTC()">
					</div>
					<div class="column is-narrow">
						<label class="label">$</label>
					</div>
					<div class="column is-1">
					</div>
					<div class="column is-narrow">
						<label class="label">Desc %</label>
					</div>
					<div class="column is-narrow" style="width:100px">
						<input type="number" class="input is-small" value="" ng-model="descuento" ng-keyup="validaDescto()" style="text-align:right;">
					</div>
					<div class="column is-narrow">
						<label class="label">Iva %</label>
					</div>
					<div class="column is-narrow" style="width:80px">
						<input type="number" class="input is-small" id="iva" ng-model="iva" value="" ng-blur="validaIva()" required style="text-align:right;">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box">
	<div class="columns" id="barraProducto">
		<div class="column is-narrow">
			<span class="icon has-text-success">
				<i class="fas fa-plus-square" onclick="habilitar(false,true);" title="Seleccione un nuevo producto"></i>
			</span>
			<a ng-click="borraproducto()">
			<span class="icon has-text-danger">
				<i  class="far fa-trash-alt" title="Elimina el producto seleccionado de la lista"></i>
			</span>
			</a>
			<a ng-click="editaProducto()">
			<span class="icon has-text-success">
				<i class="fas fa-edit" title="Edita el producto seleccionado de la lista"></i>
			</span>
			</a>
			<a ng-click="registrar();">
			<span class="icon has-text-success">
				<i class="fas fa-file-export" title="Registra los productos de la lista"></i>
			</span>
			</a>
		</div>
		<div class="column is-3">
		</div>
		<div class="column is-narrow">
		</div>
		<div class="column is-2">
		</div>
	</div>

	<div class="columns">
		<div class="column is-full">
			<table style="width:100%" class="table">
				<col width="10%">
				<col width="25%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="10%">
				<col width="15%">
				<col width="10%">
				<tr>
					<td align="center">Código</td>
					<td>Descripción</td>
					<td align="center">Cantidad</td>
					<td align="center">U/Medida</td>
					<td align="center">Precio</td>
					<td align="center">Descuento</td>
					<td align="center" rowspan="2">
						<figure class="media-left" style="display: none;" id="imgfig">
						<p class="image is-64x64px">
						<img ng-src="{{imagePath}}">
						</p>
						</figure>
					</td>
					<td rowspan="2" style="vertical-align:middle; text-align:right;">
						<a aria-label="like" ng-click="agregar()" title="Agrega el producto seleccionado">
							<span class="icon has-text-success">
								<i class="fas fa-plus-square"></i>
							</span>
						</a>
						<a aria-label="like" ng-click="eliminarPordSed()">
						<span class="icon has-text-danger">
							<i class="fas fa-minus-circle" title="Cancela el producto seleccionado"></i>
						</span>
						</a>
					</td>
				</tr>
				<tr>
					<td><input type="text" class="input is-small" id="codigo" ng-model="codigo" ng-keyup="buscaprodbycodigo($event)" disabled></td>
					<td><input type="text" class="input is-small" id="descripcion" ng-model="descripcion" ng-keyup="lanzaBusquedaProducto($event)" disabled></td>
					<td>
						<div class="field has-addons">
							<div class="control">
								<button class="button is-info is-small" ng-click="decrease()" id="mencant" disabled>-</button>
							</div>
							<div class="control">
								<input type="text" class="input is-small" ng-model="cantidad" id="cantidad" ng-keyup="manualenter()" disabled onfocus="this.select();" required>
							</div>
							<div class="control">
								<button class="button is-info is-small" ng-click="increase()" id="mascant" disabled>+</button>
							</div>
						</div>
					</td>
					<td><input type="text" class="input is-small" id="unidad" ng-model="unidad" style="text-align:right;" disabled></td>
					<td><input type="text" class="input is-small" id="precio" ng-model="precio" disabled style="text-align:right;" required></td>
					<td><input type="text" class="input is-small" id="desctoprod" ng-model="desctoprod" ng-blur="validaDcto()" style="text-align:right;" disabled></td>
				</tr>
			</table>
			<div class="table-container" style="display:none" id="dispsearch">
				<table style="width:100%; border: 2px solid red;">
				<tr><td>
					<table style="width:100%" >
						<tr>
							<td>
								<table class="table" style="width:100%" >
									<col width="30%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<tr>
										<th>Descripción</th>
										<th align="center">Códigos</th>
										<th align="center">Unidad</th>
										<th align="center">$ Unitario</th>
										<th align="center">Existencia</th>
									</tr>
								</table>
							</td>
							<td>
								<a ng-click="closeDivSearch()">
								<span class="icon has-text-danger">
									<i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
								</span>
							</a>
							</td>
						</tr>
						<tr>
							<td>
								<div style="width:100%; height:200px; overflow:auto;">
								<table class="table is-hoverable"  style="width:100%;" id="items" >
									<col width="40%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<tr ng-repeat="x in lstaprdctbusq" ng-click="selectProdBus($index,x.IMAGEN)">
										<td>{{x.DESCRIPCION}}</td>
										<td>{{x.CODIGO}}</td>
										<td>{{x.UNIDAD_MEDIDAD}}</td>
										<td align="center">{{x.PRECIO_COMPRA_DISP}}</td>
										<td align="center">{{x.STOCK}}</td>
									</tr>
								</table>
								</div>
							</td>
						</tr>
					</table>
				</td></tr>
			</table>
			</div>
				<table style="width:100%" border="1">
					<tr>
						<td>
							<table class="table" style="width:100%" border="0">
								<col width="11%">
								<col width="34%">
								<col width="11%">
								<col width="11%">
								<col width="11%">
								<col width="11%">
								<col width="11%">
								<tr>
									<th align="left">Código</th>
									<th align="center">Descripción</th>
									<th align="center">Cantidad</th>
									<th align="right">U/Medida</th>
									<th align="right">$/Unitario</th>
									<th align="right">Descuento</th>
									<th align="right">Importe</th>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							<div id="divtablecfdi" style="width:100%; height:180px; overflow:auto;">
								<table id="tblcomprod" class="table is-bordered is-hoverable is-fullwidth" style="width:100%;">
									<col width="11%">
									<col width="34%">
									<col width="11%">
									<col width="11%">
									<col width="11%">
									<col width="11%">
									<col width="11%">
									<tr ng-repeat="x in listaproductos" ng-click="setSelected($index,x.CODIGO)" ng-class="{selected: x.CODIGO === idSelected}">
										<td>{{x.CODIGO}}</td>
										<td>{{x.DESCRIPCION}}</td>
										<td align="center">{{x.CANTIDAD}}</td>
										<td align="center">{{x.UNIDAD}}</td>
										<td align="center">$ {{x.PRECIO | number:2}}</td>
										<td align="center">{{x.DESCTO}}%</td>
										<td align="right">$ {{x.IMPORTE}}</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
		</div>
	</div>
	</div>
	<div class="box">
		<div class="columns">
			<div class="column is-7">
				<textarea class="textarea"></textarea>
			</div>
			<div class="column is-2">
			</div>
			<div class="column is-2">
				<div class="columns">
					<div class="column">
						<label class="label">Desc(-)</label>
					</div>
					<div class="column has-text-right">
						<label class="label">{{descuento}} %</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Suma</label>
					</div>
					<div class="column has-text-right">
						<label id="suma" class="label">$ {{suma}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Iva(+)</label>
					</div>
					<div class="column has-text-right">
						<label id="ivasuma" class="label">$ {{ivapaga}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Total $</label>
					</div>
					<div class="column has-text-right">
						<label id="sumtot" class="label">$ {{total}}</label>
						<input type="hidden" id="importe" value="{{total}}">
					</div>
				</div>
			</div>
		</div>
			<div id="divbtnCancel" class="columns">
				<div class="column">
					<button type="button" class="button is-primary" id="btnCancel" ng-click="btnCancel()">{{msgBton}}</button>
				</div>
			</div>
		</div>
	</form>
	</div>
	<div class="box" style="display:{{isAgrgaCompra ? 'none':'block'}}">
		<table style="width:100%" border="1">
			<tr>
				<td>
					<table style="width:100%" >
						<tr>
							<th ng-click="orderByMe('FECHA_COMPRA')" align="center" style="width:11%"><label class="label">Fecha</label></th>
							<th ng-click="orderByMe('DOCUMENTO')" align="right"  style="width:10%"><label class="label">No Doc</label></th>
							<th ng-click="orderByMe('PROVEEDOR')" align="center" style="width:20%"><label class="label">Proveedor</label></th>
							<th ng-click="orderByMe('IMPORTE')" align="center" style="width:11%"><label class="label">Importe</label></th>
							<th ng-click="orderByMe('SALDO')" align="center" style="width:11%"><label class="label">Saldo</label></th>
							<th ng-click="orderByMe('FECHA_REVISION')" align="center" style="width:11%"><label class="label">Revisión</label></th>
							<th ng-click="orderByMe('FECHA_PAGO')" align="center" style="width:11%"><label class="label">Vencimiento</label></th>
							<th ng-click="orderByMe('FORMA_PAGO')" align="center" style="width:15%"><label class="label">Forma de Pago</label></th>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:650px; overflow:auto;">
						<table class="table is-hoverable" id="compras" style="width:100%" >
							<tr ng-repeat="x in listaCompras | orderBy:myOrderBy:sortDir" ng-click="selectRowCompra(x.ID_COMPRA,$index)" ng-class="{selected: x.ID_COMPRA === idSelCompra}">
								<td align="center" style="width:11%">{{x.FECHA_COMPRA}}</td>
								<td align="right"  style="width:10%">{{x.DOCUMENTO}}</td>
								<td align="center" style="width:20%">{{x.PROVEEDOR}}</td>
								<td align="center" style="width:11%">{{x.IMPORTE}}</td>
								<td align="center" style="width:11%">{{x.SALDO}}</td>
								<td align="center" style="width:11%">{{x.FECHA_REVISION}}</td>
								<td align="center" style="width:11%">{{x.FECHA_PAGO}}</td>
								<td align="center" style="width:15%">{{x.FORMA_PAGO}}</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<div class="{{modalActive ? 'modal is-active' : 'modal' }}" id="avisoborrar">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Advertencia</p>
				<button class="delete" aria-label="close" ng-click="closeAvisoBorrar();"></button>
			</header>
			<section class="modal-card-body">
				¿Está seguro que desea eliminar esta compra <b>{{codigocompra}}</b>?
			</section>
			<footer class="modal-card-foot">
				<button class="button is-success" ng-click="eliminacompra()">Si</button>
				<button class="button" ng-click="closeAvisoBorrar();">No</button>
			</footer>
		</div>
	</div>
</div>

<script>
	var foopicker = new FooPicker({
	id: 'fechapago',
	dateFormat: 'dd/MM/yyyy'
	});
</script>
