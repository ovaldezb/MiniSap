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
var pathAcc = '/codeigniter3.1.11/access/';
var app = angular.module("myApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : "/codeigniter3.1.11/access/inicio"
    })
    .when("/clss", {
        templateUrl : "/codeigniter3.1.11/access/logout"
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

app.controller('myCtrlIndex', function($scope,$http,$location,$window)
{
  $scope.idusuario = $('#idusuario').val();
  $scope.lstEmprPerm = [];
  $scope.lstFYEmpr = [];
  $scope.lstSucursal = [];
  $scope.idEmpresaSelected = '';
  $scope.idSelFYEmp = '';
  $scope.indxdSelEmp = '';
  $scope.indxdFyEmp = '';
  $scope.nombreEmpresa = '';
  $scope.anioFiscal = '';
  $scope.isEmpPermActiv = true;
  $scope.idSucEmp = 0;
  $scope.sucursalEmpresa = '';
  $scope.init = function()
  {
    $http.get(pathEmpr+'getemppermbyusr/'+$scope.idusuario,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.lstEmprPerm = res.data;
        $scope.idEmpresaSelected = $scope.lstEmprPerm[0].ID_EMPRESA;
        $scope.indxdSelEmp = 0;
        $scope.obtieneFYEmp($scope.idEmpresaSelected);
      }else {
        alert('El usuario no tiene Empresas asignadas, favor de contactar a su administrador!')
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.obtieneFYEmp = function(idEmpresa)
  {
    $http.get(pathEmpr+'getfybyemp/'+idEmpresa,{responseType:'json'}).
    then(function(res)
    {
      $scope.lstFYEmpr = [];
      if(res.data.length > 0)
      {
        $scope.lstFYEmpr = res.data;
        $scope.idSelFYEmp = res.data[0].EJER_FISC;
        $scope.indxdFyEmp = 0;
      }
    }).
    catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.guardarEmpPerm = function()
  {
    $http.get(pathAcc+'setempfy/'+$scope.idEmpresaSelected+'/'+$scope.idSelFYEmp,{responseType:'json'}).
    then(function(res)
    {
      $scope.nombreEmpresa = $scope.lstEmprPerm[$scope.indxdSelEmp].NOMBRE;
      $scope.anioFiscal = $scope.lstFYEmpr[$scope.indxdFyEmp].EJER_FISC;
      $scope.indx = $location.absUrl().indexOf('#');
      $scope.myUrl = $location.absUrl().substring(0, $scope.indx+3);
      $window.location.href = $scope.myUrl;
      $scope.sucursalEmpresa = res.data[0].ID_SUCURSAL;
      $scope.getLstSucursales();
    }).
    catch(function(err)
    {
      console.log(err);
    });
    $scope.isEmpPermActiv = false;
  }

  $scope.getLstSucursales = function()
  {
    $http.get(pathSucr+'load/'+$scope.idEmpresaSelected,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0)
      {
        $scope.lstSucursal = res.data;
      }
    }).
    catch((err)=>{
      console.log(err);
    });
  }

  $scope.selectEmpresa = function()
  {
    $scope.isEmpPermActiv = true;
  }

  $scope.cerrarSelectEmpr = function()
  {
    if($scope.nombreEmpresa == '')
    {
      alert('Debe seleccionar una empresa');
      return;
    }
    $scope.isEmpPermActiv = false;
  }

  $scope.selectEmpPerm = function(idEmp,indx)
  {
    $scope.idEmpresaSelected = idEmp;
    $scope.indxdSelEmp = indx;
    $scope.obtieneFYEmp(idEmp);
  }

  $scope.selectFYEmp = function(ejerFisc,index)
  {
    $scope.idSelFYEmp = ejerFisc;
    $scope.indxdFyEmp = index;
  }

  $scope.cambiaSucursal = ()=>{
    $http.put(pathSucr+'updtscursaluser/'+$scope.idEmpresaSelected+'/'+$scope.sucursalEmpresa).
    then((res)=>{      
      if(res.data.value == 'OK')
      {
        alert('Se actualizó la sucursal!');
      }
    }).catch((err)=>{
      console.log(err);
    });
  }

});
