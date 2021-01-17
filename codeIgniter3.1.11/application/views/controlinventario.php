<html>
<head>
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
	<script src="../core/js/utilerias.js"></script>
  <script src="../core/js/foopicker.js"></script>
  <script src="../core/js/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="../core/css/utils.css">
  <link rel="stylesheet" href="../core/css/foopicker.css">
  <title>RTS</title>
</head>
<body >
<div ng-app="myApp" class="container" ng-controller="myCtrlControlinvent" data-ng-init="init()">
    <div class="notification">
		<h1 class="title is-4 has-text-centered">Control de Inventario</h1>
    </div>
    <div class="box">
        <div class="columns">
            <div class="column document">
                <div class="columns">
                    <div class="column is-narrow">
                        <label for="">Periodo</label>
                    </div>
                    <div class="column is-1">
                        <input type="text" ng-model="fechaIni" ng-blur="fecIniChange()" class="input is-small" id="fechaInicio" required>
                    </div>
                    <div class="column is-narrow"><label class="label">-</label></div>
                    <div class="column is-1">
                        <input type="text" ng-model="fechaFin" ng-blur="fecFinChange()" class="input is-small" id="fechaFin" required>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-narrow">
                        <label for="">Tipo</label>
                    </div>
                    <div class="column is-narrow">
                        <select ng-model="ctrlInv.tipoES" >
                            <option value="t">Todos</option>
                            <option ng-repeat=" x in entsal" value="{{x.value}}">{{x.label}}</option>

                        </select>	
                    </div>
                    <div class="column is-narrow">
                        <select ng-model="ctrlInv.tipoMov" >
                            <option value="tod">Todos</option>
                            <option ng-repeat="x in tipoMov" value="{{x.value}}">{{x.label}}</option>
                        </select>
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
                        <button class="button is-success" ng-click="creaReporte()">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-container" ng-show="isShowReporte">
    <nav class="level">
        <div class="level-right">
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
				<a ng-click="preguntaElimnaCliente()"><span class="icon has-text-danger">
                    <i class="far fa-trash-alt" title="Elimna Movimiento"></i></span>
                </a>
            </p>
		</div>
    </nav>
        <table class="table" style="width:100%">
            <thead>
                <tr>
                    <td>Fecha</td>
                    <td>No Doc</td>
                    <td>Codigo</td>
                    <td>Caja</td>
                    <td>Mov</td>
                    <td>Entrada</td>
                    <td>Salida</td>
                    <td>$ Unit</td>
                    <td>Importe</td>
                </tr>
            </thead>
        </table>
        <div>
            <table class="table" style="width:100%">
                <tbody>
                    <tr ng-repeat="inv in lstInvent">
                        <td>{{inv.FECHA}}</td>
                        <td>{{inv.DOCUMENTO}}</td>
                        <td>{{inv.CODIGO}}</td>
                        <td>{{inv.CAJA}}</td>
                        <td>{{inv.MOV.trim()}}</td>
                        <td>{{inv.IN}}</td>
                        <td>{{inv.OUT}}</td>
                        <td>{{inv.PREC_UNIT | currency}}</td>
                        <td>{{inv.IMPORTE | currency}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal is-active" ng-show="isAdd">
        <form name="myForm">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Registro de Movimientos</p>
                <button class="delete" aria-label="close" ng-click="closeAddMov();"></button>
            </header>
            <section class="modal-card-body">
                <div class="contenedor-inv">
                <div class="columns">
                    <div class="column is-4">
                        <label>Tipo de Movimiento</label>
                    </div>
                    <div class="column is-3">
                        <select ng-model="tipoES" ng-options="x.value as x.label for x in entsal" ng-change="cambiarTipo()"></select>	
                    </div>
                    <div class="column is-2">
                        <select ng-model='regmov.tipoMov' ng-options="x.value as x.label for x in tipoMMos" ></select>
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-4">
                        <label>Cliente o Proveedor</label>
                    </div>
                    <div class="column is-3">
                        <select ng-model='cliPro' ng-options="x.value as x.label for x in cliprorm" ></select>
                    </div>
                    <div class="column is-2">
                        <label>Clave</label>
                    </div>
                    <div class="column is-3">
                        <input type="text" ng-model="clave" class="input is-small">
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-4">
                        <label for="">No Documento</label>
                    </div>
                    <div class="column is-3">
                        <input type="text" ng-model="regmov.documento" class="input is-small">
                    </div>
                    <div class="column is-2">Fecha</div>
                    <div class="column is-3">
                        <input type="text" ng-model="fechaMov" class="input is-small" id="fechaMovimiento" ng-blur="validateFecha()">
                    </div>
                </div>
                <div class="columns">
                    <div class="column is-4">
                        <label for="">Tipo de moneda</label>
                    </div>
                    <div class="column is-3">
                        <select name="" id="" ng-model="regmov.idmoneda" ng-options="x.value as x.label for x in lstMoneda">  </select>
                    </div>
                    <div class="column is-1" ng-show="false">
                        <label for="">Desc.</label>
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
                        <label for="">Producto</label>
                        <input type="text" ng-model="regmov.codigo" class="input is-small">
                    </div>
                    <div class="column">
                        <label for="">Cantidad</label>
                        <input type="number" ng-model="cantidad" class="input is-small">
                    </div>
                    <div class="column">
                        <label for="">Medida</label>
                        <input type="text" ng-model="medida" class="input is-small">
                    </div>
                    <div class="column">
                        <label for="">$/Unitario</label>
                        <input type="text" ng-model="regmov.preciounit" class="input is-small">
                    </div>
                </div>
                </div>
            </section>
            <footer class="modal-card-foot">
	            <button class="button" ng-click="enviaMovimiento();" ng-disabled="myForm.$invalid">Enviar</button>
		        <button class="button" ng-click="closeAddMov()">Cerrar</button>
	        </footer>
        </div>
        </form>
    </div>
</div>
    <script src="../core/js/controlinventario.js"></script>
</body>
</html>
