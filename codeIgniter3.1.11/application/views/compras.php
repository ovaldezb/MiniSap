<input type="hidden" id="updtTable" value="F">
<div class="container" ng-controller="myCtrlCompras" data-ng-init="init()">
	<div class="notification">
		<h1 class="title is-4 has-text-centered">Compra de Productos</h1>
	</div>
	<div class="box" id="barranavegacion" ng-show="!isAgrgaCompra">
		<nav class="level">
			<div class="level-left">
				<div class="level-item">
					<p class="subtitle is-5">
	        	<strong>Filtro:</strong>
	      	</p>
				</div>
				<div class="level-item">
					<input name="producto" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tblcompras');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
				</div>
			</div>
			<div class="level-right" style="margin-right:20px">
				<p class="level-item" ng-show="permisos.alta">
					<a ng-click="agregarcompra();"><span class="icon has-text-success"><i class="fas fa-plus-square" title="Nueva Compra"></i></span></a></p>
				<p class="level-item" ng-show="permisos.modificacion">
					<a ng-click="despliegaCompra()"><span class="icon has-text-info"><i class="fas fa-folder-open" title="Visualiza Compra"></i></span></a></p>
				<p class="level-item" ng-show="permisos.baja">
					<a ng-click="preguntaelimcomp()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Compra"></i></span></a></p>
				<p class="level-item"><span class="icon has-text-info"><i class="fas fa-print" title="Imprime Compras"></i></span></p>
			</div>
		</nav>
	</div>
	<div class="box" ng-show="isAgrgaCompra">
	<form name="myForm">
	<div class="columns">
		<div class="column">
			<div class="box">
				<div class="columns">
					<div class="column is-narrow" style="width:100px">
						<label class="label">Proveedor:<label>
					</div>
					<div class="column is-narrow" style="width:110px">
						<input type="text" class="input is-small" ng-model="docprev" id="docprev">
					</div>
					<div class="column is-narrow" style="width:135px">
						<input type="text" class="input is-small" ng-model="claveprov" ng-keyup="buscaprovbyclave($event)" placeholder="Clave Proveedor">
					</div>
					<div class="column is-4">
						<input type="text" class="input is-small"  ng-model="proveedor" ng-keyup="buscaprovbynombre($event)" placeholder="Nombre del Proveedor">
					</div>
				</div>
				<div class="container" style="width:47.5%;margin-left:210px;margin-top:-25px;margin-bottom:10px" ng-show="buscaprov">
					<table style="width:100%">
						<tr>
							<td>
								<table class="table is-bordered" style="width:100%">
									<col width="25%">
									<col width="75%">
									<tr class="tbl-header-seek">
										<td>CLAVE</td>
										<td>NOMBRE</td>
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
								<div style="width:100%; height:100px; overflow:auto; border:2px solid red">
									<table class="table is-hoverable"  style="width:100%;" id="proveedores">
										<col width="25%">
										<col width="75%">
										<tr ng-repeat="x in lstaprovee" ng-click="selectProvee($index)">
											<td>{{x.CLAVE.trim()}}</td>
											<td>{{x.NOMBRE.trim()}}</td>
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
					<div class="column is-narrow" style="width:110px">
						<input type="text" class="input is-small" ng-model="numdoc" placeholder="No Documento" required>
					</div>
					<div class="column is-narrow">
						<div class="control">
							<div class="select is-small">
								<select name="tipopago" id="tipopago" onchange="cambiotp()">
								<option value="1">Contado</option>
								<option value="2">Cr&eacute;dito</option>
								</select>
							</div>
						</div>
					</div>
					<div class="column is-narrow" style="width:90px">
						<input type="number" class="input is-small" id="diascred"  onkeyup="sumadias()" ng-model="diascred" disabled>
					</div>
					<div class="column is-narrow">
						<label class="label">d&iacute;as</label>
					</div>
					<div class="column is-narrow" style="width:60px">
						<label class="label">C.R.</label>
					</div>
					<div class="column is-narrow" style="width:100px">
						<input type="text" class="input is-small" id="contrarecibo" ng-model="contrarecibo" style="text-align:right">
					</div>
					<div class="column is-narrow" style="width:65px">
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
					<div class="column is-narrow" style="width:110px" >
						<input type="text" class="input is-small" id="fechacompra" >
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
					<div class="column is-narrow">
						<label class="label">Desc %</label>
					</div>
					<div class="column is-narrow" style="width:100px">
						<input type="number" class="input is-small" value="" ng-model="descuento" id="descuento" ng-keyup="validaDescto()" style="text-align:center;">
					</div>
					<div class="column is-narrow" style="width:65px">
						<label class="label">Iva %</label>
					</div>
					<div class="column is-narrow" style="width:80px">
						<input type="number" class="input is-small" id="iva" ng-model="iva" value="" ng-blur="validaIva()" required style="text-align:center;">
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
					<td align="center">Precio Compra</td>
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
							<div class="control" style="width:55px">
								<input type="number" class="input is-small" ng-model="cantidad" id="cantidad" ng-keyup="manualenter()" disabled onfocus="this.select();" required style="text-align:center">
							</div>
							<div class="control">
								<button class="button is-info is-small" ng-click="increase()" id="mascant" disabled>+</button>
							</div>
						</div>
					</td>
					<td align="center"><label class="label">{{unidad}}</label></td>
					<td><input type="number" class="input is-small" id="precio" ng-model="precio" disabled style="text-align:center;" required></td>
					<td><input type="number" class="input is-small" id="desctoprod" ng-model="desctoprod" ng-blur="validaDcto()" style="text-align:right;" disabled></td>
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
										<td>{{x.UNIDAD_MEDIDA}}</td>
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
				<textarea class="textarea" ng-model="notas" placeholder="Observaciones"></textarea>
			</div>
			<div class="column is-2">
			</div>
			<div class="column is-2">
				<div class="columns">
					<div class="column">
						<label class="label">Suma(+)</label>
					</div>
					<div class="column has-text-right">
						<label id="suma" class="label">{{suma | currency}}</label>
					</div>
				</div>
        <div class="columns">
					<div class="column">
						<label class="label">Desc(-)</label>
					</div>
					<div class="column has-text-right">
						<label class="label">{{descuento | currency}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Iva(+)</label>
					</div>
					<div class="column has-text-right">
						<label id="ivasuma" class="label">{{ivapaga | currency}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Total $</label>
					</div>
					<div class="column has-text-right">
						<label id="sumtot" class="label">{{total | currency}}</label>
						<input type="hidden" id="importe" value="{{total}}">
					</div>
				</div>
			</div>
		</div>
			<div id="divbtnCancel" class="columns">
				<div class="column">
					<button type="button" class="button is-info" id="btnRegistrar" ng-show="btnCompHide" ng-disabled="listaproductos.length == 0" ng-click="registrar()" >Comprar</button>
					<button type="button" class="button is-danger" id="btnCancel" ng-click="btnCancel()">{{msgBton}}</button>
				</div>
			</div>
		</div>
	</form>
	</div>
	<div id="listacompras" class="container" ng-show="!isAgrgaCompra">
		<table style="width:100%"> 
			<tr>
				<td>
					<table class="table is-bordered" style="width:100%" >
            <colgroup>	
              <col width="11%">
              <col width="10%">
              <col width="20%">
              <col width="11%">
              <col width="11%">
              <col width="11%">
              <col width="11%">
              <col width="15%">
            </colgroup>
						<tr style="background-color:CornflowerBlue; color:Ivory;">
							<td ng-click="orderByMe('FECHA_COMPRA')" style="text-align:center;">Fecha</td>
							<td ng-click="orderByMe('DOCUMENTO')"  style="text-align:right;">No Doc</td>
							<td ng-click="orderByMe('PROVEEDOR')" style="text-align:center;">Proveedor</td>
							<td ng-click="orderByMe('IMPORTE')" style="text-align:center;">Importe</td>
							<td ng-click="orderByMe('SALDO')"  style="text-align:center;">Saldo</td>
							<td ng-click="orderByMe('FECHA_REVISION')" style="text-align:center;">Revisión</td>
							<td ng-click="orderByMe('FECHA_PAGO')"  style="text-align:center;">Vencimiento</td>
							<td ng-click="orderByMe('FORMA_PAGO')" style="text-align:center;">Forma de Pago</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:500px; overflow:auto;">
						<table class="table is-bordered is-hoverable" id="tblcompras" style="width:100%" >
              <colgroup>
                <col width="11%">
							  <col width="10%">
							  <col width="20%">
							  <col width="11%">
							  <col width="11%">
							  <col width="11%">
							  <col width="11%">
							  <col width="15%">
              </colgroup>
							<tr ng-repeat="x in listaCompras | orderBy:myOrderBy:sortDir" ng-click="selectRowCompra(x.ID_COMPRA,$index)" ng-dblclick="despliegaCompra()" ng-class="{selected: x.ID_COMPRA ===  idSelCompra}">
								<td style="text-align:center;font-size:14px">{{x.FECHA_COMPRA}}</td>
								<td style="text-align:right;font-size:14px">{{x.DOCUMENTO}}</td>
								<td style="text-align:center;font-size:14px">{{x.PROVEEDOR}}</td>
								<td style="text-align:center;font-size:14px">{{x.IMPORTE | currency}}</td>
								<td style="text-align:center;font-size:14px">{{x.SALDO | currency}}</td>
								<td style="text-align:center;font-size:14px">{{x.FECHA_REVISION}}</td>
								<td style="text-align:center;font-size:14px">{{x.FECHA_PAGO}}</td>
								<td style="text-align:center;font-size:14px">{{x.FORMA_PAGO}}</td>
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