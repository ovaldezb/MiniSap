
<br><br>
<div class="container" ng-controller="myCtrlUsuarios" data-ng-init="init()">
  <div class="notification">
		<h1 class="title is-2 has-text-centered">Gestión de Usuarios</h1>
	</div>
  <div class="box" id="barranavegacion">
    <nav class="level">
			<div class="level-left">
				<p class="level-item" ng-show="permisos.alta">
          <a ng-click="agregarUsuario();"><span class="icon has-text-success"><i class="far fa-file" title="Agrega Nuevo Usuario"></i></span></a></p>
				<p class="level-item" ng-show="permisos.modificacion">
          <a ng-click="visualizaUsr()"><span class="icon has-text-info"><i class="fas fa-folder-open" title="Visualizar el Usuario seleccionado"></i></span></a></p>
        <p class="level-item" ng-show="permisos.baja">
          <a ng-click="preguntaElimUser()"><span class="icon has-text-danger"><i class="far fa-trash-alt" title="Elimina el usuarios selecionado"></i></span></a></p>
      </div>
		</nav>
  </div>
  <div id="maindisplay" class="box">
    <div class="columns">
      <div class="column modulos is-two-fifths" title="Aquí se listan los módulos disponibles">
        <p><label><input type="radio" name="modulos" ng-click="doFilter1('all')" value="T" ng-checked="allModules">&nbsp;Todos<label></p>
        <p ng-repeat="x in lstModlUser">
          <label><input type="radio" name="modulos" ng-click="doFilter1(x.NOMBRE.trim())" value="mod{{x.ID_MODULO}}">&nbsp;{{x.NOMBRE.trim()}}<label>
        </p>
      </div>
      <div class="column">
        <div class="columns">
          <div class="column usuarios">
            <table style="width:100%" border="1">
              <tr>
                <td>
                  <table style="width:100%" class="table">
                    <col width="20%">
                    <col width="80%">
                    <tr>
                      <td>Clave</td>
                      <td>Nombre</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="width:100%; height:93px; overflow:auto;">
                    <table style="width:100%" class="table is-bordered is-narrow is-hoverable">
                      <col width="20%">
                      <col width="80%">
                      <tr ng-repeat="x in lstUsuarios" ng-click="selectUsr(x.ID_USUARIO,$index)" ng-class="{selected : x.ID_USUARIO == idUsuario}">
                        <td>{{x.CLAVE_USR.trim()}}</td>
                        <td>{{x.NOMBRE.trim()}}</td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <br>
        <div class="columns">
          <div class="column permisos">
            <table style="width:100%" border="1">
              <tr>
                <td>
                  <table style="width:100%" >
                    <col width="48%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="2%">
                    <tr>
                      <td align="center">Proceso</td>
                      <td align="center">P</td>
                      <td align="center">A</td>
                      <td align="center">B</td>
                      <td align="center">M</td>
                      <td align="center">C</td>
                      <td align="center"></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <div style="width:100%; height:125px; overflow:auto;">
                    <table style="width:100%;" class="table is-bordered" id="permisos">
                      <col width="50%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <tr ng-repeat="x in lstProcesos" ng-show="nomModule==x.MODULO.trim() || allModules">
                        <td>{{x.NOMBRE.trim()}}</td>
                        <td align="center" style="vertical-align:middle;" ng-click="cambiaPermiso($index)"><img src="<?php echo base_url(); ?>/img/check_mark.png" style="display:{{x.P ? 'block':'none'}}"></td>
                        <td align="center" style="vertical-align:middle; background-color:{{x.A && x.P ? 'green':'red'}};" ng-click="alertRowCell($event,$index)"></td>
                        <td align="center" style="vertical-align:middle; background-color:{{x.B && x.P ? 'green':'red'}};" ng-click="alertRowCell($event,$index)"></td>
                        <td align="center" style="vertical-align:middle; background-color:{{x.M && x.P ? 'green':'red'}};" ng-click="alertRowCell($event,$index)"></td>
                        <td align="center" style="vertical-align:middle; background-color:{{x.C && x.P ? 'green':'red'}};" ng-click="alertRowCell($event,$index)"></td>
                      </tr>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <table style="width:100%">
      <tr>
        <td align="right"><button class="button is-info" ng-click="actualizaUsrProc()">Actualizar</button></td>
      </tr>
    </table>
  </div>
  <div class="modal {{modlAddUser?'is-active':''}}">
    <div class="modal-background"></div>
    <div class="modal-card" style="width:800px">
      <header class="modal-card-head">
        <p class="modal-card-title">Datos del Usuario</p>
        <button class="delete" aria-label="close" ng-click="cerrarAddUser()"></button>
      </header>
      <section class="modal-card-body">
        <br>
        <div class="columns">
          <div class="column datos">
            <table style="width:100%">
              <col width="35%">
              <col width="30%">
              <col width="35%">
              <tr>
                <td><label style="font-size:12px">Nombre</label></td>
                <td colspan="2"><input type="text" class="input is-small" ng-model="nombre" required></td>
              </tr>
              <tr>
                <td><label style="font-size:12px">Clave de Usuario</label></td>
                <td ><input type="text" class="input is-small" ng-model="username" required></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><label style="font-size:12px">Contraseña</label></td>
                <td><input type="password" ng-model="password" class="input is-small" required></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td><label style="font-size:12px">Confirmar Contraseña</label></td>
                <td><input type="password" ng-model="cpassword" class="input is-small" required></td>
                <td>&nbsp;</td>
              </tr>
            </table>
          </div>
          <div class="column modperm">
            <table style="width:80%" class="table is-bordered is-narrow">
              <col width="70%">
              <col width="30%">
              <tr ng-repeat="x in lstModulos">
                <td>{{x.NOMBRE.trim()}}</td>
                <td ng-click="selectRowModl($index)" style="background-color:{{x.PERMITIDO ? 'green':'red'}};" align="center"><img src="<?php echo base_url(); ?>/img/check_mark.png" style="display:{{x.PERMITIDO ? 'block':'none'}}"></td>
              </tr>
            </table>
          </div>
        </div>
        <br>
        <div class="columns">
          <div class="column empperm ">
            <table style="width:100%">
              <tr>
                <td align="center">
                  <table style="width:80%">
                    <tr>
                      <td>
                        <table style="width:100%" border="1">
                          <col width="75%">
                          <col width="25%">
                          <tr>
                            <td>Nombre de la Empresa</td>
                            <td align="center">Permitir</td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div style="width:100%; height:100px; overflow:auto;">
                        <table style="width:100%" class="table is-bordered is-narrow">
                          <col width="85%">
                          <col width="15%">
                          <tr ng-repeat="x in lstEmprPerm">
                            <td>{{x.NOMBRE}}</td>
                            <td ng-click="selectRowEmpr($index)" style="background-color:{{x.PERMITIDO ? 'green':'red'}};" align="center"><img src="<?php echo base_url(); ?>/img/check_mark.png" style="display:{{x.PERMITIDO ? 'block':'none'}}"></td>
                          </tr>
                        </table>
                      </div>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" ng-click="enviaUsuario()">{{btnAccion}}</button>
        <button class="button" ng-click="cerrarAddUser()">Cancelar</button>
      </footer>
    </div>
  </div>
  <div class="modal {{modlDelUser?'is-active':''}}">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Eliniar Usuario</p>
        <button class="delete" aria-label="close" ng-click="cerrarElimUser()"></button>
      </header>
      <section class="modal-card-body">
        ¿Está seguro que desea eliminar al usuario <b>{{userElim}}?</b>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" ng-click="eliminarUsuario()">Si</button>
        <button class="button" ng-click="cerrarElimUser()">No</button>
      </footer>
    </div>
  </div>
</div>
