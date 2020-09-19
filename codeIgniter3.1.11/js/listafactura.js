var pathCliente = '/core/';
var pathCreaFact = pathCliente + 'creacfdixml/';
var pathClte = pathCliente+'cliente/';
var app = angular.module("myApp", []);
app.controller('myCtrlLisfact', function($scope,$http)
{
    $scope.lstFacturas = [];
    $scope.lstCorreos = [];
    $scope.muestraLista = false;
    $scope.enviaremail = false;
    $scope.idFactura = '';
    $scope.idCliente = '';
    $scope.idEmpresa = '';
    $scope.nvoEmail ='';
    $scope.init = function() {
        
    };

    $scope.creaReporte = function(){
        /*Cambiar cuando se busque pór fechas*/
        $http.get(pathCreaFact+'getfacbydate/1/2',{ResponseType:'json'})
            .then(res=>{
                if(res.data.length > 0){
                    $scope.lstFacturas = res.data;
                    $scope.muestraLista = true;
                }
            })
            .catch(err=>{
                console.log(err);
            });
    }

    $scope.addEmail  = function(){
        $scope.lstCorreos.push({'EMAIL':$scope.nvoEmail});
        $scope.nvoEmail = '';
    }

    $scope.eliminarEmail = function(index){
        $scope.lstCorreos.splice(index,1)
    }

    $scope.mostrarEnviarEmail = function(flag,idFactura,idCliente,idEmpresa){
        $scope.idFactura = idFactura;
        $scope.idCliente = idCliente;
        $scope.idEmpresa = idEmpresa;
        $scope.enviaremail = flag;     
        $http.get(pathClte+'loadbyid/'+idCliente)
            .then(res =>{                     
                $scope.lstCorreos.push({'EMAIL':res.data[0].EMAIL.trim()});                                
            })
            .catch(err =>{
                console.log(err);
            });
    }

    $scope.enviaCorreo = function(idFactura,idCliente,idEmpresa){   
        
        $http.get(pathCreaFact+'getfacturaby/2/'+idFactura+'/'+idCliente+'/'+idEmpresa)
            .then(res=>{                
                if(res.data.value=="OK"){
                    swal('El correo fue enviado');
                }
            })
            .catch(err=>{
                console.log(err);
            });
    }
});