
<div class="container" ng-controller="repControlVentas" data-ng-init="init()">
  <div class="notification" >
	   <h1 class="title has-text-centered">Reporte Ventas</h1>
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
        <td colspan="2" align="center"><label class="label">Análisis de Ventas del {{fecIniRep}} al {{FecFinRep}}</label></td>
      </tr>
    </table>
    <table class="table is-bordered" style="width:100%;margin-top:5px;margin-bottom:0px">
      <col width="19%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <tr>
        <th rowspan="2" style="vertical-align:middle" ng-click="orderByMe('DESCRIPCION')"><a href="">Descripción</a></th>
        <th rowspan="2" align="center" style="vertical-align:middle" ng-click="orderByMe('CODIGO')"><a href="">Código</a></th>
        <th rowspan="2" align="center" style="vertical-align:middle">Cantidad</th>
        <th colspan="2" align="center">Venta</th>
        <th rowspan="2" align="center" style="vertical-align:middle">Precio Promedio</th>
        <th rowspan="2" align="center" style="vertical-align:middle">Ventas (%)</th>
        <th rowspan="2" align="center" style="vertical-align:middle">Costo</th>
        <th rowspan="2" align="center" style="vertical-align:middle">Utilidad</th>
        <th rowspan="2" align="center" style="vertical-align:middle">Margen (%)</th>
      </tr>
      <tr>
        <th align="center">Bruta</th>
        <th align="center">Neta</th>
      </tr>
    </table>
    <table class="table is-bordered is-hoverable"style="width:100%">
      <col width="19%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <col width="9%">
      <tr ng-repeat="x in lstRepmalmcn | orderBy:myOrder:sortDir">
        <td>{{x.DESCRIPCION}}</td>
        <td>{{x.CODIGO}}</td>
        <td align="center">{{x.CANTIDAD}}</td>
        <td align="right">{{x.BRUTA | currency}}</td>
        <td align="center">{{x.NETA | currency}}</td>
        <td align="right">{{x.PRECIO_PROM | currency}}</td>
        <td align="center">{{x.PORCENTAJE | number:2}}</td>
        <td align="right">{{x.COSTO | currency}}</td>
        <td align="right" style="color:{{x.UTILIDAD<0 ? 'red':'black'}}">{{x.UTILIDAD | currency}}</td>
        <td align="center">{{x.UTILIDAD / x.NETA * 100 | number:2}}</td>
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
