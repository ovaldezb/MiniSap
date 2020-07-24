app.controller('myCtrlLinea', function ($scope, $http) {
    $scope.lstLinea = [];
    $scope.idSelLinea = 0;
    $scope.indexRowLinea = 0;
    $scope.msjBoton = "Agregar";
    $scope.isListaActivo = true;
    $scope.modalBorraLinea = false;
    $scope.lineaBorrar = "";
    $scope.idempresa = 0;        
    $scope.init = function () {                
        $http.get(pathAcc + 'getdata', { responseType: 'json' })
            .then(function (res) {
				if (res.data.value == 'OK') {
					$scope.idempresa = res.data.idempresa;
                    $scope.getListaLinea();	
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
                    alert('La línea se ha insertado');                    
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
                    alert("La línea se ha actualizado");                    
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
                alert("La línea ha sido eliminada");
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