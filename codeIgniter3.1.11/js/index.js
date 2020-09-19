var pathCliente = '/core/';
var pathPrve = pathCliente+'proveedor/';
var pathClte = pathCliente+'cliente/';
var pathUtils = pathCliente+'utils/';
var pathEmpr = pathCliente+'empresa/';
var pathProd = pathCliente+'producto/';
var pathUpld = pathCliente+'upload/startupload/';
var pathSucr = pathCliente+'sucursal/';
var pathTpv = pathCliente+'tpv/';
var pathCmpr = pathCliente+'compras/';
var pathVend = pathCliente+'vendedor/';
var pathUsr = pathCliente+'usuarios/';
var pathAcc = pathCliente+'access/';
var pathRepo = pathCliente+'reportes/';
var pathLinea = pathCliente + 'api/linea/';
var pathCreaFact = pathCliente + 'creacfdixml/';
var pathCFDI = pathCliente + 'datosfactura/';
var pathPedi = pathCliente + 'pedidos/';
var pathFacturacion = pathCliente + 'facturacion/';
var pathFactura = pathCliente + 'factura/';
var app = angular.module("myApp", ["ngRoute"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : pathCliente+'access/inicio'
    })
    .when("/clss", {
        templateUrl : pathCliente+'access/logout'
    })
    .when("/clte", {
      templateUrl:    pathCliente+'cliente'
    })
    .when("/empr", {
        templateUrl : pathCliente+'empresa'
    })
    .when("/prod", {
        templateUrl : pathCliente+'producto'
    })
    .when("/prve", {
        templateUrl : pathCliente+'proveedor'
    })
    .when("/sucr", {
        templateUrl : pathCliente+'sucursal'
    })
    .when("/cmpr", {
        templateUrl : pathCliente+'compras'
    })
    .when("/tpv", {
        templateUrl : pathCliente+'tpv'
    })
    .when("/user", {
        templateUrl : pathCliente+'usuarios'
    })
    .when("/mval",{
      templateUrl : pathRepo+'rmovalmc'
    })
    .when("/rven",{
      templateUrl : pathRepo+'rventas'
    })
    .when("/line",{
      templateUrl : pathCliente+'linea'
    })
    .when("/crfc",{
      templateUrl : pathCliente+'datosfactura'
    })
    .when("/pedi",{
      templateUrl : pathCliente+'pedidos'
    })
    .when("/fact",{
      templateUrl : pathCliente+'facturacion'
    })
    .when("/vend",{
      templateUrl : pathVend
    });
});

app.controller('myCtrlIndex', function($scope,$http,$location,$window)
{
  $scope.idusuario = $('#idusuario').val();
  $scope.usuario = $('#nombreusuario').val();
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
    $scope.loadEmpresas();  
  }

  $scope.loadEmpresas = function(){
    $http.get(pathEmpr+'getemppermbyusr/'+$scope.idusuario,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.lstEmprPerm = res.data;
        $scope.idEmpresaSelected = $scope.lstEmprPerm[0].ID_EMPRESA;
        $scope.indxdSelEmp = 0;
        $scope.obtieneFYEmp($scope.idEmpresaSelected);
      }else if($scope.usuario == 'Administrador') {
        $window.location.href = $window.location.href + 'empr';
        $scope.isEmpPermActiv = false; 
      }else{
        swal('El usuario no tiene Empresas asignadas, favor de contactar a su administrador!')
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
    $scope.loadEmpresas(); 
    $scope.isEmpPermActiv = true;
  }

  $scope.cerrarSelectEmpr = function()
  {
    if($scope.nombreEmpresa == '')
    {
      swal('Debe seleccionar una empresa');
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
        swal('Se actualizó la sucursal!');
      }
    }).catch((err)=>{
      console.log(err);
    });
  }

});
