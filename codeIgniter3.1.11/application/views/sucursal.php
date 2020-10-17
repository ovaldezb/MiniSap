<br><br>
<div class="container" ng-controller="myCtrlSucursal" data-ng-init="init()">
  <div class="notification" >
	<h1 class="title has-text-centered">Administración de Sucursales</h1>
	</div>
  <div class="box" ng-show="!isDivSucActivo">
  	<nav class="level">
  		<div class="level-left">
  			<div class="level-item">
  				<p class="subtitle is-5">
  					<strong>Filtro:</strong>
  				</p>
  			</div>
  			<div class="level-item">
  				<input name="sucursal" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablasucursal');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
  			</div>
  		</div>
  		<div class="level-right">
  			<p class="level-item" ng-show="permisos.alta">
  				<a ng-click="openDivAgregar()">
  					<span class="icon has-text-success">
  						<i title="Agrega una nueva Sucursal" class="fas fa-plus-square" ></i>
  					</span>
  				</a>
  			</p>
  			<p class="level-item" ng-show="permisos.modificacion">
  				<a ng-click="update()">
  					<span class="icon has-text-info">
  						<i title="Edita una Sucursal" class="fas fa-edit" ></i>
  					</span>
  				</a>
  			</p>
  			<p class="level-item" ng-show="permisos.baja">
  				<a ng-click="preguntaEliminar()">
  					<span class="icon has-text-danger">
  						<i title="Elimna una Sucursal" class="far fa-trash-alt"></i>
  					</span>
  				</a>
  			</p>
  		</div>
  	</nav>
  </div>
  <div class="box" ng-show="isDivSucActivo">
    <form name="myForm">
      <div class="columns">
        <div class="column is-narrow" style="width:111px">
          <label class="label">Clave:</label>
        </div>
        <div class="column is-1">
          <input type="text" name="clave" ng-model="suc.clave" class="input is-small" required placeholder="Clave">
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-narrow" style="width:111px">
          <label class="label">Dirección:</label>
        </div>
        <div class="column is-4">
          <textarea class="textarea" ng-model="suc.direccion" name="direccion" rows="2"></textarea>
        </div>
      </div>
      <div class="columns">
        <div class="column is-narrow" style="width:111px">
          <label class="label">CP:</label>
        </div>
        <div class="column is-2">
          <input type="text" name="cp" ng-model="suc.cp" class="input is-small"  maxlength="5" placeholder="Código Postal">
        </div>
      </div>
      <div class="columns">
        <div class="column is-narrow" style="width:111px">
          <label class="label">Responsable:</label>
        </div>
        <div class="column is-2">
          <input type="text" name="responsable" ng-model="suc.responsable" class="input is-small" placeholder="Responsable" >
        </div>
      </div>
      <div class="columns">
        <div class="column is-narrow" style="width:111px">
          <label class="label">Teléfono:</label>
        </div>
        <div class="column is-2">
          <input type="text" name="telefono" ng-model="suc.telefono" class="input is-small" placeholder="Teléfono"  maxlength="10">
        </div>
      </div>
      <div class="columns">
        <div class="column is-narrow" style="width:111px">
          <label class="label">Alias:</label>
        </div>
        <div class="column is-2">
          <input type="text" name="alias" ng-model="suc.alias" class="input is-small" placeholder="Alias" required>
        </div>
      </div>
      <div class="columns">
        <div class="column is-1">
          <label class="label">Comentarios:</label>
        </div>
      </div>
      <div class="columns is-gapless is-multiline is-mobile">
        <div class="column is-5" style="margin-top:-20px">
          <textarea class="textarea" name="notas" ng-model="suc.notas"></textarea>
        </div>
      </div>
      <div class="field is-grouped">
			  <p class="control">
				<button  ng-click="submitForm();" class="button is-primary" ng-disabled="myForm.$invalid">{{btnAccion}}</button>
			  </p>
			  <p class="control">
				<button type="button" ng-click="cancelar()" class="button is-light">Cancelar</button>
			  </p>
			</div>
    </form>
  </div>
  <div class="container" style="border: 2px solid black; width:90%" ng-show="!isDivSucActivo">
    <table style="width:100%">
      <tr>
        <td>
          <table class="table is-bordered" style="width:100%">
            <col width="20%">
            <col width="40%">
            <col width="30%">
            <col width="10%">
            <tr style="background-color:CornflowerBlue; color:Ivory;">
              <td style="text-align:center">CLAVE</td>
              <td style="text-align:center" ng-click="orderByMe('DIRECCION')">DIRECCIÓN</td>
              <td style="text-align:center" ng-click="orderByMe('RESPONSABLE')">RESPONSABLE</td>
              <td style="text-align:center" ng-click="orderByMe('CP')">ALIAS</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <div style="width:100%; height:590px; overflow:auto;">
            <table id="tablasucursal" class="table is-hoverable" style="width:100%">
              <col width="20%">
              <col width="40%">
              <col width="30%">
              <col width="10%">
              <tr ng-repeat="x in lstSucursal | orderBy:myOrderBy:sortDir" ng-click="selectRowSucursal(x.CLAVE,$index,x.ID_SUCURSAL)" ng-class="{selected: x.CLAVE === idSelSuc}">
                <td align="center">{{x.CLAVE}}</td>
                <td align="center">{{x.DIRECCION}}</td>
                <td align="center">{{x.RESPONSABLE.trim()}}</td>
                <td align="center">{{x.ALIAS}}</td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="{{isAvsoBrrarActv ? 'modal is-active' : 'modal'}}" >
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Advertencia</p>
	      <button class="delete" aria-label="close" ng-click="closeAvisoBorrar();"></button>
	    </header>
	    <section class="modal-card-body">
	      Está seguro que desea eliminar la Sucursal de <b>{{descSucBorrar}}</b>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-success" ng-click="eliminar()">Si</button>
	      <button class="button" ng-click="closeAvisoBorrar();">No</button>
	    </footer>
	  </div>
	</div>
</div>
