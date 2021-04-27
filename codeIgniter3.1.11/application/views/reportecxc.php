<div class="container" ng-controller="Reportecxc" data-ng-init="init()">
  <div class="notification" >
    <h1 class="title has-text-centered is-4">Estado de Cuenta</h1>
  </div>
  <div class="box">
    <form name="myForm">
      <div class="columns">
        <div class="column is-narrow"><label for="" class="label">Tipo Reporte:</label> </div>
        <div class="column is-narrow">
          <div class="select is-small">
            <select ng-model="tiporeporte" ng-change="selecTipoReporte()" ng-options="x.valor as x.label for x in lstTipoRep">
            </select>
          </div>
        </div>
        <div class="column is-1" ng-show="showInputCliente"><input type="text" class="input is-small" ng-model="idcliente" placeholder="Clave" ng-keyup="buscaClieByCve()"></div>
        <div class="column is-2" ng-show="showInputCliente"><input type="text" class="input is-small" ng-model="nombrecliente" placeholder="Nombre del Cliente" ng-keyup="buscaCliByNombre()"></div>

      </div>
      <div class="columns">
        <div class="column is-1"><label for="" class="label">Desde</label> </div>
        <div class="column is-1">
          <input type="text" ng-model="fecIni" ng-blur="fecIniChange()" class="input is-small" id="fechaInicio" required>
        </div>
        <div class="column is-1"><label for="" class="label">Hasta</label> </div>
        <div class="column is-1">
          <input type="text" ng-model="fecFin" ng-blur="fecFinChange()" class="input is-small" id="fechaFin" required>
        </div>
      </div>
      <div class="columns">
        <div class="column"><button class="button is-success" ng-click="creaReporte()">Buscar</button></div>
      </div>
    </form>
  </div>
  <div class="table-container" ng-show="isRepShow" style="border:1px solid black;width:99%" id="exportable">
    <table style="width:100%">
      <colgroup>
        <col width="55%">
        <col width="45%">
      </colgroup>
      <tr class="spaceUnder">
        <td style="text-align:right"><label class="label">{{nombreEmpresa}}</label></td>
        <td style="text-align:right"><label>{{fechaImpresion}}</label></td>
      </tr>
      <tr class="spaceUnder">
        <td colspan="2" align="center"><label class="label">{{direccionEmpresa}}</label></td>
      </tr>
      <tr class="spaceUnder">
        <td colspan="2" align="center"><label class="label">{{rfcEmpresa}}</label></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><label class="label">Reporte CXC del {{fecIniRep}} al {{FecFinRep}}</label></td>
      </tr>
    </table>
    <table class="table is-bordered" style="width:100%;margin-top:5px;margin-bottom:0px">
      <thead>
        <tr>
          <th class="font12">NOMBRE/RAZON SOCIAL</th>
          <th class="font12">CLAVE</th>
          <th class="font12">A 30 DIAS</th>
          <th class="font12">A 60 DIAS</th>
          <th class="font12">A 90 DIAS</th>
          <th class="font12">MAYOR A 90 DIAS</th>
          <th class="font12">SALDO</th>
        </tr>
      </thead>
    </table>
    <div style="width:100%;margin-top:5px;margin-bottom:0px;overflow:auto;height:300px">
      <table class="table is-bordered" style="width:100%;margin-top:5px;margin-bottom:0px">
        <tbody>
          <tr ng-repeat="x in lstRepcxc">
            <td>{{x.NOMBRE}}</td>
            <td>{{x.CLAVE}}</td>
            <td>{{x.TR_DIAS | currency}}</td>
            <td>{{x.SE_DIAS | currency}}</td>
            <td>{{x.NO_DIAS | currency}}</td>
            <td>{{x.MAYOR_90_DIAS | currency}}</td>
            <td>{{x.TR_DIAS + x.SE_DIAS + x.NO_DIAS + x.MAYOR_90_DIAS | currency}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
