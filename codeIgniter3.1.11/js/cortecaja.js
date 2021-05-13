app.controller('myCtrlCortecaja',  function($scope, $http){
  var cmfd, cmld;
  $scope.lstCortesCaja = []
  $scope.lstVentas = [];
  $scope.indexRowCC = -1
  $scope.idxDetalleVenta = -1;
  $scope.fechaOperacion = '';
  $scope.usuarioVenta = '';
  $scope.usuarioCorte
  $scope.noFactura = '';
  $scope.dataIniDay = {
    docIni:'',
    docFin:'',
    horaIni:'',
    horaFin:''
  };
  $scope.pagos = {
    efectivo:'',
    tarjeta:'',
    cheque:'',
    vales:''
  }
  $scope.tipopago={
    contado:'',
    credito:''
  }
  
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
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(res =>{
      if(res.data.value=='OK'){
        $scope.idusuario = res.data.idusuario;
        $scope.idempresa = res.data.idempresa;
        $scope.idsucursal = res.data.idsucursal;
        $scope.aniofiscal = res.data.aniofiscal;
        $scope.getreportebymes(formatFecQuery(formatDatePrint(cmfd),'ini'),formatFecQuery(formatDatePrint(cmld),'fin'));
      }
    })
    .catch(err=>{
      console.log(err);
    });
  }

  $scope.getreportebymes=(fechaini,fechafin)=>{
    $http.get(pathCorte+'reportemes/'+$scope.idempresa+'/'+$scope.aniofiscal+'/'+fechaini+'/'+fechafin)
    .then(res=>{
      if(res.data){
        $scope.lstCortesCaja = res.data;
      }else{
        $scope.lstCortesCaja = [];
      }
    })
    .catch(err=>{
      console.log(err);
    });
  }

  $scope.seleccionaMes = () =>{
    $scope.lstDetalleVta = [];
    $scope.venta = {};
    $scope.lstVentas = [];
    $scope.fechaOperacion = '';
    cmfd = new Date();
    cmfd.setMonth($scope.mes);
    cmfd.setDate(1);
    cmld = lastday(cmfd.getFullYear(), $scope.mes);
    $scope.pagos.efectivo = 0;
    $scope.pagos.tarjeta = 0;
    $scope.pagos.cheque = 0;
    $scope.pagos.vales = 0;
    $scope.tipopago.contado = 0;
    $scope.tipopago.credito = 0;
    $scope.indexRowCC = -1;
    $scope.usuarioVenta = '';
    $scope.getreportebymes(formatFecQuery(formatDatePrint(cmfd),'ini'),formatFecQuery(formatDatePrint(cmld),'fin'));
  }

  $scope.selectRowCC = (index) =>{
    $scope.indexRowCC = index;
    let fecha = $scope.lstCortesCaja[$scope.indexRowCC].FECHA;
    $scope.fechaOperacion = fecha;
    $scope.descuento = 0;
    $scope.impuestos = 0;
    $scope.noFactura = $scope.lstCortesCaja[$scope.indexRowCC].DOCUMENTO == null ? 'ND' : $scope.lstCortesCaja[$scope.indexRowCC].DOCUMENTO;
    $scope.usuarioCorte = $scope.lstCortesCaja[$scope.indexRowCC].CLAVE_USR == null ? 'ND' : $scope.lstCortesCaja[$scope.indexRowCC].CLAVE_USR;
    $scope.lstDetalleVta =[];
    $scope.venta = {};
    $scope.abreOperaciones(formatFecQuery(fecha,'ini'),formatFecQuery(fecha,'fin'));
    $scope.getDocIniFinByDate(formatFecQuery(fecha,'ini'),formatFecQuery(fecha,'fin'));
  }

  $scope.abreOperaciones = (fecIni,fecFin)=>{
    $http.get(pathCorte+'getdataopercc/'+$scope.lstCortesCaja[$scope.indexRowCC].ID_CORTE)
    .then(res=>{
      $scope.lstVentas = res.data.ventas ? res.data.ventas : [];
      $scope.pagosco = res.data.pagos;
      $scope.pagos.efectivo = res.data.pagos.EFECTIVO;
      $scope.pagos.tarjeta = res.data.pagos.TARJETA;
      $scope.pagos.cheque = res.data.pagos.CHEQUE;
      $scope.pagos.vales = res.data.pagos.VALES;
      $scope.tipopago.contado = res.data.tipopago[0].SUMA;
      $scope.tipopago.credito = res.data.tipopago[1].SUMA;
      $scope.cancelados = res.data.cancelados.TOTAL;
    })
    .catch(err => {
      console.log(err);
    });
  }

  $scope.getDocIniFinByDate = (fecIni,fecFin) =>{
    $http.get(pathCorte+'getdocinifin/'+$scope.lstCortesCaja[$scope.indexRowCC].ID_CORTE)
    .then(res =>{
      if(res.data.length === 1){
        $scope.dataIniDay.docIni=res.data[0].DOCUMENTO;
        $scope.dataIniDay.docFin=res.data[0].DOCUMENTO;
        $scope.dataIniDay.horaIni=res.data[0].FECHA_VENTA
        $scope.dataIniDay.horaFin=res.data[0].FECHA_VENTA
      }else{
        $scope.dataIniDay.docIni=res.data[0].DOCUMENTO;
        $scope.dataIniDay.docFin=res.data[1].DOCUMENTO;
        $scope.dataIniDay.horaIni=res.data[0].FECHA_VENTA
        $scope.dataIniDay.horaFin=res.data[1].FECHA_VENTA
      }
    })
    .catch(err=>{
      console.log(err);
    });
  }

  $scope.selOperacion = (idOperacion, index)=>{
    $scope.idOpSel = idOperacion;
    $scope.idxOperacion = index;
    $scope.idxDetalleVenta = -1;
    $scope.usuarioVenta = $scope.lstVentas[index].CLAVE_USR === null ? 'ND' : $scope.lstVentas[index].CLAVE_USR;
    $scope.descuento = 0;
    $scope.impuestos = 0;
    $scope.getventabyid();
  }

  $scope.getventabyid = ()=>{
    $http.get(pathCorte+'getvendasbyid/'+$scope.idOpSel)
    .then(res =>{
      $scope.lstDetalleVta = res.data.detalle;
      $scope.venta = res.data.venta;
    })
    .catch(err=>{

    });
  }

  $scope.selDetalleVenta = (index) =>{
    $scope.idxDetalleVenta = index;
    $scope.descuento = $scope.lstDetalleVta[index].CANTIDAD * $scope.lstDetalleVta[index].PRECIO * $scope.lstDetalleVta[index].DESCUENTO / 100;
    $scope.impuestos = $scope.lstDetalleVta[index].CANTIDAD * $scope.lstDetalleVta[index].PRECIO * $scope.lstDetalleVta[index].IVA / 100;
  }

  $scope.imprimeCorteCaja = () =>{
    var resumem = document.getElementById("resumenoper");
    var ventimp = window.open(" ", "popimpr");
    ventimp.document.write(resumem.innerHTML);
    ventimp.document.close();
    ventimp.print();
    ventimp.close();
  }

});

var lastday = function(y,m){
  return  new Date(y, m +1, 0);
  }