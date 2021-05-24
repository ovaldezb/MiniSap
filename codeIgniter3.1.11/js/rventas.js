app.controller('repControlVentas',function($scope,$http)
{
  $scope.isRepShow = false;
  $scope.idempresa = '';
  $scope.lstRepmalmcn = [];
  $scope.nombreEmpresa = '';
  $scope.direccionEmpresa = '';
  $scope.rfcEmpresa = '';
  $scope.tiporeporte = 1;
  $scope.sortDir = false;
  $scope.bycodigo = false;
  $scope.byname = false;
  $scope.menushow =false;
  $scope.tipoReporte = '';
  $scope.idTipoReporte = 0;
  $scope.codigo = '';
  $scope.nombre = '';
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
    $http.get(pathLinea + $scope.idempresa,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0){
        $scope.lstlinea = res.data;
      }
      $scope.lstlinea.unshift({'ID_LINEA':0,'NOMBRE':'TODOS'});
      $scope.linea = 0;
    }).catch((err)=>{
      console.log(err);
    });
  }

  $scope.creaReporte = function()
  {
    var urlVentas = '';
    switch($scope.idTipoReporte){
      case 1:
        urlVentas = '/nada/1';
        break;
      case 2:
        urlVentas = '/nada/2';
        break;
      case 3:
        urlVentas = '/'+$scope.codigo+'/3';
        break;
      case 4:
        urlVentas = '/nada/4';
        break;
    }
    $http.get(pathRepo+'ventas/'+$scope.idempresa+'/'+$scope.fiscalYear+'/'+$scope.fechaInicio+'/'+$scope.fechaFin+'/'+$scope.linea+urlVentas,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0)
      {
        var bruta=0;
        var neta=0;
        var porcen=0;
        $scope.lstRepmalmcn = res.data;
        $scope.lstRepmalmcn.forEach(row => {          
            bruta += row.BRUTA;
            neta += row.NETA;
            porcen += row.PORCENTAJE;
        });
        var totalRowIns={
          BRUTA:  bruta,
          CANTIDAD:'',
          CODIGO: '',
          COSTO: '',
          DESCRIPCION: 'Total',
          ID_PRODUCTO: '',
          NETA: neta,
          PORCENTAJE: porcen,
          PRECIO_PROM: '',
          UTILIDAD: ''
        }

        $scope.lstRepmalmcn.push(totalRowIns);
        $scope.isRepShow = true;
        $scope.fechaImpresion = formatHoraReporte(new Date());
      }else{
        $scope.lstRepmalmcn = [];
        swal('No se encuentran datos para los parámetros elegidos');
      }
    }).
    catch((err)=>{
      console.log(err);
    });
  }

  $scope.selTipoRepo = function(i){
    $scope.idTipoReporte = i;
    $scope.bycodigo = false;
    $scope.byname = false;
    switch(i){
      case 1:
        $scope.tipoReporte = 'Todos';
        break;
      case 2:
        $scope.tipoReporte = 'Los 10 más vendidos';
        break;
      case 3:
        $scope.bycodigo = true;
        $scope.byname = false;
        $scope.tipoReporte = 'Por Código';
        break;
      case 4:
        $scope.bycodigo = false;
        $scope.byname = true;
        $scope.tipoReporte = 'Por Nombre';
        break;
    }
    $scope.menushow = !$scope.menushow;
  }

  $scope.showMenu = function(){
    $scope.menushow = !$scope.menushow;
  }

  $scope.selectTipoRepo = function(i){
    $scope.tiporeporte = i;
    $scope.menushow = false;
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
    var blob = new Blob([document.getElementById('exportable').innerHTML],
    {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
    });
    saveAs(blob, "Ventas_"+formatDateExcel(new Date())+".xls");
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
