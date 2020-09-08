<br>
<input type="hidden" id="updtTblComp" value="F">
<div class="container">
  <div class="notification" align="center">
    <h1 class="title is-1">Terminal Punto de Venta</h1>
  </div>
</div>
<br>

<div class="container"  ng-controller="myCtrlTpv" data-ng-init="init()">
	<div class="columns is-gapless">
		<div class="column is-8">
			<div class="box">
				<div class="columns">
					<div class="column is-2">
						<label class="label">Documento</label>
					</div>
					<div class="column is-3">
						<p class="control is-expanded has-icons-left">
							<input class="input is-small" type="text" ng-model="docto" id="docto" placeholder="Docto" required>
							<span class="icon is-small is-left">
								<i class="fas fa-file"></i>
							</span>
						</p>
					</div>
					<div class="column is-narrow">
						<label class="label"><input type="checkbox" ng-model="captura_rapida" ng-click="capturaRapida()" name="caprap" >Captura Rápida</label>
					</div>
				</div>
				<div class="columns is-gapless is-multiline">
					<div class="column is-2">
						<label class="label">Cliente</label>
					</div>
					<div class="column is-2">
						<input type="text" ng-model="claveclte" class="input is-small">
					</div>
					<div class="column is-6">
						<div class="field has-addons">
							<p class="control is-expanded has-icons-left">
								<input class="input is-small" ng-keyup="buscacliente($event)" ng-model="nombre_cliente" type="text" placeholder="Cliente">
								<span class="icon is-small is-left">
									<i class="fas fa-user"></i>
								</span>
							</p>
							<p class="control">
								<a class="button is-info is-small" ng-click="VerificarCliente()">
								  Verificar
								</a>
							</p>
						</div>
					</div>
					<div class="column is-1">
					</div>
					<div class="column is-2">
					</div>
					<div class="column is-8" ng-show="showLstClte" >
						<table style="width:100%; border:2px solid red">
							<tr>
								<td align="center">
									<table style="width:100%">
										<col width="26%">
										<col width="63%">
										<col width="11%">
										<thead>
											<tr>
												<td style="text-align=left;">Clave</td>
												<td align="left">Nombre</td>
												<td align="right">
													<a ng-click="closeClteSearch()">
													<span class="icon has-text-danger">
													<i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
													</span>
													</a>
												</td>
											</tr>
										</thead>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center">
									<div style="width:100%; height:100px; overflow:auto;">
										<table style="width:100%;">
											<col width="26%">
											<col width="74%">
											<tr ng-repeat="x in lstCliente" ng-click="seleccionaCliente($index)">
												<td style="text-align=left;">{{x.CLAVE}}</td>
												<td align="left">{{x.NOMBRE}}</td>
											</tr>
										</table>
								</div>
								</td>
							</tr>
						</table>
						<hr class="hr" style="margin-bottom: 0;">
						<br>
					</div>
				</div>

				<div class="columns is-gapless is-multiline">
					<div class="column is-2">
						<label class="label">Vendedor</label>
					</div>
					<div class="column is-2">
						<input type="text" ng-model="idvendedor" class="input is-small">
					</div>
					<div class="column is-6">
						<div class="field has-addons">
							<p class="control is-expanded has-icons-left">
								<input class="input is-small" type="text" ng-model="nombre_vendedor" ng-keyup="buscavendedor($event)" placeholder="Vendedor">
								<span class="icon is-small is-left">
									<i class="fas fa-user-tie"></i>
								</span>
							</p>
						</div>
					</div>
					<div class="column is-1">
					</div>
					<div class="column is-2">
					</div>
					<div class="column is-8" id="listaVendedores" style="display:none;" >
						<table border="1" style="width:100%; border:2px red">
							<tr>
								<td align="center">
									<table style="width:100%" border="1">
										<col width="26%">
										<col width="74%">
										<thead>
											<tr>
												<td align="left">Clave</td>
												<td align="left">Nombre</td>
												<td>
													<a ng-click="closeVendSearch()">
													<span class="icon has-text-danger">
														<i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
													</span>
												</a>
												</td>
											</tr>
										</thead>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center">
									<div style="width:100%; height:100px; overflow:auto;">
										<table style="width:100%;" border="1">
											<col width="25%">
											<col width="75%">
											<tr ng-repeat="x in lstVendedor" ng-click="seleccionaVendedor($index)">
												<td align="left">{{x.ID_VENDEDOR}}</td>
												<td align="left">{{x.NOMBRE}}</td>
											</tr>
										</table>
								</div>
								</td>
							</tr>
						</table>
						<hr class="hr" style="margin-bottom: 0;">
					</div>
				</div>
			</div>
			<div class="box" id="compras">
				<nav class="level" id="barraProducto">
					<div class="level-left">
						<div class="level-item">
							<a ng-click="editaProducto()">
							<span class="icon has-text-success">
								<i class="fas fa-edit" title="Edita el producto seleccionado de la lista"></i>
							</span>
							</a>
						</div>
						<div class="level-item">
							<a ng-click="borraProducto()">
							<span class="icon has-text-danger">
								<i  class="far fa-trash-alt" title="Elimina el producto seleccionado de la lista"></i>
							</span>
							</a>
						</div>
					</div>
					<div class="level-right">
				    <div class="level-item">
							<button class="button is-success" ng-click="verificaExistencia()" style="display:{{isVerifExis ? 'block':'none'}}">Verificar Existencia</button>
						</div>
					</div>
				</nav>
				<div class="columns">
					<div class="column is-2">
						<input type="text" id="codigo_prodto" ng-model="codigo_prodto"  ng-keyup="buscaprodbycodigo($event)" class="input is-small" >
					</div>
					<div class="column is-4">
						<input type="text"  ng-model="prod_desc" ng-keyup="buscprodbydesc($event)" class="input is-small" >
					</div>
					<div class="column is-2" >
						<div class="field has-addons">
							<div class="control">
								<button class="button is-info is-small" ng-click="decrease()" id="mencant">-</button>
							</div>
							<div class="control">
								<input type="text" class="input is-small" ng-model="cantidad" id="cantidad" style="text-align:center;" ng-keyup="manualenter()" onfocus="this.select();" required>
							</div>
							<div class="control">
								<button class="button is-info is-small" ng-click="increase()" id="mascant" >+</button>
							</div>
						</div>
					</div>
					<div class="column is-small">
						<input type="text" class="input is-small" id="unidad" ng-model="unidad" style="text-align:right;" disabled>
					</div>
					<div class="column is-small">
						<input type="text" class="input is-small" id="precio" ng-model="precio" style="text-align:right;" disabled>
					</div>
					<div class="column is-narrow" style="display:{{agregaProd==true ? 'block':'none'}}">
						<a ng-click="agregaProducto()" aria-label="like" >
						<span class="icon has-text-success" >
							<i class="fas fa-plus-square" title="Agrega el producto actual"></i>
						</span>
						</a>
					</div>
					<div class="column is-narrow">
						<a ng-click="borraProdenProgreso()" aria-label="like">
							<span class="icon has-text-danger">
								<i class="fas fa-minus-circle" title="Borra el producto actual"></i>
							</span>
						</a>
					</div>
				</div>
				<div class="table-container" style="display:none;" id="dispsearch">
					<table style="width:100%;">
						<tr>
							<td>
								<table class="table" style="width:100%" border="1" >
									<col width="15%">
									<col width="40%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<tr style="background-color:Crimson; color:Ivory;">
										<th align="center">Código</th>
										<th>Descripción</th>
										<th align="center">Unidad</th>
										<th align="center">Precio</th>
										<th align="center">Existencia</th>
									</tr>
								</table>
							</td>
							<td>
								<a ng-click="closeDivSearch()" aria-label="like">
								<span class="icon has-text-danger">
									<i class="fas fa-times-circle"></i>
								</span>
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<div style="width:100%; height:200px; overflow:auto; border:2px solid red">
									<table class="table is-hoverable" style="width:100%;">
										<col width="15%">
										<col width="40%">
										<col width="15%">
										<col width="15%">
										<tr ng-repeat="x in lstProdBusqueda" ng-click="selectProdBus($index)">
											<td align="center">{{x.CODIGO}}</td>
											<td>{{x.DESCRIPCION}}</td>
											<td align="center">{{x.UNIDAD_MEDIDAD}}</td>
											<td align="right">{{x.PREC_LISTA_DISP}}</td>
											<td align="right">{{x.STOCK}}</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
					<hr class="hr">
				</div>
				<div class="columns">
					<div class="column">
						<table border="1" style="width:100%; border: 2px blue">
							<tr>
								<td>
									<table class="table" style="width:100%">
										<colgroup>
											<col width="40%">
											<col width="15%">
											<col width="15%">
											<col width="15%">
											<col width="15%">
									  </colgroup>
										<thead>
										<tr class="th" style="background-color:CornflowerBlue; color:Ivory;">
											<th>Descripción</th>
											<th align="center">Cantidad</th>
											<th align="center">Unidad</th>
											<th align="right">Precio</th>
											<th align="right">Importe</th>
										</tr>
										</thead>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<div style="width:100%; height:285px; overflow:auto;">
										<table class="table" style="width:100%;">
											<colgroup>
												<col width="40%">
												<col width="15%">
												<col width="15%">
												<col width="15%">
												<col width="15%">
										  </colgroup>
											<tr ng-repeat="p in lstProdCompra" ng-click="setSelected($index,p.CODIGO)" ng-class="{selected: p.CODIGO === idSelCompra}">
												<td>{{p.DESCRIPCION}}</td>
												<td align="center">{{p.CANTIDAD}}</td>
												<td align="center">{{p.UNIDAD}}</td>
												<td align="right">$ {{p.PRECIO_LISTA | number:2}}</td>
												<td align="right">$ {{p.IMPORTE | number:2}}</td>
											</tr>
										</table>
								</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<button class="button is-info is-rounded" ng-click="iniciaRegistrarCompra()" id="regcompra">Registrar Venta</button>
			</div>
		</div>
		<div class="column is-4" style="border:2px solid green">
			<div class="box box-color" >
				<div class="columns">
					<div class="column has-text-right">
						<h1 class="title is-5">{{fechaPantalla}}</h1>
					</div>
				</div>
				<div class="columns">
					<div class="column has-text-right">
						<h1 class="title is-6">{{hora}}</h1>
					</div>
				</div>
				<hr class="hr" style="margin-bottom:0;">

				<div class="columns" style="background:#000033;">
					<div class="column">
						<h1 class="title is-4 has-text-white" >Documento: </h1>
					</div>
					<div class="column has-text-right">
						<h1 class="title is-4 has-text-white" >{{docto}}</h1>
					</div>
				</div>

				<hr class="hr" style="margin-bottom:0;">
				<div class="columns">
					<div class="column">
						<h1 class="title  has-text-success is-2 has-text-centered is-family-sans-serif is-size-3" >$ {{total | number:2}}</h1>
					</div>
				</div>
				<div class="columns" style="height:130px">
					<div class="column has-text-centered" >
						<h1 class="title is-5" style="display:{{qtyProdSuc==0 && tipo_ps=='P' ?'block':'none'}}; color:red;">Este producto no se encuentra disponible en esta sucursal, verifique existencia en otras.</h1>
						<h1 class="title is-5" style="display:{{esDscto?'block':'none'}}; color:red;">El producto tiene {{descuento}}% descuento</h1>
						<h1 class="title is-5" style="display:{{esPromo?'block':'none'}}; color:red;">El producto está promoción con un precio de ${{promocion}}</h1>
					</div>
				</div>
				<div class="columns is-mobile is-centered">
				  <div class="column is-full" style="border:2px solid blue">
						<table style="width:100%">
							<tr>
								<td align="center" style="vertical-align:middle">
									<figure class="media-center">
										<p class="image is-128x128">
										<img ng-src="{{imagePath}}"  style="display: none;" id="imgfig">
										</p>
								  </figure>
								</td>
							</tr>
						</table>
				  </div>
				</div>

				<div class="columns">
					<div class="column">
						<label>Importe Neto:</label>
					</div>
					<div class="column">
						<label>$ {{importeNeto | number:2}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label>Descuento(-):</label>
					</div>
					<div class="column">
						<label>$ {{dsctoValor | number:2}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label>Impuestos(+):</label>
					</div>
					<div class="column">
						<label>$ {{impuestos | number:2}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Total:</label>
					</div>
					<div class="column">
						<label>$ {{total | number:2}}</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="{{rgstracompra ? 'modal is-active' : 'modal'}}" >
	  <div class="modal-background"></div>
	  <div class="modal-card" style="width:50%">
	   	<header class="modal-card-head" style="height:30px">
	     	<p class="modal-card-title">Registro y Cobranza</p>
	     	<button class="delete" aria-label="close" ng-click="cancelVenta()"></button>
	   	</header>
	   	<section class="modal-card-body" >
				<br>
				<div class="columns">
					<div class="column document has-text-centered">
						<label class="label">{{docto}}</label>
					</div>
					<div class="column imprte has-text-centered">
						<label class="label">$ {{total | number:2}}</label>
					</div>
					<div class="column cambio has-text-centered">
						<label class="label">$ {{cambio | number:2}}</label>
					</div>
				</div>
				<div style="border: 2px red">
				<table border="0" style="width:100%">
					<tr>
						<td style="vertical-align:middle">
							<label class="label">Pago:</label>
						</td>
						<td style="vertical-align:middle">
							<div class="select is-small">
							<select ng-model="fact.formapago" ng-options="x.CLAVE as x.DESCRIPCION for x in lstFormpago"></select>
							</div>
						</td>
						<td>&nbsp;</td>
						<td style="vertical-align:middle"><label class="label">Tarjeta:</label></td>
						<td>
							<input type="text" class="input" ng-model="pago_tarjeta" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onfocus="this.select()">
						</td>
						<td align="right" style="vertical-align:middle">
						<div class="select is-small">
							<select id="idtarjea">
								<option value="">Elija una opción</option>
<?php foreach ($tarjetas as $tarjeta) { ?>
								<option value=<?php echo $tarjeta['ID_TARJETA']?>><?php echo $tarjeta['NOMBRE'] ?></option>
<?php }?>
							</select>
							</div>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:middle"><label class="label">Efectivo:</label></td>
						<td><input type="text" class="input" ng-model="pago_efectivo" id="pago_efectivo" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onclick="this.select()" ng-keyup="calculaCambio()" ></td>
						<td>&nbsp;</td>
						<td style="vertical-align:middle"><label class="label">Cheque:</label></td>
						<td>
							<input type="text" class="input" ng-model="pago_cheque" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onfocus="this.select()">
						</td>
						<td align="right" style="vertical-align:middle">
						<div class="select is-small">
							<select name="banco" id="banco">
								<option value="">Elija una opción</option>
<?php foreach ($bancos as $banco) { ?>
						<option value=<?php echo $banco['ID_BANCO']?>><?php echo $banco['DESCRIPCION'] ?></option>
<?php }?>
						</select>
						</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align:middle">
							<div ng-show="fact.isavailable">
							<input type="checkbox" ng-model="fact.req_factura" ng-disabled="!fact.isfacturable" title="{{fact.isfacturable?'Puede facturar':'Para poder facturar debe tener un cliente'}}"> Requiere Factura: 	
							</div>						
							<div ng-show="!fact.isavailable">
								<label class="label">La sucursal no puede facturar</label>
							</div>
						</td>
						<td>&nbsp;</td>
						<td style="vertical-align:middle">
							<label class="label">Vales:</label>
						</td>
						<td>
							<input type="text" class="input is-small" ng-model="pago_vales" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onfocus="this.select()">
						</td>
						<td align="right" style="vertical-align:middle">
						<div class="select is-small">
							<select id="idvales" >
								<option value="">Elija una opción</option>
<?php foreach ($vales as $vale) { ?>
								<option value="<?php echo $vale['ID_VALE']?>" title="<?php echo $vale['EMPRESA']?>"><?php echo $vale['NOMBRE'] ?></option>
<?php }?>
							</select>
							</div>
						</td>
					</tr>					
				</table>				
			</div>
			<div style="display:{{fact.req_factura ? 'block':'none'}}; width:100%; border: 2px solid red">
				<div class="columns" >	
					<div class="column is-narrow" style="width:131px"><label class="label">Serie:</label></div>
					<div class="column"><input type="text" ng-model="fact.serie" class="input is-small"></div>
					<div class="column is-narrow"><label class="label">Folio:</label></div>
					<div class="column">{{docto}}</div>
				</div>
				<div class="columns">
					<div class="column is-narrow" style="width:131px"><label class="label">Cliente:</label></div>
					<div class="column">{{nombre_cliente}}</div>
					<div class="column is-narrow"><label class="label">RFC:</label></div>
					<div class="column">{{rfc_cliente}}</div>
				</div>
				<div class="columns">
					<div class="column is-narrow" style="width:131px"><label class="label">Uso CFDI:</label></div>
					<div class="column" >
						<div class="select is-small">
						<select ng-model="fact.usocfdi" ng-options="x.CLAVE as x.DESCRIPCION for x in lstUsocfdi"></select>
						</div>
					</div>
				</div>
				<div class="columns">
					<div class="column is-narrow" style="width:131px"><label class="label">Moneda:</label></div>
					<div class="column is-narrow">
						<div class="select is-small">
						<select class="select is-small" ng-model="fact.moneda" ng-options="x.CODIGO as x.NOMBRE for x in lstMoneda"></select>
						</div>
					</div>
					<div class="column is-narrow"><label class="label">Tipo de Cambio:</label></div>
					<div class="column is-narrow" style="width:100px"><input type="number" class="input is-small" ng-model="fact.tipocambio" required ></div>
				</div>
				<div class="columns">
					<div class="column is-narrow" style="width:131px"><label class="label">Metodo Pago:</label></div>
					<div class="column">
						<div class="select is-small">
							<select class="select is-small" ng-model="fact.metodopago" ng-options="x.MET_PAGO as x.DESCRIPCION for x in lstMetpago"></select>
						</div>
					</div>
				</div>
			</div>
	   	</section>
	   	<footer class="modal-card-foot">
	     	<button class="button is-success" ng-click="registraCompra()">Registrar</button>
				<button class="button is-primary" ng-click="imprimeCompra()" >Imprimir</button>
	     	<button class="button is-danger" ng-click="cancelVenta()">Cancelar</button>
	   	</footer>
	  </div>
	</div>

	<div class="{{modalVerifProdSuc ? 'modal is-active' : 'modal' }}" id="avisoborrar">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Verifica Existencia de Productos</p>
				<button class="delete" aria-label="close" ng-click="closeVerifProdSuc();"></button>
			</header>
			<section class="modal-card-body">
				<label class="label">Filtro:<input type="text" class="input is-small" onKeyUp="doFilter(this.value,'lstPrdSucExis')"></label>
				<table style="width:100%">
					<tr>
						<td>
							<table class="table" style="width:100%" border="1">
								<col width="10%">
								<col width="20%">
								<col width="60%">
								<col width="10%">
								<tr>
									<td>No</td>
									<td ng-click="orderByMe('ALIAS')">Alias</td>
									<td style="text-align:center" ng-click="orderByMe('DIRECCION')">DIRECCION</td>
									<td ng-click="orderByMe('STOCK')">Existencias</td>
								</tr>
							</table>
						</td>
					<tr>
					<tr>
						<td>
							<div style="width:100%; height:300px; overflow:auto;">
								<table class="table is-striped" border="1" style="width:100%" id="lstPrdSucExis">
									<col width="10%">
									<col width="20%">
									<col width="60%">
									<col width="10%">
									<tr ng-repeat="x in lstPrdSucExis |orderBy:myOrderBy:sortDir" >
										<td>{{$index+1}}</td>
										<td>{{x.ALIAS}}</td>
										<td>{{x.DIRECCION}}</td>
										<td style="text-align:right">{{x.STOCK}}</td>
									</tr>
								</table>
						</div>
						</td>
					<tr>
				</table>
			</section>
			<footer class="modal-card-foot">
				<button class="button" ng-click="closeVerifProdSuc();">Cerrar</button>
			</footer>
		</div>
	</div>
	<div class="{{modalVerfClte ? 'modal is-active' : 'modal' }}">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Datos Generales del Cliente</p>
	      <button class="delete" aria-label="close" ng-click="closeVerifClte();"></button>
	    </header>
	    <section class="modal-card-body">
	      <table class="table is-striped" style="width:100%" border="0">
					<col width="15%">
					<col width="35%">
					<col width="15%">
					<col width="35%">
					<tr>
						<td><label class="label">{{clave}}</label></td>
						<td colspan="3"><input type="text" class="input is-small" ng-model="cliente.nombre" placeholder="NOMBRE" required></td>
					</tr>
					<tr>
						<td>Domicilio:</td>
						<td colspan="3"><input type="text" class="input is-small" ng-model="cliente.domicilio" placeholder="DOMICILIO"></td>
					</tr>
					<tr>
						<td>Telefono:</td>
						<td><input maxlength="10" type="number" class="input is-small" ng-model="cliente.telefono" placeholder="TELEFONO"></td>
						<td>CP:</td>
						<td><input maxlength="5" type="number" class="input is-small" ng-model="cliente.cp" placeholder="CP"></td>
					</tr>
					<tr>
						<td>Contacto:</td>
						<td colspan="3"><input type="text" class="input is-small" ng-model="cliente.contacto" placeholder="CONTACTO"></td>
					</tr>
					<tr>
						<td>RFC:</td>
						<td><input maxlength="20" type="text" class="input is-small" ng-model="cliente.rfc" placeholder="RFC"></td>
						<td>CURP:</td>
						<td><input maxlength="20" type="text" class="input is-small" ng-model="cliente.curp" placeholder="CURP" required></td>
					</tr>
					<tr>
						<td>Cliente:</td>
						<td>
              <select name="id_tipo_cliente" id="id_tipo_cliente">
      <?php	foreach ($tipo_cliente as $tc) { ?>
      					<option value=<?php echo $tc['ID_TIPO_CLTE'] ?>><?php echo $tc['DESCRIPCION']?></option>
      <?php	} ?>
      				</select>
            </td>
						<td>Crédito:</td>
						<td><input type="number" class="input is-small" ng-model="cliente.dcredito" placeholder="DIAS DE CREDITO"></td>
					</tr>
					<tr>
						<td>Revisión:</td>
						<td>
              <select name="revision" id="revision">
      <?php 		foreach($revision as $rev) {?>
      					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
      <?php 		}?>
      				</select>
            </td>
						<td>Pagos:</td>
						<td>
              <select id="pagos">
      <?php 		foreach($revision as $rev) {?>
      					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
      <?php 		}?>
      				</select>
            </td>
					</tr>
					<tr>
						<td>Forma Pago:</td>
						<td colspan="3">
              <select name="id_forma_pago" id="id_forma_pago">
      <?php foreach($forma_pago as $fp) {?>
      					<option value='<?php echo $fp['ID_FORMA_PAGO']?>'><?php echo trim($fp['CLAVE'])?> <?php echo trim($fp['DESCRIPCION'])?></option>
      <?php }?>
      				</select>
            </td>
					</tr>
					<tr>
						<td>Vendedor:</td>
						<td colspan="3">
              <select name="id_vendedor" id="id_vendedor">
      <?php	foreach($vendedor as $vend) {?>
      					<option value='<?php echo $vend['ID_VENDEDOR']?>'><?php echo trim($vend['NOMBRE'])?></option>
      <?php	}?>
      				</select>
            </td>
					</tr>
					<tr>
						<td>Uso CFDI:</td>
						<td colspan="3">
						<div class="select is-small">
						<select class="select is-small" ng-model="cliente.id_uso_cfdi" ng-options="x.ID_CFDI as x.DESCRIPCION for x in lstUsocfdi"></select>
						</div>
            			</td>
					</tr>
					<tr>
						<td>Email:</td>
						<td colspan="2"><input type="text" class="input is-small" ng-model="cliente.email" placeholder="EMAIL"></td>
            <td></td>
					</tr>
					<tr>
						<td>No Proveedor:</td>
						<td><input type="text" class="input is-small" ng-model="cliente.num_proveedor" placeholder="PROVEEDOR"></td>
            <td colspan="2"></td>
					</tr>
					<tr>
						<td>Observaciones:</td>
						<td colspan="3">
              <textarea ng-model="cliente.notas" id="tacliente"></textarea>
            </td>
					</tr>
				</table>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button" ng-click="enviaDatosCliente();">{{btnVerifClte}}</button>
		  <button class="button" ng-click="closeVerifClte()">Cerrar</button>
	    </footer>
	  </div>
	</div>
</div>
