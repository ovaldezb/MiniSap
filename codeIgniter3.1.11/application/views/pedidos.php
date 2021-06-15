<input type="hidden" id="updtTblComp" value="F">
<div class="container">
  <div class="notification">
    <h1 class="title is-4 has-text-centered">Registro de Pedidos</h1>
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
						<a ng-show="indexRowPedido == -1">
              <span class="icon has-text-info">
                <i class="fas fa-edit" title="Ver Pedido" style="color:grey"></i>
              </span>
            </a>
            <a ng-click="abrePedido()" ng-show="indexRowPedido != -1">
              <span class="icon has-text-info">
                <i class="fas fa-edit" title="Ver Pedido"></i>
              </span>
            </a>
					</p>
					<p class="level-item" ng-show="permisos.baja">
						<a ng-show="!estatus">
              <span class="icon has-text-danger">
                <i class="far fa-trash-alt" title="Cancela Pedido" style="color:grey"></i>
              </span>
            </a>
            <a ng-click="borraPedido()" ng-show="estatus">
              <span class="icon has-text-danger">
                <i class="far fa-trash-alt" title="Cancela Pedido"></i>
              </span>
            </a>
					</p>
				</div>
			</nav>
		</div>
		<div class="table-container is-centered" style="margin:auto 0px" id="lstclientes" ng-show="!isCapturaPedido">
			<table border="1" style="width:100%">
				<tr>
					<td>
						<table class="table is-bordered" style="width:100%">
              <colgroup>
							  <col width="12%"> 
							  <col width="23%">
							  <col width="15%">
							  <col width="15%">
							  <col width="15%">
							  <col width="10%">
                <col width="10%">
              </colgroup>
							<thead>
								<tr class="tbl-header">
									<td style="color:white; text-align:left;">DOCUMENTO</td>
									<td style="color:white; text-align:center;">CLIENTE</td>
									<td style="color:white; text-align:center;">FECHA PEDIDO</td>
									<td style="color:white; text-align:center;">IMPORTE</td>
									<td style="color:white; text-align:center;">VENDEDOR</td>
									<td style="color:white; text-align:center;">FACTURADO</td>
                  <td style="color:white; text-align:center;">ESTATUS</td>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:100%; height:500px; overflow:auto;">
							<table class="table is-hoverable is-bordered" style="width:100%" id="tblClientes">
                <colgroup>
								  <col width="12%"> 
								  <col width="23%">
								  <col width="15%">
								  <col width="15%">
								  <col width="15%">
								  <col width="10%">
                  <col width="10%">
                </colgroup>
								<tr ng-repeat="x in lstPedidos" ng-click="selectRowPedido(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
									<td class="font12" style="text-align:left;">{{x.DOCUMENTO}}</td>
									<td class="font12" style="text-align:left;">{{x.CLIENTE}}</td>
									<td class="font12" style="text-align:center;">{{x.FECHA_PEDIDO | date : "dd-MM-y"}}</td>
									<td class="font12" style="text-align:center;">{{x.IMPORTE | currency}}</td>
									<td class="font12" style="text-align:center;">{{x.VENDEDOR}}</td>
									<td class="font12" style="text-align:center;">{{x.VENDIDO=='f' ? 'No' : 'Si'}}</td>
                  <td class="font12" style="text-align:center;">{{x.ESTATUS}}</td>
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
						<div class="column is-narrow" style="width:220px">
							<div class="columns">
								<div class="column is-4">
									<label class="label">Pedido</label>
								</div>
								<div class="column is-8">
									<p class="control is-expanded has-icons-left">
										<input class="input is-small" type="text" ng-model="pedido.docto" id="docto" placeholder="Docto" required>
										<span class="icon is-small is-left">
											<i class="fas fa-file"></i>
										</span>
									</p>
								</div>
							</div>
							<div class="columns">
								<div class="column is-4">
									<label for="mooneda" class="label">Moneda</label>
								</div>
								<div class="column is-8">
									<div class="select is-small">
										<select class="select is-small" ng-model="pedido.idmoneda" ng-options="x.ID_MONEDA as x.NOMBRE for x in lstMoneda"></select>
									</div>
								</div>
							</div>
              <div class="columns">
                <div class="column is-4">
									<label for="entregar" class="label">F/Entrega</label>
								</div>
								<div class="column is-8">
									<input type="text" class="input is-small" id="fechaentrega" ng-model="fechaentrega" ng-blur="fecEntrega()">
								</div>
              </div>
              <div class="columns">
                <div class="column is-4 " >
									<label for="cuenta" class="label">Cuenta</label>
								</div>
								<div class="column is-8">
									<input type="text" class="input is-small" ng-model="pedido.cuenta" >
								</div>
              </div>
              <div class="columns" ng-show="showUsuario">
                <div class="column">
                  <label class="label">Usuario:</label>
                </div>
                <div class="column">{{usuario}}</div>
              </div>
						</div>
						<div class="column">
							<div class="columns is-gapless is-multiline">
								<div class="column is-2">
									<label class="label">Cliente</label>
								</div>
								<div class="column is-2">
									<input type="text" ng-model="claveclte" ng-keyup="buscacodcliente($event)" class="input is-small">
								</div>
								<div class="column is-narrow">
									<div class="field has-addons" style="width:300px">
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
															<td class="font12" style="text-align=left;">{{x.CLAVE}}</td>
															<td class="font12" style="text-align:center;">{{x.NOMBRE}}</td>
														</tr>
													</table>
											</div>
											</td>
										</tr>
									</table>
								</div>
							</div>

							<div class="columns is-gapless is-multiline">
								<div class="column is-2">
									<label class="label">Vendedor</label>
								</div>
								<div class="column is-2">
									<input type="text" ng-model="pedido.idvendedor" ng-keyup="buscacodvendedor($event)" class="input is-small">
								</div>
								<div class="column is-narrow" style="width:228px">
									<div class="field has-addons">
										<p class="control is-expanded has-icons-left">
											<input class="input is-small" type="text" ng-model="nombre_vendedor" ng-keyup="buscavendedor($event)" placeholder="Vendedor">
											<span class="icon is-small is-left">
												<i class="fas fa-user-tie"></i>
											</span>
										</p>
									</div>
								</div>
								<div class="column is-8" ng-show="listaVendedores" style="margin-left:90px" >
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
															<td class="font12" style="text-align:left;">{{x.ID_VENDEDOR}}</td>
															<td class="font12" style="text-align:left;">{{x.NOMBRE}}</td>
														</tr>
													</table>
											</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
							<div class="columns">
								<div class="column is-2" >
									<label for="fpago" class="label">T/Pago</label>
								</div>
								<div class="column is-3">
									<div class="select is-small">
										<select ng-model="pedido.tpago" ng-options="x.ID_TIPO_PAGO as x.DESCRIPCION for x in lstTipopago"></select>
									</div>
								</div>
								<div class="column is-narrow" style="width:78px;margin-left:-25px">
									<input type="number" class="input is-small" value="0" ng-model="pedido.dias" ng-disabled="pedido.tpago==1" title="dias de crédito">
								</div>
                <div class="column is-narrow" style="margin-left:-15px">
									<label for="mpago" class="label">F/Pago</label>
								</div>
                <div class="column is-narrow" style="margin-left:-20px">
                  <div class="select is-small">
									  <select ng-model="pedido.fpago" ng-options="x.ID_FORMA_PAGO as x.DESCRIPCION for x in lstFormpago"></select>
                  </div>
								</div>
							</div>
              <div class="columns">
                <div class="column is-2"><label for="" class="label">M/Pago</label></div>
                <div class="column is-8">
                  <div class="select is-small">
									  <select ng-model="pedido.mpago" ng-options="x.ID_MET_PAGO as x.MET_PAGO+' '+x.DESCRIPCION for x in lstMetpago" ng-style="mpago_style" ng-change="selectMPago()"></select>
                  </div>
                </div>
              </div>
							<div class="columns">
								<div class="column is-narrow">
                  <label class="label">Entregar en:</label>
                </div>
                <div class="column is-narrow">
                  <div class="select is-small">
                    <select ng-model="pedido.domi" id="domicilios" ng-options="x.ID_DOMICILIO as x.LUGAR+' '+x.CIUDAD for x in lstDomis" ng-change="cambiaDomicilio()"></select>
                  </div>
                </div>
							</div>
              <div class="columns">
								<div class="column is-2">
									<label class="label">Notas</label>
								</div>
								<div class="column is-narrow">
									<input type="text" class="input is-small" ng-model="pedido.comentarios">
								</div>
							</div>
						</div>
					</div>
				</div>
        <progress class="progress is-small is-primary" max="100" ng-show="showProgressBar">15%</progress>
				<div class="box" id="compras" style="border:1px grey solid;margin-top:-20px">
					<nav class="level" id="barraProducto" ng-show="isActualiza || isRegistra">
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
              <div class="level-item"></div>
              <div class="level-item"></div>
              <div class="level-item"></div>
              <div class="level-item"></div>
              <div class="level-item"></div>
              <div class="level-item" ng-show="isMadera">
                <label class="label">Calidad</label>
              </div>
              <div class="level-item" ng-show="isMadera">
                <div class="select is-small">
                  <select name="calidad" ng-model="producto.idcalidad" ng-options="x.ID_CALIDAD_MADERA as x.DESCRIPCION for x in lstCalidadMadera"></select>
                </div>
              </div>
						</div>
						<div class="level-right">
              <div class="level-item" ng-show="lstProdCompra.length > 0">
							  <button class="button is-info" ng-click="addDescuento()">Descuento</button>
						  </div>
						  <div class="level-item">
								<button class="button is-success" ng-click="verificaExistencia()" style="display:{{isVerifExis ? 'block':'none'}}">Verificar Existencia</button>
							</div>
						</div>
					</nav>
					<div class="columns" ng-show="isActualiza || isRegistra" style="margin-top:-30px">
						<div class="column is-2">
							<input type="text"  ng-model="producto.codigo_prodto"  ng-keyup="buscaprodbycodigo($event)" class="input is-small" >
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
							<input type="text" class="input is-small" id="precio" ng-model="producto.precio" style="text-align:right;" ng-disabled="!permisos.modificacion">
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
					<div class="table-container" ng-show="dispsearch">
						<table style="width:100%;">
							<tr>
								<td>
									<table class="table" style="width:99%" border="1" >
                    <colgroup>
                      <col width="20%">
                      <col width="35%">
                      <col width="15%">
                      <col width="15%">
                      <col width="15%">
                    </colgroup>
										<tr style="background-color:Crimson; color:Ivory;">
											<td style="text-align:center">Código</td>
											<td>Descripción</td>
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
                      <colgroup>
											  <col width="20%">
											  <col width="35%">
											  <col width="15%">
											  <col width="15%">
                        <col width="15%">
                      </colgroup>
											<tr ng-repeat="x in lstProdBusqueda" ng-click="selectProdBus($index)">
												<td class="font12" style="text-align:center">{{x.CODIGO}}</td>
												<td class="font12">{{x.DESCRIPCION}}</td>
												<td class="font12" style="text-align:center">{{x.UNIDAD_MEDIDA}}</td>
												<td class="font12" style="text-align:right">{{x.PREC_LISTA_DISP}}</td>
												<td class="font12" style="text-align:right">{{x.STOCK}}</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
						</table>
						<hr class="hr">
					</div>
					<div class="columns" style="margin-top:-25px">
						<div class="column">		
              <table class="table is-bordered" style="width:100%">
                <colgroup>
                  <col width="40%">
                  <col width="12%">
                  <col width="12%">
                  <col width="12%">
                  <col width="12%">
                  <col width="12%">
              </colgroup>
                <tbody>
                <tr class="tbl-header">
                  <td>Descripción</td>
                  <td style="text-align:center">Cantidad</td>
                  <td style="text-align:center">Unidad</td>
                  <td style="text-align:right">Precio</td>
                  <td style="text-align:right">Descto</td>
                  <td style="text-align:right">Importe</td>
                </tr>
                </tbody>
              </table>
              <div style="width:100%; height:185px; overflow:auto;margin-top:-25px">
                <table class="table is-bordered is-hoverable" style="width:100%;">
                  <colgroup>
                    <col width="39%">
                    <col width="13%">
                    <col width="12%">
                    <col width="12%">
                    <col width="12%">
                    <col width="12%">
                  </colgroup>
                  <tr ng-repeat="p in lstProdCompra" ng-click="setSelected($index,p.CODIGO)" ng-class="{selected: p.CODIGO === idSelCompra}">
                    <td class="font12">{{p.DESCRIPCION}}</td>
                    <td class="font12" style="text-align:center">{{p.CANTIDAD}}</td>
                    <td class="font12" style="text-align:center">{{p.UNIDAD_MEDIDA}}</td>
                    <td class="font12" style="text-align:right">{{p.PRECIO_LISTA | currency}}</td>
                    <td class="font12" style="text-align:right">{{p.DESCUENTO * p.CANTIDAD * p.PRECIO_LISTA / 100 | currency}}</td>
                    <td class="font12" style="text-align:right">{{p.IMPORTE | currency}}</td>
                  </tr>
                </table>
            </div>
						</div>
					</div>
					<button class="button is-info is-rounded" ng-click="registraPedido()" ng-disabled="regpedido" ng-show="isRegistra">Registrar Pedido</button>
          <button class="button is-info is-rounded" ng-click="actualizaPedido()" ng-disabled="actpedido" ng-show="isActualiza">Actualiza Pedido</button>
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
							<label>Sub Total:</label>
						</div>
						<div class="column" style="text-align:right">
							<label>{{pedido.subtotal | currency}}</label>
						</div>
					</div>
					<div class="columns">
						<div class="column">
							<label>Descuento(-):</label>
						</div>
						<div class="column" style="text-align:right">
							<label>{{dsctoValor | currency}}</label>
						</div>
					</div>
					<div class="columns">
						<div class="column">
							<label>Impuestos(+):</label>
						</div>
						<div class="column" style="border-bottom:2px solid black; text-align:right;">
							<label>{{impuestos | currency}}</label>
						</div>
					</div>
					<div class="columns">
						<div class="column">
							<label class="label">Total:</label>
						</div>
						<div class="column" style="text-align:right">
							<label>{{pedido.total | currency}}</label>
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
              <div class="select is-small">
                <select name="id_tipo_cliente" id="id_tipo_cliente">
      <?php	foreach ($tipo_cliente as $tc) { ?>
      					  <option value=<?php echo $tc['ID_TIPO_CLTE'] ?>><?php echo $tc['DESCRIPCION']?></option>
      <?php	} ?>
      				  </select>
              </div>
            </td>
						<td>Crédito:</td>
						<td><input type="number" class="input is-small" ng-model="cliente.dcredito" placeholder="DIAS DE CREDITO"></td>
					</tr>
					<tr>
						<td>Revisión:</td>
						<td>
              <div class="select is-small">
              <select name="revision" id="revision">
      <?php 		foreach($revision as $rev) {?>
      					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
      <?php 		}?>
      				</select>
              </div>
            </td>
						<td>Pagos:</td>
						<td>
              <div class="select is-small">
              <select id="pagos">
      <?php 		foreach($revision as $rev) {?>
      					<option value='<?php echo $rev['ID_DIA']?>'><?php echo trim($rev['NOMBRE'])?></option>
      <?php 		}?>
      				</select>
              </div>
            </td>
					</tr>
					<tr>
						<td>Forma Pago:</td>
						<td colspan="3">
              <div class="select is-small">
              <select ng-model="cliente.id_forma_pago" ng-options="x.ID_FORMA_PAGO as x.DESCRIPCION for x in lstFormpago"></select>
              </div>
            </td>
					</tr>
					<tr>
						<td>Vendedor:</td>
						<td colspan="3">
              <div class="select is-small">
                <select ng-model='cliente.id_vendedor' ng-options="x.ID_VENDEDOR as x.NOMBRE for x in lstVendedorVerif" ></select>
              </div>
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
  <div class="modal is-active" id="adddscnt" ng-show="modalAddDscnt">
		<div class="modal-background"></div>
		<div class="modal-card" style="width:700px">
			<header class="modal-card-head">
				<p class="modal-card-title">Agregar descuento a los Productos</p>
				<button class="delete" aria-label="close" ng-click="closeAddDscnt();"></button>
			</header>
			<section class="modal-card-body">				
				<div class="columns" ng-show="!proddscnt.producto">
					<div class="column is-5">Descuento total:</div>
					<div class="column is-2"><input type="number" class="input is-small" ng-model="proddscnt.descuentoTodos" ng-keyup="calculaDescTodos()"></div>
					
				</div>
				<div class="columns" ng-show="proddscnt.producto">
					<div class="column is-5">{{proddscnt.producto}}</div>
					<div class="column is-2">{{proddscnt.precio | currency}}</div>						
					<div class="column is-2"><input type="number" class="input is-small is-1" ng-keyup="calculaDescInd()" ng-chage="calculaDescInd()" ng-model="proddscnt.descuento"></div>
					
					<div class="column is-2">
						<a ng-click="escondeRenglon()">
							<span class="icon has-text-danger">
								<i title="Limpia el renglon" class="fas fa-times-circle"></i>
							</span>
						</a>
					</div>
				</div>
					
				<table style="width:100%">
					<tr>
						<td>
							<table class="table" style="width:100%">
								<colgroup>
									<col width="30%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<col width="10%">
								</colgroup>
                <tbody>
                  <tr class="tbl-header">
                    <td>Descripcion</td>
                    <td>Cantidad</td>
                    <td>Unidad</td>
                    <td>Precio</td>
                    <td>Importe</td>
                    <td>Desc</td>
                  </tr>
                </tbody>
							</table>
						</td>
					<tr>
					<tr>
						<td>
							<div style="width:100%; height:150px; overflow:auto;">
								<table class="table" style="width:100%;">
									<colgroup>
										<col width="30%">
										<col width="15%">
										<col width="15%">
										<col width="15%">
										<col width="15%">
										<col width="10%">
									</colgroup>
									<tr ng-repeat="p in lstProdCompra" ng-click="setSelectedDscnt($index)" ng-class="{selected: $index === indexRowCompra}">
										<td class="font12" >{{p.DESCRIPCION}}</td>
										<td class="font12" style="text-align:center">{{p.CANTIDAD}}</td>
										<td class="font12" style="text-align:center">{{p.UNIDAD}}</td>
										<td class="font12" style="text-align:right">{{p.PRECIO_LISTA | currency}}</td>
										<td class="font12" style="text-align:right">{{p.IMPORTE | currency}}</td>
										<td class="font12" style="text-align:right">{{p.DESCUENTO}}%</td>
									</tr>
								</table>	
							</div>
						</td>
					<tr>
				</table>
			</section>
			<footer class="modal-card-foot">
				<button class="button" ng-click="closeAddDscnt();">Cerrar</button>
			</footer>
		</div>
	</div>
	
  <div style="width: 100%; display:none" id="pedido">
	<table  border="1" style="width:100%">
		<tbody>
			<tr>
				<td colspan="2">
					<table style="border:2px solid black; width:100%">
            <colgroup>
              <col width="20%"/>
              <col width="15%"/>
              <col width="65%"/>
            </colgroup>
						<tr>
              <td rowspan="4">
                <img src="../img/logo.jpg" style="width:110px;height:100px">
              </td>
							<td colspan="2" style="text-align:center">EMPRESA</td>
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
			<tr>
				<td>
          <table style="width:100%;border:2px solid black">
						<tr>
							<td colspan="2" style="text-align:center">CLIENTE</td>
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
							<td>Teléfono:</td>
							<td>{{cliente.telefono}}</td>
						</tr>
						<tr>
							<td>RFC:</td>
							<td>{{rfc_cliente}}</td>
						</tr>
					</table>
				</td>
        <td>
          <table style="width:100%;border:2px solid black">
            <tr>
              <td>PEDIDO NO.</td>
              <td style="color:red">{{pedido.docto}}</td>
            </tr>
            <tr>
              <td>FECHA PEDIDO</td>
              <td>{{fechapedido}}</td>
            </tr>
            <tr>
              <td>FECHA ENTREGA</td>
              <td>{{fechaentrega}}</td>
            </tr>
            <tr>
              <td colspan='2'>&nbsp;</td>
            </tr>
            <tr>
              <td colspan='2'>&nbsp;</td>
            </tr>
          </table>
        </td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
			<td colspan="2">
				<table style="width: 100%; border: 5px double black" >
					<tbody>
						<tr>
              <td style="width: 25px;  text-align:center;border-right:2px solid black;">NP</td>
							<td style="width: 80px;  text-align:center;border-right:2px solid black;">CANTIDAD</td>
              <td style="width: 80px;  text-align:center;border-right:2px solid black;">CODIGO</td>
              <td style="width: 240px; text-align:center;border-right:2px solid black;">DESCRIPCION</td>
							<td style="width: 100px; text-align:center; border-right:2px solid black;">$/UNIT</td>
							<td style="width: 100px; text-align:center; border-right:2px solid black;">IMPORTE</td>
						</tr>
          </tbody>
        </table>
        <div style="height: 400px; width: 100%;">
        <table style="border: 5px double black; width: 100%;border-collapse:collapse;border-spacing: 0px;">
          <tbody>
						<tr ng-repeat="p in lstProdCompra">
              <td class="font12" style="width: 25px; text-align: center;border-right:2px solid black;">{{$index + 1}}</td>
              <td class="font12" style="width: 80px; text-align: center;border-right:2px solid black;">{{p.CANTIDAD}}</td>
              <td class="font12" style="width: 80px; text-align: center;border-right:2px solid black;">{{p.CODIGO}}</td>
							<td class="font12" style="width: 240px; text-align: center;border-right:2px solid black;">{{p.DESCRIPCION}}</td>
							<td class="font12" style="width: 100px; text-align: right;border-right:2px solid black;">{{p.PRECIO_LISTA | currency}}</td>
							<td class="font12" style="width: 100px; text-align: right;border-right:2px solid black;">{{p.IMPORTE | currency}}</td>
						</tr>
            <tr ng-repeat="p in lstComplemento">
              <td style="width: 25px; border-right:2px solid black;">&nbsp;</td>
              <td style="width: 80px; border-right:2px solid black;">&nbsp;</td>
              <td style="width: 80px; border-right:2px solid black;">&nbsp;</td>
							<td style="width: 240px; border-right:2px solid black;">&nbsp;</td>
							<td style="width: 100px; border-right:2px solid black;">&nbsp;</td>
							<td style="width: 100px; border-right:2px solid black;">&nbsp;</td>
            </tr>
					</tbody>
				</table>
				</div>
			</td>
			</tr>
			<tr>
        <td colspan="2">
          <table style="width:100%">
            <tbody>
              <tr>
                <td>Entregar en </td>
                <td>{{domientrega.lugar}}</td>
                <td></td>
                <td style="text-align: right">Sub Total</td>
							  <td style="text-align: right">{{pedido.subtotal | currency}}</td>
              </tr>
              <tr>
                <td>Calle:</td>
                <td>{{domientrega.calle}}</td>
                <td></td>
                <td style="text-align: right">Descuento</td>
							  <td style="text-align: right">{{dsctoValor | currency}}</td>
              </tr>
              <tr>
                <td>Colonia:</td>
                <td>{{domientrega.colonia}}</td>
                <td></td>
                <td style="text-align: right">Impuestos</td>
							  <td style="text-align: right; margin-bottom:2px solid black">{{impuestos | currency}}</td>
              </tr>
              <tr>
                <td>CP:</td>
                <td>{{domientrega.cp}}</td>
                <td></td>
                <td style="text-align: right">Total</td>
							  <td style="text-align: right">{{pedido.total | currency}}</td>
              </tr>
              <tr>
                <td>Ciudad:</td>
                <td colspan="4">{{domientrega.ciudad}}</td>
              </tr>
              <tr>
                <td>Contacto:</td>
                <td>{{domientrega.contacto}}</td>
                <td colspan="3"></td>
              </tr>
              <tr>
                <td>Nota:</td>
                <td>{{pedido.comentarios}}</td>
                <td colspan="3"></td>
              </tr>
            </tbody>
          </table>
        </td>
			</tr>
		</tbody>
	</table>
  </div>
</div>