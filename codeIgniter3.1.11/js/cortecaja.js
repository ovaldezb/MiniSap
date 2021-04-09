app.controller('myCtrlCortecaja',  function($scope, $http){
  var cmfd, cmld;
  $scope.meses = [
    {mes:'Enero',valor:0},
    {mes:'Febrero',valor:1},
    {mes:'Marzo',valor:2},
    {mes:'Abril',valor:3},
    {mes:'Mayo',valor:4},
    {mes:'Junio',valor:5},
    {mes:'Julio',valor:6},
    {mes:'Agosto',valor:7},
    {mes:'Septiembre',valor:8},
    {mes:'Octubre',valor:9},
    {mes:'Noviembre',valor:10},
    {mes:'Diciembre',valor:11}];
  $scope.mes = 0;
  $scope.init = () =>{
    let meshoy = new Date();
    $scope.mes = meshoy.getMonth();
    cmfd = new Date();
    cmfd.setMonth($scope.mes);
    cmfd.setDate(1);
    cmld = lastday(cmfd.getFullYear(), $scope.mes);
  }

  $scope.getreportebymes=()=>{
    $http.get(pathCorte+'reportemes/'+idempresa+'/'+aniofiscal+'/'+fechaini+'/'+fechafin).then().catch();
  }

  $scope.seleccionaMes = () =>{
    cmfd = new Date();
    cmfd.setMonth($scope.mes);
    cmfd.setDate(1);
    cmld = lastday(cmfd.getFullYear(), $scope.mes);
  }
});

var lastday = function(y,m){
  return  new Date(y, m +1, 0);
  }