
<div class="container" ng-controller="repControlMovAlm" data-ng-init="init()">
  <div class="notification" >
	   <h1 class="title has-text-centered">Reporte de Movimiento de Almacen</h1>
	</div>
  <div class="box" ng-show="!isRepShow">
    <form name="myForm">
    <div class="columns">
      <div class="column is-narrow" style="width:85px">
        <label class="label">Línea:</label>
      </div>
      <div class="column">
        <div class="select is-small">
          <select ng-model="linea" ng-options="x.ID_LINEA as x.NOMBRE for x in lstlinea"></select>
        </div>
      </div>
    </div>
    <div class="columns">
      <div class="column is-narrow">
        <label class="label">Periodo:</label>
      </div>
      <div class="column is-1">
        <input type="text" ng-model="fecIni" ng-blur="fecIniChange()" class="input is-small" id="fechaInicio" required>
      </div>
      <div class="column is-narrow"><label class="label">-</label></div>
      <div class="column is-1">
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
  <div class="table-container" ng-show="isRepShow" style="border:1px solid black;width:100%" id="exportable">
    <table style="width:100%">
      <tr class="spaceUnder">
        <td align="right" width="55%"><label class="label">{{nombreEmpresa}}</label></td>
        <td align="right" width="45%"><label>{{fechaImpresion}}</label></td>
      </tr>
      <tr class="spaceUnder">
        <td colspan="2" align="center"><label class="label">{{direccionEmpresa}}</label></td>
      </tr>
      <tr class="spaceUnder">
        <td colspan="2" align="center"><label class="label">{{rfcEmpresa}}</label></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><label class="label">Movimiento de Almacen del {{fecIniRep}} al {{FecFinRep}}</label></td>
      </tr>
    </table>
    <table class="table is-bordered" style="width:100%;margin-top:5px;margin-bottom:0px">
      <col width="25%">
      <col width="11%">
      <col width="8%">
      <col width="10%">
      <col width="8%">
      <col width="10%">
      <col width="8%">
      <col width="10%">
      <col width="10%">
      <tr>
        <th rowspan="2" style="vertical-align:middle" ng-click="orderByMe('DESCRIPCION')"><a href="">Descripción</a></th>
        <th rowspan="2" style="vertical-align:middle" align="center" ng-click="orderByMe('CODIGO')"><a href="">Código</a></th>
        <th colspan="2" style="vertical-align:middle" align="center">Entrada</th>
        <th colspan="2" style="vertical-align:middle" align="center">Salida</th>
        <th colspan="3" style="vertical-align:middle" align="center">Existencia</th>
      </tr>
      <tr>
        <th align="center" style="background:GhostWhite">Cantidad</th>
        <th align="center" style="background:GhostWhite">Importe</th>
        <th align="center">Cantidad</th>
        <th align="center">Importe</th>
        <th align="center">Cantidad</th>
        <th align="center">$ Lista</th>
        <th align="center">Importe</th>
      </tr>
    </table>
    <table class="table is-bordered is-hoverable"style="width:100%">
      <col width="25%">
      <col width="11%">
      <col width="8%">
      <col width="10%">
      <col width="8%">
      <col width="10%">
      <col width="8%">
      <col width="10%">
      <col width="10%">
      <tr ng-repeat="x in lstRepmalmcn | orderBy:myOrder:sortDir">
        <td>{{x.DESCRIPCION}}</td>
        <td>{{x.CODIGO}}</td>
        <td align="center" style="background:GhostWhite">{{x.CANT_COMP}}</td>
        <td align="right" style="background:GhostWhite">{{x.IMP_TOT_COMP | currency}}</td>
        <td align="center">{{x.CANT_VENTA}}</td>
        <td align="right">{{x.IMPO_TOT_VTA | currency}}</td>
        <td align="center">{{x.CANT_EXIST}}</td>
        <td align="right">{{x.PRECIO_LISTA | currency}}</td>
        <td align="right" style="color:{{x.IMPO_EXIST>1000 ? 'red':'black'}}">{{x.IMPO_EXIST | currency}}</td>
      </tr>
    </table>
  </div>
  <nav class="pagination is-centered" role="navigation" ng-show="isRepShow" aria-label="pagination" style="width:100%">
    <a class="pagination-next" ng-click="cerrarReporte()">Cerrar</a>
    <ul class="pagination-list">
      <li><a class="pagination-previous">Previous</a></li>
      <li><a class="pagination-link" aria-label="Goto page 1">1</a></li>
      <li><span class="pagination-ellipsis">&hellip;</span></li>
      <li><a class="pagination-link" aria-label="Goto page 45">45</a></li>
      <li><a class="pagination-link is-current" aria-label="Page 46" aria-current="page">46</a></li>
      <li><a class="pagination-link" aria-label="Goto page 47">47</a></li>
      <li><span class="pagination-ellipsis">&hellip;</span></li>
      <li><a class="pagination-link" aria-label="Goto page 86">86</a></li>
      <li><a class="pagination-next">Next</a></li>
    </ul>
    <a class="pagination-next" ng-click="exportExcel()">Excel</a>
    <a class="pagination-next" ng-click="exportCSV()">csv</a>
    <a class="pagination-next" ng-click="exportPDF()">PDF</a>
  </nav>
</div>
