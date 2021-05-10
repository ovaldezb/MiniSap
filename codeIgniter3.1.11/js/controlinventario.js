app.controller("myCtrlControlinvent", function ($scope, $http,$routeParams) {
  $scope.isShowReporte = false;
  $scope.lstInvent = [];
  $scope.lstProducto = [];
  $scope.isAdd = false;
  $scope.fechaIni = '';
  $scope.fechaFin = '';
  $scope.fechaMov = '';
  $scope.tipoES='e';
  $scope.cantidad='';
  $scope.medida='';
  $scope.cliPro='cli';
  $scope.prddesc = '';
  $scope.clave='';
  $scope.idSelInv = -1;
  $scope.idusuario = '';
  $scope.idProceso = $routeParams.idproc;
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
          $scope.idUsuario = res.data.idusuario;
          $scope.permisos();
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
    $scope.fechaIni = $('#fechaInicio').val();
  }

  $scope.fecFinChange = () =>{
    $scope.ctrlInv.fechaFin = formatFecQuery($('#fechaFin').val(),'fin');
    $scope.fechaFin = $('#fechaFin').val();
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
      $scope.fechaMov = $('#fechaMovimiento').val(); 
    }
    
  }

  $scope.selectRowInv = (idInv) =>{
    console.log(idInv);
    $scope.idSelInv =  idInv;
  }

  $scope.creaReporte = () =>{
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

    $http.post(pathInv+'saveinventario',$scope.regmov)
    .then(res=>{
      if(res.status === 200){
        swal('El movimiento se guardó con éxito');
        $scope.closeAddMov()
      }
    })
    .catch(err=>{
      console.log(err);
    });
  }


  $scope.elliminaMovimiento = ()=>{
    swal({
      title: "Desea eliminar este movimiento?",
      text: "una vez eliminado no será posible recuperarlo",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $http.delete(pathInv+'delmov/'+$scope.idSelInv)
        .then(res =>{
          $http.post(pathInv+'getinventario',$scope.ctrlInv)
          .then(res =>{
            if(res.data.length > 0){
              $scope.lstInvent = res.data;
            }
          })
          .catch(err =>{
            console.log(err);
          });
        })
        .catch(err=>{
          console.log(err);
        });
        swal("El movimiento ha sido eliminado!", {
          icon: "success",
        });
      } 
    });
  }

  $scope.buscaProducto=(event)=> {
    event.stopPropagation();
    if (event.keyCode == 13) {
      if($scope.regmov.codigo === undefined || $scope.regmov.codigo === ''){
        $http.get(pathTpv+'getitems/'+$scope.ctrlInv.idempresa+'/vacio/V', {responseType: 'json'}).
          then(res =>
          {
            if(res.status == '200')
            {
              $scope.lstProducto = res.data;
            }
          }).catch(err =>
          {
            console.log(err);
          })
      }else{
        $http.get(pathProd+'prodbycode/'+$scope.regmov.codigo+'/'+$scope.ctrlInv.idempresa+'/'+$scope.regmov.idsucursal)
          .then(res =>{
            if(res.data.length > 0){
              $scope.regmov.codigo = res.data[0].CODIGO.trim();
              $scope.prddesc = res.data[0].DESCRIPCION.trim();
              $scope.medida = res.data[0].UNIDAD_MEDIDA.trim();
              $scope.regmov.idproducto = res.data[0].ID_PRODUCTO;
            }else{              
              $http.get(pathTpv+'getitems/'+$scope.ctrlInv.idempresa+'/'+$scope.regmov.codigo+'/V', {responseType: 'json'}).
                then(res =>
                {
                  if(res.data.length > 0)
                  {
                    $scope.lstProducto = res.data;
                  }else{
                    swal('No se encontró un producto con el código o descripción '+$scope.regmov.codigo);
                  }
                }).catch(err =>
                {
                  console.log(err);
                })
            }
          })
          .catch();
      }
    }
  }

  $scope.selectRowPrd = (index, idproducto) =>{
    $scope.regmov.codigo = $scope.lstProducto[index].CODIGO;
    $scope.prddesc = $scope.lstProducto[index].DESCRIPCION;
    $scope.medida = $scope.lstProducto[index].UNIDAD_MEDIDA;
    $scope.regmov.idproducto = idproducto;
    $scope.lstProducto = [];
  }

  $scope.agregaMovimiento = () =>{
    $scope.isAdd = true;
  }

  $scope.closeAddMov = (event) =>{
    $scope.isAdd = false;
    $scope.regmov ={};
    $scope.medida = '';
    $scope.cantidad = '';
    $scope.fechaMov = '';
  }
  
});
