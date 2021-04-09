<div class="container" ng-controller="myCtrlCortecaja" data-ng-init="init()">
  <div class="container">
    <div class="notification" align="center">
      <h1 class="title is-1">Cortes de Caja</h1>
    </div>
  </div>

  <div class="container">
    <div class="columns">
      <div class="column box">
        <div class="columns">
          <div class="column is-1">Mes:</div>
          <div class="column is-narrow" style="width:170px">
            <select ng-model="mes" ng-options="x.valor as x.mes for x in meses" ng-change="seleccionaMes()"></select>
          </div>
          <div class="column is-1">Caja:</div>
          <div class="column is-1">01</div>
        </div>
        <div class="columns">
          <div class="column">
            <table class="table is-bordered" style="width:100%">
              <thead>
                <tr>
                  <th style="text-align:center">Dia</th>
                  <th style="text-align:center"># Operaciones</th>
                  <th style="text-align:center">Canceladas</th>
                  <th style="text-align:center">Importe</th>
                </tr>
              </thead>
            </table>
            <div>
              <table class="table" style="width:100%">
                <tbody>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="column box">
        <div class="columns">
          <div class="column">
            Cajero
          </div>
        </div>
      </div>
    </div>
  </div>

</div>