app.controller('myCtrCargMasiva', function($scope,$http,$interval)
{
    $scope.file = {
        producto:'',
        cliente:'',
        proveedor:''
    };
    $scope.TotalRows = 0;
    $scope.init = function(){
        //console.log('Up & Running');
    }

    $scope.uploadProducto = function(){
        
        var config = { 
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity
        }
        var fd = new FormData();
        var files = document.getElementById('fileproducto').files[0];
        fd.append('file',files);
        $http.post(pathCargaMasiva+'cargaproducto',fd,config)
        .then(res => {
            swal("Se insertaron "+res.data.Total+" productos!","Felicidades","success");
            document.getElementById('fileproducto').value = '';
        }).catch(err => {
            console.log(err);
        });
    }

    $scope.uploadCliente = function(value){
        console.log('Carga de clientes');
        var config = { 
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity
        }
        var fd = new FormData();
        var fileClt = document.getElementById('filecliente').files[0];
        fd.append('file',fileClt);
        $http.post(pathCargaMasiva+'cargacliente',fd, config)
        .then(res => {
            console.log(res);
            swal("Se insertaron "+res.data.Total+" clientes","Felicidades","success");

        })
        .catch(err=>{
            console.log(err);
        });
    }

    $scope.uploadProveedor = function(value){
        var config = { 
            headers: {'Content-Type': undefined},
            transformRequest: angular.identity
        }
        var fd = new FormData();
        var filePRov = document.getElementById('fileproveedor').files[0];
        fd.append('file',filePRov);
        $http.post(pathCargaMasiva+'cargaproveedor',fd, config)
        .then(res => {
            swal("Se insertaron "+res.data.Total+" proveedores","Felicidades","success");
        })
        .catch(err=>{
            console.log(err);
        });
    }
});