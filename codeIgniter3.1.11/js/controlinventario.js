var pathCliente = '/core/';
var pathAcc = pathCliente+'access/';
var pathUsr = pathCliente+'usuarios/';
var pathInv = pathCliente+'inventario/';
var app = angular.module("myApp", []);
app.controller("myCtrlControlinvent", function ($scope, $http) {
  $scope.isShowReporte = false;
  $scope.lstInvent = [];
  $scope.isAdd = false;
  $scope.fechaIni = '';
  $scope.fechaFin = '';
  $scope.fechaMov = '';
  $scope.tipoES='e';
  $scope.cantidad=0,
  $scope.medida='';
  $scope.cliPro='cli';
  $scope.clave='';
  $scope.ctrlInv = {
    fechaIni:'',
    fechaFin:'',
    tipoES:'t',
    tipoMov:'tod',
    caja:'',
    codigoProducto:'',
    idempresa:'',
    aniofiscal:''  
  }
  $scope.regmov = {
    in:0,
    out:0,
    tipoMov:'COM',
    idmoneda:1,
    documento:'',
    fecha:'',
    caja:1,
    codigo:'',
    idproducto:'',
    aniofiscal:0,
    preciounit:'',
    importe:0,
    idsucursal:'',
    idempresa:'',
    idcliente:'',
    idproveedor:'',
    idusuario:''
  };

  $scope.permisos = {
    alta: true,
    baja: true,
    modificacion:true,
    consulta:true
  };

  $scope.clipro = [
    {label:'Cliente',value:'cli'},
    {label:'Proveedor',value:'pro'}];

  $scope.cliprorm = [
    {label:'Cliente',value:'cli'},
    {label:'Proveedor',value:'pro'},
    {label:'Interno',value:'int'}];

  $scope.entsal = [
    {label:'Entrada',value:'e'},
    {label:'Salida',value:'s'}
  ];

  $scope.tipoMMos =[];
  $scope.tipoMEnt =[
    {value:"COM",label:'Compra'},
    {value:"DVE",label:'Dev/Ventas'},
    {value:"INV",label:'Inventario'},
    {value:"SOB",label:'Sobrante'},
    {value:"EXT",label:'Ent x Transpaso'},
    {value:"CON",label:'Consignación'}
  ];
  $scope.tipoMSal =[
    {value:"VEN",label:'Venta'},
    {value:"DCO",label:'Dev/Compras'},
    {value:"FAL",label:'Faltante'},
    {value:"SXT",label:'Sal x Transpaso'}
  ];

  $scope.tipoMov = [
  {value:"VEN",label:'Venta'},
  {value:"COM",label:'Compra'},
  {value:"DCO",label:'Dev/Compras'},
  {value:"DVE",label:'Dev/Ventas'},
  {value:"INV",label:'Inventario'},
  {value:"FAL",label:'Faltante'},
  {value:"SOB",label:'Sobrante'},
  {value:"EXT",label:'Ent x Transpaso'},
  {value:"SXT",label:'Sal x Transpaso'},
  {value:"CON",label:'Consignación'}
  ]

  $scope.lstMoneda =[
    {value:1,label:"Pesos"},
    {value:2,label:"Dólares"},
    {value:3,label:"Euros"}
  ];

  $scope.init = () => {
    $scope.tipoMMos = $scope.tipoMEnt;
    $http.get(pathAcc+'getdata',{responseType:'json'}).
      then(res => {
        if(res.data.value=='OK'){
          $scope.ctrlInv.idempresa = res.data.idempresa;
          $scope.regmov.idempresa = res.data.idempresa;
          $scope.regmov.idsucursal = res.data.idsucursal;
          $scope.ctrlInv.aniofiscal = res.data.aniofiscal;
          $scope.regmov.aniofiscal = res.data.aniofiscal;
        }
      })
      .catch(err =>{
        console.log(err);  
      });

    var foopicker = new FooPicker({
      id: "fechaInicio",
      dateFormat: "dd/MM/yyyy",
    });

    var foopicker1 = new FooPicker({
      id: "fechaFin",
      dateFormat: "dd/MM/yyyy",
    });

    var foopicker = new FooPicker({
      id: "fechaMovimiento",
      dateFormat: "dd/MM/yyyy",
    });
  };

  $scope.cambiarTipo = () =>{
    if($scope.regmov.tipoES=='e'){
      $scope.tipoMMos = $scope.tipoMEnt;
      $scope.regmov.tipoMov = 'COM';
    }else{
      $scope.tipoMMos = $scope.tipoMSal;
      $scope.regmov.tipoMov = 'VEN';
    }
    
  }

  $scope.permisos = () => {
    $http.get(pathUsr+'permusrproc/'+$scope.idUsuario+'/'+$scope.idProceso)
    .then(res =>{
      $scope.permisos.alta = res.data[0].A == 't';
      $scope.permisos.baja = res.data[0].B == 't';
      $scope.permisos.modificacion = res.data[0].M == 't';
      $scope.permisos.consulta = res.data[0].C == 't';
    }).catch(err => {
      console.log(err);
    });
  }

  $scope.fecIniChange =()=>{
    $scope.ctrlInv.fechaIni = formatFecQuery($('#fechaInicio').val(),'ini');
  }

  $scope.fecFinChange = () =>{
    $scope.ctrlInv.fechaFin = formatFecQuery($('#fechaFin').val(),'fin');
  }

  $scope.validateFecha = () =>{
    var date = new Date();
    var today = new Date(date.getFullYear()+'-'+(date.getMonth()+1) +'-'+ date.getDate()+' 00:00:00');
    var fecha = $('#fechaMovimiento').val();
    var fecmov = Date.parse(fecha.substring(6,10)+'-'+fecha.substring(3,5)+'-'+fecha.substring(0,2)+' 00:00:00');
    
    if(today < fecmov){
      swal('La fecha de movimiento \nno puede ser mayor a la fecha de hoy');
      $('#fechaMovimiento').val('');
      return;
    }else{
      date.setFullYear(fecha.substring(6,10));
      date.getMonth(fecha.substring(3,5));
      date.setDate(fecha.substring(0,2));
      $scope.regmov.fecha = formatDateInsert(date);
    }
    
  }

  $scope.creaReporte = () =>{
      $scope.permisos.alta=true;
      $scope.permisos.baja=true;
      $scope.permisos.modificacion=true;
      $scope.permisos.consulta=true;
      $scope.isShowReporte = true;
      $http.post(pathInv+'getinventario',$scope.ctrlInv)
        .then(res =>{
          if(res.data.length > 0){
            $scope.lstInvent = res.data;
          }
        })
        .catch(err =>{
          console.log(err);
        });
  }

  $scope.enviaMovimiento = () =>{
    if($scope.tipoES =='e'){
      $scope.regmov.in = $scope.cantidad;
      $scope.regmov.out = 0;
    }else{
      $scope.regmov.in = 0;
      $scope.regmov.out = $scope.cantidad;
    }
    $scope.regmov.importe = $scope.cantidad * $scope.regmov.preciounit;
    if($scope.cliPro=='cli'){
      $scope.regmov.idcliente=$scope.clave;
      $scope.regmov.idproveedor=null;
      $scope.regmov.idusuario=null;
    }else if($scope.cliPro=='pro'){
      $scope.regmov.idcliente=null;
      $scope.regmov.idproveedor=$scope.clave;
      $scope.regmov.idusuario=null;
    }else{
      $scope.regmov.idcliente=null;
      $scope.regmov.idproveedor=null;
      $scope.regmov.idusuario=$scope.clave;
    }

    console.log($scope.regmov);
  }

  $scope.agregaMovimiento = () =>{
    $scope.isAdd = true;
    
  }

  $scope.closeAddMov = () =>{
    $scope.isAdd = false;
  }
  
});
