<div class="container" ng-controller="myCtrlCortecaja" data-ng-init="init()">
  <div class="container">
    <div class="notification">
      <h1 class="title is-4 has-text-centered">Consulta Cortes de Caja</h1>
    </div>
  </div>
  <div class="container">
    <div class="columns">
      <div class="column">
        <div class="box">
        <div class="columns">
          <div class="column is-1"><label class="label">Mes:</label> </div>
          <div class="column is-narrow" style="width:170px">
            <div class="select is-small">
              <select ng-model="mes" ng-options="x.valor as x.mes for x in meses" ng-change="seleccionaMes()"></select>
            </div>
          </div>
          <div class="column is-1">Caja:</div>
          <div class="column is-1">01</div>
        </div>
        <div class="columns">
          <div class="column">
            <table class="table is-bordered" style="width:100%">
              <colgroup>
                <col width="30%"/>
                <col width="20%"/>
                <col width="25%"/>
                <col width="25%"/>
              </colgroup>
              <tbody>
                <tr class="tbl-header">
                  <td class="font12" style="text-align:center">Día</td>
                  <td class="font12" style="text-align:center">Operaciones</td>
                  <td class="font12" style="text-align:center">Canceladas</td>
                  <td class="font12" style="text-align:center">Importe</td>
                </tr>
              </tbody>
            </table>
            <div style="width:100%; height:175px; overflow:auto;border:solid black 2px;margin-top:-25px">
              <table class="table is-bordered is-hoverable" style="width:100%">
                <colgroup>
                  <col width="30%"/>
                  <col width="20%"/>
                  <col width="25%"/>
                  <col width="25%"/>
                </colgroup>
                <tbody>
                  <tr ng-repeat="x in lstCortesCaja" ng-click="selectRowCC($index)" ng-class="{selected: $index === indexRowCC}">
                    <td style="text-align:center" class="font12">{{x.FECHA}}</td>
                    <td style="text-align:center" class="font12">{{x.OPERACIONES}}</td>
                    <td style="text-align:center" class="font12">{{x.CANCELADOS}}</td>
                    <td style="text-align:center" class="font12">{{x.IMPORTE | currency}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <table style="width:100%">
            <tr>
              <td>
                <table style="width:60%;">
                  <colgroup>
                    <col width="25%"/>
                    <col width="25%"/>
                    <col width="25%"/>
                    <col width="25%"/>
                  </colgroup>
                  <tbody>
                    <tr>
                      <td style="text-align:right">Cajero</td>
                      <td colspan="3" style="text-align:right">{{usuarioCorte}}</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Inicio</td>
                      <td>{{dataIniDay.horaIni}}</td>
                      <td style="text-align:right">{{dataIniDay.docIni}}</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Fin</td>
                      <td>{{dataIniDay.horaFin}}</td>
                      <td style="text-align:right">{{dataIniDay.docFin}}</td>
                    </tr>
                    <tr>
                      <td colspan="4">&nbsp;</td>
                    </tr>
                  </tbody>
                </table>
                <table style="width:60%">
                  <colgroup>
                    <col width="25%"/>
                    <col width="40%"/>
                    <col width="35%"/>
                  </colgroup>
                  <tbody>
                    <tr>
                      <td colspan="3" style="text-align:left">Pagado en:</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Efectivo</td>
                      <td style="text-align:right">{{pagos.efectivo | currency}}</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Tarjeta</td>
                      <td style="text-align:right">{{pagos.tarjeta | currency}}</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Cheque</td>
                      <td style="text-align:right">{{pagos.cheque | currency}}</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Vales</td>
                      <td style="text-align:right">{{pagos.vales | currency}}</td>
                    </tr>
                    <tr>
                      <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Contado-></td>
                      <td style="text-align:right">{{tipopago.contado | currency}}</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>Crédito-></td>
                      <td style="text-align:right">{{tipopago.credito | currency}}</td>
                    </tr>
                    <tr>
                      <td colspan="2"></td>
                      <td style="border-bottom:2px solid black">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>
                        <legend style="font-weight: bold;">
                        Total
                        </legend>
                      </td>
                      <td style="text-align:right;font-weight: bold;">{{tipopago.contado + tipopago.credito | currency}}</td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td>
                <p class="level-item" ng-show="indexRowCC === -1">
                  <a>
                    <span class="icon has-text-info">
                      <i title="Imprime Corte de Caja" class="fas fa-print" style="color:grey"></i>
                    </span>
                  </a>
                </p>
                <p class="level-item" ng-show="indexRowCC !== -1">
                  <a ng-click="imprimeCorteCaja()">
                    <span class="icon has-text-info">
                      <i title="Imprime Corte de Caja" class="fas fa-print"></i>
                    </span>
                  </a>
                </p>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column">
        <div class="box">
          <table class="table" style="width:100%">
            <colgroup>
              <col width="35%">
              <col width="15%">
              <col width="20%">
              <col width="30%">
            </colgroup>
            <tbody>
              <tr>
                <td class="font12" colspan="4">Operaciones por día: {{fechaOperacion}}</td>
              </tr>
              <tr class="tbl-header">
                <td class="font12" style="text-align:center">Documento</td>
                <td class="font12" style="text-align:center">Part</td>
                <td class="font12" style="text-align:center">FP</td>
                <td class="font12" style="text-align:center">Importe</td>
              </tr>
            </tbody>
          </table>
          <div style="overflow:auto; height:200px;margin-top:-25px;border:2px solid black">
            <table class="table is-bordered is-hoverable" style="width:100%">
              <col width="35%">
              <col width="15%">
              <col width="20%">
              <col width="30%">
              <tr ng-repeat="x in lstVentas" ng-click="selOperacion(x.ID_VENTA,$index)"  ng-class="{selected: x.ID_VENTA === idOpSel}">
                <td class="font12" ng-class="{canceled: x.CANCELADO === 't'}">{{x.DOCUMENTO.trim()}}</td>
                <td class="font12" ng-class="{canceled: x.CANCELADO === 't'}" style="text-align:center">{{x.COUNT}}</td>
                <td class="font12" ng-class="{canceled: x.CANCELADO === 't'}" style="text-align:center">{{x.TIPO_PAGO}}</td>
                <td class="font12" ng-class="{canceled: x.CANCELADO === 't'}" style="text-align:right;">{{x.IMPORTE | currency}}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="column">
        <div class="box">
          <table style="width:100%">
            <colgroup>
              <col width="70%"/>
              <col width="30%"/>
            </colgroup>
            <tr>
              <td>
                <table style="width:100%;margin-bottom:-1px" class="table">
                  <tbody>
                    <colgroup>
                      <col width="40%"/> 
                      <col width="30%"/> 
                      <col width="30%"/> 
                    </colgroup>
                    <tr>
                      <td class="font12" style="text-align:left">Detalle de Ventas</td>
                      <td class="font12" style="text-align:center">{{usuarioVenta}}</td>
                      <td class="font12" style="text-align:right">Forma de Pago</td>
                    </tr>
                  </tbody>
                </table>
                <table style="width:100%" class="table">
                  <tbody>
                    <colgroup>
                      <col width="60%"/> 
                      <col width="20%"/> 
                      <col width="20%"/> 
                    </colgroup>
                    <tr class="tbl-header">
                      <td class="font12">Desc</td>
                      <td class="font12">Cant</td>                      
                      <td class="font12">Impte</td>
                    </tr>
                  </tbody>
                </table>
                <div style="overflow:auto; height:200px;margin-top:-25px;border:2px solid black;width:100%">
                  <table class="table" style="width:100%">
                    <tbody>
                      <tr ng-repeat="x in lstDetalleVta" ng-click="selDetalleVenta($index)" ng-class="{selected: $index === idxDetalleVenta}">
                        <td class="font12">{{x.DESCRIPCION}}</td>
                        <td class="font12">{{x.CANTIDAD}}</td>
                        <td class="font12">{{x.IMPORTE | currency}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <table style="width:100%">
                  <colgroup>
                    <col width="25%"/>
                    <col width="25%"/>
                    <col width="25%"/>
                    <col width="25%"/>
                  </colgroup>
                  <tbody>
                    <tr>
                      <td class="font12">Descuento</td>
                      <td class="font12">{{descuento | currency}}</td>
                      <td class="font12">Impuestos</td>
                      <td class="font12">{{impuestos | currency}}</td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td>
                <table class="table" style="width:100%;"  >
                  <tbody>
                    <tr>
                      <td colspan="2" class="font12" style="text-align:center">{{venta.ID_TIPO_PAGO === 1 ? 'Contado' : 'Crédito'}}</td>
                    </tr>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class="font12">Efectivo</td>
                      <td class="font12" style="text-align:right">{{venta.PAG_EFECTIVO - venta.CAMBIO| currency}}</td>
                    </tr>
                    <tr>
                      <td class="font12" style="text-align:right">Recibido</td>
                      <td class="font12" style="text-align:right">{{venta.PAG_EFECTIVO | currency}}</td>
                    </tr>
                    <tr>
                      <td class="font12" style="text-align:right">Cambio</td>
                      <td class="font12" style="text-align:right">{{venta.CAMBIO| currency}}</td>
                    </tr>
                    <tr>
                      <td class="font12">Tarjeta</td>
                      <td class="font12" style="text-align:right">{{venta.PAG_TARJETA | currency}}</td>
                    </tr>
                    <tr>
                      <td class="font12">Cheque</td>
                      <td class="font12" style="text-align:right">{{venta.PAG_CHEQUE | currency}}</td>
                    </tr>
                    <tr>
                      <td class="font12">Vales</td>
                      <td class="font12" style="text-align:right">{{venta.PAG_VALES | currency}}</td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <div id="resumenoper" style="display:none">
        <h1 class="title is-4 has-text-centered">Corte de Caja del {{fechaOperacion}}</h1> 
        <table style="width:70%;">
          <colgroup>
            <col width="25%"/>
            <col width="25%"/>
            <col width="25%"/>
            <col width="25%"/>
          </colgroup>
          <tbody>
            <tr>
              <td style="text-align:right">Cajero</td>
              <td colspan="3" style="text-align:right">{{usuarioCorte}}</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Inicio</td>
              <td>{{dataIniDay.horaIni}}</td>
              <td style="text-align:right">{{dataIniDay.docIni}}</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Fin</td>
              <td>{{dataIniDay.horaFin}}</td>
              <td style="text-align:right">{{dataIniDay.docFin}}</td>
            </tr>
            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
          </tbody>
        </table>
        <table style="width:70%">
          <colgroup>
            <col width="25%"/>
            <col width="40%"/>
            <col width="35%"/>
          </colgroup>
          <tbody>
            <tr>
              <td colspan="3" style="text-align:left">Pagado en:</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Efectivo</td>
              <td style="text-align:right">{{pagos.efectivo | currency}}</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Tarjeta</td>
              <td style="text-align:right">{{pagos.tarjeta | currency}}</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Cheque</td>
              <td style="text-align:right">{{pagos.cheque | currency}}</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Vales</td>
              <td style="text-align:right">{{pagos.vales | currency}}</td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Contado-></td>
              <td style="text-align:right">{{tipopago.contado | currency}}</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>Crédito-></td>
              <td style="text-align:right">{{tipopago.credito | currency}}</td>
            </tr>
            <tr>
              <td colspan="2"></td>
              <td style="border-bottom:2px solid black">&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>
                <legend style="font-weight: bold;">
                Total
                </legend>
              </td>
              <td style="text-align:right;font-weight: bold;">{{tipopago.contado + tipopago.credito | currency}}</td>
            </tr>
          </tbody>
        </table>
        <br>
        <table>
          <thead>
            <th>
              <td>Factura generada en CXC número: {{noFactura}}</td>
            </th>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>