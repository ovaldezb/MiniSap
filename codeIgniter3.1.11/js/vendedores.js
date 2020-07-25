app.controller('myCtrlVendedores', function($scope,$http)
{
  $scope.lstVendedor = [];  
  $scope.nombre = '';
  $scope.sortDir = false;
  $scope.indexRowVendedor = 0;
  $scope.idVendedor = '';
  $scope.modalBorraVend = false;
  $scope.vendBorrar = '';
  $scope.isAddOpen = false;
  $scope.msjBoton = 'Agregar';

  $scope.init = function(){
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
    $http.get(pathVend+'loadByEmpresa/'+$scope.idempresa, { responseType: 'json'}).
    then(function(res){
      if(res.data.length > 0){
        $scope.lstVendedor = res.data;
        $scope.selectRowVendedor(0,$scope.lstVendedor[0].ID_VENDEDOR);
      }
      else{
        $scope.lstVendedor = [];
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.selectRowVendedor = function(index,idVendedor){
    $scope.indexRowVendedor = index;
    $scope.idVendedor = idVendedor;
  }

  $scope.agregaVendedor = function(){
    $scope.isAddOpen = true;
  }

  $scope.cancelVendedor = function(){
    $scope.isAddOpen = false;
    $scope.cleanup();
  }

  $scope.addVendedor = function(){
    var  row, dataVend;
    dataVendedor = {
      nombre:$scope.nombre,      
      id_area:$('#id_area').val(),
      id_puesto:$('#id_puesto').val(),
      id_titulo:$('#id_titulo').val(),
      idempresa:$scope.idempresa
    };

    if($scope.msjBoton =='Agregar'){
      var nextId, idCliente, respuesta;
      $http.post(pathVend+'save', dataVend).
        then(function(res){
          if(res.data.length > 0) {
            var row = {
              NOMBRE:$scope.nombre,
              ID_VENDEDOR:res.data[0].crea_vendedor
            };
            $scope.lstVendedor.push(row);
            $scope.cancelVendedor();
            alert('El vendedor se insertó correctamente');
          }
        }).catch(function(err) {
          console.log(err);
      });
    }
    else{
      $http.put(pathVend+'update/'+$scope.idVendedor, dataVend).
      then(function(res){
        if(res.status==200){
          row = {
            NOMBRE:$scope.nombre,
            ID_CLIENTE:$scope.idVendedor
          };
          $scope.lstVendedor[$scope.indexRowVendedor] = row;
          $scope.msjBoton = 'Agregar';
          $scope.cancelVendedor();
          $scope.selectRowVendedor($scope.indexRowVendedor,$scope.lstVendedor[$scope.indexRowVendedor].ID_VENDEDOR);
          alert(res.data.value);
        }
      }).catch(function(err){
        console.log(err);
      });
    }
  }

  $scope.borraVendedor = function(){
    $http.delete(pathVend+'delete/'+$scope.idVendedor).
      then(function(res){
        console.log(res);
        if(res.status==200){
          if(res.data.value=='OK'){
            $scope.lstVendedor.splice($scope.indexRowVendedor,1);
            $scope.selectRowVendedor(0,$scope.lstCliente[0].ID_VENDEDOR);
            alert('Vendedor eliminado exitosamente');
            $scope.modalBorraClte = false;
          }
        }
      }).catch(function(err){
        console.log(err)
      });
  }

  $scope.editaVendedor = function(){
    $http.get(pathVend+'loadbyid/'+$scope.idVendedor, {responseType: 'json'}).
      then(function(res){
        if(res.status == 200){
          $scope.nombre = res.data[0].NOMBRE;
          $("#id_area").val(res.data[0].ID_AREA);
          $("#id_puesto").val(res.data[0].ID_PUESTO);
          $("#id_titulo").val(res.data[0].ID_TITULO);
        }
      }).catch(function(err){
        console.log(err);
      });
    $scope.isAddOpen = true;
    $scope.msjBoton = 'Actualizar';
  }

  $scope.preguntaElimnaVendedor = function(){
    $scope.vendBorrar = $scope.lstVendedor[$scope.indexRowVendedor].NOMBRE;
    $scope.modalBorraVend = true;
  }

  $scope.cerrarBorraVendedor = function(){
    $scope.vendBorrar = '';
    $scope.modalBorraVend = false;
  }

  $scope.cleanup = function(){
    $scope.nombre = '';
    $('#id_area').val('1');
    $('#id_puesto').val('1');
    $('#id_titulo').val('1');
  }

});
