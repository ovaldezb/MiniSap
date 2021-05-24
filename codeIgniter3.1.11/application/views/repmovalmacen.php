
<div class="container" ng-controller="repControlMovAlm" data-ng-init="init()">
  <div class="notification" >
	   <h1 class="title has-text-centered">Reporte de Movimiento de Almacen</h1>
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
                      <span>Tipo de Reporte</span>
                      <span class="icon is-small">
                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                      </span>
                    </button>
                  </div>
                  <div class="dropdown-menu" id="dropdown-menu3" role="menu" style="margin-top:-3px">
                    <div class="dropdown-content" style="width:170px">
                      <a class="dropdown-item" ng-click="selTipoRepo(1)">Todos</a>                      
                      <a class="dropdown-item" ng-click="selTipoRepo(2)">Por Codigo</a>
                      <a class="dropdown-item" ng-click="selTipoRepo(3)">Por nombre</a>
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
    <a class="pagination-next" ng-click="exportExcel()">Excel</a>
    <a class="pagination-next" ng-click="exportCSV()">csv</a>
    <a class="pagination-next" ng-click="exportPDF()">PDF</a>
  </nav>
</div>
