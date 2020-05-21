app.controller('myCtrlSucursal', function($scope,$http)
{
  $scope.lstSucursal = [];
  $scope.btnAccion = 'Agregar';
  $scope.isAvsoBrrarActv = false;
  $scope.isDivSucActivo = false;
  $scope.sortDir = false;
  $scope.clave = '';
  $scope.direccion = '';
  $scope.responsable = '';
  $scope.telefono = '';
  $scope.cp = '';
  $scope.alias = '';
  $scope.notas = '';
  $scope.idSelSuc = '';
  $scope.idxRowSuc = '';
  $scope.idSucursal = '';
  $scope.idempresa = '';

  $scope.init = function() {
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idempresa = res.data.idempresa;
        $scope.getDataInit();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getDataInit = function(){
    $http.get(pathSucr+'load/'+$scope.idempresa,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length>0)
      {
        $scope.lstSucursal = res.data;
        $scope.selectRowSucursal($scope.lstSucursal[0].CLAVE,0,$scope.lstSucursal[0].ID_SUCURSAL);
      }else {
        $scope.lstSucursal = [];
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.openDivAgregar = function()
  {
    $scope.isDivSucActivo = true;
  }

  $scope.submitForm = function()
  {
    var dataSuc =
    {
      clave:$scope.clave,
      direccion:$scope.direccion,
      responsable:$scope.responsable,
      telefono:$scope.telefono,
      cp:$scope.cp,
      alias:$scope.alias,
      notas:$scope.notas,
      idempresa:$scope.idempresa
    };

    if($scope.btnAccion == 'Agregar')
    {
      $http.post(pathSucr+'save',dataSuc).
      then(function(res)
      {
        $('#message').html(res.data);
        if(res.data.length > 0)
        {
          var dataSucAdd =
          {
            CLAVE:$scope.clave,
            DIRECCION:$scope.direccion,
            RESPONSABLE:$scope.responsable,
            CP:$scope.cp,
            ID_SUCURSAL:res.data[0].crea_sucursal
          };
          $scope.lstSucursal.push(dataSucAdd);
          $scope.selectRowSucursal($scope.lstSucursal[0].CLAVE,0,$scope.lstSucursal[0].ID_SUCURSAL);
          alert('La nueva sucursal ha sido almacenada');
          $scope.cancelar();
        }
      }).catch(function(err)
      {
        console.log(err);
      });

    }else {
      $http.put(pathSucr+'update/'+$scope.idSucursal,dataSuc).
      then(function(res)
      {
        if(res.data.value == 'OK')
        {
          var dataSucUpdt =
          {
            CLAVE:$scope.clave,
            DIRECCION:$scope.direccion,
            RESPONSABLE:$scope.responsable,
            CP:$scope.cp,
            ID_SUCURSAL:$scope.idSucursal
          };
          $scope.lstSucursal[$scope.idxRowSuc] = dataSucUpdt;
          alert('Se actualizó correctamente la sucursal');
          $scope.btnAccion = 'Agregar';
          $scope.cancelar();
        }
      }).catch(function(err)
      {
        console.log(err);
      });
    }
  }

  $scope.update = function()
  {
    $http.get(pathSucr+'loadbyid/'+$scope.idSucursal).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.clave = res.data[0].CLAVE.trim();
        $scope.direccion = res.data[0].DIRECCION;
        $scope.responsable = res.data[0].RESPONSABLE.trim();
        $scope.telefono = res.data[0].TELEFONO;
        $scope.cp = res.data[0].CP
        $scope.alias = res.data[0].ALIAS.trim();
        $scope.notas = res.data[0].NOTAS;
        $scope.isDivSucActivo = true;
        $scope.btnAccion = 'Actualizar';
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.eliminar = function()
  {
    $http.delete(pathSucr+'delete/'+$scope.idSucursal).
    then(function(res)
    {
      if(res.data.value=='OK')
      {
        if($scope.lstSucursal.length > 2)
        {
          $scope.lstSucursal.splice($scope.idxRowSuc,1);
          $scope.selectRowSucursal($scope.lstSucursal[0].CLAVE,0,$scope.lstSucursal[0].ID_SUCURSAL);
        }else {
          $scope.lstSucursal = [];
        }

        alert('Se ha eliminado correctamente la sucursal');
        $scope.closeAvisoBorrar();
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.preguntaEliminar = function()
  {
    $scope.descSucBorrar = $scope.lstSucursal[$scope.idxRowSuc].DIRECCION;
    $scope.isAvsoBrrarActv = true;
  }

  $scope.closeAvisoBorrar = function()
  {
    $scope.descSucBorrar = '';
    $scope.isAvsoBrrarActv = false;
  }

  $scope.orderByMe = function(val)
  {
    $scope.myOrderBy = val;
    $scope.sortDir = !$scope.sortDir;
  }

  $scope.selectRowSucursal = function(idSelSuc,idxRowSuc,idSucursal)
  {
    $scope.idSelSuc = idSelSuc;
    $scope.idxRowSuc = idxRowSuc;
    $scope.idSucursal = idSucursal;
  }

  $scope.cancelar = function()
  {
    $scope.isDivSucActivo = false;
    $scope.clave = '';
    $scope.direccion = '';
    $scope.responsable = '';
    $scope.telefono = '';
    $scope.cp = '';
    $scope.alias = '';
    $scope.notas = '';
  }
});
