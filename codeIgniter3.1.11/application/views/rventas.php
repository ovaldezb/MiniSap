
<div class="container" ng-controller="repControlVentas" data-ng-init="init()">
  <div class="notification" >
	   <h1 class="title has-text-centered is-4">Reporte Ventas</h1>
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
        <div class="column">
          <div class="columns">
            <div class="column is-3">
              <div class="columns">
                <div class="column">
                <label class="label">Tipo Reporte</label>
                </div>
              </div>
              <div class="columns">
                <div class="column">
                  <div class="select is-small">
                    <select ng-model="filRep" ng-options="x.value as x.label for x in filtroReporte" ng-change="selTipoRepo()"></select>
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
    </form>
  </div>
  <nav class="is-centered" ng-show="isRepShow" style="width:95%">
    <button class="button is-info is-small" ng-click="cerrarReporte()">Cerrar</button>
    <button class="button is-info is-small" ng-click="exportExcel()">Excel</button>
    <button class="button is-info is-small" ng-click="exportPDF()">PDF</button>
  </nav>
  <div class="table-container" ng-show="isRepShow" style="border:1px solid black;width:99%" id="exportable">
    <table style="width:100%" id="rvempresa">
      <tr class="spaceUnder">
        <td style="text-align:center"><label class="label">{{nombreEmpresa}}</label></td>
      </tr>
      <tr class="spaceUnder">
        <td style="text-align:center"><label class="label">{{direccionEmpresa}}</label></td>
      </tr>
      <tr class="spaceUnder">
        <td style="text-align:center"><label class="label">{{rfcEmpresa}}</label></td>
      </tr>
      <tr>
        <td style="text-align:center"><label class="label">Análisis de Ventas del {{fecIniRep}} al {{FecFinRep}}</label></td>
      </tr>
      <tr>
        <td style="text-align:right"><label>{{fechaImpresion}}</label></td>
      </tr>
    </table>
    <table class="table is-bordered" style="width:100%;margin-top:5px;margin-bottom:0px" id="rvheader">
      <colgroup>
        <col width="21%">
        <col width="12%">
        <col width="7%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="8%">
        <col width="8%">
      </colgroup>
      <tr style="background-color:CornflowerBlue; color:Ivory;">
        <td class="font12" rowspan="2" style="vertical-align:middle" ng-click="orderByMe('DESCRIPCION')"><a href="">Descripción</a></td>
        <td class="font12" rowspan="2" style="text-align:center; vertical-align:middle" ng-click="orderByMe('CODIGO')"><a href="">Código</a></td>
        <td class="font12" rowspan="2" style="text-align:center; vertical-align:middle">Cantidad</td>
        <td class="font12" colspan="2" style="text-align:center;">Venta</td>
        <td class="font12" rowspan="2" style="text-align:center;vertical-align:middle">Precio Promedio</td>
        <td class="font12" rowspan="2" style="text-align:center;vertical-align:middle">Ventas (%)</td>
        <td class="font12" rowspan="2" style="text-align:center;vertical-align:middle">Costo</td>
        <td class="font12" rowspan="2" style="text-align:center;vertical-align:middle">Utilidad</td>
        <td class="font12" rowspan="2" style="text-align:center;vertical-align:middle">Margen (%)</td>
      </tr>
      <tr style="background-color:CornflowerBlue; color:Ivory;">
        <td class="font12" style="text-align:center">Bruta</td>
        <td class="font12" style="text-align:center">Neta</td>
      </tr>
    </table>
    <div style="height:500px;overflow:auto">
    <table class="table is-bordered is-hoverable"style="width:100%" id="rvbody">
      <colgroup>
        <col width="22%">
        <col width="10%">
        <col width="7%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="8%">
        <col width="8%">
      </colgroup>
      <tr ng-repeat="x in lstRepmalmcn | orderBy:myOrder:sortDir">
        <td class="font12">{{x.DESCRIPCION}}</td>
        <td class="font12" style="text-align:center">{{x.CODIGO}}</td>
        <td class="font12" style="text-align:center">{{x.CANTIDAD}}</td>
        <td class="font12" style="text-align:right">{{x.BRUTA | currency}}</td>
        <td class="font12" style="text-align:right">{{x.NETA | currency}}</td>
        <td class="font12" style="text-align:right">{{x.PRECIO_PROM | currency}}</td>
        <td class="font12" style="text-align:center">{{x.PORCENTAJE | number:2}}</td>
        <td class="font12" style="text-align:right">{{x.COSTO | currency}}</td>
        <td class="font12" style="text-align:right; color:{{x.UTILIDAD<0 ? 'red':'black'}}">{{x.UTILIDAD | currency}}</td>
        <td class="font12" style="text-align:center">{{x.UTILIDAD / x.NETA * 100 | number:2}}</td>
      </tr>
    </table>
    </div>
    <table class="table is-bordered is-hoverable"style="width:100%">
      <colgroup>
        <col width="21%">
        <col width="12%">
        <col width="7%">
        <col width="9%">
        <col width="9%">
        <col width="9%">
        <col width="8%">
        <col width="9%">
        <col width="8%">
        <col width="8%">
      </colgroup>
      <tr>
        <td class="font12">{{total.DESCRIPCION}}</td>
        <td class="font12"></td>
        <td class="font12"></td>
        <td class="font12" style="text-align:right">{{total.BRUTA | currency}}</td>
        <td class="font12" style="text-align:right">{{total.NETA | currency}}</td>
        <td class="font12" style="text-align:right" >{{total.PRECIO_PROM | currency}}</td>
        <td class="font12" style="text-align:center">{{total.PORCENTAJE | number:2}}</td>
        <td class="font12" style="text-align:right">{{total.COSTO | currency}}</td>
        <td class="font12" style="text-align:right">{{total.UTILIDAD | currency}}</td>
        <td class="font12"></td>
      </tr>
    </table>
  </div>
</div>
