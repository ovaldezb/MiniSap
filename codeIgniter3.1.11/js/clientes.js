app.controller('myCtrlClientes', function($scope,$http,$routeParams)
{
  $scope.lstCliente = [];
  $scope.lstTipoClte = [];
  $scope.lstFactDtl = [];
  $scope.lstVendedor = [];
  $scope.lstDomicilios = [];
  $scope.lstFormpago = [];
  $scope.lstUsoCFDI = [];
  $scope.totalImporFact = 0;
  $scope.totalImporCobr = 0;
  $scope.totalSaldoFact = 0;
  $scope.cliente = '';
  $scope.clave = '';
  $scope.claveTmp = '';
  $scope.nombre = '';
  $scope.domicilio = '';
  $scope.cp = '';
  $scope.telefono = '';
  $scope.contacto = '';
  $scope.rfc = '';
  $scope.curp = '';
  $scope.dcredito = 0;
  $scope.email = '';
  $scope.notas = '';
  $scope.sortDir = false;
  $scope.idSelCompra = '';
  $scope.indexRowCliente = 0;
  $scope.idCliente = -1;
  $scope.idVendedor = '-1';
  $scope.modalDetalleClte = false;
  $scope.clteBorrar = '';
  $scope.isAddOpen = false;
  $scope.idUsuario = '';
  $scope.msjBoton = 'Agregar';
  $scope.idProceso = $routeParams.idproc;
  $scope.aniofiscal = '';
  $scope.addDomEntrega = false;
  $scope.cltedsbl = true;
  $scope.idxRowDom = -1;
  $scope.id_forma_pago = '1';
  $scope.id_uso_cfdi = -1;
  $scope.isGuardado = false;
  $scope.btnClose = 'Cerrar';
  $scope.cfdi_style = {background:'pink'};
  $scope.vendedor_style = {background:'pink'};
  let indexRow = -1;
  $scope.btnName = 'Guardar';
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.domicilios = {
    ID_CLIENTE:'',
    LUGAR:'',
    CALLE:'',
    COLONIA:'',
    CIUDAD:'',
    LATITUD:'',
    LONGITUD:'',
    CONTACTO:''
  };

  $scope.init = function()
  {
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idempresa = res.data.idempresa;
        $scope.idUsuario = res.data.idusuario;
        $scope.aniofiscal = res.data.aniofiscal
        $scope.getDataInit();
        $scope.permisos();
        $scope.getvendedores();
      }
    }).catch(function(err){
      console.log(err);
    });
    $scope.getFormPago();
    $scope.getUsoCFDI();
  }

  $scope.getDataInit = function()
  {
    var valor=0;
    $http.get(pathClte+'loadByEmpresa/'+$scope.idempresa+'/'+$scope.aniofiscal, { responseType: 'json'}).
    then(res =>
    {
      if(res.data.length > 0)
      {
        valor = 1;
        $scope.lstCliente = res.data;
        $scope.selectRowCliente($scope.lstCliente[0].CLAVE,0,$scope.lstCliente[0].ID_CLIENTE);
      }else {
        $scope.lstCliente = [];
        valor = 0;
      }
      $scope.getNextDocClte();

    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.getNextDocClte = function()
  {
    $http.get(pathUtils+'incremento/CLTE/'+$scope.idempresa+'/4').
    then((res)=>
    {
      $scope.clave = res.data[0].VALOR;
      $scope.claveTmp = res.data[0].VALOR;
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.getvendedores = function(){
    $http.get(pathVend+'getvendedores/'+$scope.idempresa+'/vacio').
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.lstVendedor = res.data;
      }else {
        $scope.lstVendedor = [];
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.getUsoCFDI = () =>{
    $http.get(pathUtils+'getusocfdi')
    .then(res=>{
      $scope.lstUsoCFDI = res.data;
    }).catch(err=>{
      console.log(err);
    });
  }

  $scope.permisos = function(){
    $http.get(pathUsr+'permusrproc/'+$scope.idUsuario+'/'+$scope.idProceso)
    .then(res =>{
      $scope.permisos.alta = res.data[0].A == 't';
      $scope.permisos.baja = res.data[0].B == 't';
      $scope.permisos.modificacion = res.data[0].M == 't';
      $scope.permisos.consulta = res.data[0].C == 't';
    }).catch(err => {
      console.log(err);
    });
  }

  $scope.getFormPago = function(){
    $http.get(pathUtils+'getformpag',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstFormpago = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.validaCP = function()
  {
    if(isNaN($scope.cp))
    {
      swal('Sólo se permiten números');
      $('#cp').focus();
    }
  }

  $scope.getDomicilios = () =>{
    $http.get(pathClte+'getdomis/'+$scope.idCliente)
    .then(res =>{
      if(res.data.length>0){
        $scope.lstDomicilios = res.data;
      }
    })
    .catch(err =>{
      console.log(err);
    });
  }

  $scope.orderByMe = function(x) {
    $scope.myOrderBy = x;
    $scope.sortDir = !$scope.sortDir;
  }

  $scope.selectRowCliente = function(idSelCompra,index,idCliente)
  {
    $scope.idSelCompra = idSelCompra;
    $scope.indexRowCliente = index;
    $scope.idCliente = idCliente;
  }

  $scope.agregaCliente = function()
  {
    $scope.isAddOpen = true;
    $scope.msjBoton = "Agregar";
  }

  $scope.cancelCliente = function()
  {
    $scope.isAddOpen = false;
    $scope.clave = $scope.claveTmp;
    $scope.cleanup();
  }

  $scope.addCliente = function()
  {
    var  row, dataClte;
    if($scope.id_forma_pago===''){
      swal('Seleccione la forma de pago');
      return;
    }

    if($scope.idVendedor === '-1'){
      swal('Seleccione un Vendedor');
      return;
    }

    if($scope.id_uso_cfdi === -1){
      swal('Seleccione el uso del CFDI');
      return;
    }

    dataClte = {
      clave:$scope.clave,
      nombre:$scope.nombre,
      domicilio:$scope.domicilio,
      cp:$scope.cp,
      telefono:$scope.telefono,
      contacto:$scope.contacto,
      rfc:$scope.rfc,
      curp:$scope.curp,
      id_tipo_cliente:$('#id_tipo_cliente').val(),
      revision:$('#revision').val(),
      pagos:$('#pagos').val(),
      id_forma_pago:$scope.id_forma_pago,
      id_vendedor:$scope.idVendedor,
      id_uso_cfdi:$scope.id_uso_cfdi, 
      email:$scope.email,
      notas:$scope.notas,
      dcredito:$scope.dcredito,
      idempresa:$scope.idempresa
    };

    if($scope.msjBoton =='Agregar')
    {
      var nextId, idCliente, respuesta;
      $http.post(pathClte+'save', dataClte).
      then(function(res)
      {
        if(res.data.length > 0) {
          var row = {
            CLAVE:$scope.clave,
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            CURP:$scope.curp,
            ID_CLIENTE:res.data[0].crea_cliente,
            SALDO:0
          };
          
          if($scope.lstDomicilios.length > 0){
            $scope.lstDomicilios.forEach(elem =>{
              elem.ID_CLIENTE = res.data[0].crea_cliente
              $http.post(pathClte+'savedomi',elem)
              .then(resp =>{

              }).catch(err=>{
                console.log(err);
              });
            });
          }
          swal('El cliente se insertó correctamente');
          $scope.getDataInit();
          $scope.cancelCliente();
          $scope.getNextDocClte();
        }
      }).catch(function(err) {
        console.log(err);
      });

    }else{
      $http.put(pathClte+'updatecliente/'+$scope.idCliente, dataClte).
      then(function(res)
    	{
    		if(res.status==200){
          row = {
            CLAVE:$scope.clave,
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            CURP:$scope.curp,
            ID_CLIENTE:$scope.idCliente
          };
          swal('Cliente actualizado correctamente');
          $scope.lstCliente[$scope.indexRowCliente] = row;
          $scope.msjBoton = 'Agregar';
    			$scope.getDataInit();
          $scope.cancelCliente();
          //$scope.selectRowCliente($scope.lstCliente[$scope.indexRowCliente].CLAVE,$scope.indexRowCliente,$scope.lstCliente[$scope.indexRowCliente].ID_CLIENTE);
    		}
    	}).catch(function(err)
    	{
    		console.log(err);
    	});
    }
  }

  $scope.borraCliente = function()
  {
    if($scope.idCliente == -1){
      swal('Debe seleccionar un Cliente');
      return;
    }
    swal({
      title: "Esta seguro que desea eliminar el Cliente "+$scope.lstCliente[$scope.indexRowCliente].NOMBRE,
      text: "Una vez eliminado, no se podrá recuperar!",
      icon: "warning",
      buttons: [true,true],
      dangerMode: true,
    })
    .then((willDelete) => {
      if(willDelete){
        $http.delete(pathClte+'deleteclte/'+$scope.idCliente).
        then(function(res){
          if(res.status==200)
            {
              if(res.data.value=='OK'){
                $scope.lstCliente.splice($scope.indexRowCliente,1);
                $scope.selectRowCliente($scope.lstCliente[0].CLAVE,0,$scope.lstCliente[0].ID_CLIENTE);
                swal('Cliente elimnado exitosamente');
            }
          }
        }).catch(function(err){
            console.log(err)
        });
      }
    });
  }

  $scope.editaCliente = function()
  {
    $http.get(pathClte+'loadbyid/'+$scope.idCliente, {responseType: 'json'}).
    then(function(res)
    {
      if(res.status == 200)
      {
        $scope.clave = res.data[0].CLAVE.trim();
        $scope.nombre = res.data[0].NOMBRE;
        $scope.domicilio = res.data[0].DOMICILIO;
        $scope.cp = res.data[0].CP;
        $scope.telefono = res.data[0].TELEFONO.trim();
        $scope.contacto = res.data[0].CONTACTO;
        $scope.rfc = res.data[0].RFC.trim();
        $scope.curp = res.data[0].CURP.trim();
        $scope.cp = res.data[0].CP;
        $scope.email = res.data[0].EMAIL.trim();
        $scope.notas = res.data[0].NOTAS;
        $scope.dcredito = res.data[0].DIAS_CREDITO;
        $("#id_tipo_cliente").val(res.data[0].ID_TIPO_CLIENTE);
        $("#revision").val(res.data[0].ID_REVISION);
        $("#pagos").val(res.data[0].ID_PAGOS);
        $scope.id_forma_pago = res.data[0].ID_FORMA_PAGO.toString();
        $scope.idVendedor = res.data[0].ID_VENDEDOR.toString();
        $scope.id_uso_cfdi = res.data[0].ID_USO_CFDI;
      }
    }).catch(function(err)
    {
      console.log(err);
    });
    $scope.lstDomicilios = [];
    $scope.getDomicilios();
    $scope.isAddOpen = true;
    $scope.msjBoton = 'Actualizar';
  }

  $scope.muestraDetalle =()=>{
    $scope.modalDetalleClte = true;
    $scope.cliente = $scope.lstCliente[$scope.indexRowCliente].NOMBRE;
    $http.get(pathClte+'factcliente/'+$scope.idCliente+'/'+$scope.aniofiscal)
    .then(res=>{
      $scope.lstFactDtl = res.data;
      res.data.forEach(elem =>{
        $scope.totalImporFact += Number(elem.IMPORTE);
        $scope.totalImporCobr += Number(elem.COBRO);
      });
      $scope.totalSaldo = $scope.totalImporFact - $scope.totalImporCobr;
    })
    .catch(err=>{

    });
  }

  $scope.selectCFDI = ()=>{
    $scope.cfdi_style = {background:'lightgreen'};
  }

  $scope.selectVendedor = () =>{
    $scope.vendedor_style = {background:'lightgreen'};
  }

  $scope.selectRowDom = (index) =>{
    $scope.idxRowDom = index;
    $scope.domicilios = $scope.lstDomicilios[$scope.idxRowDom];
    $scope.cltedsbl = false;
    $scope.btnName = "Actualizar";
  }

  $scope.guardaDom =()=>{
    if($scope.btnName === 'Guardar'){
      $scope.lstDomicilios.push($scope.domicilios);
      if($scope.msjBoton !== 'Agregar'){
        $scope.domicilios.ID_CLIENTE = $scope.lstCliente[$scope.indexRowCliente].ID_CLIENTE;
        $http.post(pathClte+'savedomi',$scope.domicilios)
        .then(resp =>{
          
          })
        .catch(err=>{
          console.log(err);
        });
      }
      swal('El Domicilio se guardó con éxito!');
      //$scope.btnClose = 'Cerrar';
    }else{
      $http.put(pathClte+'updatedomi/'+$scope.lstDomicilios[$scope.idxRowDom].ID_DOMICILIO,$scope.domicilios)
      .then(resp =>{
        swal('El Domicilio se actualizó con exito!');
        })
      .catch(err=>{
        console.log(err);
      });
      $scope.lstDomicilios[$scope.idxRowDom] = $scope.domicilios;
      $scope.btnName = 'Guardar';
    }
    $scope.limpiaDom();
  }

  $scope.eliminaDom = ()=>{
    swal({
      title: "Esta seguro que desea eliminar el Domicilio ",
      text: "Una vez eliminado, no se podrá recuperar!",
      icon: "warning",
      buttons: [true,true],
      dangerMode: true,
    })
    .then((willDelete) => {
      if(willDelete){
        if($scope.lstDomicilios[$scope.idxRowDom].ID_DOMICILIO !== undefined){
          $http.delete(pathClte+'updatedomidel/'+$scope.lstDomicilios[$scope.idxRowDom].ID_DOMICILIO)
          .then(res =>{

          })
          .catch(err =>{
            console.log(err);
          });
        }
        $scope.lstDomicilios.splice($scope.idxRowDom,1);
        /*if($scope.lstDomicilios.length === 0){
          $scope.btnClose = 'Cancelar';
        }*/
        $scope.limpiaDom();
      }
    });
  }

  $scope.limpiaDom = ()=>{
    $scope.domicilios = {};
    $scope.cltedsbl = true;
    $scope.btnName = 'Guardar';
    $scope.idxRowDom = -1;
  }

  $scope.agregaDom = ()=>{
    $scope.limpiaDom();
    $scope.domicilios.LATITUD = null;
    $scope.domicilios.LONGITUD = null;
    $scope.domicilios.CONTACTO = '';
    $scope.cltedsbl = false;
  }

  $scope.agregaDomEntrega = () =>{
    $scope.addDomEntrega = true;
  }

  $scope.clseDomEntrega = () =>{
    /*if($scope.btnClose === 'Cancelar'){
      swal({
        title: "No ha almacenado ningún domicilio",
        text: "Desea salir?",
        icon: "warning",
        buttons: [true,true],
        dangerMode: true,
      })
      .then((willDelete) => {
        if(willDelete){
          swal('Se van a guardar '+$scope.lstDomicilios.length+' Domicilios!');
          $scope.limpiaDom();
          $scope.addDomEntrega = false;
        }else{
          return;
        }
      })
    }else{
      swal('Se van a guardar '+$scope.lstDomicilios.length+' Domicilios!');
      $scope.limpiaDom();
      $scope.addDomEntrega = false;
    }*/
    $scope.addDomEntrega = false;
  }

  $scope.cerrarBorraCliente = function()
  {
    $scope.modalDetalleClte = false;
    $scope.lstFactDtl = [];
    $scope.totalImporFact = 0;
    $scope.totalImporCobr = 0;
    $scope.totalSaldoFact = 0;
  }

  $scope.cleanup = function()
  {
  	$scope.nombre = '';
  	$scope.domicilio = '';
  	$scope.cp = '';
  	$scope.telefono = '';
  	$scope.contacto = '';
  	$scope.rfc = '';
  	$scope.curp = '';
  	$('#id_tipo_cliente').val('1');
  	$('#revision').val('1');
  	$('#pagos').val('1');
    $scope.id_forma_pago = '1';
  	$scope.idVendedor = '-1';
  	$scope.id_uso_cfdi = -1;
  	$scope.email = '';
  	$scope.notas = '';
    $scope.dcredito = 0;
    $scope.cfdi_style = {background:'pink'};
    $scope.vendedor_style = {background:'pink'};
    $scope.lstDomicilios = [];
  }

});
