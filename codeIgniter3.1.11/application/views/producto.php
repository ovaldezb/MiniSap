<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$size1 = 100;
?>
<div class="container" ng-controller="myCtrlProducto" data-ng-init="init()">
	<div class="notification" >
		<h1 class="title is-4 has-text-centered">Administración de Productos y Servicios</h1>
	</div>
	<nav class="level" ng-show="!isMainDivPrdcto">
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
	    	<p class="level-item" ng-show="permisos.alta">
				<a ng-click="openDivAgregar()">
					<span class="icon has-text-success">
						<i title="Agregar un nuevo producto" class="fas fa-plus-square" ></i>
					</span>
				</a>
			</p>
			<p class="level-item" ng-show="permisos.modificacion">
				<a ng-click="update()">
					<span class="icon has-text-info">
						<i title="Editar un producto" class="fas fa-edit" ></i>
					</span>
				</a>
			</p>
			<p class="level-item" ng-show="permisos.baja">
				<a ng-click="preguntaEliminar()">
					<span class="icon has-text-danger">
						<i title="Elimnar un producto" class="far fa-trash-alt"></i>
					</span>
				</a>
			</p>
  		</div>
	</nav>
	<div ng-show="isMainDivPrdcto">
		<form name="myForm">
			<div class="box" style="margin-top:-25px">
				<div class="columns">
					<div class="column is-7">
						<div class="columns">
							<div class="column is-narrow" style="width: <?php echo $size1 ?>px;">
								<label class="label">Código</label>
							</div>
							<div class="column is-3">
								<input name="codigo" ng-model="codigo" class="input is-small" ng-blur="validarcodigo()" onkeyup="toUpper(this)" type="text" placeholder="CÓDIGO" required>
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
										<select ng-model="linea" ng-options="x.ID_LINEA as x.NOMBRE for x in lstlinea"></select>	
									</div>
								</div>
							</div>
						<div class="column is-1">
						</div>
						<div class="column is-narrow">
							<label class="label">Unidad de Medidad</label>
						</div>
						<div class="column is-2">
              <div class="select is-small">
							<select id="umedida">
<?php	foreach ($umedidas as $umedida) { ?>
								<option value=<?php echo $umedida['UNIDAD'] ?>><?php echo $umedida['UNIDAD']?></option>
<?php	} ?>
							</select>
              </div>
						</div>
						<div class="column is-narrow" style="margin-left:70px">
							<div class="control" id="cargarimg">
								<button  ng-click="openImageWnd($event);" id="btnAddImg" class="button is-primary">Agregar Imagen</button>
							</div>
						</div>
					</div>
				</div>
				<div class="column is-1">
				</div>
				<div class="column is-narrow" style="margin-left:100px">
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
				
			</div>
		</div>

<div class="box" style="margin-top:-25px">
	<h4 class="title is-5 has-text-centered">Datos Obligatorios para CFDI 3.3</h4>
	<div class="columns">
		<div class="column is-one-quarter">
			<div class="field-body">
				<div class="field-label is-normal">
					<label class="label">Código</label>
				</div>
				<input ng-model="codigocfdi" class="input is-small" ng-keyup="getCodigoSAT($event)" type="text" placeholder="CÓDIGO CFDI" >
			</div>
		</div>
		<div class="column is-6">
			<div class="field-body">
				<div class="field-label is-normal">
					<label class="label">Descripción</label>
				</div>
				<input  ng-model="cfdidesc" class="input is-small" ng-keyup="ejecutagetitem($event)" id="cfdidesc" type="text" placeholder="DESCRIPCIÓN">
				<div class="control">
					<button class="button is-primary is-small" ng-click="getItemsSAT();">Verificar</button>
				</div>
			</div>
		</div>
	</div>
	<div class="table-container" ng-show="isCFDIBusqueda">
		<table  style="width:80%;">
			<tr>
				<td align="center">
					<table style="width:100%;" class="table">
            <colgroup>
						  <col width="20%">
						  <col width="30%">
						  <col width="50%">
            </colgroup>
						<tr style="background-color:Crimson; color:Ivory;">
							<td ><label  class="label"># Item</label></td>
							<td ><label  class="label">Clave</label></td>
							<td ><label  class="label">Descripción</label></td>
						</tr>
					</table>
				</td>
        <td>
					<a ng-click="cierraCFDI()">
					<span class="icon has-text-danger">
						<i class="fas fa-times-circle" title="Cerrar ventan de búsqueda"></i>
					</span>
					</a>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:180px; overflow:auto;border:2px solid red">
						<table class="table is-hoverable" style="width:100%;">
              <colgroup>
							  <col width="20%">
							  <col width="30%">
							  <col width="50%">
              </colgroup>
							<tr ng-repeat="x in lstItemsSAT" ng-click="selectRowItemSAT($index)">
								<td>{{$index+1}}</td>
								<td>{{x.CLAVE}}</td>
								<td>{{x.DESCRIPCION}}</td>
							</tr>
						</table>
					</div>
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
				<input ng-model="unidad_sat" class="input is-small" type="text" ng-keyup="getMedidaSATCode($event)" placeholder="UNIDAD SAT">
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
		<div style="margin-left:80px; width:70%; margin-top:-20px" ng-show="isUndSATBusqda">
			<table style="width:80%;">
				<tr>
					<td>
						<table style="width:100%;" class="table is-striped" border="1">
              <colgroup>
							  <col width="30%">
							  <col width="70%">
              </colgroup>
							<tr style="background-color:Crimson; color:Ivory;">
								<td style="text-align:center">Clave</td>
								<td style="text-align:center">Descripción</td>
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
						<div style="width:100%; height:180px; overflow:auto;border:2px solid red">
							<table class="table is-hoverable" style="width:100%;">
                <colgroup>
								  <col width="30%">
								  <col width="70%">
                </colgroup>
								<tr ng-repeat="x in lstUndadSAT" ng-click="selectUnidadSAT($index)">
									<td style="text-align:center">{{x.CLAVE}}</td>
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
					<input ng-model="preciolista" class="input is-small" type="number" step="any" placeholder="PRECIO" required>
				</div>
				<div class="column is-1"></div>
				<div class="column is-narrow" style="width:400px">
					<label class="label">Última actualización:</label><p id="ultact"></p>
				</div>
				<div class="column is-narrow" style="margin-right:80px">
					<label class="label">Stock</label>
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
				<div class="column is-narrow" style="width:130px">
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
				<div class="column is-narrow" style="margin-left:30px">
					<label class="label">Máximo</label>
				</div>
				<div class="column is-1">
					<input ng-model="maxstock" id="maxstock" class="input is-small" type="number" style="margin-left:10px">
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
				<div class="column is-narrow" style="width:130px">
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
				<div class="column is-narrow" style="margin-left:30px">
					<label class="label">Mínimo</label>
				</div>
				<div class="column is-1">
					<input ng-model="minstock" id="minstock" class="input is-small" type="number" style="margin-left:10px">
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
				<div class="column is-narrow" style="width:90px">
					<input id="ieps" ng-model="ieps" class="input is-small" type="text" placeholder="IEPS">
				</div>
				<div class="column is-narrow" style="width:90px">
				</div>
				<div class="column is-narrow" style="width:125px">
					<label class="label">Tasa Exenta</label>
				</div>
				<div class="column is-narrow" style="margin-right:5px">
					<input id="estasaexenta" ng-model="estasaexenta" class="checkbox" type="checkbox">
				</div>
			</div>
			<div class="columns is-gapless is-multiline is-mobile">
				<div class="column is-2">
					<label class="label">Observaciones</label>
				</div>
			</div>
			<div class="columns" style="margin-top:-20px">
				<div class="column is-narrow" style="width:705px">
					<textarea class="textarea" ng-model="notas" rows="2"></textarea>
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
	<div style="border: 2px solid black; width:99%" ng-show="!isMainDivPrdcto">
		<table  class="table" style="width:100%;"  >
			<tr>
				<td>
					<table class="table is-bordered" style="width:100%">
						<col width='42%'>
						<col width='18%'>
            <col width='15%'>
						<col width='15%'>
						<col width='10%'>
						<tr class="tbl-header">
							<td ng-click="orderByMe('DESCRIPCION')">DESCRIPCIÓN</td>
							<td ng-click="orderByMe('CODIGO')" style="text-align:center">CÓDIGO</td>
              <td ng-click="orderByMe('LINEA')" style="text-align:center">LINEA</td>
							<td ng-click="orderByMe('PRECIO_LISTA')" style="text-align:center">PRECIO LISTA</td>
							<td ng-click="orderByMe('STOCK')" style="text-align:center">EXISTENCIA</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div style="width:100%; height:500px; overflow:auto;margin-top:-9px">
						<table class="table is-bordered" id="tablaproducto" style="width:100%">
							<col width='42%'>
							<col width='18%'>
              <col width='15%'>
							<col width='15%'>
							<col width='10%'>
							<tr ng-repeat="x in lstPrdcts | orderBy:myOrderBy:sortDir" ng-click="selectRowProducto(x.CODIGO,$index,x.ID_PRODUCTO)" ng-class="{selected: x.ID_PRODUCTO === idProducto}" ng-dblclick="verdetalle()">
								<td>{{x.DESCRIPCION}}</td>
								<td style="text-align:center">{{x.CODIGO}}</td>
                <td style="text-align:center">{{x.LINEA}}</td>
								<td style="text-align:right">${{x.PRECIO_LISTA | number:2}}</td>
								<td style="text-align:right">{{x.STOCK}}</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</div>
  <div class="{{modalDetalleProd ? 'modal is-active' : 'modal' }}">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Detalle del Producto: <span class="font12">{{producto}}</span> </p>
	      <button class="delete" aria-label="close" ng-click="cerrarDetalleProd()"></button>
	    </header>
	    <section class="modal-card-body">
	      <table style="width:100%" class="table is-bordered">
        <colgroup>
          <col width="25%"/>
          <col width="25%"/>
          <col width="25%"/>
          <col width="25%"/>
        </colgroup>
        <thead>
          <tr style="background-color:#7ca9e8">
            <th style="text-align:center">Documento</th>
            <th style="text-align:center">Fecha</th>
            <th style="text-align:center">Tipo</th>
            <th style="text-align:center">Cantidad</th>
          </tr>
          </thead>
        </table>
        <div style="height:200px;overflow:auto;margin-top:-25px">
          <table style="width:100%;" class="table is-bordered">
            <colgroup>
              <col width="25%"/>
              <col width="25%"/>
              <col width="25%"/>
              <col width="25%"/>
            </colgroup>
            <tr ng-repeat="x in lstDetailProd">
              <td style="text-align:center">{{x.DOCUMENTO}}</td>
              <td style="text-align:center">{{x.FECHA | date}}</td>
              <td style="text-align:center">{{x.TIPO}}</td>
              <td style="text-align:right">{{x.CANTIDAD }}</td>
            </tr>
            <tr>
              <td colspan="3" style="text-align:right">Total:</td>
              <td style="text-align:right">{{prodDetailTot}}</td>
            </tr>
          </table>
        </div>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button" ng-click="cerrarDetalleProd()">Cerrar</button>
	    </footer>
	  </div>
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
