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
    $scope.idCobro = -1;
    $scope.btnName = "Guardar";
    $scope.idProceso = $routeParams.idproc;
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
        $http.get(pathCob+'getcobrofac/'+$scope.idFactura)
        .then(res=>{
            if(res.data.length > 0)
                $scope.lstCobros = res.data;
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
    $scope.cobro.idcliente = $scope.lstFacturas[$scope.idRowSel].CLAVE;
    $scope.cobro.documento = $scope.lstFacturas[$scope.idRowSel].DOCUMENTO;
    $scope.cobro.importecobro = $scope.lstFacturas[$scope.idRowSel].SALDO;
    $scope.cobro.idfactura = $scope.lstFacturas[$scope.idRowSel].ID_FACTURA;
    $scope.isCapturaCobro = true;
    $scope.getcobros();
  }


  $scope.guardaCobro = () =>{
    if($scope.btnName === 'Guardar'){
      $http.post(pathCob+'guardacobro',$scope.cobro)
      .then(res=>{
          if(res.status===200){
              swal('Se guardo el cobro con exito');
              $scope.closeMovimiento();
              $scope.getcobros();
          }
      })
      .catch(err=>{

      });
    }else{
      $http.put(pathCob+'guardacobro',$scope.cobro)
      .then(res=>{
          if(res.status===200){
              swal('Se actualizo el cobro con exito');
              $scope.closeMovimiento();
              $scope.getcobros();
          }
      })
      .catch(err=>{

      });
    }
    
  }

  $scope.editaCobro=()=>{
    $http.get(pathCob+'getcobroid/'+$scope.idCobro)
    .then(res=>{
      if(res.data.length>0){
        $scope.cobro.aniofiscal = res.data[0].ANIO_FISCAL;
        $scope.cobro.cheque=res.data[0].CHEQUE;
        $scope.cobro.depositoen=res.data[0].DEPOSITO;
        //$scope.cobro.fechacobro=
        var e = jQuery.Event("keyup");
        e.which = 13; // # Some key code value
        
        $('#fechaCobro').val(res.data[0].FECHA_COBRO);
        $("#fechaCobro").trigger(e);
        $scope.cobro.banco=res.data[0].ID_BANCO;
        //$scope.cobro. res.data[0].ID_EMPRESA;
        //$scope.cobro. res.data[0].ID_FACTURA;
        //$scope.cobro. res.data[0].ID_MOVIMIENTO;
        //$scope.cobro. res.data[0].ID_cobro;
        $scope.cobro.importebase=res.data[0].IMPORTE_BASE;
        $scope.cobro.importecobro=res.data[0].IMPORTE_COBRO;
        $scope.cobro.poliza=res.data[0].POLIZA;
        $scope.isModalActive = true;
        $scope.btnName = "Actualizar";
      }
      
    })
    .catch(err=>{

    });
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

    $scope.selectRowCobro = (idCobro,importeCobro) =>{
        $scope.idCobro = idCobro;
        $scope.importecobro = importeCobro;
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
});