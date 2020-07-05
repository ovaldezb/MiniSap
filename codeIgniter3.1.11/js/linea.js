app.controller('myCtrlLinea', function ($scope, $http) {
    $scope.lstLinea = [];
    $scope.idSelLinea = 0;
    $scope.indexRowLinea = 0;
    $scope.msjBoton = "Agregar";
    $scope.isListaActivo = true;
    $scope.modalBorraLinea = false;
    $scope.lineaBorrar = "";
    $scope.init = function () {        
        $scope.IdEmpresa = 1;        
        $scope.getListaLinea();
    }

    $scope.getListaLinea = function(){
        $http.get(pathLinea + $scope.IdEmpresa, { responseType: 'json' })
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
        $scope.isListaActivo = true;
    }

    $scope.selectRowLinea = function (idSelLinea, indexRowLinea) {
        $scope.idSelLinea = idSelLinea;
        $scope.indexRowLinea = indexRowLinea;
    }

    $scope.addLinea = function(){
        if($scope.msjBoton=="Agregar"){
            $http.post(pathLinea,JSON.stringify({NOMBRE:$scope.nombre,ID_EMPRESA:$scope.IdEmpresa}))
            .then(res => {                
                if(res.status = '200'){
                    alert('La línea se ha insertado');
                    $scope.nombre = "";
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
                    $scope.nombre = "";
                    $scope.msjBoton="Agregar"
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

        $http.get(pathLinea + $scope.IdEmpresa+'/'+$scope.lstLinea[$scope.indexRowLinea].ID_LINEA, { responseType: 'json' })
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