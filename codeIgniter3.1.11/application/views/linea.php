<div class="container" ng-controller="myCtrlLinea" data-ng-init="init()">
    <div class="notification" >
      <h1 class="title is-4 has-text-centered">Gestión de Líneas</h1>
    </div>
  <nav class="level" ng-show="isListaActivo">
    <div class="level-left">
      <div class="level-item">
        <p class="subtitle is-5">
          <strong>Filtro:</strong>
        </p>
      </div>
      <div class="level-item">
        <input name="producto" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablalinea');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
      </div>
    </div>
    <div class="level-right" style="margin-right:30px">
      <p class="level-item" ng-show="permisos.alta">
        <a ng-click="openDivAgregar()">
          <span class="icon has-text-success">
            <i title="Agregar una nueva línea" class="fas fa-plus-square" ></i>
          </span>
        </a>
      </p>
      <p class="level-item" ng-show="permisos.modificacion">
        <a ng-click="updateLinea()">
          <span class="icon has-text-info">
            <i title="Editar una linea" class="fas fa-edit" ></i>
          </span>
        </a>
      </p>
      <p class="level-item" ng-show="permisos.baja">
        <a ng-click="preguntaEliminar()">
          <span class="icon has-text-danger">
            <i title="Elimnar una linea" class="far fa-trash-alt"></i>
          </span>
        </a>
      </p>
    </div>
  </nav>
  <div class="columns">
    <div class="column is-narrow" style="border: 2px solid black; width:35%;">
      <table style="width:100%">
          <tr>
              <td>
                  <table style="width:100%" border="1">
                      <col width="20%">
                      <col width="80%">
                      <tr style="background-color:CornflowerBlue; color:Ivory;">                        
                          <td align="center">CLAVE</td>
                          <td ng-click="orderByMe('NOMBRE')" align="center">NOMBRE</td>                        
                      </tr>
                  </table>
              </td>
          </tr>
          <tr>
              <td>
                  <div style="width:100%; height:500px; overflow:auto;">
                      <table id="tablalinea" class="table is-hoverable" style="width:100%">                        
                          <col width="20%">
                          <col width="80%">
                          <tr ng-repeat="x in lstLinea" ng-click="selectRowLinea(x.ID_LINEA,$index)" ng-dblclick="updateLinea()" ng-class="{selected: x.ID_LINEA === idSelLinea}">
                              <td>{{$index+1}}</td>
                              <td>{{x.NOMBRE.trim()}}</td>                            
                          </tr>
                      </table>
                  </div>
              </td>
          </tr>
      </table>
    </div>
    
    <div class="column">
      <div ng-show="!isListaActivo">
        <form>
            <div class="columns">
                <div class="column is-1">
                    <label class="label">Nombre</label>
                </div>
                <div class="column is-narrow">
                    <input type="text" class="input is-small" ng-model="nombre" required>
                </div>
            </div>
            <div class="field is-grouped">
        <div class="control" id="submit">
            <button id="add" class="button is-info" ng-click="addLinea();" ng-disabled="myForm.$invalid">{{msjBoton}}</button>
        </div>
        <div class="control" id="cancelar">
            <button id="cancel" class="button is-danger" ng-click="cancelLinea();">Cancelar</button>
        </div>
        </div>
        </form>
      </div>
    </div>
  </div>    
    <div class="{{modalBorraLinea ? 'modal is-active' : 'modal' }}">
      <div class="modal-background"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Advertencia</p>
          <button class="delete" aria-label="close" ng-click="cerrarBorraLinea()"></button>
        </header>
        <section class="modal-card-body">
          ¿Está seguro que desea eliminar la línea <b>{{lineaBorrar}}</b>?
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" ng-click="borraLinea()">Si</button>
          <button class="button" ng-click="cerrarBorraLinea()">No</button>
        </footer>
      </div>
    </div>
</div>