<br>
<input type="hidden" id="updtTblComp" value="F">
<div class="container">
  <div class="notification" align="center">
    <h1 class="title is-1">Facturas</h1>
  </div>
</div>
<div class="container"  ng-controller="myCtrlFacturacion" data-ng-init="init()">
	<div class="container">
		<div class="box" id="barranavegacion" ng-show="!isCapturaFactura">
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
						<a ng-click="agregaFactura();"><span class="icon has-text-success"><i class="fas fa-plus-square" title="Agrega Factura"></i></span></a></p>
					<p class="level-item" ng-show="permisos.modificacion">
						<a ng-click="abreFactura()"><span class="icon has-text-info"><i class="fas fa-edit" title="Ver Factura"></i></span></a></p>
					<p class="level-item" ng-show="permisos.baja">
						<a ng-click="preguntaElimnaFactura()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Factura"></i></span></a></p>
				</div>
			</nav>
		</div>
		<div class="table-container is-centered" style="margin:auto 0px" id="lstclientes" ng-show="!isCapturaFactura">
			<table style="width:99.5%">
				<tr>
					<td>
						<table class="table is-bordered" style="width:100%">							
							<col width="9%">
							<col width="9%">
							<col width="14%">
							<col width="8%">
							<col width="8%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
							<col width="12%">
							<tr class="tbl-header">
								<td style="text-align:center;font-size:12px">DOCUMENTO</td>									
								<td style="text-align:center;font-size:12px">FECHA</td>
								<td style="text-align:center;font-size:12px">CLIENTE</td>
								<td style="text-align:center;font-size:12px">IMPORTE</td>
								<td style="text-align:center;font-size:12px">SALDO</td>
								<td style="text-align:center;font-size:12px">FORMA DE PAGO</td>
								<td style="text-align:center;font-size:12px">REVISION</td>
								<td style="text-align:center;font-size:12px">VENCE</td>
								<td style="text-align:center;font-size:12px">PEDIDO</td>
								<td style="text-align:center;font-size:12px">VENDEDOR</td>
							</tr>							
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:100%; height:500px; overflow:auto;">
							<table class="table is-bordered is-hoverable" style="width:100%" id="tblClientes">
								<col width="9%">
								<col width="9%">
								<col width="14%">
								<col width="8%">
								<col width="8%">
								<col width="10%">
								<col width="10%">
								<col width="10%">
								<col width="10%">
								<col width="12%">
								<tr ng-repeat="x in lstFacturas" ng-click="selectRowFactura(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
									<td style="text-align:center;font-size:12px">{{x.DOCUMENTO}}</td>									
									<td style="text-align:center;font-size:12px">{{x.FECHA_FACTURA}}</td>
									<td style="text-align:center;font-size:12px">{{x.CLIENTE}}</td>
									<td style="text-align:center;font-size:12px">{{x.IMPORTE | currency}}</td>
									<td style="text-align:center;font-size:12px">{{x.SALDO | currency}}</td>
									<td style="text-align:center;font-size:12px">{{x.ID_TIPO_PAGO == 1 ? 'Contado':'Crédito'}}</td>
									<td style="text-align:center;font-size:12px">{{x.FECHA_REVISION}}</td>
									<td style="text-align:center;font-size:12px">{{x.FECHA_VENCIMIENTO}}</td>
									<td style="text-align:center;font-size:12px">&nbsp;</td>
									<td style="text-align:center;font-size:12px">{{x.VENDEDOR}}</td>
								</tr>
							</table>
						</div>
					</td>
			</tr>
			</table>
		</div>
	</div>
	<div class="container" ng-show="isCapturaFactura">
		<div class="columns is-gapless">
			<div class="column is-8">
				<div class="box">
					<div class="columns">
						<div class="column is-narrow" style="width:200px">
							<div class="columns is-gapless">
								<div class="column is-narrow" style="width:70px;margin-left:-15px">
									<label class="label">Numero</label>
								</div>
								<div class="column is-narrow" style="width:100px">
									<input class="input is-small" type="text" ng-model="factura.docto" id="docto" placeholder="Docto" required>
								</div>
								<div class="column is-narrow" style="width:30px">
									<input type="text" class="input is-small" ng-model="tcaptura" ng-keyup="entrydata()" onKeyUp="this.value = this.value.toUpperCase();"/>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:70px;margin-left:-15px">
									<label for="mooneda" class="label">Moneda</label>
								</div>
								<div class="column is-narrow" style="width:130px">
									<div class="select is-small">
										<select class="select is-small" ng-model="factura.idmoneda" ng-options="x.ID_MONEDA as x.NOMBRE for x in lstMoneda"></select>
									</div>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="margin-left:-15px">
									<p class="control">
									<label class="label">Captura Rápida</label>
									<input type="checkbox" ng-model="captura_rapida" ng-click="capturaRapida()" name="caprap" >
									</p>
								</div>
							</div>
						</div>
						<div class="column">
							<div class="columns is-gapless is-multiline">
								<div class="column is-narrow" style="width:60px;margin-left:20px">
									<label class="label">Cliente</label>
								</div>
								<div class="column is-narrow" style="width:90px">
									<input type="text" ng-model="claveclte" class="input is-small">
								</div>
								<div class="column is-narrow">
									<div class="field has-addons" style="width:340px">
										<p class="control is-expanded has-icons-left">
											<input class="input is-small" ng-keyup="buscacliente($event)" ng-model="nombre_cliente" type="text" placeholder="Cliente">
											<span class="icon is-small is-left">
												<i class="fas fa-user"></i>
											</span>
										</p>
										<p class="control">
											<a class="button is-info is-small" ng-click="VerificarCliente()">Verificar</a>
										</p>
									</div>
								</div>
								
								<div class="column is-narrow" ng-show="showLstClte" style="margin-left:80px; overflow:auto; heigth:50px; width:360px;">
									<table style="width:100%; border:2px solid black">
										<tr>
											<td>
												<table class="table" style="width:100%">
													<col width="26%">
													<col width="63%">
													<col width="11%">
													<tr>
														<td style="text-align:left;">Clave</td>
														<td style="text-align:left;">Nombre</td>
														<td style="text-align:right;">
															<a ng-click="closeClteSearch()">
															<span class="icon has-text-danger">
															<i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
															</span>
															</a>
														</td>
													</tr>													
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<div style="width:100%; height:100px; overflow:auto;">
													<table class="table is-hoverable" style="width:100%;">
														<col width="26%">
														<col width="74%">
														<tr ng-repeat="x in lstCliente" ng-click="seleccionaCliente($index)">
															<td style="text-align:left;">{{x.CLAVE}}</td>
															<td style="text-align:left;">{{x.NOMBRE}}</td>
														</tr>
													</table>
											</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="columns is-gapless is-multiline">
								<div class="column is-narrow" style="width:80px">
									<label class="label">Vendedor</label>
								</div>
								<div class="column is-2">
									<input type="text" ng-model="factura.idvendedor" class="input is-small">
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
								<div class="column is-narrow" style="width:350; margin-left:80px; border:2px black solid" ng-show="isLstVendedor" >
									<table style="width:100%; border:2px red">
										<tr>
											<td>
												<table style="width:100%">
													<col width="26%">
													<col width="74%">
													<tr>
														<td style="text-align:left">Clave</td>
														<td style="text-align:left">Nombre</td>
														<td>
															<a ng-click="closeVendSearch()">
															<span class="icon has-text-danger">
																<i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
															</span>
														</a>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<div style="width:100%; height:100px; overflow:auto;">
													<table class="table is-hoverable" style="width:100%;">
														<col width="25%">
														<col width="75%">
														<tr ng-repeat="x in lstVendedor" ng-click="seleccionaVendedor($index)">
															<td style="text-align:left;">{{x.ID_VENDEDOR}}</td>
															<td style="text-align:left;">{{x.NOMBRE}}</td>
														</tr>
													</table>
											</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:80px">
									<label class="label">Contacto</label>
								</div>
								<div class="column is-narrow">
									<input type="text" class="input is-small" ng-model="factura.contacto" required>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:80px">
									<label class="label">F/Pago</label>
								</div>
								<div class="column is-narrow" style="width:130px">
									<select ng-model="factura.tpago" ng-change="cambioTpago()" ng-options="x.ID_TIPO_PAGO as x.DESCRIPCION for x in lstTipopago"></select>
								</div>
								<div class="column is-narrow" style="width:78px;margin-left:-25px">
									<input type="number" class="input is-small" value="0" ng-model="factura.dias" ng-disabled="factura.tpago == 1">
								</div>
								<div class="column is-narrow" style="width:55px;margin-left:-20px">dias</div>
								<div class="column is-narrow" style="width:82px;margin-right:-20px">
									<label class="label">% Desc</label>
								</div>
								<div class="column is-narrow" style="width:75px;margin-rigth:-10px">
									<input type="number" class="input is-small" ng-model="factura.descuento" style="text-align:center">
								</div>
								<div class="column is-narrow" style="width:40px;margin-rigth:-10px">
									<label for="entregar" class="label">Iva</label>
								</div>
								<div class="column is-narrow" style="width:75px">
									<input type="number" class="input is-small" id="iva" ng-model="factura.iva" style="text-align:center">
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:160px">
									<label for="mpago" class="label">Forma de Pago</label>
								</div>
								<div class="colummn is-narrow select is-small" style="width:188px">									
									<select ng-model="factura.fpago" ng-options="x.CLAVE as x.DESCRIPCION for x in lstFormpago"></select>
								</div>
								<div class="colummnvis-narrow" style="width:60px;margin-left:10px">
									<label for="cuenta" class="label">Cuenta</label>
								</div>
								<div class="colummn is-narrow" style="width:135px">
									<input type="text" class="input is-small" ng-model="factura.cuenta" >
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow">
									<label class="label">Uso del CFDI</label>
								</div>
								<div class="column is-narrow select is-small" style="width:380px">
									<select ng-model="factura.cfdi" ng-options="x.ID_CFDI as x.CLAVE+' '+x.DESCRIPCION for x in lstUsocfdi "></select>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:80px">
									<label class="label">M&eacute;todo</label>
								</div>
								<div class="column is-narrow" style="width:280px">
									<select ng-model="factura.mpago" ng-options="x.ID_MET_PAGO as x.MET_PAGO+' '+x.DESCRIPCION for x in lstMetpago"></select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box" id="compras">
					<nav class="level" id="barraProducto" ng-show="!isImprimir">
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
					<div class="columns" ng-show="!isImprimir">
						<div class="column is-2">
							<input type="text" id="codigo_prodto" ng-model="producto.codigo_prodto"  ng-keyup="buscaprodbycodigo($event)" class="input is-small" >
						</div>
						<div class="column is-4">
							<input type="text"  ng-model="producto.prod_desc" ng-keyup="buscprodbydesc($event)" class="input is-small" >
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
							<input type="text" class="input is-small" id="unidad" ng-model="producto.unidad" style="text-align:right;" disabled>
						</div>
						<div class="column is-small">
							<input type="text" class="input is-small" id="precio" ng-model="producto.precio" style="text-align:right;" disabled>
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
					<div class="container" ng-show="dispsearch">
						<table style="width:100%;">
							<tr>
								<td>
									<table class="table is-bordered" style="width:100%">
										<col width="23%">
										<col width="32%">
										<col width="15%">
										<col width="15%">
										<col width="15%">
										<tr style="background-color:Crimson; color:Ivory;">
											<td style="text-align:center">Código</td>
											<td style="text-align:center">Descripción</td>
											<td style="text-align:center">Unidad</td>
											<td style="text-align:center">Precio</td>
											<td style="text-align:center">Existencia</td>
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
										<table class="table is-hoverable is-bordered" style="width:100%;">
											<col width="23%">
											<col width="32%">
											<col width="15%">
											<col width="15%">
											<col width="15%">
											<tr ng-repeat="x in lstProdBusqueda" ng-click="selectProdBus($index)">
												<td style="text-align:left;font-size:12px">{{x.CODIGO}}</td>
												<td style="font-size:12px">{{x.DESCRIPCION}}</td>
												<td style="text-align:center;font-size:12px">{{x.UNIDAD_MEDIDA}}</td>
												<td style="text-align:right;font-size:12px">{{x.PRECIO_LISTA | currency}}</td>
												<td style="text-align:right;font-size:12px">{{x.STOCK}}</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
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
											<tr class="tbl-header">
												<td>Descripción</td>
												<td style="text-align:center">Cantidad</td>
												<td style="text-align:center">Unidad</td>
												<td style="text-align:right">Precio</td>
												<td style="text-align:right">Importe</td>
											</tr>
											
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
													<td style="text-align:center">{{p.CANTIDAD}}</td>
													<td style="text-align:center">{{p.UNIDAD}}</td>
													<td style="text-align:right">$ {{p.PRECIO_LISTA | number:2}}</td>
													<td style="text-align:	right">$ {{p.IMPORTE | number:2}}</td>
												</tr>
											</table>
									</div>
									</td>
								</tr>
							</table>
						</div>
					</div>
					<button class="button is-info is-rounded" ng-click="registraFactura()" ng-disabled="regfactura" >Registrar</button>
					<button class="button is-dark is-rounded" ng-click="cierraFactura()">Cerrar</button>
				</div>
			</div>
			<div class="column is-4" style="border:2px solid green">
				<div class="box box-color">
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
							<h1 class="title is-4 has-text-white" >{{factura.docto}}</h1>
						</div>
					</div>

					<hr class="hr" style="margin-bottom:0;">
					<div class="columns">
						<div class="column">
							<h1 class="title  has-text-success is-2 has-text-centered is-family-sans-serif is-size-3" >$ {{factura.total | number:2}}</h1>
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
											<img ng-src="{{producto.imagePath}}"  style="display: none;" id="imgfig">
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
							<label>$ {{factura.total | number:2}}</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="{{modalVerifProdSuc ? 'modal is-active' : 'modal' }}" id="avisoborrar">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Verifica existencia de productos</p>
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
	      <button class="button is-info" ng-click="enviaDatosCliente();">{{btnVerifClte}}</button>
		  <button class="button is-error" ng-click="closeVerifClte()">Cerrar</button>
	    </footer>
	  </div>
	</div>
	<div class="modal is-active" ng-show="showInputData">
		<div class="modal-background"></div>
		<div class="modal-card" style="width:1100px">
			<header class="modal-card-head">
				<p class="modal-card-title">Seleccionar Pedido</p>
				<button class="delete" aria-label="close" ng-click="closeInputData()"></button>
			</header>
			<section class="modal-card-body">
				<table class="table is-bordered" style="width:100%">
					<tr>
						<td style="text-align:center;width:60px">Documento</td>
						<td style="text-align:center;width:150px">Cliente</td>
						<td style="text-align:center;width:120px">Fecha Pedido</td>
						<td style="text-align:center;width:80px">Importe</td>
						<td style="text-align:center;width:100px">Vendedor</td>
					</tr>
				</table>
				<div style="width:100%; height:500px; overflow:auto; margin-top:-24px; border:2px solid black">
					<table class="table is-hoverable" style="width:100%" id="tblClientes">
						<tr ng-repeat="x in lstPedidos" ng-click="selectRowPedido(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
							<td style="text-align:center;width:60px">{{x.DOCUMENTO}}</td>
							<td style="text-align:center;width:150px">{{x.CLIENTE}}</td>
							<td style="text-align:center;width:120px">{{x.FECHA_PEDIDO}}</td>
							<td style="text-align:right;width:80px">{{x.IMPORTE | currency}}</td>
							<td style="text-align:right;width:100px">{{x.VENDEDOR}}</td>
						</tr>
					</table>
				</div>
			</section>
			<footer class="modal-card-foot">
				<button class="button is-success" ng-click="seleccionarPedido()">Seleccionar</button>
				<button class="button" ng-click="closeInputData()">Cerrar</button>
			</footer>
		</div>
	</div>

	<div class="modal is-active" ng-show="showEliminaFactura">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Eliminar Factura</p>
				<button class="delete" aria-label="close" ng-click="closeEliminaFactura()"></button>
			</header>
			<section class="modal-card-body">
				<p>Está seguro que desea eliminar la factura <strong>{{factura.docto}}</strong>?</p>
			</section>
			<footer class="modal-card-foot">
				<button class="button is-success" ng-click="eliminarFactura()">Si</button>
				<button class="button" ng-click="closeEliminaFactura()">No</button>
			</footer>
		</div>
	</div>
	
	<table style="width: 100%; display:none" id="factura">
		<tbody>
			<tr>
			<td style="width: 107.067px">Logo</td>
			<td style="width: 414.933px">Datos de la empresa</td>
			</tr>
			<tr>
			<td style="width: 100%" colspan="2">Datos del cliente</td>
			</tr>
			<tr>
			<td style="width: 100%" colspan="2">&nbsp;</td>
			</tr>
			<tr>
			<td style="width: 100%" colspan="2">
				<div style="border: 2px solid black; height: 400px; width: 100%;">
				<table style="width: 100%" >
					<tbody>
						<tr>
							<td style="width: 200px; text-align: center">Descripcion</td>
							<td style="width: 73.8px; text-align: center">Cantidad</td>
							<td style="width: 81.2px; text-align: center">Unidad</td>
							<td style="width: 128px; text-align: right">Costo Unitario</td>
							<td style="width: 104px; text-align: right">Costo Total</td>
						</tr>
						<tr ng-repeat="p in lstProdCompra">
							<td style="width: 200px; text-align: center">{{p.DESCRIPCION}}</td>
							<td style="width: 73.8px; text-align: center">{{p.CANTIDAD}}</td>
							<td style="width: 81.2px; text-align: center">{{p.UNIDAD_MEDIDA}}</td>
							<td style="width: 128px; text-align: right">$ {{p.PRECIO | number:2}}</td>
							<td style="width: 104px; text-align: right">$ {{p.IMPORTE | number:2}}</td>
						</tr>
					</tbody>
				</table>
				</div>
			</td>
			</tr>
			<tr>
				<td style="width:100%;" colspan="2">
					<table style="width: 100%" >
						<tbody>
						<tr>
							<td style="width: 83%; text-align: right">Subtotal</td>
							<td style="width: 17%; text-align: right">$132.16</td>
						</tr>
						<tr>
							<td style="width: 83%; text-align: right">Impuestos</td>
							<td style="width: 17%; text-align: right">$21.14</td>
						</tr>
						<tr>
							<td style="width: 83%; text-align: right">Total</td>
							<td style="width: 17%; text-align: right">$153.30</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>