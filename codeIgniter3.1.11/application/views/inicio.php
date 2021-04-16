<div ng-app="myInicioApp" ng-controller="myInicio" data-ng-init="init()">
<table border="1" style="width:100%">
  <colgroup>
    <col width="20%"/>
    <col width="20%"/>
    <col width="20%"/>
    <col width="20%"/>
    <col width="20%"/>
  </colgroup>
  <tbody>
    <tr>
      <td style="height:300px" colspan="2">
        <table style="width:100%">
          <tbody>
            <tr>
              <td style="text-align:center">Valor del Inventario</td>
              <td style="text-align:center; valign:middle">{{valorinventario | currency}}</td>
            </tr>
            <tr>
              <td colspan="2">
                <canvas id="pie" class="chart chart-pie"
                  chart-data="datinvent" chart-labels="labinvent" chart-options="options">
                </canvas> 
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <div style="overflow:auto; heigth:50px;border:2px solid blue">
                  <table style="width:100%">
                    <tr ng-repeat="x in qtyinvent">
                      <td class="font12">{{x.LINEA}}</td>
                      <td class="font12" style="text-align:right">{{x.SUMA | currency}}</td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
      <td style="height:300px; text-align:'center'" colspan="3">
        <h4 class="centro">Ventas</h4>
        <div>
          <canvas id="bar" class="chart chart-bar"
            chart-data="data" chart-labels="labels" chart-series="series" >
          </canvas>
        </div>
      </td>
    </tr>
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">
        <h4 class="centro">Cuentas por Cobrar {{valorcxc | currency}}</h4>
          <canvas id="pie" class="chart chart-pie"
            chart-data="datacxc" chart-labels="labelscxc" chart-options="options">
          </canvas> 
      </td>
      <td>&nbsp;</td>
      <td colspan="2">
        <h4 class="centro" >Cuentas por Pagar {{valorcxp | currency}}</h4>
        <canvas id="pie" class="chart chart-pie"
          chart-data="datacxp" chart-labels="labelscxp" chart-options="options">
        </canvas> 
      </td>
    </tr>
  </tbody>
</table>
</div>