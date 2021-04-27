app.controller('Reportecxc',($scope,$http) =>
{
  $scope.lstRepcxc = [];
  $scope.tiporeporte =1;
  $scope.showInputCliente = false;
  $scope.isRepShow = false;
  $scope.lstTipoRep = [{'valor':1,'label':'Todos'},{'valor':2,'label':'Cliente'}]
  $scope.init = () =>{
    var foopicker = new FooPicker({
    id: 'fechaInicio',
    dateFormat: 'dd/MM/yyyy'
    });
    var foopicker1 = new FooPicker({
    id: 'fechaFin',
    dateFormat: 'dd/MM/yyyy'
    });
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idempresa = res.data.idempresa;
        $scope.fiscalYear = res.data.aniofiscal;
        $scope.getDataEmpresa();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.selecTipoReporte = () =>{
    $scope.showInputCliente = $scope.tiporeporte===2;
  }

  $scope.getDataEmpresa = () =>
  {
    $http.get(pathEmpr+'loadbyid/'+$scope.idempresa,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0){
        $scope.nombreEmpresa = res.data[0].NOMBRE;
        $scope.direccionEmpresa = res.data[0].DOMICILIO;
        $scope.rfcEmpresa = res.data[0].RFC;
      }
    }).catch((err)=>{
      console.log(err);
    });
  }

  $scope.creaReporte=()=>{
    $scope.fechaImpresion = formatHoraReporte(new Date());
    $http.get(pathRepcxc+'getrepcxc/'+$scope.idempresa+'/'+$scope.fiscalYear)
    .then(res =>{
      $scope.lstRepcxc = res.data;
      $scope.isRepShow = true;
    })
    .catch(err=>{
      console.log(err);
    });
    
  }

  $scope.fecIniChange = function(){
    var e = jQuery.Event("keydown");
    e.which = 13; // # Some key code value
    $("#fechaInicio").trigger(e);
    $scope.fecIni = $('#fechaInicio').val();
    $scope.fechaInicio = formatFecQuery($('#fechaInicio').val(),'ini');
    $scope.fecIniRep = formatDateReporte($('#fechaInicio').val());
  }

  $scope.fecFinChange = function()
  {
    var e = jQuery.Event("keydown");
    e.which = 13; // # Some key code value
    $("#fechaFin").trigger(e);
    $scope.fecFin = $('#fechaFin').val();
    $scope.fechaFin = formatFecQuery($('#fechaFin').val(),'fin');
    $scope.FecFinRep = formatDateReporte($('#fechaFin').val());
  }

});