<div ng-controller="myCtrlPagos" data-ng-init="init()">
    <div class="container">
      <div class="notification" >
      <h1 class="title is-2 has-text-centered">Cobranza</h1>
      </div>
    </div>
    <div class="container">
      <div class="box" id="barranavegacion" ng-show="!isCapturaPago">
        <nav class="level">
          <div class="level-left">
          <div class="level-item">
            Filtro:
          </div>
            <div class="level-item">
              <input name="filtrocliente" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tblClientes');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
            </div>
          </div>
          <div class="level-right">
            <p class="level-item" ng-show="permisos.alta">
              <a ng-click="agregaCobranza();"><span class="icon has-text-success"><i class="fas fa-file" title="Aplicar pago"></i></span></a>
            </p>
            <p class="level-item" ng-show="permisos.baja">
              <a ng-click="preguntaElimnaFactura()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Factura"></i></span></a>
            </p>
          </div>
        </nav>
      </div>
      <div class="table-container is-centered" style="margin:auto 0px" id="lstclientes" ng-show="!isCapturaPago">
        <table class="table is-bordered" style="width:100%">
          <colgroup>						
            <col width="9%">
            <col width="9%">
            <col width="14%">
            <col width="8%">
            <col width="8%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
          </colgroup>
          <tr class="tbl-header">
            <td style="text-align:center;font-size:12px">DOCUMENTO</td>									
            <td style="text-align:center;font-size:12px">FECHA</td>
            <td style="text-align:center;font-size:12px">MET</td>
            <td style="text-align:center;font-size:12px">CLIENTE</td>
            <td style="text-align:center;font-size:12px">IMPORTE</td>
            <td style="text-align:center;font-size:12px">SALDO</td>
            <td style="text-align:center;font-size:12px">VENCE</td>
            <td style="text-align:center;font-size:12px">FORMA DE PAGO</td>
            <td style="text-align:center;font-size:12px">VENDEDOR</td>
          </tr>							
        </table>
        <div style="width:100%; height:500px; overflow:auto;margin-top:-25px">
					<table class="table is-bordered is-hoverable" style="width:100%" id="tblClientes">
						<colgroup>
              <col width="9%">
							<col width="9%">
							<col width="14%">
							<col width="8%">
							<col width="8%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
							<col width="10%">
            </colgroup>
  					<tr ng-repeat="x in lstFacturas" ng-click="selectRowFactura(x.ID_FACTURA, $index)" ng-class="{selected: x.ID_FACTURA === idFactura}">
							<td style="text-align:center;font-size:12px">{{x.DOCUMENTO}}</td>									
							<td style="text-align:center;font-size:12px">{{x.FECHA_FACTURA | date}}</td>
              <td style="text-align:center;font-size:12px">{{x.METODO_PAGO}}</td>
							<td style="text-align:center;font-size:12px">{{x.CLIENTE}}</td>
							<td style="text-align:center;font-size:12px">{{x.IMPORTE | currency}}</td>
							<td style="text-align:center;font-size:12px">{{x.SALDO | currency}}</td>
              <td style="text-align:center;font-size:12px">{{x.FECHA_VENCIMIENTO}}</td>
  						<td style="text-align:center;font-size:12px">{{x.FORMA_PAGO}}</td>
							<td style="text-align:center;font-size:12px">{{x.VENDEDOR}}</td>
						</tr>
					</table>
				</div>
      </div>
      <div ng-show="isCapturaPago">
        <div class="container">
          <div class="box" id="barranavegacion" ng-show="isCapturaPago">
            <nav class="level">
              <div class="level-left">
                <div class="level-item">
                  <p class="subtitle is-5"><strong>Filtro:</strong></p>
                </div>
                
              </div>
              <div class="level-right">
                <p class="level-item" ng-show="permisos.alta">
                  <a ng-click="agregaPago();"><span class="icon has-text-success"><i class="fas fa-hand-holding-usd" title="Agrega Pago"></i></span></a></p>
                <p class="level-item" ng-show="permisos.modificacion">
                  <a ng-click="editaPago()"><span class="icon has-text-info"><i class="fas fa-edit" title="Editar Pago"></i></span></a></p>
                <p class="level-item" ng-show="permisos.baja">
                  <a ng-click="eliminaPago()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Pago"></i></span></a></p>
              </div>
            </nav>
          </div>
          <div class="box">
          <div class="columns">
            <div class="column">
              <legend>Serie</legend>
            </div>
            <div class="column">
            <legend>Documento</legend>
            </div>
            <div class="column">
            <legend>Cliente</legend>
            </div>
          </div>
          <div class="columns">
            <div class="column">
              <input type="text" name="serie"  />
            </div>
            <div class="column">
              <input type="text" name="documento" ng-model="pago.documento"/>
            </div>
            <div class="column">
              <input type="text" name="cliente" ng-model="pago.idcliente"/>
            </div>
            <div class="column">
              <label for="">{{pago.cliente}}</label>
            </div>
          </div>
          </div>
          <div class="columns">
            <div class="box">
              <div class="column">
                <div class="columns">
                  <div class="column">Fecha</div>
                  <div class="column">10/02/2021</div>
                </div>
                <div class="columns">
                  <div class="column">Importe</div>
                  <div class="column">{{pago.saldo | currency}}</div>
                </div>
                <div class="columns">
                  <div class="column">Cobrado</div>
                  <div class="column">{{pago.cobrado | currency}}</div>
                </div>
                <div class="columns">
                  <div class="column">Saldo</div>
                  <div class="column">{{pago.saldopendiente | currency}}</div>
                </div>
              </div>
            </div>
            
            <div class="column">
              <div class="box">
                <table style="width:100%">
                <colgroup>
                  <col width="25%"/>
                  <col width="25%"/>
                  <col width="25%"/>
                  <col width="25%"/>
                </colgroup>
                  <thead>
                    <tr>
                      <td style="text-align:center">Fecha</td>
                      <td style="text-align:center">REP</td>
                      <td style="text-align:center">Importe</td>
                      <td style="text-align:center">Referencia</td>
                    </tr>
                  </thead>
                </table>
                <div style="height:110px;border:2px solid black; overflow:auto">
                  <table class="table table-hover" style="width:100%">
                    <colgroup>
                      <col width="25%"/>
                      <col width="25%"/>
                      <col width="25%"/>
                      <col width="25%"/>
                    </colgroup>
                    <tbody>
                      <tr ng-repeat="x in lstPagos" ng-click="selectRowPago(x.ID_PAGO,x.IMPORTE_PAGO)" ng-class="{selected: x.ID_PAGO === idPago}" >
                        <td style="text-align:center">{{x.FECHA_PAGO}}</td>
                        <td></td>
                        <td style="text-align:center">{{x.IMPORTE_PAGO | currency}}</td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <button class="button is-link" ng-click="cerrarPago()">Cerrar</button>
        </div>
      </div>
    </div>
    <div class="{{isModalActive ? 'modal is-active':'modal'}}">
      <div class="modal-background"></div>
      <div class="modal-card pagos-width">
        <header class="modal-card-head">
          <p class="modal-card-title">Movimiento</p>
          <button class="delete" aria-label="close" ng-click="closeMovimiento()"></button>
        </header>
        <section class="modal-card-body">
          <table style="width:60%">
            <tr>
              <td><label for="">Factura</label></td>
              <td>{{pago.documento}}</td> 
            </tr>
          </table>
          <form name="myForm">
          <div class="columns">
            <div class="column">
              <fieldset>
                <legend>Datos del pago</legend>
                <div class="columns">
                    <div class="column">
                      <legend>Fecha</legend>  
                    </div>
                    <div class="column">
                      <input type="text" class="input is-small" id="fechaPago" ng-blur="fecPagoChange()" ng-model="fechapago" required />
                    </div>
                </div>
                <div class="columns">
                  <div class="column">Importe</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="pago.importepago" required/></div>
                </div>
                <div class="columns">
                  <div class="column">Movimiento</div>
                  <div class="column">
                    <select name="" id="">
                      <option value="">Cheque</option>
                      <option value="">Efectivo</option>
                      <option value="">Transferencia</option>
                    </select>
                  </div>
                </div>
                <div class="columns">
                  <div class="column">Banco</div>
                  <div class="column">
                    <select name="" id="">
                      <option value="">BBVA</option>
                      <option value="">Santander</option>
                      <option value="">Banamex</option>
                    </select>
                  </div>
                </div>
                <div class="columns">
                  <div class="column">Cheque/Pol</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="pago.cheque"/></div>
                </div>
                <div class="columns">
                  <div class="column">Depósito en</div>
                  <div class="column">
                    <select name="" id="">
                      <option value="">1</option>
                      <option value="">2</option>
                      <option value="">3</option>
                    </select>
                  </div>
                </div>
                <div class="columns">
                  <div class="column">Póliza predefinida</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="pago.poliza"></div>
                </div>
                <div class="columns">
                  <div class="column">Importe Base</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="pago.importebase"></div>
                </div>
              </fieldset>
            </div>
          </div>
          </form>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" ng-click="guardaPago()" ng-disabled="myForm.$invalid">{{btnName}}</button>
          <button class="button" ng-click="closeMovimiento()">Cerrar</button>
        </footer>
      </div>
    </div>
</div>