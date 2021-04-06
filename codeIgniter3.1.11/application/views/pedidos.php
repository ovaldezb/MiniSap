<br>
<input type="hidden" id="updtTblComp" value="F">
<div class="container">
  <div class="notification" align="center">
    <h1 class="title is-1">Registro de Pedidos</h1>
  </div>
</div>
<div class="container"  ng-controller="myCtrlPedi" data-ng-init="init()">
	<div class="container">
		<div class="box" id="barranavegacion" ng-show="!isCapturaPedido">
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
						<a ng-click="agregaPEdido();"><span class="icon has-text-success"><i class="fas fa-plus-square" title="Agrega Pedido"></i></span></a>
					</p>
					<p class="level-item" ng-show="permisos.modificacion">
						<a ng-click="abrePedido()"><span class="icon has-text-info"><i class="fas fa-edit" title="Ver Pedido"></i></span></a>
					</p>
					<p class="level-item" ng-show="permisos.baja">
						<a ng-click="preguntaElimnaPedido()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimna Pedido"></i></span></a>
					</p>
				</div>
			</nav>
		</div>
		<div class="table-container is-centered" style="margin:auto 0px" id="lstclientes" ng-show="!isCapturaPedido">
			<table border="1" style="width:100%">
				<tr>
					<td>
						<table class="table" style="width:100%">
							<col width="9%"> 
							<col width="25%">
							<col width="15%">
							<col width="15%">
							<col width="26%">
							<col width="10%">
							<thead>
								<tr class="tbl-header">
									<td style="color:white; text-align:left; ">DOCUMENTO</td>
									<td style="color:white; text-align:center;">CLIENTE</td>
									<td style="color:white; text-align:center;">FECHA PEDIDO</td>
									<td style="color:white; text-align:center;">IMPORTE</td>
									<td style="color:white; text-align:center;">VENDEDOR</td>
									<td style="color:white; text-align:center;">VENDIDO</td>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:100%; height:500px; overflow:auto;">
							<table class="table" style="width:100%" id="tblClientes">
								<col width="8%"> 
								<col width="25%">
								<col width="17%">
								<col width="15%">
								<col width="25%">
								<col width="10%">
								<tr ng-repeat="x in lstPedidos" ng-click="selectRowPedido(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
									<td style="text-align:center;">{{x.DOCUMENTO}}</td>
									<td style="text-align:center;">{{x.CLIENTE}}</td>
									<td style="text-align:center;">{{x.FECHA_PEDIDO | date : "dd-MM-y"}}</td>
									<td style="text-align:center;">{{x.IMPORTE | currency}}</td>
									<td style="text-align:center;">{{x.VENDEDOR}}</td>
									<td style="text-align:center;">{{x.VENDIDO=='f' ? 'No' : 'Si'}}</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="container" ng-show="isCapturaPedido">
		<div class="columns is-gapless">
			<div class="column is-8">
				<div class="box">
					<div class="columns">
						<div class="column is-narrow" style="width:200px">
							<div class="columns">
								<div class="column is-narrow" style="width:70px">
									<label class="label">Pedido</label>
								</div>
								<div class="column is-narrow" style="width:130px">
									<p class="control is-expanded has-icons-left">
										<input class="input is-small" type="text" ng-model="pedido.docto" id="docto" placeholder="Docto" required>
										<span class="icon is-small is-left">
											<i class="fas fa-file"></i>
										</span>
									</p>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:70px">
									<label for="mooneda" class="label">Moneda</label>
								</div>
								<div class="column is-narrow" style="width:130px">
									<div class="select is-small">
										<select class="select is-small" ng-model="pedido.idmoneda" ng-options="x.ID_MONEDA as x.NOMBRE for x in lstMoneda"></select>
									</div>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow">
									<p class="control">
									<label class="label">Captura Rápida</label>
									<input type="checkbox" ng-model="captura_rapida" ng-click="capturaRapida()" name="caprap" >
									</p>
								</div>
							</div>
						</div>
						<div class="column">
							<div class="columns is-gapless is-multiline">
								<div class="column is-narrow" style="width:80px">
									<label class="label">Cliente</label>
								</div>
								<div class="column is-narrow" style="width:90px">
									<input type="text" ng-model="claveclte" ng-keyup="buscacodcliente($event)" class="input is-small">
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
								
								<div class="column is-9" ng-show="showLstClte" style="margin-left:90px;">
									<table style="width:100%;">
										<tr>
											<td>
												<table class="table is-bordered"  style="width:100%">
													<colgroup>
                            <col width="26%">
													  <col width="74%">
													</colgroup>
													<thead>
														<tr class="tbl-header-seek">
															<td style="text-align=left;">CLAVE</td>
															<td style="text-align:center;">NOMBRE</td>
														</tr>
													</thead>
												</table>
											</td>
                      <td style="text-align:right;">
                        <a ng-click="closeClteSearch()">
                          <span class="icon has-text-danger">
                          <i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
                        </span>
                        </a>
                      </td>
										</tr>
										<tr>
											<td>
												<div style="width:100%; height:100px; overflow:auto;border:2px solid red">
													<table class="table is-bordered" style="width:100%;">
														<colgroup>
                              <col width="26%">
														  <col width="74%">
                            </colgroup>
														<tr ng-repeat="x in lstCliente" ng-click="seleccionaCliente($index)">
															<td style="text-align=left;">{{x.CLAVE}}</td>
															<td style="text-align:center;">{{x.NOMBRE}}</td>
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
									<input type="text" ng-model="pedido.idvendedor" ng-keyup="buscacodvendedor($event)" class="input is-small">
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
								<div class="column is-8" ng-show="listaVendedores" style="" >
									<table style="width:100%; border:2px red">
										<tr>
											<td>
												<table class="table is-bordered" style="width:100%">
													<col width="26%">
													<col width="74%">
													<thead>
														<tr class="tbl-header-seek"> 
															<td style="text-align:center">CLAVE</td>
															<td style="text-align:center">NOMBRE</td>
														</tr>
													</thead>
												</table>
											</td>
                      <td>
                        <a ng-click="closeVendSearch()">
                          <span class="icon has-text-danger">
                            <i title="Cierra la búsqueda" class="fas fa-times-circle"></i>
                          </span>
                        </a>
                      </td>
										</tr>
										<tr>
											<td>
												<div style="width:100%; height:100px; overflow:auto;border:2px solid red;">
													<table class="table" style="width:100%;cursor:pointer">
														<col width="25%">
														<col width="75%">
														<tr ng-repeat="x in lstVendedor" ng-click="seleccionaVendedor($index)">
															<td style="text-align:left;font-size:14px;">{{x.ID_VENDEDOR}}</td>
															<td style="text-align:left;font-size:14px;">{{x.NOMBRE}}</td>
														</tr>
													</table>
											</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="80px">
									<label class="label">Contacto</label>
								</div>
								<div class="column is-narrow">
									<input type="text" class="input is-small" ng-model="pedido.contacto" required>
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:80px">
									<label for="fpago" class="label">F/Pago</label>
								</div>
								<div class="column is-narrow" style="width:130px">
									<div class="select is-small">
										<select ng-model="pedido.tpago" ng-options="x.ID_TIPO_PAGO as x.DESCRIPCION for x in lstTipopago"></select>
									</div>
								</div>
								<div class="column is-narrow" style="width:78px;margin-left:-25px">
									<input type="number" class="input is-small" value="0" ng-model="pedido.dias">
								</div>
								<div class="column is-narrow" style="width:95px;margin-left:-20px">dias</div>
								<div class="column is-narrow" style="width:70px">
									<label for="entregar" class="label">Entregar</label>
								</div>
								<div class="column is-narrow" style="width:156px">
									<input type="text" class="input is-small" id="fechaentrega" ng-model="fechaentrega" ng-blur="fecEntrega()">
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:160px">
									<label for="mpago" class="label">Metodo de Pago</label>
								</div>
								<div class="colummn is-narrow select is-small" style="width:188px">									
									<select ng-model="pedido.fpago" ng-options="x.ID_FORMA_PAGO as x.DESCRIPCION for x in lstFormpago"></select>
								</div>
								<div class="colummnvis-narrow" style="width:60px;margin-left:10px">
									<label for="cuenta" class="label">Cuenta</label>
								</div>
								<div class="colummn is-narrow" style="width:135px">
									<input type="text" class="input is-small" ng-model="pedido.cuenta" >
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
					<button class="button is-info is-rounded" ng-click="registraPedido()" ng-disabled="regpedido" ng-show="!isImprimir">Registrar Pedido</button>
					<button class="button is-info is-rounded" ng-click="imprimePedido('pedido')" ng-disabled="" ng-show="isImprimir">Imprimir</button>
					<button class="button is-dark is-rounded" ng-click="cancelaPedido()">Cerrar</button>
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
							<h1 class="title is-4 has-text-white" >{{pedido.docto}}</h1>
						</div>
					</div>

					<hr class="hr" style="margin-bottom:0;">
					<div class="columns">
						<div class="column">
							<h1 class="title  has-text-success is-2 has-text-centered is-family-sans-serif is-size-3" >$ {{pedido.total | number:2}}</h1>
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
							<label>$ {{pedido.total | number:2}}</label>
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
						<td><input maxlength="20" type="text" class="input is-small" ng-model="cliente.rfc" placeholder="RFC" required></td>
						<td>CURP:</td>
						<td><input maxlength="20" type="text" class="input is-small" ng-model="cliente.curp" placeholder="CURP" ></td>
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
            <select ng-model='cliente.id_vendedor' ng-options="x.ID_VENDEDOR as x.NOMBRE for x in lstVendedorVerif" ></select>
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
	<div class="modal is-active" ng-show="pregElimiPedi">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Advertencia</p>
	      <button class="delete" aria-label="close" ng-click="cerrarEliminaPedido()"></button>
	    </header>
	    <section class="modal-card-body">
	      ¿Está seguro que desea eliminar el Pedido <b>{{doctoEliminar}}</b>?
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-success" ng-click="borraPedido()">Si</button>
	      <button class="button" ng-click="cerrarEliminaPedido()">No</button>
	    </footer>
	  </div>
	</div>
	<table style="width: 100%; display:none" id="pedido">
		<tbody>
			<tr>`
				<td style="width: 107.067px">Logo</td>
				<td style="width: 414.933px">
					<table style="border:2px solid black">
						<tr>
							<td colspan="2">EMPRESA</td>
						</tr>
						<tr>
							<td>Nombre:</td>
							<td>{{empresa.nombre}}</td>
						</tr>
						<tr>
							<td>Domicilio:</td>
							<td>{{empresa.domicilio}}</td>
						</tr>
						<tr>
							<td>RFC:</td>
							<td>{{empresa.rfc}}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td style="width: 100%" colspan="2">
					<table tyle="border:2px solid black">
						<tr>
							<td colspan="2">CLIENTE</td>
						</tr>
						<tr>
							<td>Nombre:</td>
							<td>{{nombre_cliente}}</td>
						</tr>
						<tr>
							<td>Domicilio:</td>
							<td>{{cliente.domicilio}}</td>
						</tr>
						<tr>
							<td>Contacto:</td>
							<td>{{pedido.contacto}}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="width: 100%" colspan="2">&nbsp;</td>
			</tr>
			<tr>
			<td style="width: 100%" colspan="2">
				<div style="border: 2px solid black; height: 350px; width: 100%;">
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
							<td style="width: 81.2px; text-align: center">{{p.UNIDAD}}</td>
							<td style="width: 128px; text-align: right">$ {{p.PRECIO_LISTA | number:2}}</td>
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