app.controller('repControlMovAlm',function($scope,$http)
{
  $scope.isRepShow = false;
  $scope.idempresa = '';
  $scope.lstRepmalmcn = [];
  $scope.lstlinea = [];
  $scope.SumaTotales = {
    CANT_COMP:0,
    IMP_TOT_COMP:0,
    CANT_VENTA:0,
    IMPO_TOT_VTA:0,
    CANT_EXIST:0,
    IMPO_EXIST:0
  };
  $scope.nombreEmpresa = '';
  $scope.direccionEmpresa = '';
  $scope.rfcEmpresa = '';
  $scope.linea = 0;
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
        let row = {
          ID_LINEA:0,
          NOMBRE:"TODOS"
        };
        $scope.lstlinea.push(row);
        res.data.forEach(elem=>{
          $scope.lstlinea.push(elem);
        });
      }
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
    $http.get(pathRepo+'movalmacen/'+$scope.idempresa+'/'+$scope.fiscalYear+'/'+$scope.fechaInicio+'/'+$scope.fechaFin+'/'+$scope.linea,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0)
      {
        $scope.lstRepmalmcn = res.data;
        $scope.lstRepmalmcn.forEach(e=>{
          $scope.SumaTotales.CANT_COMP += e.CANT_COMP;
          $scope.SumaTotales.IMP_TOT_COMP += e.IMP_TOT_COMP;
          $scope.SumaTotales.CANT_VENTA += e.CANT_VENTA;
          $scope.SumaTotales.IMPO_TOT_VTA += e.IMPO_TOT_VTA;
          $scope.SumaTotales.CANT_EXIST += e.CANT_EXIST;
          $scope.SumaTotales.IMPO_EXIST =+ e.IMPO_EXIST;
        });
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
    saveAs(blob, "MovAlmacen_"+formatDateExcel(new Date())+".xls");
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
    let fontSizeHeader = 9;
    let fontSizeBody = 6;
    let maxSize1Page = 28;
    let fontSizeFooter = 7;
    let offset = 35;
    let finalOffset = 0;
    var doc = new jsPDF('p', 'pt', 'letter'); 
    var y = 20;  
    doc.setLineWidth(2);  
    doc.text(200, y = y + 30, "REPORTE DE MOVIMIENTO DE ALMACEN");
    doc.autoTable({
      html:'#rmaempresa',
      startY:60,
      fontStyle:'bold',
      columnStyles:{
        0:{
          cellWidth: 550,
          fontSize: fontSizeHeader,
          halign: 'center',
        }
      },
      styles: {  
        minCellHeight: 20 ,
        halign: 'center'
      }
    });
    doc.autoTable({
      html:'#rmaheader',
      startY:165, 
      theme:'grid',
      columnStyles:{
        0:{
          cellWidth: 120,
          fontSize: fontSizeHeader,
          halign: 'center',
          valign: 'middle',
          fontStyle:'bold'
        },
        1:{
          cellWidth: 90,
          fontSize: fontSizeHeader,
          halign: 'center',
          valign: 'middle',
          fontStyle:'bold'
        },
        2:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
        3:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
        4:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
        5:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
        6:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
        7:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
        8:{
          cellWidth: 50,
          fontSize: fontSizeHeader,
          halign: 'center',
          fontStyle:'bold'
        },
      }
    });
    let bodyRepMovAlm = [];
    let subArray = [];
    let ini = 0, fin=maxSize1Page;
    let i = 0;
    $scope.lstRepmalmcn.forEach(elem=>{
      let row = {
        DESCRIPCION:elem.DESCRIPCION+'-'+(i++),
        CODIGO:elem.CODIGO,
        CANT_COMP:elem.CANT_COMP,
        IMP_TOT_COMP:Number(elem.IMP_TOT_COMP).toFixed(2),
        CANT_VENTA:elem.CANT_VENTA,
        IMPO_TOT_VTA:Number(elem.IMPO_TOT_VTA).toFixed(2),
        CANT_EXIST:elem.CANT_EXIST,
        PRECIO_LISTA:'$'+Number(elem.PRECIO_LISTA).toFixed(2),
        IMPO_EXIST:'$'+Number(elem.IMPO_EXIST).toFixed(2)
      };
      bodyRepMovAlm.push(row);
    });
    if(bodyRepMovAlm.length <= maxSize1Page){
      subArray = bodyRepMovAlm.slice(0,bodyRepMovAlm.length);
    }else{
      subArray = bodyRepMovAlm.slice(ini,fin);
    }
    doc.autoTable({
      body:subArray,
      startY:205,
      theme:'grid',
      columnStyles:{
        0:{
          cellWidth: 120,
          fontSize: fontSizeBody,
          halign: 'center',
          valign: 'middle'
        },
        1:{
          cellWidth: 90,
          fontSize: fontSizeBody,
          halign: 'center',
          valign: 'middle'
        },
        2:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
        3:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
        4:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
        5:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
        6:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
        7:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
        8:{
          cellWidth: 50,
          fontSize: fontSizeBody,
          halign: 'center',
        },
      }
    });
    if(bodyRepMovAlm.length > maxSize1Page){
      ini = fin; 
      fin = ini + offset;
      while(fin <= bodyRepMovAlm.length){
        doc.addPage();
        subArray = bodyRepMovAlm.slice(ini,fin);
        doc.autoTable({
          html:'#rmaheader',
          startY:30, 
          theme:'grid',
          columnStyles:{
            0:{
              cellWidth: 120,
              fontSize: fontSizeHeader,
              halign: 'center',
              valign: 'middle',
              fontStyle:'bold'
            },
            1:{
              cellWidth: 90,
              fontSize: fontSizeHeader,
              halign: 'center',
              valign: 'middle',
              fontStyle:'bold'
            },
            2:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            3:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            4:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            5:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            6:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            7:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            8:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
          }
        });

        doc.autoTable({
          body:subArray,
          startY:70,
          theme:'grid',
          columnStyles:{
            0:{
              cellWidth: 120,
              fontSize: fontSizeBody,
              halign: 'center',
              valign: 'middle'
            },
            1:{
              cellWidth: 90,
              fontSize: fontSizeBody,
              halign: 'center',
              valign: 'middle'
            },
            2:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            3:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            4:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            5:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            6:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            7:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            8:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
          }
        });
        ini = fin; 
        fin = ini + offset;
      }

      if(fin > bodyRepMovAlm.length){
        doc.addPage();
        subArray = bodyRepMovAlm.slice(ini,bodyRepMovAlm.length);
        doc.autoTable({
          html:'#rmaheader',
          startY:30, 
          theme:'grid',
          columnStyles:{
            0:{
              cellWidth: 120,
              fontSize: fontSizeHeader,
              halign: 'center',
              valign: 'middle',
              fontStyle:'bold'
            },
            1:{
              cellWidth: 90,
              fontSize: fontSizeHeader,
              halign: 'center',
              valign: 'middle',
              fontStyle:'bold'
            },
            2:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            3:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            4:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            5:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            6:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            7:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
            8:{
              cellWidth: 50,
              fontSize: fontSizeHeader,
              halign: 'center',
              fontStyle:'bold'
            },
          }
        });

        doc.autoTable({
          body:subArray,
          startY:70,
          theme:'grid',
          columnStyles:{
            0:{
              cellWidth: 120,
              fontSize: fontSizeBody,
              halign: 'center',
              valign: 'middle'
            },
            1:{
              cellWidth: 90,
              fontSize: fontSizeBody,
              halign: 'center',
              valign: 'middle'
            },
            2:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            3:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            4:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            5:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            6:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            7:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
            8:{
              cellWidth: 50,
              fontSize: fontSizeBody,
              halign: 'center',
            },
          }
        });
      }
      finalOffset = 40 + (bodyRepMovAlm.length-ini) * 22.5;
    }else{
      finalOffset = 165 + (bodyRepMovAlm.length) * 22.5;
    }
    doc.autoTable({
      html:'#rmafooter',
      startY:finalOffset, 
      theme:'grid',
      columnStyles:{
        0:{
          cellWidth: 120,
          fontSize: fontSizeFooter,
          halign: 'center',
          valign: 'middle',
          fontStyle:'bold'
        },
        1:{
          cellWidth: 90,
          fontSize: fontSizeFooter,
          halign: 'center',
          valign: 'middle',
          fontStyle:'bold'
        },
        2:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
        3:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
        4:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
        5:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
        6:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
        7:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
        8:{
          cellWidth: 50,
          fontSize: fontSizeFooter,
          halign: 'center',
          fontStyle:'bold'
        },
      }
    });
    doc.save(`RepMovAlmacen_${new Date().toISOString()}.pdf`);   
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
        $scope.bycodigo = true;
        $scope.byname = false;
        $scope.tipoReporte = 'Por Código';
        break;
      case 3:
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
