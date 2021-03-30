app.controller('myCtrlPagos', function($scope,$http,$routeParams)
{
    $scope.idusuario = '';
    $scope.idempresa = '';
    $scope.idsucursal = '';
    $scope.aniofiscal = '';
    $scope.idFactura = '';
    $scope.idRowSel = -1;
    $scope.importepago = 0;
    $scope.isModalActive = false;
    $scope.fechapago = '';
    $scope.idPago = -1;
    $scope.idProceso = $routeParams.idproc;
    $scope.btnName = "Guardar";
    $scope.pago = {
        idfactura:'',
        cliente:'',
        idcliente:'',
        documento:'',
        saldo:0,
        cobrado:0,
        saldopendiente:0,
        fechapago:'',
        importepago:0,
        movimiento:0,
        banco:0,
        cheque:'',
        depositoen:0,
        poliza:'',
        importebase:'',
        idempresa:'',
        aniofiscal:''
    }
    $scope.lstFacturas = [];
    $scope.lstPagos = [];
    $scope.permisos = {
        alta: false,
        baja: false,
        modificacion:false,
        consulta:false
    };
    $scope.isCapturaPago = false;
    $scope.init = () =>{
        var foopicker = new FooPicker({
            id: 'fechaPago',
            dateFormat: 'dd/MM/yyyy'
            });
        $http.get(pathAcc+'getdata',{responseType:'json'}).
        then(function(res){
            if(res.data.value=='OK'){
            $scope.pago.idempresa = res.data.idempresa;
            $scope.pago.aniofiscal = res.data.aniofiscal;
            $scope.idusuario = res.data.idusuario;
            $scope.getListaFacturas();
            $scope.permisos();
            }
    }).catch(function(err){
        console.log(err);
    });
    };

    $scope.getListaFacturas = () =>{
        $http.get(pathPag+'getfacturas/'+$scope.pago.idempresa+'/'+$scope.pago.aniofiscal)
        .then(res =>{
            if(res.data.length > 0){
              $scope.lstFacturas = res.data;
            }
        })
        .catch(err =>{
          console.log(err);
        });
    }

    $scope.getpagos = () =>{
        $http.get(pathPag+'getpagofac/'+$scope.idFactura)
        .then(res=>{
            if(res.data.length > 0)
                $scope.lstPagos = res.data;
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

  $scope.agregaCobranza = () =>{
    if($scope.idFactura === ''){
        swal('Debe elegir una factura para poder aplicar un pago');
        return;
    }
    $scope.pago.cliente = $scope.lstFacturas[$scope.idRowSel].CLIENTE;
    $scope.pago.idcliente = $scope.lstFacturas[$scope.idRowSel].CLAVE;
    $scope.pago.documento = $scope.lstFacturas[$scope.idRowSel].DOCUMENTO;
    $scope.pago.importepago = $scope.lstFacturas[$scope.idRowSel].SALDO;
    $scope.pago.idfactura = $scope.lstFacturas[$scope.idRowSel].ID_FACTURA;
    $scope.isCapturaPago = true;
    $scope.getpagos();
  }


  $scope.guardaPago = () =>{
    if($scope.btnName === 'Guardar'){
      $http.post(pathPag+'guardapago',$scope.pago)
      .then(res=>{
          if(res.status===200){
              swal('Se guardo el pago con exito');
              $scope.closeMovimiento();
              $scope.getpagos();
          }
      })
      .catch(err=>{

      });
    }else{
      $http.put(pathPag+'guardapago',$scope.pago)
      .then(res=>{
          if(res.status===200){
              swal('Se guardo el pago con exito');
              $scope.closeMovimiento();
              $scope.getpagos();
          }
      })
      .catch(err=>{

      });
    }
    
  }

  $scope.editaPago=()=>{
    $http.get(pathPag+'getpagoid/'+$scope.idPago)
    .then(res=>{
      if(res.data.length>0){
        $scope.pago.aniofiscal = res.data[0].ANIO_FISCAL;
        $scope.pago.cheque=res.data[0].CHEQUE;
        $scope.pago.depositoen=res.data[0].DEPOSITO;
        //$scope.pago.fechapago=
        var e = jQuery.Event("keyup");
        e.which = 13; // # Some key code value
        
        $('#fechaPago').val(res.data[0].FECHA_PAGO);
        $("#fechaPago").trigger(e);
        $scope.pago.banco=res.data[0].ID_BANCO;
        //$scope.pago. res.data[0].ID_EMPRESA;
        //$scope.pago. res.data[0].ID_FACTURA;
        //$scope.pago. res.data[0].ID_MOVIMIENTO;
        //$scope.pago. res.data[0].ID_PAGO;
        $scope.pago.importebase=res.data[0].IMPORTE_BASE;
        $scope.pago.importepago=res.data[0].IMPORTE_PAGO;
        $scope.pago.poliza=res.data[0].POLIZA;
        $scope.isModalActive = true;
        $scope.btnName = "Actualizar";
      }
      
    })
    .catch(err=>{

    });
  }

    $scope.eliminaPago = () =>{
        if($scope.idPago === -1){
            swal('Debe seleccionar un pago');
            return;
        }
        swal({
            title: "Esta seguro que desea eliminar el pago",
            text: "Una vez eliminado, no se podrá recuperar!",
            icon: "warning",
            buttons: [true,true],
            dangerMode: true,
          })
          .then((willDelete) => {
            if(willDelete){
                $http.delete(pathPag+'deletepago/'+$scope.idPago+'/'+$scope.pago.idfactura+'/'+$scope.importepago)
                .then(res=>{
                    if(res.status === 200){
                        $scope.getpagos();
                        swal('El pago ha sido eliminado');
                        $scope.idPago = -1;
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

    $scope.selectRowFactura = (idFactura, index) =>{
        $scope.idFactura = idFactura;
        $scope.idRowSel = index;
    }

    $scope.selectRowPago = (idPago,importePago) =>{
        $scope.idPago = idPago;
        $scope.importepago = importePago;
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
});