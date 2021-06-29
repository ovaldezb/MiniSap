
<div ng-controller="myCtrlCobros" data-ng-init="init()">
    <div class="container">
      <div class="notification" >
      <h1 class="title is-2 has-text-centered">Cobranza</h1>
      </div>
    </div>
    <div class="container">
      <div class="box" id="barranavegacion" ng-show="!isCapturaCobro">
        <nav class="level">
          <div class="level-left">
            <div class="level-item">
              Filtro:
            </div>
            <div class="level-item">
              <input name="filtrocliente" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tblClientes');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
            </div>
            <div class="level-item" ng-show="permisos.modificacion">
              Historico: <input type="checkbox" ng-click="getHistorico($event)" id="historico">
            </div>
          </div>
          <div class="level-right">
              <p class="level-item" ng-show="permisos.alta">
                <a ng-click="agregaCobranza();"><span class="icon has-text-success"><i class="fas fa-file" title="Aplicar Cobro"></i></span></a>
              </p>
              <p class="level-item" ng-show="permisos.baja">
                <a ng-click="preguntaElimnaFactura()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Factura"></i></span></a>
              </p>
          </div>
        </nav>
      </div>
      <div class="table-container is-centered" style="margin:auto 0px" id="lstclientes" ng-show="!isCapturaCobro">
        <table class="table is-bordered" style="width:100%">
          <colgroup>						
            <col width="9%">
            <col width="9%">
            <col width="5%">
            <col width="17%">
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
							<col width="5%">
							<col width="17%">
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
      <div ng-show="isCapturaCobro">
        <div class="container">
          <div class="box" id="barranavegacion" ng-show="isCapturaCobro">
            <nav class="level">
              <div class="level-left">
                <div class="level-item">
                  <p class="subtitle is-5"></p>
                </div>
                
              </div>
              <div class="level-right">
                <p class="level-item" ng-show="permisos.alta">
                  <a ng-click="agregaCobro();"><span class="icon has-text-success"><i class="fas fa-hand-holding-usd" title="Agrega Cobro"></i></span></a></p>
                <p class="level-item" ng-show="permisos.modificacion">
                  <a ng-click="editaCobro()"><span class="icon has-text-info"><i class="fas fa-edit" title="Editar Cobro"></i></span></a></p>
                <p class="level-item" ng-show="permisos.baja">
                  <a ng-click="eliminaCobro()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina Cobro"></i></span></a></p>
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
              <label for="">{{cobro.documento}}</label>
            </div>
            <div class="column">
              <label for="">{{cobro.cliente}}</label>
            </div>
          </div>
          </div>
          <div class="columns">
            <div class="box">
              <div class="column">
                <div class="columns">
                  <div class="column">Fecha</div>
                  <div class="column">{{hoy}}</div>
                </div>
                <div class="columns">
                  <div class="column">Importe</div>
                  <div class="column">{{cobro.importetotal | currency}}</div>
                </div>
                <div class="columns">
                  <div class="column">Cobrado</div>
                  <div class="column">{{cobro.cobrado | currency}}</div>
                </div>
                <div class="columns">
                  <div class="column">Saldo</div>
                  <div class="column">{{cobro.saldo | currency}}</div>
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
                <div style="height:160px;border:2px solid black; overflow:auto">
                  <table class="table table-hover" style="width:100%">
                    <colgroup>
                      <col width="25%"/>
                      <col width="25%"/>
                      <col width="25%"/>
                      <col width="25%"/>
                    </colgroup>
                    <tbody>
                      <tr ng-repeat="x in lstCobros" ng-click="selectRowCobro(x.ID_COBRO,x.IMPORTE_COBRO,$index)" ng-class="{selected: x.ID_COBRO === idCobro}" >
                        <td style="text-align:center">{{x.FECHA_COBRO}}</td>
                        <td></td>
                        <td style="text-align:center">{{x.IMPORTE_COBRO | currency}}</td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <button class="button is-link" ng-click="cerrarCobro()">Cerrar</button>
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
              <td>{{cobro.documento}}</td> 
            </tr>
          </table>
          <form name="myForm">
          <div class="columns">
            <div class="column">
              <fieldset>
                <legend>Datos del cobro</legend>
                <div class="columns">
                    <div class="column">
                      <legend>Fecha</legend>  
                    </div>
                    <div class="column">
                      <input type="text" class="input is-small" id="fechaCobro" ng-blur="fecCobroChange()" ng-model="fechacobro" required />
                    </div>
                </div>
                <div class="columns">
                  <div class="column">Importe</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="cobro.importecobro" required/></div>
                </div>
                <div class="columns">
                  <div class="column">Movimiento</div>
                  <div class="column">
                    <div class="select is-small">
                    <select ng-model="cobro.movimiento" ng-options="x.ID_FORMA_PAGO as x.CLAVE+' '+x.DESCRIPCION for x in lstFormaPago"></select>
                    </div>
                  </div>
                </div>
                <div class="columns">
                  <div class="column">Banco</div>
                  <div class="column">
                    <div class="select is-small">
                    <select ng-model="cobro.banco" ng-options="x.ID_BANCO as x.DESCRIPCION for x in lstBancos"></select>
                    </div>
                  </div>
                </div>
                <div class="columns">
                  <div class="column">Cheque/Pol</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="cobro.cheque"/></div>
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
                  <div class="column"><input type="text" class="input is-small" ng-model="cobro.poliza"></div>
                </div>
                <div class="columns">
                  <div class="column">Importe Base</div>
                  <div class="column"><input type="text" class="input is-small" ng-model="cobro.importebase"></div>
                </div>
              </fieldset>
            </div>
          </div>
          </form>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" ng-click="guardaCobro()" ng-disabled="myForm.$invalid">{{btnName}}</button>
          <button class="button" ng-click="closeMovimiento()">Cerrar</button>
        </footer>
      </div>
    </div>
</div>

