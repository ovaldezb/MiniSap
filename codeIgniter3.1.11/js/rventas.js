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
  $scope.total = {};
  $scope.tipoReporte = '';
  $scope.idTipoReporte = 0;
  $scope.codigo = '';
  $scope.nombre = '';
  $scope.filRep = -1;

  $scope.filtroReporte =[
    {value:1,label:'Todos'},
    {value:2,label:'Los 10 mas vendidos'},
    {value:3,label:'Por Código'},
    {value:4,label:'Por Nombre'}
  ];

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
    if($scope.filRep === 0){
      swal('Seleccione un tipo de reporte');
      return;
    }
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
        urlVentas = '/'+$scope.nombre+'/4';
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
          COSTO: 0,
          DESCRIPCION: 'TOTAL',
          ID_PRODUCTO: '',
          NETA: neta,
          PORCENTAJE: porcen,
          PRECIO_PROM: 0,
          UTILIDAD: 0
        }

        //$scope.lstRepmalmcn.push(totalRowIns);
        $scope.total = totalRowIns;
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

  $scope.selTipoRepo = function(){
    var i = $scope.filRep;
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
    var blob = new Blob([s2ab(document.getElementById('exportable').innerHTML)],
    {
      type:'application/vnd.ms-excel'
      
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
    var offset = 20;
    var fontSizeHeader = 8;
    var doc = new jsPDF('p', 'pt', 'letter'); 
    var y = 20;  
    doc.setLineWidth(2);  
    doc.text(200, y = y + 30, "REPORTE DE VENTAS");
    doc.autoTable({  
      html: '#rvempresa',  
      startY: 70,  
      theme: 'striped',  
      columnStyles: {  
          0: {cellWidth: 550,}  
      },  
      styles: {  
          minCellHeight: 20 ,
          halign: 'center'
      }  
    });
    doc.autoTable({  
      html: '#rvheader',  
      startY: 180,  
      theme: 'grid',  
      columnStyles: {  
          0: {cellWidth: 95,fontSize: fontSizeHeader},
          1: {cellWidth: 55,fontSize: fontSizeHeader},
          2: {  
            cellWidth: 55,  
            fontSize: fontSizeHeader
          },
          3: {  
            cellWidth: 45,  
            fontSize: fontSizeHeader
          },
          4: {  
            cellWidth: 45,  
            fontSize: fontSizeHeader
          },
          5: {  
            cellWidth: 65,  
            fontSize: fontSizeHeader
          },
          6: {  
            cellWidth: 45,  
            fontSize: fontSizeHeader
          },
          7: {  
            cellWidth: 50,  
            fontSize: fontSizeHeader
          },
          8: {  
            cellWidth: 45,  
            fontSize: fontSizeHeader
          },
          9: {  
            cellWidth: 50,  
            fontSize: fontSizeHeader
          }  
      },  
      styles: {  
          minCellHeight: 20 ,
          halign: 'center',
          fontStyle:'bold',
          fontSize: 8
      }  
    });
    let bodyRepVen = [];
    
    $scope.lstRepmalmcn.forEach(elem =>{
      let row = {
        DESCRIPCION:elem.DESCRIPCION,
        CODIGO:elem.CODIGO ,
        CANTIDAD:elem.CANTIDAD ,
        BRUTA:'$'+Number(elem.BRUTA).toFixed(2) ,
        NETA:'$'+Number(elem.NETA).toFixed(2) ,
        PRECIO_PROM:'$'+Number(elem.PRECIO_PROM).toFixed(2) ,
        VENTAS:Number(elem.PORCENTAJE).toFixed(2),
        COSTO: '$'+Number(elem.COSTO).toFixed(2),
        UTILIDAD:'$'+Number(elem.UTILIDAD).toFixed(2) ,
        MARGEN: Number(elem.UTILIDAD / elem.NETA * 100).toFixed(2)
      };
      bodyRepVen.push(row);
    });
    if(bodyRepVen.length <= 16){
      doc.autoTable({
        body:bodyRepVen,
        startY: 220,  
        theme: 'grid',
        columnStyles: {  
          0: {  
            cellWidth: 95,  
            fontSize: 6
          },
          1: {  
            cellWidth: 55, 
            fontSize: 6 
          },
          2: {  
            cellWidth: 55,  
            fontSize: 6,
            halign: 'center',
          },
          3: {  
            cellWidth: 45,  
            fontSize: 6,
          },
          4: {  
            cellWidth: 45,  
            fontSize: 6,
          },
          5: {  
            cellWidth: 65,  
            fontSize: 6,
            halign: 'center',
          },
          6: {  
            cellWidth: 45,  
            fontSize: 6,
            halign: 'center',
          },
          7: {  
            cellWidth: 50,  
            fontSize: 6,
            halign: 'right',
          },
          8: {  
            cellWidth: 45,  
            fontSize: 6,
            halign: 'right',
          },
          9: {  
            cellWidth: 50,  
            fontSize: 6,
            halign: 'right',
          }  
        },
        style:{
          fontSize: 6
        }
      });
    }else{
      let fontSize = 7;
      let ini=0,fin=18;
      let subArray = bodyRepVen.slice(ini,fin);
      doc.autoTable({
        body:subArray,
        startY: 220,  
        theme: 'grid',
        columnStyles: {  
          0: {  
            cellWidth: 95,  
            fontSize: fontSize
          },
          1: {  
            cellWidth: 55, 
            fontSize: fontSize 
          },
          2: {  
            cellWidth: 55,  
            fontSize: fontSize,
            halign: 'center',
          },
          3: {  
            cellWidth: 45,  
            fontSize: fontSize,
          },
          4: {  
            cellWidth: 45,  
            fontSize: fontSize,
          },
          5: {  
            cellWidth: 65,  
            fontSize: fontSize,
            halign: 'center',
          },
          6: {  
            cellWidth: 45,  
            fontSize: fontSize,
            halign: 'center',
          },
          7: {  
            cellWidth: 50,  
            fontSize: fontSize,
            halign: 'right',
          },
          8: {  
            cellWidth: 45,  
            fontSize: fontSize,
            halign: 'right',
          },
          9: {  
            cellWidth: 50,  
            fontSize: fontSize,
            halign: 'right',
          }  
        },
        style:{
          fontSize: fontSize
        }
      });
      ini = fin; 
      fin = ini + offset;
      
      while(fin<=bodyRepVen.length){
        doc.addPage();
        subArray = bodyRepVen.slice(ini,fin);
        doc.autoTable({  
          html: '#rvheader',  
          startY:40,
          theme: 'grid',  
          columnStyles: {  
              0: {  
                cellWidth: 95,  
                fontSize: fontSizeHeader
              },
              1: {  
                cellWidth: 55,  
                fontSize: fontSizeHeader
              },
              2: {  
                cellWidth: 55,  
                fontSize: fontSizeHeader
              },
              3: {  
                cellWidth: 45,  
                fontSize: fontSizeHeader
              },
              4: {  
                cellWidth: 45,  
                fontSize: fontSizeHeader
              },
              5: {  
                cellWidth: 65,  
                fontSize: fontSizeHeader
              },
              6: {  
                cellWidth: 45,  
                fontSize: fontSizeHeader
              },
              7: {  
                cellWidth: 50,  
                fontSize: fontSizeHeader
              },
              8: {  
                cellWidth: 45,  
                fontSize: fontSizeHeader
              },
              9: {  
                cellWidth: 50,  
                fontSize: fontSizeHeader
              }  
          },  
          styles: {  
              minCellHeight: 20 ,
              halign: 'center',
              fontStyle:'bold',
              fontSize: 8
          }  
        });

        doc.autoTable({
          body:subArray,
          startY:80,
          theme: 'grid',
          columnStyles: {  
            0: {  
              cellWidth: 95,  
              fontSize: fontSize
            },
            1: {  
              cellWidth: 55, 
              fontSize: fontSize
            },
            2: {  
              cellWidth: 55,  
              fontSize: fontSize,
              halign: 'center',
            },
            3: {  
              cellWidth: 45,  
              fontSize: fontSize,
            },
            4: {  
              cellWidth: 45,  
              fontSize: fontSize,
            },
            5: {  
              cellWidth: 65,  
              fontSize: fontSize,
              halign: 'center',
            },
            6: {  
              cellWidth: 45,  
              fontSize: fontSize,
              halign: 'center',
            },
            7: {  
              cellWidth: 50,  
              fontSize: fontSize,
              halign: 'right',
            },
            8: {  
              cellWidth: 45,  
              fontSize: fontSize,
              halign: 'right',
            },
            9: {  
              cellWidth: 50,  
              fontSize: fontSize,
              halign: 'right',
            }  
          },
          style:{
            fontSize: fontSize
          }
        });
        ini = fin; 
        fin = ini + offset;
      }
    }
    doc.save(`ReporteVentas_${new Date().toISOString()}.pdf`);   
    
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
