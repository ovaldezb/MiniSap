app.controller('myCtrlLinea', function ($scope, $http, $routeParams) {
    $scope.lstLinea = [];
    $scope.idSelLinea = 0;
    $scope.indexRowLinea = 0;
    $scope.msjBoton = "Agregar";
    $scope.isListaActivo = true;
    $scope.modalBorraLinea = false;
    $scope.lineaBorrar = "";
    $scope.idempresa = 0; 
    $scope.idUsuario = '';
    $scope.idProceso = $routeParams.idproc;
    $scope.permisos = {
        alta: false,
        baja: false,
        modificacion:false,
        consulta:false
    };
    $scope.init = function () {                
        $http.get(pathAcc + 'getdata', { responseType: 'json' })
        .then(function (res) {
				  if (res.data.value == 'OK') {
            $scope.idempresa = res.data.idempresa;
            $scope.idUsuario = res.data.idusuario;
            $scope.getListaLinea();	
            $scope.permisos();
				  }
			}).catch(function (err) {
				console.log(err);
			});        
    }

    $scope.getListaLinea = function(){
        $http.get(pathLinea + $scope.idempresa, { responseType: 'json' })
        .then(res => {                
            if (res.data.length > 0) {                    
                $scope.lstLinea = res.data;
            }
        }).catch(err => {
            console.log(err)
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

    $scope.openDivAgregar = function () {
        $scope.isListaActivo = false;
    }

    $scope.cancelLinea = function () {
        $scope.msjBoton="Agregar";
        $scope.nombre = "";
        $scope.isListaActivo = true;
    }

    $scope.selectRowLinea = function (idSelLinea, indexRowLinea) {
        $scope.idSelLinea = idSelLinea;
        $scope.indexRowLinea = indexRowLinea;
    }

    $scope.addLinea = function(){
        if($scope.msjBoton=="Agregar"){
            $http.post(pathLinea,JSON.stringify({NOMBRE:$scope.nombre,ID_EMPRESA:$scope.idempresa}))
            .then(res => {                
                if(res.status = '200'){
                    swal('La línea se ha insertado');                    
                    $scope.cancelLinea();
                    $scope.getListaLinea();
                }
            })
            .catch(err => {
                console.log(err);
            });
        }else{
            $http.put(pathLinea+'/'+$scope.lstLinea[$scope.indexRowLinea].ID_LINEA,JSON.stringify({NOMBRE:$scope.nombre}))
            .then(res => {
                if(res.status == 200){
                    swal("La línea se ha actualizado");                    
                    $scope.cancelLinea();
                    $scope.getListaLinea();
                }
            })
            .catch(err => {
                console.log(err);
            });
        }
    }

    $scope.updateLinea = function(){

        $http.get(pathLinea + $scope.idempresa+'/'+$scope.lstLinea[$scope.indexRowLinea].ID_LINEA, { responseType: 'json' })
        .then(res => {                      
            if (res.data.length > 0) {                    
                $scope.nombre = res.data[0].NOMBRE;
                $scope.openDivAgregar();
                $scope.msjBoton = "Actualizar";
            }
        }).catch(err => {
            console.log(err)
        });        
    }

    $scope.borraLinea = function(){
        $http.delete(pathLinea +$scope.lstLinea[$scope.indexRowLinea].ID_LINEA)
        .then(res => {
            if(res.status == 200){
                swal("La línea ha sido eliminada");
                $scope.getListaLinea();
                $scope.cerrarBorraLinea();
            }
        })
        .catch(err => {
            console.log(err);
        });
    }

    $scope.preguntaEliminar = function(){
        $scope.lineaBorrar = $scope.lstLinea[$scope.indexRowLinea].NOMBRE;
        $scope.modalBorraLinea = true;
    }

    $scope.cerrarBorraLinea = function()
    {
        $scope.modalBorraLinea = false;
    }

});