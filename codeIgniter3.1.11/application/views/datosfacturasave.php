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
  <link rel="stylesheet" href="../core/css/utils.css">
  <link rel="stylesheet" href="../core/css/foopicker.css">
  <title>RTS</title>

</head>
<body>
|<div class="container" ng-app="myApp" ng-controller="myCtrlDatosFactura" data-ng-init="init()">
    <div class="notification" >
    <h1 class="title has-text-centered">
      <?php if(isset($totalFiles)) echo "Archivos cargados ".count($totalFiles)." files"; ?></h1>
    <h2><?php foreach($totalFiles as $tf){
          echo "<li>".$tf."</li>";
        }?>
    </h2>
    
    <h2> <?php if(isset($error)) print_r($error) ?></h2>
    
    <h2 class="h2">Contraseña:<?php echo $pass;?></h2>
    </div>
    
 </div>
<script src="../core/js/index.js"></script>
<script src="../core/js/datosfactura.js"></script>

</body>
</html>