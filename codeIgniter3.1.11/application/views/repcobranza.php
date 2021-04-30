
<div class="container" ng-controller="CtrlCobranza" data-ng-init="init()">
  <div class="notification" >
	  <h1 class="title is-4 has-text-centered">Reporte de Cobranza</h1>
	</div>
  <div class="box" ng-show="!isRepShow">
    <form name="myForm">
      <div class="columns">
        <div class="column is-narrow">
          <label class="label">Periodo:</label>
        </div>
        <div class="column is-narrow" style="margin-right:-20px; width:110px">
          <input type="text" ng-model="fecIni" ng-blur="fecIniChange()" class="input is-small" id="fechaInicio" required>
        </div>
        <div class="column is-narrow"><label class="label">-</label></div>
        <div class="column is-narrow" style="margin-left:-20px; width:110px">
          <input type="text" ng-model="fecFin" ng-blur="fecFinChange()" class="input is-small" id="fechaFin" required>
        </div>
      </div>
      <div class="columns">
        <div class="column">
          <button class="button is-success" ng-click="creaReporte()" ng-disabled="myForm.$invalid">Enviar</button>
        </div>
      </div>
    </form>
  </div>
  <div class="table-container" ng-show="isRepShow" style="border:1px solid black;width:99%" id="exportable">
    <table class="table" style="width:100%">
      <colgroup>
        <col width="20%" >
        <col width="35%" >
        <col width="10%" >
        <col width="15%" >
        <col width="20%" >
      </colgroup>
      <thead>
        <tr>
          <th style="text-align:center">No DOC</th>
          <th style="text-align:center">NOMBRE/RAZON SOCIAL</th>          
          <th style="text-align:center">FP</th>
          <th style="text-align:center">MOVIMIENTO</th>
          <th style="text-align:center">ABONO</th>
        </tr>
      </thead>
    </table>
    <div style="margin-top:-25px;height:560px;overflow:auto">
      <table class="table is-bordered is-hoverable" style="width:100%">
        <colgroup>
          <col width="20%" >
          <col width="35%" >
          <col width="10%" >
          <col width="15%" >
          <col width="20%" >
        </colgroup>
        <tbody>
          <tr ng-repeat="x in lstRepCobr">
            <td ng-if="x.TITLE === 0" colspan="5"><label class="label">{{x.NOMBRE}}</label></td>
            <td ng-if="x.TITLE === 1">{{x.DOCTO}}</td>
            <td ng-if="x.TITLE === 1">{{x.NOMBRE}}</td>
            <td ng-if="x.TITLE === 1" style="text-align:center">{{x.FP}}</td>
            <td ng-if="x.TITLE === 1" style="text-align:center">{{x.MP}}</td>
            <td ng-if="x.TITLE === 1" style="text-align:right">{{x.ABONO | currency}}</td>
            <td ng-if="x.TITLE === 2" style="text-align:center" colspan="4"><label class="label">{{x.DOCTO}} Mov(s)</label></td>
            <td ng-if="x.TITLE === 2" style="text-align:right" ><label class="label">{{x.ABONO | currency}}</label></td>
            <td ng-if="x.TITLE === 3" style="text-align:center;border-top:2px solid black;border-bottom:2px solid black" colspan="4"><label class="label">{{x.DOCTO}} Mov(s)</label></td>
            <td ng-if="x.TITLE === 3" style="text-align:right;border-top:2px solid black;border-bottom:2px solid black" ><label class="label">{{x.ABONO | currency}}</label></td>
          </tr>
        </tbody>
      </table>
      <table class="table" style="width:100%;margin-top:-25px">
        <colgroup>
          <col width="25%"/>
          <col width="25%"/>
          <col width="25%"/>
          <col width="25%"/>
        </colgroup>
        <tbody>
          <tr>
            <td style="text-align:center">TOTALES POR MOVIMIENTO</td>
            <td></td>
            <td style="text-align:right">Moneda Nacional</td>
            <td></td>
          </tr>
          <tr ng-repeat="x in lstFormpago">
            <td></td>
            <td>{{x.DESCRIPCION}}</td>
            <td style="text-align:right">{{x.QTY | currency}}</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
