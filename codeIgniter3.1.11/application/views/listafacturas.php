<html>
<head>
  <meta http-equiv="Expires" content="0">
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <!--script src="https://unpkg.com/axios/dist/axios.min.js"></script-->
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="../core/js/utilerias.js"></script>
	<script src="../core/js/jquery-3.4.1.slim.js"></script>
  <script src="../core/js/foopicker.js"></script>
  <link rel="stylesheet" href="../core/css/utils.css">
  <link rel="stylesheet" href="../core/css/foopicker.css">
  <title>RTS</title>
  </head>
<body>
    <div class="container" ng-app="myApp" ng-controller="myCtrlLisfact" data-ng-init="init()">
        <div class="notification" >
            <h1 class="title is-2 has-text-centered">Gestión de Facturas</h1>
        </div>
        <nav class="level">
            <div class="level-left">
                <div class="level-item">
                    <p class="subtitle is-5">
                        <strong>Filtro:</strong>
                    </p>
                </div>
                <div class="level-item">
                    <input name="factura" class="input is-small" type="input" onKeyUp="doFilter(this.value,'tablalistafacturas');" title="Ingrese cualquier dato que desee encontrar, Ej. nombre, código, precio ">
                </div>
            </div>
            <div class="level-right">
                <p class="level-item">
                    <a ng-click="openDivAgregar()">
                        <span class="icon has-text-success">
                            <i title="Agregar una nueva empresa" class="fas fa-plus-square" ></i>
                        </span>
                    </a>
                </p>
                <p class="level-item">
                    <a ng-click="update()">
                        <span class="icon has-text-info">
                            <i title="Editar una empresa" class="fas fa-edit" ></i>
                        </span>
                    </a>
                </p>
                <p class="level-item">
                    <a ng-click="preguntaEliminar()">
                        <span class="icon has-text-danger">
                            <i title="Elimnar una empresa" class="far fa-trash-alt"></i>
                        </span>
                    </a>
                </p>
            </div>
        </nav>
        <form name="myForm">
            <div class="columns">
                <div class="column is-narrow">
                    <label class="label">Periodo:</label>
                </div>
                <div class="column is-1">
                    <input type="text" ng-model="fecIni" ng-blur="fecIniChange()" class="input is-small" id="fechaInicio" required>
                </div>
                <div class="column is-narrow"><label class="label">-</label></div>
                <div class="column is-1">
                    <input type="text" ng-model="fecFin" ng-blur="fecFinChange()" class="input is-small" id="fechaFin" required>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <button class="button is-success" ng-click="creaReporte()" ng-disabled="myForm.$invalid">Enviar</button>
                </div>
            </div>
        </form>
        <div class="box" style="border: 2px solid black" ng-show="muestraLista">
            <table style="width:100%">
                <tr>
                    <td>
                        <table class="table is-bordered" style="width:100%">
                            <tr style="background-color:CornflowerBlue; color:Ivory;">
                                <td ng-click="orderByMe('FOLIO')" style="width:110; text-align:center">FOLIO</td>
                                <td ng-click="orderByMe('CLIENTE')" style="width:235; text-align:center">CLIENTE</td>
                                <td ng-click="orderByMe('RFC')" style="width:180; text-align:center">RFC</td>
                                <td ng-click="orderByMe('FECHA_TIMBRADO')" style="width:250; text-align:center">FECHA FACTURA</td>
                                <td style="width:190; text-align:center">Descargar</td>
                                <td style="width:190; text-align:center">Correo</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="width:100%; height:590px; overflow:auto;">
                            <table id="tablalistafacturas" class="table is-hoverable is-bordered" style="width:100%">
                                <tr ng-repeat="x in lstFacturas " >
                                    <td style="width:110;">{{x.FOLIO}}</td>
                                    <td style="width:235;">{{x.CLIENTE}}</td>
                                    <td style="width:180;">{{x.RFC}}</td>
                                    <td style="width:250; text-align:center">{{x.FECHA_TIMBRADO}}</td>
                                    <td style="width:190; text-align:center"><a href="./creacfdixml/getfacturaby/1/{{x.ID_FACTURA}}/{{x.ID_CLIENTE}}/{{x.ID_EMPRESA}}" >Descargar: {{x.FOLIO}}</a></td>
                                    <td style="width:190; text-align:center">
                                        <button class="button is-info" ng-click="mostrarEnviarEmail(true,x.ID_FACTURA,x.ID_CLIENTE,x.ID_EMPRESA)" >Correo: {{x.FOLIO}}</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal is-active" ng-show="enviaremail">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Aviso</p>
                    <button class="delete" aria-label="close" ng-click="mostrarEnviarEmail(false,idFactura,idCliente,idEmpresa);"></button>
                </header>
                <section class="modal-card-body">
                    <label class="label">Enviar el correo a la(s) siguiente(s) persona(s)</label>
                    <br>
                    <form name="myEmailForm">
                        <input type="text" name="nvoEmail" class="input" ng-model="nvoEmail" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                        <button class="button is-info is-small" ng-click="addEmail()"  ng-disabled="myEmailForm.nvoEmail.$invalid || myForm.nvoEmail.$dirty">Agregar</button>
                    </form>
                    <div style="width: 50%;  margin: 0 auto;">
                        <table class="table is-bordered">                            
                            <tr ng-repeat="x in lstCorreos">
                                <td>{{x.EMAIL}}</td>
                                <td><button class="button is-danger is-small" ng-click="eliminarEmail($index)">Eliminar</button></td>
                            </tr>
                        </table>
                    </div>

                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" ng-click="enviaCorreo(idFactura,idCliente,idEmpresa)">Enviar</button>
                    <button class="button is-error" ng-click="mostrarEnviarEmail(false,idFactura,idCliente,idEmpresa)">Cerrar</button>
                </footer>
            </div>
        </div>
    </div>    
<script src="../core/js/listafactura.js"></script>
</body>
</html>