app.controller('CtrlCobranza', ($scope,$http,$routeParams) =>
{
  $scope.lstRepCobr = [];
  $scope.lstRepCobrIni = []; 
  $scope.lstFormpago = [];
  listaFormaPago = [];
  listaBancoPago = [];
  $scope.isRepShow = false;
  $scope.idempresa = '';
  $scope.fiscalYear = '';
  let currentDate = '';
  var fpHash = new Map();
  var bcHash = new Map();
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
    $scope.getFormPago();
    $scope.getbancos();
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

  $scope.getFormPago = function(){
    $http.get(pathUtils+'getformpag',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        listaFormaPago = res.data;
        res.data.forEach((elem) =>{
          fpHash.set(elem.ID_FORMA_PAGO,0);
        });
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.getbancos = function(){
    $http.get(pathUtils+'getbancos',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        listaBancoPago = res.data;
        res.data.forEach((elem) =>{
          bcHash.set(elem.ID_BANCO,0);
        });
      }
    }).catch(err =>	{
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

  $scope.creaReporte = () =>{
    let lastRow = false;
    let sigma = 0;
    let sigmaTotal = 0;
    let counter = 0;
    let counterTotal = 0;
    $http.get(pathRepcobr+'repcobranza/'+$scope.fiscalYear+'/'+$scope.idempresa+'/'+$scope.fechaInicio+'/'+$scope.fechaFin)
    .then(res =>{
      $scope.isRepShow = true;
      lstRepCobrIni = res.data;
      for(var i=0;i<lstRepCobrIni.length;i++){
        if(currentDate != lstRepCobrIni[i].FECHA_COBRO){
          if(lastRow){
            let row = JSON.parse(JSON.stringify(lstRepCobrIni[i]));
            row.TITLE = 2;
            row.DOCTO = counter;
            row.ABONO = sigma;
            $scope.lstRepCobr.push(row);  
            counter = 0;
            sigma = 0;
          }
          let tmpRow = JSON.parse(JSON.stringify(lstRepCobrIni[i]));
          tmpRow.TITLE = 1;
          lstRepCobrIni[i].TITLE = 0;
          lstRepCobrIni[i].NOMBRE = 'Movimiento del '+lstRepCobrIni[i].FECHA_COBRO;
          $scope.lstRepCobr.push(lstRepCobrIni[i]);
          $scope.lstRepCobr.push(tmpRow);
          lastRow = true;
        }else{
          lstRepCobrIni[i].TITLE = 1;
          $scope.lstRepCobr.push(lstRepCobrIni[i]);
        }
        sigma += Number(lstRepCobrIni[i].ABONO);
        sigmaTotal += Number(lstRepCobrIni[i].ABONO);
        currentDate = lstRepCobrIni[i].FECHA_COBRO;
        counter++;
        counterTotal++;
        fpHash.set(lstRepCobrIni[i].ID_FP,fpHash.get(lstRepCobrIni[i].ID_FP)+Number(lstRepCobrIni[i].ABONO));
        
      }
      let row = JSON.parse(JSON.stringify(lstRepCobrIni[lstRepCobrIni.length-1]));
      row.TITLE = 2;
      row.DOCTO = counter;
      row.ABONO = sigma;
      let rowFinal = JSON.parse(JSON.stringify(lstRepCobrIni[lstRepCobrIni.length-1]));
      rowFinal.TITLE = 3;
      rowFinal.DOCTO = counterTotal;
      rowFinal.ABONO = sigmaTotal;
      $scope.lstRepCobr.push(row);  
      $scope.lstRepCobr.push(rowFinal);  
      listaFormaPago.forEach(elem =>{
        if(fpHash.get(elem.ID_FORMA_PAGO) > 0){
          elem.QTY = fpHash.get(elem.ID_FORMA_PAGO);
          $scope.lstFormpago.push(elem);
        }
      });
    })
    .catch(err =>{
      console.log(err);
    });
  }

  
  $scope.exportAction = () =>{
    var blob = new Blob([document.getElementById('exportable').innerHTML], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8"
    });
    saveAs(blob, "Cobranza_"+formatDateExcel(new Date())+".xls");
  }

  $scope.exportPDF = () =>{
    var rowsDetail = $scope.lstRepCobr.length * 26;
    var doc = new jsPDF('p', 'pt', 'letter'); 
    var y = 20;  
    doc.setLineWidth(2);  
    doc.text(200, y = y + 30, "REPORTE DE COBRANZA");  
    doc.autoTable({  
        html: '#tblRepCobranza',  
        startY: 70,  
        theme: 'grid',  
        columnStyles: {  
            0: {  
                cellWidth: 150,  
            },  
            1: {  
                cellWidth: 100,  
            },  
            2: {  
                cellWidth: 100,  
            },  
            3: {  
                cellWidth: 100,  
            },  
            4: {  
                cellWidth: 100,  
            }  
        },  
        styles: {  
            minCellHeight: 20  
        }  
    });
    doc.autoTable({  
      html: '#tblRepCobranzaDetail',  
      startY: 90,  
      theme: 'grid',  
      columnStyles: {  
          0: {  
              cellWidth: 100,  
          },  
          1: {  
              cellWidth: 150,  
          },  
          2: {  
              cellWidth: 100,  
          },  
          3: {  
              cellWidth: 100,  
          },  
          4: {  
              cellWidth: 100,  
          }  
      },  
      styles: {  
          minCellHeight: 20  
      }  
    });
    
    doc.autoTable({  
      html: '#tblCobranzaResumen',  
      startY: rowsDetail,  
      theme: 'grid',  
      columnStyles: {  
          0: {  
              cellWidth: 150,  
          },  
          1: {  
              cellWidth: 150,  
          },  
          2: {  
              cellWidth: 150,  
          },  
          3: {  
              cellWidth: 100,  
          }  
      },  
      styles: {  
          minCellHeight: 20  
      }  
    });  
    doc.save(`${new Date().toISOString()}_cobranza.pdf`); 
  }
  
  $scope.cerrarReporte = () =>{
    $scope.isRepShow = false;
    $scope.lstRepCobr = [];
    $scope.lstFormpago = [];
    listaFormaPago.forEach((elem) =>{
      fpHash.set(elem.ID_FORMA_PAGO,0);
    });
  }
});