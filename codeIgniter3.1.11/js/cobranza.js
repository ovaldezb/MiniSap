app.controller('myCtrlCobros', function($scope,$http,$routeParams)
{
    $scope.idusuario = '';
    $scope.idempresa = '';
    $scope.idsucursal = '';
    $scope.aniofiscal = '';
    $scope.idFactura = '';
    $scope.idRowSel = -1;
    $scope.importecobro = 0;
    $scope.isModalActive = false;
    $scope.fechacobro = '';
    $scope.hoy = '';
    $scope.idCobro = -1;
    $scope.importeInicial= 0;
    $scope.idProceso = $routeParams.idproc;
    $scope.btnName = "Guardar";
    $scope.cobro = {
        idfactura:'',
        cliente:'',
        idcliente:'',
        documento:'',
        saldo:0,
        cobrado:0,
        saldopendiente:0,
        fechacobro:'',
        importecobro:0,
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
    $scope.lstCobros = [];
    $scope.permisos = {
        alta: false,
        baja: false,
        modificacion:false,
        consulta:false
    };
    $scope.isCapturaCobro = false;
    $scope.init = () =>{
      $scope.hoy = formatDatePrint(new Date());
      var foopicker = new FooPicker({
          id: 'fechaCobro',
          dateFormat: 'dd/MM/yyyy'
          });
      $http.get(pathAcc+'getdata',{responseType:'json'}).
        then(function(res){
            if(res.data.value=='OK'){
            $scope.cobro.idempresa = res.data.idempresa;
            $scope.cobro.aniofiscal = res.data.aniofiscal;
            $scope.idusuario = res.data.idusuario;
            $scope.getListaFacturas();
            $scope.permisos();
            }
        }).catch(function(err){
          console.log(err);
      });
    };

    $scope.getListaFacturas = () =>{
        $http.get(pathCob+'getfacturas/'+$scope.cobro.idempresa+'/'+$scope.cobro.aniofiscal)
        .then(res =>{
            if(res.data.length > 0){
              $scope.lstFacturas = res.data;
            }
        })
        .catch(err =>{
          console.log(err);
        });
    }

    $scope.getcobros = () =>{
      $scope.cobro.cobrado = 0;
      $scope.cobro.saldo = 0;
      $scope.lstCobros
      $http.get(pathCob+'getcobrofac/'+$scope.idFactura)
      .then(res=>{
        if(res.data.length > 0){
          $scope.lstCobros = res.data;
          res.data.forEach((elemen)=>{
            $scope.cobro.cobrado += elemen.IMPORTE_COBRO;
          });
          $scope.cobro.saldo = $scope.cobro.importetotal - $scope.cobro.cobrado;
          $scope.cobro.importecobro = $scope.cobro.saldo ;
        }else{
          $scope.cobro.importecobro = $scope.cobro.importetotal;
          $scope.cobro.saldo = $scope.cobro.importetotal;
        }
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
        swal('Debe elegir una factura para poder aplicar un cobro');
        return;
    }
    $scope.cobro.cliente = $scope.lstFacturas[$scope.idRowSel].CLIENTE;
    $scope.cobro.idcliente = $scope.lstFacturas[$scope.idRowSel].ID_CLIENTE;
    $scope.cobro.documento = $scope.lstFacturas[$scope.idRowSel].DOCUMENTO;    
    //$scope.cobro.importecobro = $scope.lstFacturas[$scope.idRowSel].SALDO;
    $scope.cobro.importetotal = $scope.lstFacturas[$scope.idRowSel].IMPORTE;
    $scope.cobro.cobrado = 0;
    $scope.cobro.saldo = 0;
    $scope.cobro.idfactura = $scope.lstFacturas[$scope.idRowSel].ID_FACTURA;
    $scope.isCapturaCobro = true;
    $scope.getcobros();
  }


  $scope.guardaCobro = () =>{
    if($scope.btnName === 'Guardar'){
      $http.post(pathCob+'guardacobro',$scope.cobro)
      .then(res=>{
          if(res.status===200){
              swal('Se guardó el cobro con éxito');
              $scope.closeMovimiento();
              $scope.getcobros();
              $scope.getListaFacturas();
          }
      })
      .catch(err=>{

      });
    }else{
      let delta = $scope.cobro.importecobro - $scope.importeInicial;
      $http.put(pathCob+'updatecobro/'+$scope.idCobro+'/'+$scope.idFactura+'/'+delta)
      .then(res=>{
          if(res.status===200){
              swal('Se actualizó el cobro con éxito');
              $scope.closeMovimiento();
              $scope.getcobros();
              $scope.getListaFacturas();
          }
      })
      .catch(err=>{

      });
    }
    
  }

  $scope.editaCobro=()=>{
    $scope.isModalActive = true;
    $scope.btnName = "Actualizar";
    $scope.cobro.importecobro = Number($scope.lstCobros[$scope.idRowCobro].IMPORTE_COBRO).toFixed(2);
    $scope.importeInicial = $scope.lstCobros[$scope.idRowCobro].IMPORTE_COBRO;
    $scope.fechacobro = $scope.lstCobros[$scope.idRowCobro].FECHA_COBRO;

  }

    $scope.eliminaCobro = () =>{
        if($scope.idCobro === -1){
            swal('Debe seleccionar un cobro');
            return;
        }
        swal({
            title: "Esta seguro que desea eliminar el cobro",
            text: "Una vez eliminado, no se podrá recuperar!",
            icon: "warning",
            buttons: [true,true],
            dangerMode: true,
          })
          .then((willDelete) => {
            if(willDelete){
                $http.delete(pathCob+'deletecobro/'+$scope.idCobro+'/'+$scope.cobro.idfactura+'/'+$scope.importecobro)
                .then(res=>{
                    if(res.status === 200){
                        $scope.getcobros();
                        swal('El cobro ha sido eliminado');
                        $scope.idCobro = -1;
                        $scope.getListaFacturas();
                    }
                })
                .catch(err=>{
                    console.log(err);
                });
            }
          });
        
    }

    $scope.fecCobroChange = () =>{
      var e = jQuery.Event("keydown");
      e.which = 13; // # Some key code value
      $("#fechaCobro").trigger(e);
      $scope.fechacobro = formatFecPagodmy($('#fechaCobro').val());
      $scope.cobro.fechacobro = formatFecQuery($('#fechaCobro').val(),'ini');
    }

    $scope.selectRowFactura = (idFactura, index) =>{
      $scope.idFactura = idFactura;
      $scope.idRowSel = index;
    }

    $scope.selectRowCobro = (idCobro,importeCobro, index) =>{
      $scope.idCobro = idCobro;
      $scope.importetotal = importeCobro;
      $scope.idRowCobro = index;
    }

    $scope.agregaCobro = () =>{
      $scope.isModalActive = true;
    }

    $scope.cerrarCobro = () =>{
      $scope.isCapturaCobro = false;
      $scope.lstCobros = [];
    }

    $scope.closeMovimiento = () =>{
      $scope.isModalActive = false;
    }

    $scope.cleanPago = () =>{
      $scope.fechacobro = '';
      $scope.cobro.cheque = '';
      $scope.cobro.poliza = '';
      $scope.cobro.importebase = '';
    }
});