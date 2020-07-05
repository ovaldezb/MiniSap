<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$size1 = 100;
?>
<br><br>
<div class="container" ng-controller="myCtrlProducto" data-ng-init="init()">
	<div class="notification" >
		<h1 class="title is-2 has-text-centered">Administración de Productos y Servicios</h1>
	</div>
	<nav class="level">
		<div class="level-left">
			<div class="level-item">
				<p class="subtitle is-5">
        	<strong>Filtro:</strong>
      	</p>
			</div>
			<div class="level-item">
				<input name="producto" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablaproducto');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
			</div>
		</div>
		<div class="level-right">
	    <p class="level-item">
				<a ng-click="openDivAgregar()">
					<span class="icon has-text-success">
						<i title="Agregar un nuevo producto" class="fas fa-plus-square" ></i>
					</span>
				</a>
			</p>
			<p class="level-item">
				<a ng-click="update()">
					<span class="icon has-text-info">
						<i title="Editar un producto" class="fas fa-edit" ></i>
					</span>
				</a>
			</p>
			<p class="level-item">
				<a ng-click="preguntaEliminar()">
					<span class="icon has-text-danger">
						<i title="Elimnar un producto" class="far fa-trash-alt"></i>
					</span>
				</a>
			</p>
  	</div>
	</nav>
	<div class="box" ng-show="isMainDivPrdcto">
		<form name="myForm">
			<div class="box">
				<div class="columns">
					<div class="column is-7">
						<div class="columns">
							<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
								<label class="label">Código</label>
							</div>
							<div class="column is-3">
								<input name="codigo" ng-model="codigo" class="input is-small" type="text" placeholder="CÓDIGO" required>
							</div>
							<div class="column is-narrow">
								<label class="label">Producto <input type="radio" ng-model="tipops" name="tipo" value="P" checked ng-click="selecTPS()" required></label>
							</div>
							<div class="column is-narrow">
								<label class="label">Servicio <input type="radio" ng-model="tipops" name="tipo" value="S" ng-click="selecTPS()"></label>
							</div>
							<div class="column">
								<label class="label">{{tipops_msg}}</label>
							</div>
						</div>
						<div class="columns">
							<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
								<label class="label">Descripción</label>
							</div>
							<div class="column">
								<input ng-model="nombre" class="input is-small" type="text" placeholder="DESCRIPCIÓN" onKeyUp="toUpper(this)" required>
							</div>
						</div>
						<div class="columns">
							<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
								<label class="label">Línea</label>
							</div>
							<div class="column is-narrow" style="width: 150px;">
								<div class="control">
									<div class="select is-small">
										<select name="linea" id="linea">
<?php	foreach ($lineas as $linea) { ?>
										<option value=<?php echo $linea['ID_LINEA'] ?>><?php echo $linea['NOMBRE']?></option>
<?php	} ?>
									</select>
								</div>
							</div>
						</div>
						<div class="column is-1">
						</div>
						<div class="column is-narrow">
							<label class="label">Unidad de Medidad</label>
						</div>
						<div class="column is-2">
							<select id="umedida">
<?php	foreach ($umedidas as $umedida) { ?>
								<option value=<?php echo $umedida['UNIDAD'] ?>><?php echo $umedida['UNIDAD']?></option>
<?php	} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="column is-1">
				</div>
				<div class="column has-text-centered">
				<figure class="media-left">
					<p class="image is-128x128">
						<input type="hidden" id="img_name" value="">
					<img style="display: none;" id="imgsrc" src="">
					</p>
		  	</figure>
				</div>
			</div>
			<div class="columns">			
				<div class="column is-narrow">
					<input type="checkbox" class="checkbox" id="esequiv" ng-model="esequiv">
				</div>
				<div class="column is-narrow" style="width: 100px;">
					<label class="label">Equivalencia</label>
				</div>
				<div class="column is-1">
					<input  ng-model="equivalencia" class="input is-small" type="text">
				</div>
				<div class="column is-3"></div>
				<div class="column is-2">
					<div class="control" id="cargarimg">
						<button  ng-click="openImageWnd();" class="button is-primary">Agregar Imagen</button>
					</div>
				</div>
			</div>
		</div>

<div class="box">
	<h4 class="title is-5 has-text-centered">Datos Obligatorios para CFDI 3.3</h4>
	<div class="columns">
		<div class="column is-one-quarter">
			<div class="field-body">
				<div class="field-label is-normal">
					<label class="label">Código</label>
				</div>
				<input ng-model="codigocfdi" class="input is-small" type="text" placeholder="CÓDIGO CFDI" >
			</div>
		</div>
		<div class="column is-6">
			<div class="field-body">
				<div class="field-label is-normal">
					<label class="label">Descripción</label>
				</div>
				<input  ng-model="cfdidesc" class="input is-small" ng-keyup="ejecutagetitem($event)" type="text" placeholder="DESCRIPCIÓN">
				<div class="control">
					<button class="button is-primary is-small" ng-click="getItemsSAT();">Verificar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="table-container" style="display:{{isCFDIBusqueda ? 'block':'none'}};">
		<table  style="width:100%;">
			<tr>
				<td align="center">
					<table border="1" style="width:100%;" class="table is-striped" style="width:100%;">
						<col width="20%">
						<col width="30%">
						<col width="50%">
						<tr>
							<td ><label  class="label"># Item</label></td>
							<td ><label  class="label">Clave</label></td>
							<td ><label  class="label">Descripción</label></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:180px; overflow:auto;">
						<table border="1" class="table is-hoverable" style="width:100%;">
							<col width="20%">
							<col width="30%">
							<col width="50%">
							<tr ng-repeat="x in lstItemsSAT" ng-click="selectRowItemSAT($index)">
								<td>{{$index+1}}</td>
								<td>{{x.CLAVE}}</td>
								<td>{{x.DESCRIPCION}}</td>
							</tr>
						</table>
					</div>
				</td>
				<td>
					<a ng-click="cierraCFDI()">
					<span class="icon has-text-danger">
						<i class="fas fa-times-circle" title="Cerrar ventan de búsqueda"></i>
					</span>
					</a>
				</td>
			</tr>
		</table>
		<hr class="hr">
	</div>
	<div class="columns">
		<div class="column is-one-quarter">
			<div class="field-body">
				<div class="field-label is-normal">
					<label class="label">Unidad</label>
				</div>
				<input ng-model="unidad_sat" class="input is-small" type="text" placeholder="UNIDAD SAT">
			</div>
		</div>
		<div class="column is-6">
			<div class="field-body">
				<div class="field-label is-normal">
					<label class="label">Descripción</label>
				</div>
					<input  ng-model="unidaddesc" class="input is-small" ng-keyup="ejecutagetunidadsat($event)" type="text" placeholder="DESCRIPCIÓN">
				<div class="control">
					<button class="button is-primary is-small" ng-click="getUnidadSAT()">Verificar</button>
				</div>
			</div>
		</div>
		</div>
		<div style="display: {{isUndSATBusqda ? 'block' : 'none'}};">
			<table border=0 style="width:80%;">
				<tr>
					<td align="center">
						<table style="width:100%;" class="table is-striped" border="1">
							<col width="15%">
							<col width="30%">
							<col width="55%">
							<tr>
								<td>No Item</td>
								<td>Clave</td>
								<td align="center">Descripción</td>
							</tr>
						</table>
					</td>
					<td>
						<a ng-click="cierraUnidadSAT()">
							<span class="icon has-text-danger">
								<i class="fas fa-times-circle" title="Cerrar ventan de búsqueda"></i>
							</span>
						</a>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:100%; height:180px; overflow:auto;">
							<table class="table is-hoverable" style="width:100%;" border="1">
								<col width="15%">
								<col width="30%">
								<col width="55%">
								<tr ng-repeat="x in lstUndadSAT" ng-click="selectUnidadSAT($index)">
									<td>{{$index+1}}</td>
									<td>{{x.CLAVE}}</td>
									<td>{{x.DESCRIPCION}}</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
			<hr> <!-- style="border-color:DarkRed; margin-bottom: 0;" -->
		</div>
	</div>
	<div class="box">
			<div class="columns is-gapless">
				<div class="column is-2 has-background-primary">
					<label class="label">Precio de Venta $</label>
				</div>
				<div class="column is-1">
					<input ng-model="preciolista" class="input is-small" type="number" placeholder="PRECIO" required>
				</div>
				<div class="column is-1"></div>
				<div class="column is-2">
					<label class="label">Última actualización:</label><p id="ultact"></p>
				</div>
			</div>
			<div class="columns is-gapless">
				<div class="column is-2 has-background-primary">
					<label class="label">Tipo de Moneda</label>
				</div>
				<div class="column is-2">
					<div class="control">
						<div class="select is-small">
							<select name="moneda" id="moneda">
		<?php 		foreach ($monedas as $moneda) { ?>
							<option value=<?php echo $moneda['ID_MONEDA'] ?>><?php echo $moneda['NOMBRE']?></option>
		<?php	} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="column is-2">
					<label class="label">En Promoción</label>
				</div>
				<div class="column is-narrow" style="margin-right:84px">
					<input type="checkbox" class="checkbox" ng-model="espromo">
				</div>
				<div class="column is-narrow">
					<label class="label">$</label>
				</div>
				<div class="column is-1">
					<input type="number" ng-model="preciopromo" class="input is-small" name="preciopromo" min="0">
				</div>
			</div>
			<div class="columns is-gapless">
				<div class="column is-1">
					<label class="label">IVA</label>
				</div>
				<div class="column is-1">
					<label class="label">Tasa:</label>
				</div>
				<div class="column is-1">
					<input ng-model="iva" class="input is-small" type="text" placeholder="IVA" required>
				</div>
				<div class="column is-1">
				</div>
				<div class="column is-2">
					<label class="label">Con Descuento</label>
				</div>
				<div class="column is-narrow" style="margin-right:80px">
					<input id="esdescnt" ng-model="esdescnt" class="checkbox" type="checkbox">
				</div>
				<div class="column is-narrow">
					<label class="label">%</label>
				</div>
				<div class="column is-1">
					<input type="number" ng-model="preciodescnt" class="input is-small" min="0" max="100">
				</div>
			</div>
			<div class="columns is-gapless">
				<div class="column is-1">
					<label class="label">IEPS</label>
				</div>
				<div class="column is-1">
					<div class="control">
						<div class="select is-small">
							<select name="idieps" id="idieps">
		<?php 		foreach ($iepss as $ieps) { ?>
							<option value=<?php echo $ieps['ID_IEPS'] ?>><?php echo $ieps['NOMBRE']?></option>
		<?php		} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="column is-1">
					<input id="ieps" ng-model="ieps" class="input is-small" type="text" placeholder="IEPS">
				</div>
				<div class="column is-1">
				</div>
				<div class="column is-narrow" style="margin-right:80px">
					<label class="label">Stock</label>
				</div>
				<div class="column is-1">
				</div>
				<div class="column is-narrow" style="margin-right:28px">
					<label class="label">Máximo</label>
				</div>
				<div class="column is-1">
					<input ng-model="maxstock" id="maxstock" class="input is-small" type="number">
				</div>
			</div>
			<div class="columns is-gapless is-multiline is-mobile">
				<div class="column is-2">
					<label class="label">Observaciones</label>
				</div>
				<div class="column is-narrow" style="margin-right:5px">
					<input id="estasaexenta" ng-model="estasaexenta" class="checkbox" type="checkbox">
				</div>
				<div class="column is-1">
					<label class="label">Tasa Exenta</label>
				</div>
				<div class="column is-3">
				</div>
				<div class="column is-narrow" style="margin-right:30px">
					<label class="label">Mínimo</label>
				</div>
				<div class="column is-1">
					<input ng-model="minstock" id="minstock" class="input is-small" type="number">
				</div>
			</div>
			<div class="columns is-gapless is-multiline is-mobile">
				<div class="column is-8">
					<textarea class="textarea" ng-model="notas"></textarea>
				</div>
			</div>
			<div class="field is-grouped">
		  	<p class="control">
					<button ng-click="submitForm();" class="button is-primary" ng-disabled="myForm.$invalid">{{btnAccion}}</button>
		  	</p>
		  	<p class="control">
					<button ng-click="cancelar()" class="button is-light">Cancelar</button>
		  	</p>
			</div>
		</div>
	</form>
	</div>
	<div style="border: 2px solid black" ng-show="!isMainDivPrdcto">
		<table  class="table" style="width:100%;"  >
				<tr>
						<td>
							<table class="table is-hoverable" style="width:100%">
								<col width='40%'>
								<col width='20%'>
								<col width='20%'>
								<col width='20%'>
								<tr style="background-color:CornflowerBlue; color:Ivory;">
									<td ng-click="orderByMe('DESCRIPCION')">DESCRIPCIÓN</td>
									<td ng-click="orderByMe('CODIGO')" align="right">CÓDIGO</td>
									<td ng-click="orderByMe('PRECIO_LISTA')" align="right">PRECIO LISTA</td>
									<td ng-click="orderByMe('STOCK')" align="right">EXISTENCIA</td>
								</tr>
							</table>
						</td>
				</tr>
				<tr>
						<td>
							<div style="width:100%; height:570px; overflow:auto;">
								<table class="table is-hoverable" id="tablaproducto" style="width:100%">
									<col width='40%'>
									<col width='20%'>
									<col width='20%'>
									<col width='20%'>
									<tr ng-repeat="x in lstPrdcts | orderBy:myOrderBy:sortDir" ng-click="selectRowProducto(x.CODIGO,$index,x.ID_PRODUCTO)" ng-class="{selected: x.CODIGO === idSelProd}">
										<td>{{x.DESCRIPCION}}</td>
										<td align="right">{{x.CODIGO}}</td>
										<td align="right">${{x.PRECIO_LISTA | number:2}}</td>
										<td align="right">{{x.STOCK}}</td>
									</tr>
								</table>
						</div>
						</td>
				</tr>
		</table>
	</div>

<div class="{{isNobrrarActive ? 'modal is-active' : 'modal'}}" id="noborrar">
	<div class="modal-background"></div>
	<div class="modal-card">
		<header class="modal-card-head">
			<p class="modal-card-title">Aviso</p>
			<button class="delete" aria-label="close" ng-click="closeModalNoBorrar();"></button>
		</header>
		<section class="modal-card-body">
		No es posible eliminar productos que tienen existencia en almacen, contacte con su Administrador de Sistemas para más informaciòn
		</section>
		<footer class="modal-card-foot">
			<button class="button is-success" ng-click="closeModalNoBorrar()">Aceptar</button>
		</footer>
	</div>
</div>
<div class="{{isAvsoBrrarActv ? 'modal is-active' : 'modal'}}" id="avisoborrar">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Advertencia</p>
      <button class="delete" aria-label="close" ng-click="closeAvisoBorrar();"></button>
    </header>
    <section class="modal-card-body">
      Está seguro que desea eliminar este producto <b>{{descprodborrar}}</b>
    </section>
    <footer class="modal-card-foot">
      <button class="button is-success" ng-click="eliminar()">Si</button>
      <button class="button" ng-click="closeAvisoBorrar();">No</button>
    </footer>
  </div>
</div>
</div>
