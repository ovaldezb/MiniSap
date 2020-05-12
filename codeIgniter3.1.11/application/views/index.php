<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-route.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
  <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
	<script src="<?php echo base_url(); ?>js/utilerias.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery-3.4.1.slim.js"></script>
  <script src="<?php echo base_url(); ?>js/foopicker.js"></script>
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/utils.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/foopicker.css">
</head>
<body ng-app="myApp">
  <nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
    <a class="navbar-item" href="https://bulma.io">
      <img src="<?php echo base_url(); ?>/img/cercanias.png" alt="Bulma: Free, open source, and modern CSS framework based on Flexbox" width="40" height="28">
    </a>

    <!--a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a-->
  </div>
</nav>
  <div class="columns">
    <div class="column is-one-fifth">
      <aside class="menu">
        <p class="menu-label">
          General
        </p>
        <ul class="menu-list">
          <li><a href="#!user">Administracion de Usuarios</a></li>
        </ul>
        <p class="menu-label">
          Administración
        </p>
        <ul class="menu-list">
          <li><a href="#!empr">Empresas</a></li>
          <li><a href="#!clte">Clientes</a></li>
          <li><a href="#!prod">Productos</a></li>
          <li><a href="#!prve">Proveedor</a></li>
          <li><a href="#!sucr">Sucursal</a></li>
        </ul>
        <p class="menu-label">CRM</p>
        <ul class="menu-list">
          <li><a href="#!cmpr">Compras</a></li>
        </ul>
        <p class="menu-label">TPV</p>
        <ul class="menu-list">
          <li><a href="#!tpv">TPV</a></li>
        </ul>
      </aside>
    </div>
    <div class="column">
      <div ng-view></div>
    </div>
  </div>
<script>
var pathPrve = '/codeigniter3.1.11/proveedor/';
var pathClte = '/codeigniter3.1.11/cliente/';
var pathUtils = '/codeigniter3.1.11/utils/';
var pathEmpr = '/codeigniter3.1.11/empresa/';
var pathProd = '/codeigniter3.1.11/producto/';
var pathUpld = '/codeigniter3.1.11/upload/startupload/';
var pathSucr = '/codeigniter3.1.11/sucursal/';
var pathTpv = '/codeigniter3.1.11/tpv/';
var pathCmpr = '/codeigniter3.1.11/compras/';
var pathVend = '/codeigniter3.1.11/vendedor/';
var pathUsr = '/codeigniter3.1.11/usuarios/';
var app = angular.module("myApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "/codeigniter3.1.11/access"
    })
    .when("/clte", {
      templateUrl: '/codeigniter3.1.11/cliente'
    })
    .when("/empr", {
        templateUrl : '/codeigniter3.1.11/empresa'
    })
    .when("/prod", {
        templateUrl : '/codeigniter3.1.11/producto'
    })
    .when("/prve", {
        templateUrl : '/codeigniter3.1.11/proveedor'
    })
    .when("/sucr", {
        templateUrl : '/codeigniter3.1.11/sucursal'
    })
    .when("/cmpr", {
        templateUrl : '/codeigniter3.1.11/compras'
    })
    .when("/tpv", {
        templateUrl : '/codeigniter3.1.11/tpv'
    })
    .when("/user", {
        templateUrl : '/codeigniter3.1.11/usuarios'
    });
});
</script>
<script src="<?php echo base_url(); ?>js/clientes.js"></script>
<script src="<?php echo base_url(); ?>js/empresa.js"></script>
<script src="<?php echo base_url(); ?>js/producto.js"></script>
<script src="<?php echo base_url(); ?>js/proveedor.js"></script>
<script src="<?php echo base_url(); ?>js/sucursal.js"></script>
<script src="<?php echo base_url(); ?>js/compras.js"></script>
<script src="<?php echo base_url(); ?>js/tpv.js"></script>
<script src="<?php echo base_url(); ?>js/usuarios.js"></script>
</body>
</html>
