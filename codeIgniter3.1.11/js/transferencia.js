app.controller("CtrlTransfer", function ($scope, $http,$routeParams) {
  let idempresa = '';
  let idUsuario = '';
  let anioFiscal = '';
  let idsucursal = '';
  $scope.dispsearch = false;
  $scope.lstSucursal = [];
  $scope.lstProdBusqueda = [];
  $scope.lstTransfer = [];
  $scope.lstRecepcion = [];
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.transfer ={
    nombresucorigen:'',
    idsucorigen:0,
    idsucdestino:0,
    claveproducto:'',
    descproducto:'',
    idproducto:0,
    cantidad:0,
    cantdisp:0,
    idusuario:0,
    fechatransfer:'',
    idempresa:'',
    aniofiscal:''
  };
  let idProceso = $routeParams.idproc;
  $scope.isAddTransfer = false;
  $scope.init = () =>{
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        idempresa = res.data.idempresa;
        idUsuario = res.data.idusuario;
        anioFiscal = res.data.aniofiscal;
        idsucursal = Number(res.data.idsucursal);
        $scope.getTransferInit();
        $scope.getSucursales();
        $scope.permisos();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getTransferInit = ()=>{
    $http.get(pathTransfer+'gettransfer/'+idempresa+'/'+anioFiscal+'/'+idsucursal)
    .then(res =>{
      if(res.data){
        $scope.lstTransfer = res.data.tx;
        $scope.lstRecepcion = res.data.rx;
      }
    })
    .catch(err =>{
      console.log(err);
    });
  }

  $scope.permisos = function(){
    $http.get(pathUsr+'permusrproc/'+idUsuario+'/'+idProceso)
    .then(res =>{
      $scope.permisos.alta = res.data[0].A == 't';
      $scope.permisos.baja = res.data[0].B == 't';
      $scope.permisos.modificacion = res.data[0].M == 't';
      $scope.permisos.consulta = res.data[0].C == 't';
    }).catch(err => {
      console.log(err);
    });
  }

  $scope.getSucursales = () =>{
    $http.get(pathSucr+'load/'+idempresa)
    .then(res =>{
      if(res.data.length > 0){
        $scope.lstSucursal = res.data.filter(elem =>{
          if(elem.ID_SUCURSAL===idsucursal){
            $scope.transfer.nombresucorigen = elem.ALIAS;
          }
          return (elem.ID_SUCURSAL !== idsucursal);
        });
        if($scope.lstSucursal.length > 0){
          $scope.transfer.idsucdestino = $scope.lstSucursal[0].ID_SUCURSAL;
        }
        
      }else{
        $scope.lstSucursal = [];
      }
    })
    .catch(err =>{
      console.log(err);
    });
  }

  $scope.buscprodbydesc = function (event) {
    if (event.keyCode != 13) {
      return;
    }
    $scope.dispsearch = true;
    var searchword = $scope.transfer.descproducto != "" ? $scope.transfer.descproducto : "vacio";
    $http.get(pathTpv + "getitems/" + idempresa + "/" + searchword + "/V", {responseType: "json",})
      .then(function (res) {
        if (res.status == "200") {
          $scope.lstProdBusqueda = res.data.filter(elem =>{
            return(elem.STOCK > 0);
          });
        }
      })
      .catch(function (err) {
        console.log(err);
      });
  };

  $scope.buscaprodbycodigo =  (event) => {
    if (event.keyCode == 13) {
      $http
        .get(
          pathProd +"prodbycode/" +$scope.transfer.claveproducto +"/" +idempresa +"/" +idsucursal,{ responseType: "json" }      )
        .then(function (res) {
          if (res.data != false) {
            if(res.data[0].STOCK > 0){
              $scope.transfer.claveproducto = res.data[0].CODIGO
              $scope.transfer.descproducto = res.data[0].DESCRIPCION;
              $scope.transfer.idproducto = res.data[0].ID_PRODUCTO;
              $scope.transfer.cantdisp = res.data[0].STOCK;
            }else{
              swal("El producto tiene existencia 0, no se puede hacer transferencia");
            }

          } else {
            swal(
              "No existe un producto con el código " + $scope.transfer.claveproducto
            );
          }
        })
        .catch();
    }
  };

  $scope.selectProdBus = (idxRowListaBusq)=> {
    $scope.transfer.claveproducto = $scope.lstProdBusqueda[idxRowListaBusq].CODIGO
    $scope.transfer.descproducto = $scope.lstProdBusqueda[idxRowListaBusq].DESCRIPCION;
    $scope.transfer.idproducto = $scope.lstProdBusqueda[idxRowListaBusq].ID_PRODUCTO;
    $scope.transfer.cantdisp = $scope.lstProdBusqueda[idxRowListaBusq].STOCK;
    $scope.closeDivSearch();
  }

  $scope.validaCantidad = () =>{
    if($scope.transfer.cantidad > $scope.transfer.cantdisp){
      swal("La cantidad que desea transferir es mayor a la disponible");
      $scope.transfer.cantidad = 0;
      return;
    }
  }

  $scope.guardarTransfer = () =>{
    if($scope.transfer.idproducto === 0){
      swal("Debe elegir un producto");
      return;
    }
    $scope.transfer.idusuario = idUsuario;
    $scope.transfer.idempresa = idempresa;
    $scope.transfer.aniofiscal = anioFiscal;
    $scope.transfer.fechatransfer = formatDateInsert(new Date());
    $scope.transfer.idsucorigen = idsucursal;
    $http.post(pathTransfer+'savetransfer',$scope.transfer)
    .then(res =>{
      if(res.data.registra_transferencia==='ok'){
        $scope.getTransferInit();
        $scope.closeAddTransfer();
        swal('La transferencia se realizó de manera exitosa!');
      }else{
        swal('No se pudo realizar la transferencia, por favor revise los valores');
      }
    })
    .catch(err =>{
      console.log(err);
    });
  }

  $scope.closeDivSearch = function () {
    $scope.dispsearch = false;
    $scope.lstProdBusqueda = [];
  };

  $scope.openDivAgregar = () =>{
    $scope.isAddTransfer = true;
  }

  $scope.closeAddTransfer = () =>{
    $scope.isAddTransfer = false;
    $scope.transfer.claveproducto = ''
    $scope.transfer.descproducto = '';
    $scope.transfer.idproducto = '';
    $scope.transfer.cantdisp = 0;
  }

});