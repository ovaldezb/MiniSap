app.controller('myCtrlSucursal', function($scope,$http,$routeParams)
{
  $scope.lstSucursal = [];
  $scope.btnAccion = 'Agregar';
  $scope.isAvsoBrrarActv = false;
  $scope.isDivSucActivo = false;
  $scope.sortDir = false;
  $scope.idSelSuc = '';
  $scope.idxRowSuc = '';
  $scope.idSucursal = '';
  $scope.idempresa = '';
  $scope.idUsuario = '';
  $scope.idProceso = $routeParams.idproc;
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.suc = {
    clave:'',
    direccion:'',
    responsable:'',
    telefono:'',
    cp:'',
    alias:'',
    notas:''
  };

  $scope.init = function() {
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idempresa = res.data.idempresa;
        $scope.idUsuario = res.data.idusuario;
        $scope.getDataInit();
        $scope.permisos();
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

  $scope.permisos = function(){
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

  $scope.openDivAgregar = function()
  {
    $scope.isDivSucActivo = true;
  }

  $scope.submitForm = function()
  {
    var dataSuc =
    {
      clave:$scope.suc.clave,
      direccion:$scope.suc.direccion,
      responsable:$scope.suc.responsable,
      telefono:$scope.suc.telefono,
      cp:$scope.suc.cp,
      alias:$scope.suc.alias,
      notas:$scope.suc.notas,
      idempresa:$scope.idempresa
    };

    if($scope.btnAccion == 'Agregar')
    {
      $http.post(pathSucr+'save',dataSuc).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          var dataSucAdd =
          {
            CLAVE:$scope.suc.clave,
            DIRECCION:$scope.suc.direccion,
            RESPONSABLE:$scope.suc.responsable,
            ALIAS:$scope.suc.alias,
            CP:$scope.suc.cp,
            ID_SUCURSAL:res.data[0].crea_sucursal
          };
          $scope.lstSucursal.push(dataSucAdd);
          $scope.selectRowSucursal($scope.lstSucursal[0].CLAVE,0,$scope.lstSucursal[0].ID_SUCURSAL);
          swal('La nueva sucursal ha sido almacenada');
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
            CLAVE:$scope.suc.clave,
            DIRECCION:$scope.suc.direccion,
            RESPONSABLE:$scope.suc.responsable,
            CP:$scope.suc.cp,
            ALIAS:$scope.suc.alias,
            ID_SUCURSAL:$scope.idSucursal
          };
          $scope.lstSucursal[$scope.idxRowSuc] = dataSucUpdt;
          swal('Se actualizó correctamente la sucursal');
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
        $scope.suc.clave = res.data[0].CLAVE.trim();
        $scope.suc.direccion = res.data[0].DIRECCION;
        $scope.suc.responsable = res.data[0].RESPONSABLE!=null?res.data[0].RESPONSABLE.trim():'';
        $scope.suc.telefono = res.data[0].TELEFONO!=null?res.data[0].TELEFONO:'';
        $scope.suc.cp = res.data[0].CP!=null?res.data[0].CP:'';
        $scope.suc.alias = res.data[0].ALIAS!=null?res.data[0].ALIAS.trim():'';
        $scope.suc.notas = res.data[0].NOTAS!=null?res.data[0].NOTAS:'';
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
    if($scope.lstSucursal.length===1){
      swal('No se pueden eliminar todas las sucursales, debe existir al menos una');
      return;
    }
    $http.delete(pathSucr+'delete/'+$scope.idSucursal).
    then(function(res)
    {
      if(res.data.value=='OK')
      {
        $scope.getDataInit();
          //$scope.lstSucursal.splice($scope.idxRowSuc,1);
          //$scope.selectRowSucursal($scope.lstSucursal[0].CLAVE,0,$scope.lstSucursal[0].ID_SUCURSAL);
        
        swal('Se ha eliminado correctamente la sucursal');
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
    $scope.suc.clave = '';
    $scope.suc.direccion = '';
    $scope.suc.responsable = '';
    $scope.suc.telefono = '';
    $scope.suc.cp = '';
    $scope.suc.alias = '';
    $scope.suc.notas = '';
  }
});
