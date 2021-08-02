app.controller('myCtrlPagos', function($scope,$http,$routeParams)
{
    $scope.idusuario = '';
    $scope.idempresa = '';
    $scope.idsucursal = '';
    $scope.aniofiscal = '';
    $scope.idCompra = '';
    $scope.idRowSel = -1;
    $scope.idRowPago = -1;
    $scope.importepago = 0;
    $scope.isModalActive = false;
    $scope.fechapago = '';
    $scope.lstFormaPago = [];
    $scope.lstBancos = [];
    $scope.hoy = '';
    $scope.idPago = -1;
    $scope.importeInicial = 0;
    $scope.idProceso = $routeParams.idproc;
    $scope.btnName = "Guardar";
    $scope.pago = {
        idcompra:'',
        cliente:'',
        idproveedor:'',
        documento:'',
        saldo:0,
        cobrado:0,
        saldopendiente:0,
        fechapago:'',
        importepago:0,
        movimiento:"1",
        banco:-1,
        pagado:0,
        cheque:'',
        depositoen:0,
        poliza:'',
        importebase:'',
        idempresa:'',
        aniofiscal:'',
        idsucursal:''
    }
    var banco = {
      'ID_BANCO':-1,
      'CLAVE':'',
      'DESCRIPCION':'Seleccione un Banco',
      'SAT':''
    };
    $scope.lstCompras = [];
    $scope.lstPagos = [];
    $scope.permisos = {
        alta: false,
        baja: false,
        modificacion:false,
        consulta:false
    };
    $scope.isCapturaPago = false;
    $scope.init = () =>{
        $scope.hoy = formatDatePrint(new Date());
        var foopicker = new FooPicker({
            id: 'fechaPago',
            dateFormat: 'dd/MM/yyyy'
            });
        $http.get(pathAcc+'getdata',{responseType:'json'}).
        then(function(res){
            if(res.data.value=='OK'){
            $scope.pago.idempresa = res.data.idempresa;
            $scope.pago.aniofiscal = res.data.aniofiscal;
            $scope.pago.idsucursal = res.data.idsucursal;
            $scope.idusuario = res.data.idusuario;
            $scope.getListaCompras();
            $scope.permisos();
            }
    }).catch(function(err){
        console.log(err);
    });
    $scope.getFormaPago();
    $scope.getBancos();
    };

    $scope.getListaCompras = () =>{
        $http.get(pathCmpr + 'getcompras/'+$scope.pago.idempresa+'/'+$scope.pago.aniofiscal+'/'+$scope.pago.idsucursal)
        .then(res =>{
            if(res.data.length > 0){
              $scope.lstCompras = res.data;
            }
        })
        .catch(err =>{
          console.log(err);
        });
    }

    $scope.getpagos = () =>{
      $scope.pago.pagado = 0;
      $scope.pago.saldo = 0;
      $scope.lstPagos = [];
        $http.get(pathPag+'getpagocom/'+$scope.idCompra)
        .then(res=>{
          if(res.data.length > 0){
            $scope.lstPagos = res.data;
            res.data.forEach((elemen)=>{
              $scope.pago.pagado += elemen.IMPORTE_PAGO;
            });
            $scope.pago.saldo = $scope.pago.importetotal - $scope.pago.pagado;
            $scope.pago.importepago = $scope.pago.saldo ;
          }else{
            $scope.pago.importepago = $scope.pago.importetotal;
            $scope.pago.saldo = $scope.pago.importetotal;
          }
          $scope.cleanPago();
        })
        .catch(err=>{
            console.log(err);
        });
    }

    $scope.permisos = function(){
        $http.get(pathUsr+'permusrproc/'+$scope.idusuario+'/'+$scope.idProceso)
        .then(res =>{
          $scope.permisos.alta = res.data[0].A == 't';
          $scope.permisos.baja = res.data[0].B == 't';
          $scope.permisos.modificacion = res.data[0].M == 't';
          $scope.permisos.consulta = res.data[0].C == 't';
        }).catch(err => {
          console.log(err);
        });
     
    }


  $scope.getFormaPago = function(){
    $http.get(pathUtils+'getformpag',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstFormaPago = res.data;
      }
    }).catch(err =>	{
      console.log(err);
    });
  }
  
    $scope.getBancos = () =>{
      $http.get(pathUtils+'getbancos')
      .then(res =>{
        $scope.lstBancos = res.data;
        $scope.lstBancos.unshift(banco);
      })
      .catch(err=>{
        console.log(err);
      });
    }

  $scope.aplicaPago = () =>{
    if($scope.idCompra === ''){
        swal('Debe elegir una compra para poder aplicar un pago');
        return;
    }

    $scope.pago.proveedor = $scope.lstCompras[$scope.idRowSel].PROVEEDOR;
    $scope.pago.idproveedor = $scope.lstCompras[$scope.idRowSel].ID_PROVEEDOR;
    $scope.pago.documento = $scope.lstCompras[$scope.idRowSel].DOCUMENTO;
    //$scope.pago.importepago = $scope.lstCompras[$scope.idRowSel].IMPORTE;
    $scope.pago.importetotal = $scope.lstCompras[$scope.idRowSel].IMPORTE;
    $scope.pago.saldo = 0;
    $scope.pago.pagado = 0;
    $scope.pago.idcompra = $scope.lstCompras[$scope.idRowSel].ID_COMPRA;
    $scope.isCapturaPago = true;
    $scope.getpagos();
  }


  $scope.guardaPago = () =>{
    if($scope.btnName === 'Guardar'){
      $http.post(pathPag+'guardapago',$scope.pago)
      .then(res=>{
          if(res.status===200){
              swal('Se guardó el pago con éxito');
              $scope.closeMovimiento();
              $scope.getpagos();
              //Esto es para refrescar la lista de Compras
              $scope.getListaCompras();
          }
      })
      .catch(err=>{

      });
    }else{
      let delta = $scope.pago.importepago - $scope.importeInicial;
      $http.put(pathPag+'updatepago/'+$scope.idPago+'/'+$scope.pago.idcompra+'/'+delta)
      .then(res=>{
          if(res.status===200){
              swal('Se actualizó el pago con éxito');
              $scope.closeMovimiento();
              $scope.getpagos();
              //Esto es para refrescar la lista de Compras
              $scope.getListaCompras();
          }
      })
      .catch(err=>{

      });
    } 
  }

  $scope.editaPago = () =>{
    $scope.isModalActive = true;
    $scope.btnName = "Actualizar";
    $scope.pago.importepago = $scope.lstPagos[$scope.idRowPago].IMPORTE_PAGO;
    $scope.importeInicial = $scope.lstPagos[$scope.idRowPago].IMPORTE_PAGO;
    $scope.fechapago = $scope.lstPagos[$scope.idRowPago].FECHA_PAGO;
  }

  $scope.eliminaPago = () =>{
    if($scope.idPago === -1){
        swal('Debe seleccionar un pago');
        return;
    }
    swal({
        title: "Está seguro que desea eliminar el pago",
        text: "Una vez eliminado, no se podrá recuperar!",
        icon: "warning",
        buttons: [true,true],
        dangerMode: true,
      })
      .then((willDelete) => {
        if(willDelete){
            $http.delete(pathPag+'deletepago/'+$scope.idPago+'/'+$scope.pago.idcompra+'/'+$scope.lstPagos[$scope.idRowPago].IMPORTE_PAGO)
            .then(res=>{
                if(res.status === 200){
                    $scope.getpagos();
                    swal('El pago ha sido eliminado');
                    $scope.idPago = -1;
                    //Esto es para refrescar la lista de Compras
                    $scope.getListaCompras();
                }
            })
            .catch(err=>{
                console.log(err);
            });
        }
      });
  }

    $scope.fecPagoChange = () =>{
        var e = jQuery.Event("keydown");
        e.which = 13; // # Some key code value
        $("#fechaPago").trigger(e);
        $scope.fechapago = formatFecPagodmy($('#fechaPago').val());
        $scope.pago.fechapago = formatFecQuery($('#fechaPago').val(),'ini');
    }

    $scope.selectRowCompra = (idCompra, index) =>{
        $scope.idCompra = idCompra;
        $scope.idRowSel = index;
    }

    $scope.selectRowPago = (idPago,importePago, index) =>{
        $scope.idPago = idPago;
        $scope.importetotal = importePago;
        $scope.idRowPago = index;
    }

    $scope.agregaPago = () =>{
        $scope.isModalActive = true;
    }

    $scope.cerrarPago = () =>{
        $scope.isCapturaPago = false;
        $scope.lstPagos = [];
    }

    $scope.closeMovimiento = () =>{
        $scope.isModalActive = false;
    }

    $scope.cleanPago = () =>{
      $scope.fechapago = '';
      $scope.pago.cheque = '';
      $scope.pago.poliza = '';
      $scope.pago.importebase = '';
    }
});