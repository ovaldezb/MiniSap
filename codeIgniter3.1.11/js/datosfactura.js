app.controller('myCtrlDatosFactura', function($scope,$http){
    $scope.lstSucCerts = [];
    $scope.idempresa = '';
    $scope.nombre = '';
    $scope.rfc = '';
    $scope.fec_ini = '';
    $scope.fec_fin = '';
    $scope.ou = '';
    $scope.formactive = false;
    $scope.rowMatriz = true;
    $scope.btnMtrix = 'Agregar';
    $scope.indexSelected = 0;
    $scope.idsucursal = '';
    $scope.factura = {
        cerFile:"",
        keyFile:"",
        pass:"",
        idsucursal:""        
    };
    $scope.init = function(){
        $http.get(pathAcc + 'getdata', { responseType: 'json' })
            .then(function (res) {
				if (res.data.value == 'OK') {
					$scope.idempresa = res.data.idempresa;					
                    $scope.getMatrixCert();   
                    $scope.getSucCerts(); 
				}
			}).catch(function (err) {
				console.log(err);
            });
    }

    $scope.getMatrixCert = function(){
        $http.get(pathCFDI+'getmatrixcert/'+$scope.idempresa)
            .then(res =>{
                if(res.data.length > 0){                    
                    $scope.nombre = res.data[0].NOMBRE;
                    $scope.rfc = res.data[0].RFC;
                    $scope.fec_ini = res.data[0].FECHA_INICIO;
                    $scope.fec_fin = res.data[0].FECHA_FIN;
                    $scope.ou = res.data[0].OU;
                    $scope.btnMtrix = 'Actualizar';
                }
            })
            .catch(err =>{
                console.log(err);
            });
    }

    $scope.getSucCerts = function(){
        $http.get(pathCFDI+'getsuccerts/'+$scope.idempresa,{responseType: 'json'})
            .then(res =>{
                if(res.data.length > 0){
                    $scope.lstSucCerts = res.data;
                    for(var i=0;i<$scope.lstSucCerts.length;i++){
                        $scope.lstSucCerts[i].BOTON = $scope.lstSucCerts[i].FECHA_INICIO == null ? 'Agregar' : 'Actualizar'; 
                        $scope.lstSucCerts[i].FORMA = true; 
                    }
                }
            })
            .catch(err =>{
                console.log(err);
            });
    }

    $scope.submit = function(){
        console.log($scope.factura);
        $http.post(pathCFDI+'save',$scope.factura)
            .then(res =>{
                if(res.status==200){
                    alert('Todo salio de maravilla');
                }
            })
            .catch(err =>{
                console.log(err);
            });
    }

    $scope.enviar = function(index, idSucursal){
        $scope.factura.idsucursal = idSucursal;
        $scope.indexSelected = index;
        $scope.formactive = true;
        if(index==-1){
            for(var i=0;i<$scope.lstSucCerts.length;i++){
                $scope.lstSucCerts[i].FORMA = false;
            }
        }else{            
            $scope.rowMatriz = false;
            for(var i=0;i<$scope.lstSucCerts.length;i++){
                if(i!=index){
                    $scope.lstSucCerts[i].FORMA = false;
                }                
            }
        }
    }

    $scope.cancelar = function(index){
        $scope.formactive = false;
        if($scope.indexSelected==-1){
            for(var i=0;i<$scope.lstSucCerts.length;i++){
                $scope.lstSucCerts[i].FORMA = true;
            }
        }else{            
            $scope.rowMatriz = true;
            for(var i=0;i<$scope.lstSucCerts.length;i++){
                if(i!=index){
                    $scope.lstSucCerts[i].FORMA = true;
                }                
            }
        }
    }

    
    
});
app.directive("selectNgFiles", function() {
    return {
      require: "ngModel",
      link: function postLink(scope,elem,attrs,ngModel) {
        elem.on("change", function(e) {
          var files = elem[0].files;
          ngModel.$setViewValue(files);
        })
      }
    }
  });
/*app.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                scope.$apply(function () {
                    //scope.fileread = changeEvent.target.files[0];
                    // or all selected files:
                    scope.fileread = changeEvent.target.files;
                });
            });
        }
    }
}]);*/

/*app.directive("fileread", [function () {
    return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                var reader = new FileReader();
                reader.onload = function (loadEvent) {
                    scope.$apply(function () {
                        scope.fileread = loadEvent.target.result;
                    });
                }
                reader.readAsDataURL(changeEvent.target.files[0]);
            });
        }
    }
}]);*/