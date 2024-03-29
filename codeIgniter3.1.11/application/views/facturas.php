<br>
<input type="hidden" id="updtTblComp" value="F">
<div class="container">
  <div class="notification">
    <h1 class="title is-4 has-text-centered">Facturas</h1>
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
          <p class="level-item" ng-show="permisos.consulta">
            <a ng-click="abreTimbraCC()">
              <span class="icon has-text-success"><i class="fas fa-list-ol" title="Timbrar Cortes de Caja"></i></span>
            </a>
          </p>
          <p class="level-item" ng-show="permisos.consulta && indexRowFactura == -1">
            <a>
              <span class="icon has-text-success"><i class="fas fa-print" style="color:grey" title="Imprime Factura"></i></span>
            </a>
          </p>
          <p class="level-item" ng-show="permisos.consulta && indexRowFactura != -1">
            <a ng-click="printPreFactura()">
              <span class="icon has-text-success"><i class="fas fa-print" title="Imprime Factura"></i></span>
            </a>
          </p>
          <p class="level-item" ng-show="permisos.consulta">
            <a ng-click="printFacturas()">
              <span class="icon has-text-success"><i class="fas fa-file-excel" title="Genera la lista de todas las Facturas en Excel"></i></span>
            </a>
          </p>
          <p class="level-item" ng-show="!showEmail && indexRowFactura != -1">
						<a ng-click="timbrar();"><span class="icon has-text-success"><i class="fas fa-bell" title="Timbrar Factura ante el SAT"></i></span></a>
          </p>
          <p class="level-item" ng-show="showEmail || indexRowFactura == -1">
						<a ><span class="icon has-text-success"><i class="fas fa-bell" style="color:grey" title="Timbrar Factura ante el SAT"></i></span></a>
          </p>
          <p class="level-item" ng-show="showEmail">
						<a ng-click="mostrarEnviarEmail();"><span class="icon has-text-success"><i class="fas fa-envelope" title="Enviar Factura por correo electrónico"></i></span></a>
          </p>
          <p class="level-item" ng-show="!showEmail">
						<a ><span class="icon has-text-success"><i class="fas fa-envelope" style="color:grey" title="Enviar Factura por correo electrónico"></i></span></a>
          </p>
					<p class="level-item" ng-show="permisos.alta">
						<a ng-click="agregaFactura();"><span class="icon has-text-success"><i class="fas fa-plus-square" title="Agrega Factura"></i></span></a>
          </p>
					<p class="level-item" ng-show="permisos.modificacion">
						<a ng-show="indexRowFactura == -1">
              <span class="icon has-text-info"><i class="fas fa-edit" title="Ver Factura" style="color:grey"></i></span>
            </a>
            <a ng-click="abreFactura()" ng-show="indexRowFactura != -1">
              <span class="icon has-text-info"><i class="fas fa-edit" title="Ver Factura"></i></span>
            </a>
          </p>
					<p class="level-item" ng-show="permisos.baja">
						<a ng-show="indexRowFactura == -1">
              <span class="icon has-text-danger">
                <i class="far fa-trash-alt" title="Cancela Factura" style="color:grey"></i>
              </span>
            </a>
            <a ng-click="eliminarFactura()" ng-show="indexRowFactura != -1">
              <span class="icon has-text-danger">
                <i class="far fa-trash-alt" title="Cancela Factura"></i>
              </span>
            </a>
          </p>
				</div>
			</nav>
		</div>
		<div style="border: 2px solid black;margin-top:-20px;width:99%" ng-show="!isCapturaFactura">
			<table clas="table" style="width:100%">
				<tr>
					<td>
						<table class="table is-bordered" style="width:99%">
							<colgroup>						
							<col width="8%">
							<col width="9%">
							<col width="19%">
							<col width="8%">
							<col width="8%">
							<col width="9%">
							<col width="9%">
							<col width="9%">
							<col width="5%">
              <col width="5%">  
							<col width="11%">
							</colgroup>
							<tr class="tbl-header">
								<td style="text-align:center;font-size:12px">DOCTO</td>									
								<td style="text-align:center;font-size:12px">FECHA</td>
								<td style="text-align:center;font-size:12px">CLIENTE</td>
								<td style="text-align:center;font-size:12px">IMPORTE</td>
								<td style="text-align:center;font-size:12px">SALDO</td>
								<td style="text-align:center;font-size:12px">F/PAGO</td>
								<td style="text-align:center;font-size:12px">VENCE</td>
                <td style="text-align:center;font-size:12px">PEDIDO</td>
								<td style="text-align:center;font-size:12px">F</td>
                <td style="text-align:center;font-size:12px">DESC</td>
								<td style="text-align:center;font-size:12px">VENDEDOR</td>
							</tr>							
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div style="width:100%; height:500px; overflow:auto;">
							<table class="table is-bordered is-hoverable" style="width:100%" id="tblClientes">
                <colgroup>						
                  <col width="8%">
                  <col width="9%">
                  <col width="19%">
                  <col width="8%">
                  <col width="8%">
                  <col width="9%">
                  <col width="9%">
                  <col width="9%">
                  <col width="5%">
                  <col width="5%">  
                  <col width="11%">
                </colgroup>
								<tr ng-repeat="x in lstFacturas" ng-click="selectRowFactura(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.DOCUMENTO}}</td>									
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.FECHA_FACTURA}}</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.CLIENTE}}</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:right;">{{x.IMPORTE | currency}}</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:right;">{{x.SALDO | currency}}</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.ID_TIPO_PAGO == 1 ? 'Contado':'Crédito'}}</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.FECHA_REVISION}}</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.PEDIDO}}</td>

									<td class="font12" style="text-align:center;" ng-show="x.FACTURADO == 't'"><i ng-show="x.ESTATUS !== 'CANCELADA'" class="fas fa-check"></i> <i ng-show="x.ESTATUS === 'CANCELADA'" class="fas fa-ban"></i></td>
                  <td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;" ng-show="x.FACTURADO == 'f'"><i ng-show="x.ESTATUS === 'CANCELADA'" class="fas fa-ban"></i></td>
                  <td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;" ng-show="x.FACTURADO == 't'"><a href="../creacfdixml/sendfacturaby/1/{{x.ID_FACTURA}}/{{x.ID_CLIENTE}}/{{x.ID_EMPRESA}}" style="color:green" ><i class="fas fa-download"></i></a></td>
                  <td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;" ng-show="x.FACTURADO == 'f'">&nbsp;</td>
									<td class="font12" ng-class="{canceled: x.ESTATUS === 'CANCELADA'}" style="text-align:center;">{{x.VENDEDOR}}</td>
								</tr>
							</table>
						</div>
					</td>
			  </tr>
			</table>
		</div>
    <div id="exportable" ng-show="false">
			<table>
				<tr>
					<td>
						<table class="table is-bordered">
							<tr class="tbl-header">
								<td style="text-align:center;font-size:12px">DOCUMENTO</td>									
								<td style="text-align:center;font-size:12px">FECHA</td>
								<td style="text-align:center;font-size:12px">CLIENTE</td>
								<td style="text-align:center;font-size:12px">IMPORTE</td>
								<td style="text-align:center;font-size:12px">SALDO</td>
								<td style="text-align:center;font-size:12px">FORMA PAGO</td>
								<td style="text-align:center;font-size:12px">REVISION</td>
								<td style="text-align:center;font-size:12px">VENCE</td>
								<td style="text-align:center;font-size:12px">CFDI</td>
                <td style="text-align:center;font-size:12px">ESTATUS</td>
								<td style="text-align:center;font-size:12px">VENDEDOR</td>
							</tr>							
						</table>
					</td>
				</tr>
				<tr>
					<td>			
            <table class="table is-bordered is-hoverable" style="width:100%" id="tblClientes">
              <tr ng-repeat="x in lstFacturas" ng-click="selectRowFactura(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
                <td class="font12"  style="text-align:center;">{{x.DOCUMENTO}}</td>									
                <td class="font12"  style="text-align:center;">{{x.FECHA_FACTURA}}</td>
                <td class="font12"  style="text-align:center;">{{x.CLIENTE}}</td>
                <td class="font12"  style="text-align:right;">{{x.IMPORTE | currency}}</td>
                <td class="font12"  style="text-align:right;">{{x.SALDO | currency}}</td>
                <td class="font12"  style="text-align:center;">{{x.ID_TIPO_PAGO == 1 ? 'Contado':'Crédito'}}</td>
                <td class="font12"  style="text-align:center;">{{x.FECHA_REVISION}}</td>
                <td class="font12"  style="text-align:center;">{{x.FECHA_VENCIMIENTO}}</td>
                <td class="font12"  style="text-align:center;">{{x.FACTURADO === 't' ? 'Si':'No'}}</td>
                <td class="font12"  style="text-align:center;">{{x.ESTATUS}}</td>
                <td class="font12"  style="text-align:center;">{{x.VENDEDOR}}</td>
              </tr>
            </table>
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
						<div class="column is-3">
							<div class="columns is-gapless">
								<div class="column is-narrow" style="width:70px;margin-left:-15px">
									<label class="label">Número</label>
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
              <div class="columns" ng-show="showUsuario">
                <div class="column"><label class="label">Usuario:</label></div>
                <div class="column">{{usuario}}</div>
              </div>
						</div>
						<div class="column is-8">
							<div class="columns is-gapless is-multiline">
								<div class="column is-2">
									<label class="label">Cliente</label>
								</div>
								<div class="column is-2">
									<input type="text" ng-model="claveclte" ng-keyup="buscacodcliente($event)" class="input is-small">
								</div>
								<div class="column is-7">
									<div class="field has-addons">
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
								<div class="column is-narrow" ng-show="showLstClte" style="margin-left:80px; overflow:auto; heigth:50px; width:380px;border:2px solid black">
									<table style="width:100%;">
										<tr>
											<td>
												<table class="table is-bordered" style="width:100%;">
                          <colgroup>
													  <col width="26%">
													  <col width="74%">
                          </colgroup>
													<tr style="background-color:#6698FF; color:Ivory;">
														<td style="text-align:left;">Clave</td>
														<td style="text-align:center;">Nombre</td>
													</tr>													
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
												<div style="width:100%; height:100px; overflow:auto;">
													<table class="table is-hoverable is-bordered" style="width:100%;">
                            <colgroup>
														  <col width="26%">
														  <col width="74%">
                            </colgroup>
														<tr ng-repeat="x in lstCliente" ng-click="seleccionaCliente($index)">
															<td class="font12" style="text-align:left;">{{x.CLAVE}}</td>
															<td class="font12" style="text-align:left;">{{x.NOMBRE}}</td>
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
									<input type="text" ng-model="factura.idvendedor" ng-keyup="buscacodvendedor($event)" class="input is-small">
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
												<table class="table is-bordered" style="width:100%">
                          <colgroup>
													  <col width="26%">
													  <col width="74%">
                          </colgroup>
													<tr style="background-color:#6698FF; color:Ivory;">
														<td style="text-align:left">Clave</td>
														<td style="text-align:center">Nombre</td>
													</tr>
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
												<div style="width:100%; height:100px; overflow:auto;">
													<table class="table is-hoverable is-bordered" style="width:100%;">
                            <colgroup>
														  <col width="25%">
														  <col width="75%">
                            </colgroup>
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
								<div class="column is-2">
									<label class="label">Contacto</label>
								</div>
								<div class="column is-narrow">
									<input type="text" class="input is-small" ng-model="factura.contacto" placeholder="Contacto" required>
								</div>
                <div class="column">
                  <label class="label">Requiere CFDI</label>
                </div>
                <div class="column">
                  <input type="checkbox" ng-model="requiere_factura">
                </div>
							</div>
							<div class="columns">
								<div class="column is-2">
									<label class="label">T/Pago</label>
								</div>
								<div class="column is-narrow" style="width:130px">
                    <div class="select is-small">
									    <select ng-model="factura.tpago" ng-change="cambioTpago()" ng-options="x.ID_TIPO_PAGO as x.DESCRIPCION for x in lstTipopago"></select>
                    </div>
								</div>
								<div class="column is-narrow" style="width:78px;margin-left:-25px">
									<input type="number" class="input is-small" value="0" ng-model="factura.dias" ng-disabled="factura.tpago == 1">
								</div>
								<div class="column is-narrow" style="width:55px;margin-left:-20px">dias</div>
							</div>
							<div class="columns">
								<div class="column is-2">
									<label for="mpago" class="label">F/Pago</label>
								</div>
								<div class="column is-narrow">	
                  <div class="select is-small">								
									  <select ng-model="factura.fpago" ng-options="x.ID_FORMA_PAGO as x.CLAVE+' '+x.DESCRIPCION for x in lstFormpago"></select>
                  </div>
								</div>
								<div class="column is-2" style="margin-left:10px">
									<label for="cuenta" class="label">Cuenta</label>
								</div>
								<div class="column is-narrow" style="width:135px">
									<input type="text" class="input is-small" ng-model="factura.cuenta" >
								</div>
							</div>
							<div class="columns">
								<div class="column is-narrow">
									<label class="label">Uso del CFDI</label>
								</div>
								<div class="column is-narrow" style="width:380px">
                  <div class="select is-small">
									<select ng-model="factura.cfdi" ng-options="x.ID_CFDI as x.CLAVE+' '+x.DESCRIPCION for x in lstUsocfdi "></select>
                  </div>
                </div>
							</div>
							<div class="columns">
								<div class="column is-narrow" style="width:80px">
									<label class="label">M/Pago</label>
								</div>
								<div class="column is-narrow" style="width:280px">
                  <div class="select is-small">
									  <select ng-model="factura.mpago" ng-options="x.ID_MET_PAGO as x.MET_PAGO+' '+x.DESCRIPCION for x in lstMetpago"></select>
                  </div>
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
					<div class="columns" ng-show="!isImprimir">
						<div class="column is-2">
							<input type="text" ng-model="producto.codigo_prodto"  ng-keyup="buscaprodbycodigo($event)" class="input is-small" >
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
						<div class="column is-narrow">
							<a ng-click="agregaProducto()" aria-label="like" >
							  <span class="icon has-text-success">
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
                    <colgroup>
										<col width="23%">
										<col width="32%">
										<col width="15%">
										<col width="15%">
										<col width="15%">
                    </colgroup>
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
              <table class="table is-bordered" style="width:100%">
                <colgroup>
                  <col width="38%">
                  <col width="15%">
                  <col width="15%">
                  <col width="10%">
                  <col width="10%">
                  <col width="12%">
                </colgroup>											
                <tr class="tbl-header">
                  <td>Descripción</td>
                  <td style="text-align:center">Cantidad</td>
                  <td style="text-align:center">Unidad</td>
                  <td style="text-align:right">Precio</td>
                  <td style="text-align:right">Dscto</td>
                  <td style="text-align:right">Importe</td>
                </tr>
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
                    <td class="font12" style="text-align:center">{{p.UNIDAD_MEDIDA}}</td>
                    <td class="font12" style="text-align:right">{{p.PRECIO_LISTA | currency}}</td>
                    <td class="font12" style="text-align:right">{{p.DESCUENTO * p.CANTIDAD * p.PRECIO_LISTA / 100 | currency}}</td>
                    <td class="font12" style="text-align:right">{{p.IMPORTE | currency}}</td>
                  </tr>
                </table>
							</div>
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
							<label>Sub Total:</label>
						</div>
						<div class="column" style="text-align:right">
							<label>{{factura.subtotal | currency}}</label>
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
							<label>{{factura.total | currency}}</label>
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
							<table class="table is-bordered" style="width:100%" >
								<colgroup>
									<col width="36%">
									<col width="15%">
									<col width="12%">
									<col width="12%">
									<col width="15%">
									<col width="10%">
								</colgroup>
								<tr class="tbl-header">
									<td style="text-align:left">Descripción</td>
									<td style="text-align:center">Cantidad</td>
									<td style="text-align:center">Unidad</td>
									<td style="text-align:center">Precio</td>
									<td style="text-align:center">Importe</td>
									<td style="text-align:center">Desc</td>
								</tr>
							</table>
						</td>
					<tr>
					<tr>
						<td>
							<div style="width:100%; height:150px; overflow:auto;">
								<table class="table is-bordered is-hoverable" style="width:100%;">
									<colgroup>
										<col width="36%">
										<col width="15%">
										<col width="12%">
										<col width="12%">
										<col width="15%">
										<col width="10%">
									</colgroup>
									<tr ng-repeat="p in lstProdCompra" ng-click="setSelectedDscnt($index)" ng-class="{selected: $index === indexRowCompra}">
										<td class="font12">{{p.DESCRIPCION}}</td>
										<td class="font12" style="text-align:center">{{p.CANTIDAD}}</td>
										<td class="font12" style="text-align:center">{{p.UNIDAD_MEDIDA}}</td>
										<td class="font12" style="text-align:right">{{p.PRECIO_LISTA | currency}}</td>
										<td class="font12" style="text-align:right">{{p.IMPORTE | currency}}</td>
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

  <div class="modal is-active" ng-show="enviaremail">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
          <p class="modal-card-title">Aviso</p>
          <button class="delete" aria-label="close" ng-click="mostrarEnviarEmail(false,idFactura,idCliente,idEmpresa);"></button>
      </header>
      <section class="modal-card-body">
        <label class="label">Enviar el correo a la(s) siguiente(s) persona(s)</label>
        <form name="myEmailForm">
         <input type="text" name="nvoEmail" class="input" ng-model="nvoEmail" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
          <button class="button is-info is-small" ng-click="addEmail()"  ng-disabled="myEmailForm.nvoEmail.$invalid || myForm.nvoEmail.$dirty">Agregar</button>
        </form>
        <div style="width: 50%;  margin: 0 auto;">
          <table class="table is-bordered">                            
            <tr ng-repeat="x in lstCorreos">
              <td>{{x.EMAIL}}</td>
              <td>
                <button class="button is-danger is-small" ng-click="eliminarEmail($index)" ng-disabled="$index==0">Eliminar</button>
              </td>
            </tr>
          </table>
        </div>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" ng-click="enviaCorreo()">Enviar</button>
        <button class="button is-error" ng-click="cerrarEnviarEmail()">Cerrar</button>
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
                <select ng-model="cliente.id_forma_pago" ng-options="x.ID_FORMA_PAGO as x.CLAVE+' '+x.DESCRIPCION for x in lstFormpago"></select>
              </div>
            </td>
					</tr>
					<tr>
						<td>Vendedor:</td>
						<td colspan="3">
              <div class="select is-small">
                <select ng-model="cliente.idvendedor" ng-options="x.ID_VENDEDOR as x.NOMBRE for x in lstVendedorVerif"></select>
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
	<div class="modal is-active" ng-show="showInputData">
		<div class="modal-background"></div>
		<div class="modal-card" style="width:1100px">
			<header class="modal-card-head">
				<p class="modal-card-title">Seleccionar Pedido</p>
				<button class="delete" aria-label="close" ng-click="closeInputData()"></button>
			</header>
			<section class="modal-card-body">
        <label class="label">Filtro:<input type="text" class="input is-small" onKeyUp="doFilter(this.value,'tblPedidos')"></label>
				<table class="table is-bordered" style="width:100%">
        <thead>
					<tr>
						<th style="text-align:center;width:60px">Documento</th>
						<th style="text-align:center;width:150px">Cliente</th>
						<th style="text-align:center;width:120px">Fecha Pedido</th>
						<th style="text-align:center;width:80px">Importe</th>
						<th style="text-align:center;width:100px">Vendedor</th>
					</tr>
          </thead>
				</table>
				<div style="width:100%; height:500px; overflow:auto; margin-top:-24px; border:2px solid black">
					<table class="table is-hoverable" style="width:100%" id="tblPedidos">
						<tr ng-repeat="x in lstPedidos" ng-click="selectRowPedido(x.DOCUMENTO,$index)" ng-class="{selected: x.DOCUMENTO === idDocumento}">
							<td class="font12" style="text-align:center;width:60px">{{x.DOCUMENTO}}</td>
							<td class="font12" style="text-align:center;width:150px">{{x.CLIENTE}}</td>
							<td class="font12" style="text-align:center;width:120px">{{x.FECHA_PEDIDO}}</td>
							<td class="font12" style="text-align:right;width:80px">{{x.IMPORTE | currency}}</td>
							<td class="font12" style="text-align:right;width:100px">{{x.VENDEDOR}}</td>
						</tr>
					</table>
				</div>
			</section>
			<footer class="modal-card-foot">
				<button class="button is-success" ng-click="seleccionarPedido()" ng-disabled="indexRowPedido == -1">Seleccionar</button>
				<button class="button" ng-click="closeInputData()">Cerrar</button>
			</footer>
		</div>
	</div>

	<div class="modal is-active" ng-show="isTimbrarCC">
		<div class="modal-background"></div>
		<div class="modal-card">
			<header class="modal-card-head">
				<p class="modal-card-title">Timbrar Cortes de Caja</p>
				<button class="delete" aria-label="close" ng-click="closeTimbrarCC()"></button>
			</header>
			<section class="modal-card-body">
				<table class="table" style="width:100%">
          <tr class="tbl-header">
            <td></td>
            <td>Fecha</td>
            <td>Cliente</td>
            <td>Importe</td>
          </tr>
        </table>
        <div style="height:250px;overflow:auto;margin-top:-25px">
          <table class="table">
            <tr ng-repeat="x in lstCortesCajaNT">
              <td><input type="checkbox" name="cc{{$index}}" id="cc{{$index}}"></td>
              <td>{{x.FECHA_CORTE}}</td>
              <td>{{x.CLIENTE}}</td>
              <td>{{x.IMPORTE | currency}}</td>
            </tr>
          </table>
        </div>
			</section>
			<footer class="modal-card-foot">
				<button class="button is-success" ng-click="timbraCC()">Timbrar</button>
				<button class="button" ng-click="closeTimbrarCC()">No</button>
			</footer>
		</div>
	</div>
	
	<table style="width: 100%; display:none" id="factura">
		<tbody>
			<tr>
			  <td style="width: 30%">
          <table style="border-collapse: collapse; width: 100%; height: 90px;" border="1">
            <tbody>
              <tr style="height: 18px;">
                <td style="height: 54px; width: 33.3942%;" rowspan="6"><img src="../img/logo.jpg" width="150px"></td>
                <td style="text-align: center; height: 18px; width: 33.2117%;" colspan="2"> <h1 class="title is-3 has-text-centered">{{Empresa.NOMBRE}}</h1></td>
              </tr>
							<tr style="height: 18px;">
                <td style="width: 33.2117%; height: 18px;">Código de Indetificación Forestal</td>
                <td style="width: 33.2117%; height: 18px; text-align: right;">R-29-010-FOS-001/11</td>
              </tr>
              <tr style="height: 18px;">
                <td style="width: 33.2117%; height: 18px;">{{Empresa.DOMICILIO}}</td>
                <td style="width: 33.2117%; height: 18px; text-align: right;">Factura</td>
              </tr>
              <tr style="height: 18px;">
                <td style="width: 33.2117%; height: 18px;">{{Empresa.RFC}}</td>
                <td style="width: 33.2117%; height: 18px; text-align: right;">{{FacturaPrint.DOCUMENTO}}</td>
              </tr>
              <tr style="height: 18px;">
                <td style="width: 33.2117%; height: 18px;">{{Empresa.CP}}</td>
                <td style="width: 33.2117%; text-align: right; height: 18px;">Fecha</td>
              </tr>
              <tr style="height: 18px;">
                <td style="width: 33.2117%; height: 18px;"></td>
                <td style="width: 33.2117%; text-align: right; height: 18px;">{{FacturaPrint.FECHA_FACTURA}}</td>
              </tr>
            </tbody>
          </table>
        </td>
		  </tr>
      <tr>
        <td>
        <hr/>
        </td>
      </tr>
		  <tr>
			  <td style="width: 100%" >
          <table>
            <tr>
              <td>Cliente:</td>
              <td>{{Cliente.NOMBRE}}</td>
            </tr>
            <tr>
              <td>Direccion</td>
              <td>{{Cliente.DOMICILIO}}</td>
            </tr>
            <tr>
              <td>Contacto</td>
              <td>{{Cliente.CONTACTO}}</td>
            </tr>
          </table>
        </td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			<td style="width: 100%">
				<div style="border: 2px solid black; height: 400px; width: 100%;">
				<table style="width: 100%" >
					<thead>
						<tr>
							<th style="width: 200px; text-align: center;font-size:14px">Descripcion</th>
							<th style="width: 73.8px; text-align: center;font-size:14px">Cantidad</th>
							<th style="width: 81.2px; text-align: center;font-size:14px">Unidad</th>
							<th style="width: 128px; text-align: right;font-size:14px">Costo Unitario</th>
						</tr>
          </thead>
          <tbody>
						<tr ng-repeat="p in lstProdCompra">
							<td style="width: 200px; text-align: center">{{p.DESCRIPCION}}</td>
							<td style="width: 73.8px; text-align: center">{{p.CANTIDAD}}</td>
							<td style="width: 81.2px; text-align: center">{{p.UNIDAD_MEDIDA}}</td>
							<td style="width: 104px; text-align: right">$ {{p.IMPORTE | number:2}}</td>
						</tr>
					</tbody>
				</table>
				</div>
			</td>
			</tr>
			<tr>
				<td style="width:100%; text-align:right">
					<table style="width: 100%" >
						<tbody>
						<tr>
							<td style="width: 83%; text-align: right">Sub Total</td>
							<td style="width: 17%; text-align: right">{{factura.subtotal | currency}}</td>
						</tr>
            <tr>
							<td style="width: 83%; text-align: right">Descuento</td>
							<td style="width: 17%; text-align: right">{{dsctoValor | currency}}</td>
						</tr>
						<tr>
							<td style="width: 83%; text-align: right">Impuestos</td>
							<td style="width: 17%; text-align: right">{{impuestos | currency}}</td>
						</tr>
						<tr>
							<td style="width: 83%; text-align: right">Total</td>
							<td style="width: 17%; text-align: right">{{factura.total | currency}}</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>