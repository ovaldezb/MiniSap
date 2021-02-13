var app = angular.module("myApp", ["ngRoute"]);
app.controller('myCtrlPagos', function($scope,$http)
{
    $scope.init = () =>{
        console.log("Iniciando...");
    };
}
);