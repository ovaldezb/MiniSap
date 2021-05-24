
<div class="container" ng-controller="repControlVentas" data-ng-init="init()">
  <div class="notification" >
	   <h1 class="title has-text-centered">Reporte Ventas</h1>
	</div>
  <div class="box" ng-show="!isRepShow">
    <form name="myForm">
      <div class="columns">
        <div class="column is-4">
          <div class="columns">
            <div class="column is-narrow center" style="width:85px">
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
        </div>
        <div class="columns">
          <div class="column">
            <div class="columns">
              <div class="column">
                <div class="dropdown {{menushow ? 'is-active':''}}">
                  <div class="dropdown-trigger">
                    <button class="button is-small" aria-haspopup="true" aria-controls="dropdown-menu3" ng-click="showMenu()" style="width:170px">
                      <span>Por Producto</span>
                      <span class="icon is-small">
                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                      </span>
                    </button>
                  </div>
                  <div class="dropdown-menu" id="dropdown-menu3" role="menu" style="margin-top:-3px">
                    <div class="dropdown-content" style="width:170px">
                      <a class="dropdown-item" ng-click="selTipoRepo(1)">Todos</a>
                      <a class="dropdown-item" ng-click="selTipoRepo(2)">Los 10 más vendidos</a>
                      <a class="dropdown-item" ng-click="selTipoRepo(3)">Por Codigo</a>
                      <a class="dropdown-item" ng-click="selTipoRepo(4)">Por nombre</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column">
                <label class="label">{{tipoReporte}}</label>
              </div>
            </div>
            <div class="columns" ng-show="bycodigo">
              <div class="column is-3">
                <label class="label">CODIGO:</label>
              </div>
              <div class="column is-6">
                <input type="text" class="input is-small"  ng-model="codigo" name="" id="">
              </div>
            </div>
            <div class="columns" ng-show="byname">
              <div class="column is-3">
                <label class="label">NOMBRE:</label>
              </div>
              <div class="column is-6">
                <input type="text" class="input is-small" ng-model="nombre" name="" id="">
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="table-container" ng-show="isRepShow" style="border:1px solid black;width:99%" id="exportable">
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
      <colgroup>
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
      </colgroup>
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
    <a class="pagination-next" ng-click="exportExcel()">Excel</a>
    <a class="pagination-next" ng-click="exportPDF()">PDF</a>
  </nav>
</div>
