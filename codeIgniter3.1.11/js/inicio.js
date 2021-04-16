app.controller('myInicio', function($scope,$http,$interval){
  $scope.idUsuario = '';
  $scope.idempresa = '';
  $scope.idsucursal = '';
  $scope.aniofiscal = '';
  $scope.response = 'ERROR';
  $scope.counter = 0;
  $scope.valorinventario = 0;
  $scope.valorcxc = 0;
  $scope.valorcxp = 0;
  $scope.labels = [];
  $scope.series = [];
  $scope.datinvent = [];
  $scope.labinvent = [];
  $scope.qtyinvent = [];
  $scope.datacxc = [];
  $scope.labelscxc = [];
  $scope.datacxp = [];
  $scope.labelscxp = [];
  var stop = $interval(()=>{
    if($scope.response === 'ERROR'){
      $scope.getData();
    }
    },1500); 

  $scope.init = () =>{
    //console.log('En init');
  }

  $scope.getData = () =>{
    $http.get(pathAcc+'getdata',{responseType:'json'}).
      then(res =>{
        $scope.response = res.data.value;
        if(res.data.value=='OK'){
          $scope.idUsuario = res.data.idusuario;
          $scope.idempresa = res.data.idempresa;
          $scope.idsucursal = res.data.idsucursal;
          $scope.aniofiscal = res.data.aniofiscal;
          $scope.getInventario();
          $scope.getVentas();  
          $scope.getCuentasXCobrar();
          $scope.getCuentasXPagar();
        }
      });
  }

  $scope.getInventario = ()=>{
    $http.get(pathRepo+'valinvent/'+$scope.idempresa).
    then(res =>{
      if(res.data.length > 0){
        $scope.qtyinvent = res.data;
        res.data.forEach(element => {
          $scope.datinvent.push(Number(element.PORCENTAJE).toFixed(2));
          $scope.labinvent.push(element.LINEA+' '+element.SUMACURR);
          $scope.valorinventario += Number(element.SUMA)
        });
      }
    }).catch(err =>{

    });
  }

  $scope.getVentas = ()=>{
    $scope.data = [[0,0,0,0,0,0,0,0,0,0,0,0]];
    $scope.labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul','Ago','Sep','Oct','Nov','Dic'];
    $scope.series = [$scope.aniofiscal];
    $http.get(pathRepo+'ventasfy/'+$scope.idempresa+'/'+$scope.aniofiscal)
    .then(res =>{
      if(res.data){
        for(var i=0;i<res.data.length;i++){
          $scope.data[0][res.data[i].MES] = Number(res.data[i].IMPORTE).toFixed(2);
        }
      }
    })
    .catch(err=>{
      console.log(err);
    });
  }

  $scope.getCuentasXCobrar = () =>{
    $http.get(pathRepo+'ctsxcob/'+$scope.idempresa+'/'+$scope.aniofiscal)
    .then(res =>{
      if(res.data.length > 0){
        res.data.forEach(element => {
          $scope.datacxc.push(Number(element.PORCENTAJE).toFixed(2));
          $scope.labelscxc.push(element.CLIENTE+' '+element.SALDOCURR);
          $scope.valorcxc += Number(element.SALDO)
        });
      }
    })
  }

  $scope.getCuentasXPagar = () =>{
    $http.get(pathRepo+'ctsxpag/'+$scope.idempresa+'/'+$scope.aniofiscal)
    .then(res =>{
      if(res.data.length > 0){
        res.data.forEach(element => {
          $scope.datacxp.push(Number(element.PORCENTAJE).toFixed(2));
          $scope.labelscxp.push(element.NOMBRE+' '+element.SALDOCURR);
          $scope.valorcxp += Number(element.SALDO)
        });
      }
    })
  }

});