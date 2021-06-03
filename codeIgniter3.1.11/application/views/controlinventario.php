<div class="container" ng-controller="myCtrlControlinvent" data-ng-init="init()">
  <div class="notification">
    <h1 class="title is-4 has-text-centered">Control de Inventario</h1>
  </div>
  <div class="box">
    <div class="columns">
      <div class="column inventario">
        <form name="myForm">
          <div class="columns">
            <div class="column is-narrow">
                <label class="label">Periodo</label>
            </div>
            <div class="column is-narrow" style="width:110px">
                <input type="text" ng-model="fechaIni" ng-blur="fecIniChange()" class="input is-small" id="fechaInicio" required>
            </div>
            <div class="column is-narrow"><label class="label">-</label></div>
            <div class="column is-narrow" style="width:110px">
                <input type="text" ng-model="fechaFin" ng-blur="fecFinChange()" class="input is-small" id="fechaFin" required>
            </div>
          </div>
          <div class="columns">
            <div class="column is-narrow">
              <label for="">Tipo</label>
            </div>
            <div class="column is-narrow">
              <div class="select is-small">
                <select ng-model="ctrlInv.tipoES" >
                  <option value="t">Todos</option>
                  <option ng-repeat=" x in entsal" value="{{x.value}}">{{x.label}}</option>
                </select>	
              </div>
            </div>
            <div class="column is-narrow">
              <div class="select is-small">
                <select ng-model="ctrlInv.tipoMov" >
                  <option value="tod">Todos</option>
                  <option ng-repeat="x in tipoMov" value="{{x.value}}">{{x.label}}</option>
                </select>
              </div>
            </div>
          </div>
          <div class="columns">
            <div class="column is-narrow">
              <label for="">Caja</label>
            </div>
            <div class="column is-1">
              <input type="text" ng-model="ctrlInv.caja" class="input is-small" />
            </div>
            <div class="column is-narrow">Producto</div>
            <div class="column is-2">
              <input type="text" ng-model="ctrlInv.codigoProducto" class="input is-small"/>
            </div>
          </div>
          <div class="columns">
            <div class="column">
                <button class="button is-success" ng-click="creaReporte()" ng-disabled="myForm.$invalid">Aceptar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
    
  <div class="table-container box" ng-show="isShowReporte">
    <nav class="level">
      <div class="level-left">
        <div class="level-item">
          <p class="subtitle is-5">
            <strong>Filtro:</strong>
          </p>
        </div>
        <div class="level-item">
          <input name="producto" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablainventario');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
        </div>
      </div>
      <div class="level-right">
        <p class="level-item" ng-show="permisos.consulta">
          <a ng-click="descargaPDF();"><span class="icon has-text-success">
            <i class="fas fa-file-pdf" title="Descarga en PDF"></i></span>
          </a>
        </p>
        <p class="level-item" ng-show="permisos.consulta">
          <a ng-click="descargaExcel();"><span class="icon has-text-success">
            <i class="fas fa-file-excel" title="Descarga en Excel"></i></span>
          </a>
        </p>
        <p class="level-item" ng-show="permisos.alta">
          <a ng-click="agregaMovimiento();"><span class="icon has-text-success">
            <i class="fas fa-plus-square" title="Agrega Movimiento"></i></span>
          </a>
        </p>
        <p class="level-item" ng-show="permisos.modificacion">
          <a ng-click="editaCliente()"><span class="icon has-text-info">
                  <i class="fas fa-edit" title="Edita Movimiento"></i></span>
              </a>
        </p>
        <p class="level-item" ng-show="permisos.baja">
          <a ng-click="elliminaMovimiento()"><span class="icon has-text-danger">
                  <i class="far fa-trash-alt" title="Elimna Movimiento"></i></span>
              </a>
        </p>
      </div>
    </nav>
    <div id="exportable">
      <table class="table is-bordered" style="width:100%;" id="ciheader">
        <colgroup>
          <col width='11%'/>
          <col width='11%'/>
          <col width='15%'/>
          <col width='7%'/>
          <col width='11%'/>
          <col width='11%'/>
          <col width='11%'/>
          <col width='11%'/>
          <col width='12%'/>
        </colgroup>
        <tbody>
          <tr style="background-color:CornflowerBlue; color:Ivory;">
            <td class="font12" style="text-align:center">Fecha</td>
            <td class="font12" style="text-align:center">No Doc</td>
            <td class="font12" style="text-align:center">Codigo</td>
            <td class="font12" style="text-align:center">Caja</td>
            <td class="font12" style="text-align:center">Mov</td>
            <td class="font12" style="text-align:center">Entrada</td>
            <td class="font12" style="text-align:center">Salida</td>
            <td class="font12" style="text-align:center">$ Unit</td>
            <td class="font12" style="text-align:center">Importe</td>
          </tr>
        </tbody>
      </table>
      <div style="width:100%; height:250px; overflow:auto;margin-top:-25px ">
          <table class="table is-bordered is-hoverable" style="width:100%" id="tablainventario">
            <colgroup>
              <col width='11%'/>
              <col width='11%'/>
              <col width='15%'/>
              <col width='7%'/>
              <col width='11%'/>
              <col width='11%'/>
              <col width='11%'/>
              <col width='11%'/>
              <col width='12%'/>
            </colgroup>
            <tbody>
              <tr ng-repeat="inv in lstInvent" ng-click="selectRowInv(inv.ID)" ng-class="{selected: inv.ID === idSelInv}">
                <td class="font12" style="text-align:left">{{inv.FECHA}}</td>
                <td class="font12" style="text-align:center">{{inv.DOCUMENTO}}</td>
                <td class="font12" style="text-align:left">{{inv.CODIGO}}</td>
                <td class="font12" style="text-align:center">{{inv.CAJA}}</td>
                <td class="font12" style="text-align:center">{{inv.MOV.trim()}}</td>
                <td class="font12" style="text-align:center">{{inv.IN}}</td>
                <td class="font12" style="text-align:center">{{inv.OUT}}</td>
                <td class="font12" style="text-align:right">{{inv.PREC_UNIT | currency}}</td>
                <td class="font12" style="text-align:right">{{inv.IMPORTE | currency}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="modal is-active" ng-show="isAdd">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Registro de Movimientos</p>
            <button class="delete" id="upcerrar" aria-label="close" ng-click="closeAddMov($event);"></button>
        </header>
        <section class="modal-card-body">
            <form name="regMov">
            <div class="contenedor-inv">
            <div class="columns">
                <div class="column is-4">
                    <label class="label">Tipo de Movimiento</label>
                </div>
                <div class="column is-3">
                  <div class="select is-small">
                    <select ng-model="regmov.tipoES" ng-options="x.value as x.label for x in entsal" ng-change="cambiarTipo()"></select>	
                  </div>
                </div>
                <div class="column is-4">
                  <div class="select is-small">
                    <select ng-model='regmov.tipoMov' ng-options="x.value as x.label for x in tipoMMos" ></select>
                  </div>    
                </div>
            </div>
            <div class="columns">
              <div class="column is-4">
                  <label class="label">Cliente o Proveedor</label>
              </div>
              <div class="column is-3">
                <div class="select is-small">
                  <select ng-model='cliPro' ng-options="x.value as x.label for x in cliprorm" ng-change="cambioCliPro()" ></select>
                </div>
              </div>
              <div class="column is-2">
                <label class="label">Clave</label>
              </div>
              <div class="column is-4">
                <input type="text" ng-model="clave" class="input is-small" ng-keyup="buscaClave($event)" ng-disabled="claveDisabled" required>
              </div>   
            </div>
            <div class="columns" ng-show="isShowCli" style="margin-top:-35px">
              <div class="column">
                <table style="width:100%">
                  <colgroup>
                    <col width="95%">
                    <col width="5%">
                  </colgroup>
                  <tr>
                    <td>
                      <table style="width:100%">
                        <colgroup>
                          <col width="30%">
                          <col width="70%">
                        </colgroup>
                        <thead>
                          <tr class="tbl-header-seek">
                            <td style="text-align:center">Clave</td>
                            <td style="text-align:center">Cliente</td>
                          </tr>
                        </thead>
                      </table>
                      <div style="width:100%; height:100px; overflow:auto;border:2px solid red">
                        <table class="table is-bordered" style="width:100%">
                          <colgroup>
                            <col width="30%">
                            <col width="70%">
                          </colgroup>
                          <tbody>
                            <tr ng-repeat="x in lstCliente" ng-click="selectClte($index)">
                              <td style="font12">{{x.CLAVE}}</td>
                              <td style="font12">{{x.NOMBRE}}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </td>
                    <td>
                      <i class="fas fa-times-circle" ng-click="cierraBusqueda()"></i>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="columns" ng-show="isShowPro" style="margin-top:-35px">
              <div class="column">
                <table style="width:100%">
                  <colgroup>
                    <col width="95%">
                    <col width="5%">
                  </colgroup>
                  <tr>
                    <td>
                      <table style="width:100%">
                        <colgroup>
                          <col width="30%">
                          <col width="70%">
                        </colgroup>
                        <thead>
                          <tr class="tbl-header-seek">
                            <td style="text-align:center">Clave</td>
                            <td style="text-align:center">Proveedor</td>
                          </tr>
                        </thead>
                      </table>
                      <div style="width:100%; height:100px; overflow:auto;border:2px solid red">
                        <table class="table is-bordered" style="width:100%">
                          <colgroup>
                            <col width="30%">
                            <col width="70%">
                          </colgroup>
                          <tbody>
                            <tr ng-repeat="x in lstaprovee" ng-click="selectProv($index)">
                              <td style="font12">{{x.CLAVE}}</td>
                              <td style="font12">{{x.NOMBRE}}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </td>
                    <td>
                      <i class="fas fa-times-circle" ng-click="cierraBusqueda()"></i>
                    </td>
                  </tr>
                </table>
              </div>
            </div>
            <div class="columns">
                <div class="column is-4">
                    <label class="label">No Documento</label>
                </div>
                <div class="column is-3">
                    <input type="text" ng-model="regmov.documento" class="input is-small" required>
                </div>
                <div class="column is-2"><label class="label">Fecha</label></div>
                <div class="column is-3">
                    <input type="text" ng-model="fechaMov" class="input is-small" id="fechaMovimiento" ng-blur="validateFecha()" required>
                </div>
            </div>
            <div class="columns">
                <div class="column is-4">
                    <label class="label">Tipo de moneda</label>
                </div>
                <div class="column is-3">
                  <div class="select is-small">
                    <select name="" id="" ng-model="regmov.idmoneda" ng-options="x.value as x.label for x in lstMoneda"></select>
                  </div>
                </div>
                <div class="column is-1" ng-show="false">
                    <label class="label">Desc.</label>
                </div>
                <div class="column is-2" ng-show="false">
                    <input type="text" class="input is-small">
                </div>
                <div class="column is-1" ng-show="false">Caja</div>
                <div class="column is-2" ng-show="false">
                    <input type="text" class="input is-small">
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <label class="label">Producto</label>
                    <input type="text" ng-model="regmov.codigo" class="input is-small" ng-keyup="buscaProducto($event)"  title="{{prddesc}}" required>
                </div>
                <div class="column">
                    <label class="label">Cantidad</label>
                    <input type="number" ng-model="cantidad" class="input is-small" required>
                </div>
                <div class="column">
                    <label class="label">Medida</label>
                    <input type="text" ng-model="medida" class="input is-small" disabled>
                </div>
                <div class="column">
                    <label class="label">$/Unitario</label>
                    <input type="text" ng-model="regmov.preciounit" class="input is-small" required>
                </div>
            </div>
            <div style="width:100%; height:100px; overflow:auto;border:2px solid black;margin-top:-15px" ng-show="lstProducto.length > 0 ">
              <table class="table is-hoverable">
                <colgroup>
                  <col width="30%"/>
                  <col width="70%"/>
                </colgroup>
                <tbody>
                    <tr ng-repeat="prd in lstProducto" ng-click="selectRowPrd($index,prd.ID_PRODUCTO)" >
                      <td>{{prd.CODIGO}}</td>
                      <td>{{prd.DESCRIPCION}}</td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" ng-click="enviaMovimiento()" ng-disabled="regMov.$invalid">Enviar</button>
      <button class="button is-danger" ng-click="closeAddMov()">Cerrar</button>
      </footer>
    </div>  
  </div>
</div>
