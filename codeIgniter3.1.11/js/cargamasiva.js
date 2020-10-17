var pathCliente = '/core/';
var pathCargaMasiva = pathCliente + 'cargamasiva/';
var pathClte = pathCliente+'cliente/';
var app = angular.module("myApp", []);
app.controller('myCtrCargMasiva', function($scope,$http,$interval)
{
    $scope.file = {
        producto:'',
        cliente:'',
        proveedor:''
    };

    $scope.init = function(){
        console.log('Up & Running');
    }
});