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
				</div>
				<div class="columns is-gapless is-multiline">
					<div class="column is-2">
						<label class="label">Cliente</label>
					</div>
					<div class="column is-2">
						<input type="text" ng-model="claveclte" ng-keyup="buscacodcliente($event)" onfocus="this.select();" class="input is-small">
					</div>
					<div class="column is-6">
						<div class="field has-addons">
							<p class="control is-expanded has-icons-left">
								<input class="input is-small" ng-keyup="buscacliente($event)" ng-model="nombre_cliente" type="text" onfocus="this.select();" placeholder="Cliente">
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
					<div class="column is-8" ng-show="showLstClte" style="margin-left:120px">
						<table style="width:92%;">
							<tr>
								<td>
									<table class="table is-bordered" style="width:100%">
										<colgroup>
                      <col width="26%">
										  <col width="74%">																				
                    </colgroup>
                    <thead>
                      <tr class="tbl-header-seek">
                        <td style="text-align:center;">CLAVE</td>
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
												<td style="text-align:center;font-size:14px;">{{x.CLAVE}}</td>
												<td style="text-align:left;font-size:14px;">{{x.NOMBRE}}</td>
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
						<input type="text" ng-model="idvendedor" ng-keyup="buscacodvendedor($event)"  class="input is-small">
					</div>
					<div class="column is-5">
						<div class="field has-addons">
							<p class="control is-expanded has-icons-left">
								<input class="input is-small" type="text" ng-model="nombre_vendedor" ng-keyup="buscavendedor($event)" placeholder="Vendedor">
								<span class="icon is-small is-left">
									<i class="fas fa-user-tie"></i>
								</span>
							</p>
						</div>
					</div>
					
					<div class="column is-8" ng-show="listaVendedores" style="margin-left:120px" >
						<table style="width:97%; border:2px red">
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
						<div class="level-item" ng-show="lstProdCompra.length > 0">
							<button class="button is-info" ng-click="addDescuento()">Descuento</button>
						</div>
				    <div class="level-item">
							<button class="button is-success" ng-click="verificaExistencia()" ng-show="isVerifExis">Verificar Existencia</button>
						</div>
					</div>
				</nav>
				<div class="columns">
					<div class="column is-2">
						<input type="text" ng-model="codigo_prodto"  ng-keyup="buscaprodbycodigo($event)" class="input is-small" >
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
						<input type="text" class="input is-small" id="precio" ng-model="precio" style="text-align:right;" ng-disabled="!permisos.modificacion">
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
                  <colgroup>
                    <col width="15%">
                    <col width="40%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                  </colgroup>
									<tr style="background-color:Crimson; color:Ivory;">
										<th style="text-align:center">Código</th>
										<th style="text-align:center">Descripción</th>
										<th style="text-align:center">Unidad</th>
										<th style="text-align:center">Precio</th>
										<th style="text-align:center">Existencia</th>
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
                    <colgroup>
                      <col width="15%">
                      <col width="40%">
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
				<div class="columns">
					<div class="column">
            <table class="table is-bordered" style="width:100%">
              <colgroup>
                <col width="38%">
                <col width="15%">
                <col width="15%">
                <col width="10%">
                <col width="10%">
                <col width="12%">
              </colgroup>
              <tbody>
              <tr class="tbl-header">
                <td>Descripción</td>
                <td style="text-align:center">Cantidad</td>
                <td style="text-align:center">Unidad</td>
                <td style="text-align:center">Precio</td>
                <td style="text-align:center">Descto</td>
                <td style="text-align:center">Importe</td>
              </tr>
              </tbody>
            </table>
          
            <div style="width:100%; height:285px; overflow:auto;margin-top:-25px">
              <table class="table is-bordered" style="width:100%;">
                <colgroup>
                  <col width="38%">
                  <col width="15%">
                  <col width="15%">
                  <col width="10%">
                  <col width="10%">
                  <col width="12%">
                </colgroup>
                <tr ng-repeat="p in lstProdCompra" ng-click="setSelected($index,p.CODIGO)" ng-class="{selected: p.CODIGO === idSelCompra}">
                  <td class="font12">{{p.DESCRIPCION}}</td>
                  <td class="font12" style="text-align:center">{{p.CANTIDAD}}</td>
                  <td class="font12" style="text-align:center">{{p.UNIDAD}}</td>
                  <td class="font12" style="text-align:right">{{p.PRECIO_LISTA | currency}}</td>
                  <td class="font12" style="text-align:right">{{p.DESCUENTO * p.CANTIDAD * p.PRECIO_LISTA / 100 | currency}}</td>
                  <td class="font12" style="text-align:right">{{p.IMPORTE | currency}}</td>
                </tr>
              </table>
            </div>
					</div>
				</div>
				<button class="button is-info is-rounded" ng-click="iniciaRegistrarCompra()" id="regcompra">Registrar Venta</button>
			</div>
		</div>
		<div class="column is-4" style="border:2px solid green">
			<div class="box box-color" >
				<div class="columns">
					<div class="column has-text-right">
						<h1 class="title is-7">{{fechaPantalla}} {{hora}}</h1>
					</div>
				</div>
				<div class="columns">
          <div class="column" ng-show="isAdmin">
            <input type="date" ng-model="fechaCorteTmp" id="fechaCorteTmp" ng-change="cambiaFecha()">
          </div>
					<div class="column" style="display:flex;justify-content: flex-end">
						<button class="button is-info" ng-click="abreOperaciones()">Operaciones</button>
					</div>
				</div>
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
						<label>Sub Total:</label>
					</div>
					<div class="column" style="text-align:right">
						<label>{{subtotal | currency}}</label>
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
					<div class="column" style="border-bottom:2px solid black; text-align:right">
						<label>{{impuestos | currency}}</label>
					</div>
				</div>
				<div class="columns">
					<div class="column">
						<label class="label">Total:</label>
					</div>
					<div class="column" style="text-align:right">
						<label>{{total | currency}}</label>
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
								<select ng-model="fact.tipopago" ng-options="x.ID_TIPO_PAGO as x.DESCRIPCION for x in lstTipopago"></select>
							</div>
						</td>
						<td>&nbsp;</td>
						<td style="vertical-align:middle"><label class="label">Tarjeta:</label></td>
						<td>
							<input type="text" class="input" ng-model="pago_tarjeta" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onfocus="this.select()">
						</td>
						<td style="text-align:right;vertical-align:middle">
							<div class="select is-small">
								<select ng-init="idtarjeta" ng-model="idtarjeta">
									<option value="0" disabled>Elije una opción</option>
								 	<option ng-repeat="x in lstTarjetas" value="{{x.ID_TARJETA}}">{{x.NOMBRE}}</option>
								 </select>
							</div>
						</td>
					</tr>
					<tr>
						<td style="vertical-align:middle"><label class="label">Efectivo:</label></td>
						<td>
							<input type="number" class="input is-small" ng-model="pago_efectivo" id="pago_efectivo" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onclick="this.select()" ng-keyup="calculaCambio()" >
						</td>
						<td>&nbsp;</td>
						<td style="vertical-align:middle"><label class="label">Cheque:</label></td>
						<td>
							<input type="text" class="input" ng-model="pago_cheque" style="text-align:right; font-size: 20px; color: red;font-weight: bold;" onfocus="this.select()">
						</td>
						<td style="text-align:right; vertical-align:middle">
							<div class="select is-small">
								<select ng-model="idbanco">
									<option value="0" disabled>Elije una opción</option>
									<option ng-repeat="x in lstBancos" value="{{x.ID_BANCO}}">{{x.DESCRIPCION }}</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align:middle">
							<div ng-show="fact.tipopago==2" style="margin-top:5px;width:100px;margin-left:70px">
								<input type="text" class="input is-small" ng-model="fact.documento" disabled>
							</div>
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
						<td style="text-align:right;vertical-align:middle">
							<div class="select is-small">
								<select ng-model="idvales">
									<option value="0" disabled>Elije una opción</option>
									<option ng-repeat="x in lstVales" value="{{x.ID_VALE}}" title="{{x.EMPRESA}}">{{x.NOMBRE}}</option>
								</select>
							</div>
						</td>
					</tr>					
				</table>				
			</div>
	   	</section>
	   	<footer class="modal-card-foot">
	     	<button class="button is-success" ng-click="registraCompra()" ng-disabled="disableRegistra">Registrar</button>
	     	<button class="button is-danger" ng-click="cancelVenta()">Cancelar</button>
	   	</footer>
	  </div>
	</div>

	<div class="{{isOperaciones ? 'modal is-active' : 'modal' }}">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Consulta de operaciones</p>
				<button class="delete" aria-label="close" ng-click="closeOperaciones();"></button>
			</header>
			<section class="modal-card-body">
				<div class="columns">
					<div class="column is-8">
						<div class="columns" style="margin-top:-20px;margin-bottom:-25px">
							<div class="column">
								<label class="label">Ventas del {{fechaCorte}} Caja - {{noCaja}}</label>
							</div>
						</div>
						<div class="columns">
							<div class="column">
								<table class="table is-bordered" style="margin-bottom:-10px; width:100%">
                  <colgroup>
									  <col width="35%">
									  <col width="15%">
									  <col width="20%">
									  <col width="30%">
                  </colgroup>
                  <thead>
                    <tr class="tbl-header">
                      <th style="text-align:center">Dcto</th>
                      <th style="text-align:center">Part</th>
                      <th style="text-align:center">FP</th>
                      <th style="text-align:center">Importe</th>
                    </tr>
                  </thead>
								</table>
								<div style="overflow:auto; height:200px;">
									<table class="table is-bordered" style="width:100%">
                    <colgroup>
										  <col width="35%">
										  <col width="15%">
										  <col width="20%">
										  <col width="30%">
                    </colgroup>
										<tr ng-repeat="x in lstVentas" ng-click="selOperacion(x.ID_VENTA,$index)"  ng-class="{selected: x.ID_VENTA === idOpSel}" >
											<td ng-class="{canceled: x.CANCELADO === 't'}">{{x.DOCUMENTO.trim()}}</td>
											<td ng-class="{canceled: x.CANCELADO === 't'}" style="text-align:center">{{x.COUNT}}</td>
											<td ng-class="{canceled: x.CANCELADO === 't'}" style="text-align:center">{{x.ID_TIPO_PAGO == '1' ? x.TIPO_PAGO :'CR'}}</td>
											<td ng-class="{canceled: x.CANCELADO === 't'}" style="text-align:right;">{{x.IMPORTE | currency}}</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="column is-4">
						<div class="columns" style="margin-bottom:30px">
							<nav class="level">
								<div class="level-right">
									<p class="level-item">
										<a ng-click="corteCaja()">
											<span class="icon has-text-success">
												<i title="Corte de caja" class="fas fa-funnel-dollar"></i>
											</span>
										</a>
									</p>
									<p class="level-item" ng-show="!isCancel">
                    <span class="icon has-text-danger">
                      <i title="Cancela partida" class="fas fa-times" style="color:grey"></i>
                    </span>
									</p>
                  <p class="level-item" ng-show="isCancel">
										<a ng-click="eliminaOperacion()">
											<span class="icon has-text-danger">
												<i title="Cancela partida" class="fas fa-times"></i>
											</span>
										</a>
									</p>
									<p class="level-item" ng-show="idxOperacion == -1">
											<span class="icon has-text-info">
												<i title="Imprime Operación" class="fas fa-print" style="color:grey"></i>
											</span>
									</p>
                  <p class="level-item" ng-show="idxOperacion != -1">
										<a ng-click="imprimeCompra()">
											<span class="icon has-text-info">
												<i title="Imprime Operación" class="fas fa-print"></i>
											</span>
										</a>
									</p>
								</div>
							</nav>
						</div>
						<div class="columns" id="resumenoperaciones">
							<div class="column totales">
								<table style="width:100%">
                  <colgroup>
									  <col with="40%">
									  <col with="20%">
									  <col with="40%">
                  </colgroup>
									<tr>
										<td>Operaciones</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{lstVentas.length}}</td>
									</tr>
									<tr>
										<td>Canceladas</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{cancelados}}</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td>En efectivo:</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{pagos[0].EFECTIVO | currency}}</td>
									</tr>
									<tr>
										<td>Con tarjeta:</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{pagos[0].TARJETA | currency}}</td>
									</tr>
									<tr>
										<td>Con cheque:</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{pagos[0].CHEQUE | currency}}</td>
									</tr>
									<tr>
										<td>Con vales:</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{pagos[0].VALES | currency}}</td>
									</tr>
									<tr>
										<td colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td style="text-align:right;">Contado:</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{tipopago[0].sum | currency}}</td>
									</tr>
									<tr>
										<td style="text-align:right;">Crédito:</td>
										<td>&nbsp;</td>
										<td style="text-align:right;">{{tipopago[1].sum | currency}}</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
			<footer class="modal-card-foot">
				<button class="button" ng-click="closeOperaciones();">Cerrar</button>
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
						<td><input maxlength="20" type="text" class="input is-small" ng-model="cliente.rfc" placeholder="RFC" required></td>
						<td>CURP:</td>
						<td><input maxlength="20" type="text" class="input is-small" ng-model="cliente.curp" placeholder="CURP"></td>
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
						<td>Días Crédito:</td>
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
                <select ng-model="cliente.id_forma_pago" ng-options="x.ID_FORMA_PAGO as x.CLAVE+' '+x.DESCRIPCION for x in lstFormpago"></select>
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
	      <button class="button" ng-click="enviaDatosCliente();">{{btnVerifClte}}</button>
		    <button class="button" ng-click="closeVerifClte()">Cerrar</button>
	    </footer>
	  </div>
	</div>

	<div class="{{modalAddDscnt ? 'modal is-active' : 'modal' }}" id="adddscnt">
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
							<table class="table" style="width:100%" border="1">
								<colgroup>
									<col width="30%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<col width="15%">
									<col width="10%">
								</colgroup>
								<tr class="tbl-header">
									<td>Descripcion</td>
									<td>Cantidad</td>
									<td>Unidad</td>
									<td>Precio</td>
									<td>Importe</td>
									<td>Desc</td>
								</tr>
							</table>
						</td>
					<tr>
					<tr>
						<td>
							<div style="width:100%; height:150px; overflow:auto;border:2px solid black">
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
										<td class="font12">{{p.DESCRIPCION}}</td>
										<td class="font12" style="text-align:center">{{p.CANTIDAD}}</td>
										<td class="font12" style="text-align:center">{{p.UNIDAD}}</td>
										<td class="font12" style="text-align:right">$ {{p.PRECIO_LISTA | number:2}}</td>
										<td class="font12" style="text-align:right">$ {{p.IMPORTE | number:2}}</td>
										<td class="font12" style="text-align:center">{{p.DESCUENTO}}%</td>
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

	<div id="ticket" style="display:none">
		<div class="ticket ffont">
			<table>
				<tr>
					<td colspan="2" style="text-align:center">
						<img src="https://ready2solve.club/pinabete/img/logo.jpg" alt="" style="width:40">
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">TICKET DE VENTA</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">{{empresa.NOMBRE}}</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">{{empresa.DOMICILIO}}</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center">{{empresa.RFC}}</td>
				</tr>
				<tr>
					<td colspan="2" id="fechaTicket" style="text-align:center"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>No Ticket:</td>
					<td>{{docto}}</td>
				</tr>
				<tr>
					<td>Cliente:</td>
					<td>{{nombre_cliente}}</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
			<table style="width:100%">
				<tr style="border-top: 1px solid black;border-collapse: collapse;">
					<th class="cantidad">CANTIDAD</th>
					<th class="producto">DESCRIPCION</th>
					<th class="precio">PRECIO</th>
				</tr>
				<tr ng-repeat="x in lstProdCompra">
					<td class="cantidad">{{x.CANTIDAD}}</td>
					<td class="producto">{{x.DESCRIPCION}}</td>
					<td class="precio">{{x.IMPORTE | currency}}</td>
				</tr>
				<tr style="border-top: 1px solid black;border-collapse: collapse;">
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" class="cantidad" style="width:80px">Importe Neto:</td>
					<td style="text-align:right;width:40px">{{importeNeto | currency}}</td>
				</tr>
				<tr>
					<td colspan="2" class="cantidad">Descuento:</td>
					<td style="text-align:right;width:40px">{{dsctoValor | currency}}</td>
				</tr>
				<tr>
					<td colspan="2" class="cantidad">IVA:</td>
					<td style="text-align:right;width:40px">{{impuestos | currency}}</td>
				</tr>
				<tr>
					<td colspan="2" class="cantidad">Total:</td>
					<td style="text-align:right;width:40px">{{total | currency}}</td>
				</tr>
				<tr>
					<td colspan="2" class="cantidad">Forma de Pago:</td>
					<td style="text-align:right;width:40px">{{formaPago}}</td>
				</tr>
        
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
			</table>
			<table style="width:100%">
				<tr>
					<td style="text-align:center" colspan="3">Gracias por su compra, nos puede contactar en:</td>
				</tr>
				<tr>
					<td style="text-align:center">72 34 23 56 23</td>
				</tr>
				<tr>
					<td style="text-align:center">servicio@rts.com.mx</td>
				</tr>
			</table>
		</div>
	</div>
</div>

