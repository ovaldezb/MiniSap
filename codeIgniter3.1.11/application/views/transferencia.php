<div class="container"  ng-controller="CtrlTransfer" data-ng-init="init()">
  <div class="notification" >
		<h1 class="title has-text-centered">Transferencia entre sucursales</h1>
	</div>
  <nav class="level" ng-show="!isDivEmpActivo" style="width:90%">
		<div class="level-left">
			<div class="level-item">
				<p class="subtitle is-5">
        			<strong>Filtro:</strong>
      			</p>
			</div>
			<div class="level-item">
				<input name="transferencia" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablatransfer');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
			</div>
		</div>
		<div class="level-right">
	    <p class="level-item" ng-show="permisos.alta">
				<a ng-click="openDivAgregar()">
					<span class="icon has-text-success">
						<i title="Agregar nueva transferencia" class="fas fa-plus-square" ></i>
					</span>
				</a>
			</p>
  	</div>
	</nav>
  <div class="container" style="width:90%" ng-show="!isAddTransfer">
    <table class="table is-bordered" style="width:100%">
      <colgroup>
        <col width="15%"/>
        <col width="15%"/>
        <col width="30%"/>
        <col width="10%"/>
        <col width="20%"/>
        <col width="10%"/>
      </colgroup>
      <tbody>
        <tr style="background-color:CornflowerBlue; color:Ivory;">
          <td class="font12" style="text-align:center">Origen</td>
          <td class="font12" style="text-align:center">Destino</td>
          <td class="font12" style="text-align:center">Producto</td>
          <td class="font12" style="text-align:center">Cant</td>
          <td class="font12" style="text-align:center">Fecha</td>
          <td class="font12" style="text-align:center">Usuario</td>
        </tr>
      </tbody>
    </table>
    <div style="width:100%;height:500px;margin-top:-25px">
      <table class="table is-bordered is-hoverable" style="width:100%" id="tablatransfer">
        <colgroup>
          <col width="15%"/>
          <col width="15%"/>
          <col width="30%"/>
          <col width="10%"/>
          <col width="20%"/>
          <col width="10%"/>
        </colgroup>
        <tbody>
          <tr ng-repeat="x in lstTransfer">
            <td class="font12" style="text-align:center">{{x.ORIGEN}}</td>
            <td class="font12" style="text-align:center">{{x.DESTINO}}</td>
            <td class="font12">{{x.DESCRIPCION}}</td>
            <td class="font12" style="text-align:center">{{x.CANTIDAD}}</td>
            <td class="font12" style="text-align:center">{{x.FECHA_TRANSPASO}}</td>
            <td class="font12" style="text-align:center">{{x.CLAVE_USR}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="modal is-active" ng-show="isAddTransfer">
	  <div class="modal-background"></div>
	  <div class="modal-card">
	    <header class="modal-card-head">
	      <p class="modal-card-title">Transferencia entre sucursales</p>
	      <button class="delete" aria-label="close" ng-click="closeAddTransfer();"></button>
	    </header>
	    <section class="modal-card-body">
	      <table class="table" style="width:100%">
          <tbody>
            <tr>
              <td style="text-align:center">Origen</td>
              <td style="text-align:center">Destino</td>
              <td style="text-align:center">Clave</td>
              <td style="text-align:center">Descripción</td>
              <td style="text-align:center">C. Disp</td>
              <td style="text-align:center">C. Trnsf</td>
            </tr>
            <tr>
                <td><label>{{transfer.nombresucorigen}}</label> </td>
                <td> 
                  <div class="select is-small">
                    <select ng-model="transfer.idsucdestino" ng-options="x.ID_SUCURSAL as x.ALIAS for x in lstSucursal"></select>
                  </div>
                </td>
                <td><input type="text" class="input is-small" ng-model="transfer.claveproducto" ng-keyup="buscaprodbycodigo($event)"></td>
                <td><input type="text" class="input is-small" ng-model="transfer.descproducto" ng-keyup="buscprodbydesc($event)"></td>
                <td><input type="text" class="input is-small" ng-model="transfer.cantdisp" disabled></td>
                <td><input type="number" class="input is-small" ng-model="transfer.cantidad" ng-keyup="validaCantidad()"></td>
              </tr>
          </tbody>
        </table>
        <div class="table-container" ng-show="dispsearch">
					<table style="width:100%;">
						<tr>
							<td>
								<table class="table" style="width:100%" border="1" >
                  <colgroup>
                    <col width="15%">
                    <col width="40%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                  </colgroup>
									<tr style="background-color:Crimson; color:Ivory;">
										<th style="text-align:center">Código</th>
										<th style="text-align:center">Descripción</th>
										<th style="text-align:center">Unidad</th>
										<th style="text-align:center">Precio</th>
										<th style="text-align:center">Existencia</th>
									</tr>
								</table>
							</td>
							<td>
								<a ng-click="closeDivSearch()" aria-label="like">
								<span class="icon has-text-danger">
									<i class="fas fa-times-circle"></i>
								</span>
								</a>
							</td>
						</tr>
						<tr>
							<td>
								<div style="width:100%; height:200px; overflow:auto; border:2px solid red">
									<table class="table is-hoverable" style="width:100%;">
                    <colgroup>
                      <col width="15%">
                      <col width="40%">
                      <col width="15%">
                      <col width="15%">
                      <col width="15%">
                    </colgroup>
										<tr ng-repeat="x in lstProdBusqueda" ng-click="selectProdBus($index)">
											<td class="font12" style="text-align:center">{{x.CODIGO}}</td>
											<td class="font12">{{x.DESCRIPCION}}</td>
											<td class="font12" style="text-align:center">{{x.UNIDAD_MEDIDA}}</td>
											<td class="font12" style="text-align:right">{{x.PREC_LISTA_DISP}}</td>
											<td class="font12" style="text-align:right">{{x.STOCK}}</td>
										</tr>
									</table>
								</div>
							</td>
						</tr>
					</table>
					<hr class="hr">
				</div>
	    </section>
	    <footer class="modal-card-foot">
	      <button class="button is-success" ng-click="guardarTransfer()">Enviar</button>
	      <button class="button" ng-click="closeAddTransfer();">Cerrar</button>
	    </footer>
	  </div>
	</div>
</div>