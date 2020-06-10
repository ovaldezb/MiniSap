app.controller('repControlMovAlm',function($scope,$http)
{
  $scope.isRepShow = false;
  $scope.idempresa = '';
  $scope.lstRepmalmcn = [];
  $scope.nombreEmpresa = '';
  $scope.direccionEmpresa = '';
  $scope.rfcEmpresa = '';
  $scope.sortDir = false;
  $scope.init = function()
  {
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
        $scope.getDataLinea();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getDataEmpresa = function()
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

  $scope.getDataLinea = function()
  {
    $http.get(pathUtils+'lineaempr/'+$scope.idempresa,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0){
        $scope.lstlinea = res.data;
        $scope.linea = res.data[0].ID_LINEA;
      }
    }).catch((err)=>{
      console.log(err);
    });
  }

  $scope.creaReporte = function()
  {
    $http.get(pathRepo+'movalmacen/'+$scope.idempresa+'/'+$scope.fiscalYear+'/'+$scope.fechaInicio+'/'+$scope.fechaFin+'/'+$scope.linea,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0)
      {
        $scope.lstRepmalmcn = res.data;
        $scope.isRepShow = true;
        $scope.fechaImpresion = formatHoraReporte(new Date());
      }else{
        $scope.lstRepmalmcn = [];
        alert('No se encuentran datos para los parámetros elegidos');
      }
    }).
    catch((err)=>{
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

  $scope.exportExcel = function()
  {
    console.log('Reporte Excel');
    var blob = new Blob([document.getElementById('exportable').innerHTML],
    {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
    });
    saveAs(blob, "Report_Example.xlsx");
  }

  $scope.exportCSV = function()
  {
    var data ='';
    for(var i=0;i<$scope.lstRepmalmcn.length;i++)
    {
      data += '\"'+$scope.lstRepmalmcn[i].DESCRIPCION+'\",'+$scope.lstRepmalmcn[i].CODIGO+','+$scope.lstRepmalmcn[i].CANT_COMP+','+$scope.lstRepmalmcn[i].IMP_TOT_COMP+'\n';
    }
    var blob = new Blob([data],
    {
      type: "text/csv;charset=utf-8"
    });
    saveAs(blob, "Report_Example.csv");
  }

  $scope.exportPDF = function()
  {
    console.log('Reporte PDF');
    /*var blob = new Blob([document.getElementById('exportable').innerHTML],
    {
      type: "application/pdf;base64"
    });*/
    window.html2canvas = html2canvas;
    var doc = new jsPDF()
    doc.html(document.getElementById('exportable').innerHTML, {
     callback: function (doc) {
       doc.save();
     }
   });
  }

  $scope.orderByMe = function(x)
  {
    $scope.myOrderBy = x;
    $scope.sortDir = !$scope.sortDir;
  }

  $scope.cerrarReporte = function()
  {
    $scope.isRepShow = false;
  }
});
