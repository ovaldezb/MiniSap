var pathCliente = '/pinabete/';
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
var pathFactura = pathCliente + 'facturas/';
var pathCargaMasiva = pathCliente + 'cargamasiva/';
var pathInv = pathCliente+'inventario/';
var pathCob = pathCliente+'cobranza/';
var pathPag = pathCliente+'pagos/';
var pathCorte = pathCliente+'cortecaja/';
var pathCreacfdi = pathCliente+'creacfdixml/';
var pathRepcxc = pathCliente+'reportecxc/';
var pathRepcxp = pathCliente+'reportecxp/';
var pathRepcobr = pathCliente+'repcobranza/';
var pathReppagos = pathCliente+'reppagos/';
var pathTransfer = pathCliente+'transferencia/';
var app = angular.module("myApp", ["ngRoute","chart.js"]);
app.config(function($routeProvider) {
    $routeProvider
    .when("/", {
        templateUrl : pathAcc +'/inicio'
    })
    .when("/clss", {
        templateUrl : pathAcc +'/logout'
    })
    .when("/clte/:idproc", {
      templateUrl: pathClte
    })
    .when("/empr/:idproc", {
        templateUrl : pathEmpr
    })
    .when("/prod/:idproc", {
        templateUrl : pathProd
    })
    .when("/prve/:idproc", {
        templateUrl : pathPrve
    })
    .when("/sucr/:idproc", {
        templateUrl : pathSucr
    })
    .when("/cmpr/:idproc", {
        templateUrl : pathCmpr
    })
    .when("/tpv/:idproc", {
        templateUrl : pathTpv
    })
    .when("/user/:idproc", {
        templateUrl : pathUsr
    })
    .when("/mval/:idproc",{
      templateUrl : pathRepo+'rmovalmc'
    })
    .when("/rven/:idproc",{
      templateUrl : pathRepo+'rventas'
    })
    .when("/line/:idproc",{
      templateUrl : pathCliente +'linea/'
    })
    .when("/crfc/:idproc",{
      templateUrl : pathCFDI
    })
    .when("/pedi/:idproc",{
      templateUrl : pathPedi
    })
    .when("/fact/:idproc",{
      templateUrl : pathFactura
    })
    .when("/vend/:idproc",{
      templateUrl : pathVend
    })
    .when("/prcm/:idproc",{
      templateUrl : pathCargaMasiva
    })
    .when("/ctin/:idproc",{
      templateUrl : pathInv
    })
    .when("/cbrz/:idproc",{
      templateUrl : pathCob
    })
    .when("/pago/:idproc",{
      templateUrl : pathPag
    })
    .when("/crdc/:idproc",{
      templateUrl : pathCorte
    })
    .when("/rcxc/:idproc",{
      templateUrl : pathRepcxc
    })
    .when("/rcxp/:idproc",{
      templateUrl : pathRepcxp
    })
    .when("/rpcb/:idproc",{
      templateUrl : pathRepcobr
    })
    .when("/rpag/:idproc",{
      templateUrl : pathReppagos
    })
    .when("/trsc/:idproc",{
      templateUrl : pathTransfer
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
  $scope.isCurrentYear = false;
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
    todayYear = new Date().getFullYear();
    $http.get(pathEmpr+'getfybyemp/'+idEmpresa,{responseType:'json'}).
    then((res) =>
    {
      $scope.lstFYEmpr = [];
      if(res.data.length > 0)
      {
        $scope.lstFYEmpr = res.data;
        $scope.idSelFYEmp = res.data[0].EJER_FISC;
        $scope.indxdFyEmp = 0;
        $scope.lstFYEmpr.forEach(yr =>{  
                
          $scope.isCurrentYear = (Number(yr.EJER_FISC)===todayYear);
        });
        
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
    then((res) =>
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

  $scope.addFY = () =>{
    todayYear = new Date();
    swal({
      title: "Desea agregar un nuevo año fiscal "+todayYear.getFullYear(),
      text: "Una vez agregado, no se podra eliminar",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((fiscalYear) => {
      if (fiscalYear) {
        $http.post(pathUtils+'addFY/'+$scope.idEmpresaSelected+'/'+todayYear.getFullYear())
        .then(res=>{
          $scope.isCurrentYear = true; 
          swal("El año fiscal ha sido agregado", {
            icon: "success",
          });
        })
        .catch(err=>{

        });
        
      } 
    });
  }

});
