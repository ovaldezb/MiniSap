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
<div class="container" ng-app="myApp" ng-controller="myCtrCargMasiva" data-ng-init="init()">
    <div class="notification">
        <h1 class="title has-text-centered">Carga Masiva de Datos</h1>
    </div>
    <div class="box">
        <div class="columns">
            <div class="column is-2">
                <label class="label">Productos</label>
            </div>
            <div class="column is-3">
                <input type="file" name="files[]" accept=".csv" select-ng-files ng-model="file.producto">
            </div>
            <div class="column">
                <a href="../core/img/PlantillaProducto.xlsx"><img src="../core/img/excel-icon.png" width="30"  title="Descargar Plantilla de Productos"></a>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <fieldset>
                    <legend>Clientes</legend>
                    <div class="columns">
                        <div class="column">
                            <input type="file" accept=".csv" select-ng-files ng-model="file.cliente">
                        </div>
                        <div class="column">
                            <a href="../core/img/PlantillaProducto.xlsx"><img src="../core/img/excel-icon.png" width="30"  title="Descargar Plantilla de Productos"></a>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <button class="button is-success">Enviar</button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <fieldset>
                    <legend>Proveedores</legend>
                    <div class="columns">
                        <div class="column">
                            <input type="file" accept=".csv" select-ng-files ng-model="file.proveedor">
                        </div>
                        <div class="column">
                            <a href="../core/img/PlantillaProducto.xlsx"><img src="../core/img/excel-icon.png" width="30"  title="Descargar Plantilla de Productos"></a>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column">
                            <button class="button is-success">Enviar</button>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>
<script src="../core/js/cargamasiva.js"></script>
</body>
</html>
