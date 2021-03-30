<div ng-app="myInicioApp" ng-controller="myInicio" data-ng-init="init()">
<table border="1" style="width:100%">
  <colgroup>
    <col width="33%"/>
    <col width="33%"/>
    <col width="33%"/>
  </colgroup>
  <tbody>
    <tr>
      <td style="height:300px">
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
                <table style="width:100%">
                  <tr ng-repeat="x in qtyinvent">
                    <td>{{x.LINEA}}</td>
                    <td style="text-align:right">{{x.SUMA | currency}}</td>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
      <td style="height:300px; text-align:'center'" colspan="2">
        <h4 class="centro">{{titulografica1}}</h4>
        <div>
          <canvas id="bar" class="chart chart-bar"
            chart-data="data" chart-labels="labels" chart-series="series" >
          </canvas>
        </div>
      </td>
    </tr>
  </tbody>
</table>
</div>