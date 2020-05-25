

app.controller('myCtrlClientes', function($scope,$http)
{
  $scope.lstCliente = [];
  $scope.lstTipoClte = [];
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
  $scope.num_proveedor = '';
  $scope.notas = '';
  $scope.sortDir = false;
  $scope.idSelCompra = '';
  $scope.indexRowCliente = 0;
  $scope.idCliente = '';
  $scope.modalBorraClte = false;
  $scope.clteBorrar = '';
  $scope.isAddOpen = false;
  $scope.msjBoton = 'Agregar';

  $scope.init = function()
  {
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idempresa = res.data.idempresa;
        $scope.getDataInit();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getDataInit = function()
  {
    var valor=0;
    $http.get(pathClte+'loadByEmpresa/'+$scope.idempresa, { responseType: 'json'}).
    then(function(res)
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
    then(function(res)
    {
      $scope.clave = res.data[0].VALOR;
      $scope.claveTmp = res.data[0].VALOR;
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.validaCP = function()
  {
    if(isNaN($scope.cp))
    {
      alert('Sólo se permiten números');
      $('#cp').focus();
    }
  }

  /*$scope.orderByMe = function(x) {
      $scope.myOrderBy = x;
      $scope.sortDir = !$scope.sortDir;
  }*/

  $scope.selectRowCliente = function(idSelCompra,index,idCliente)
  {
    $scope.idSelCompra = idSelCompra;
    $scope.indexRowCliente = index;
    $scope.idCliente = idCliente;
  }

  $scope.agregaCliente = function()
  {
    $scope.isAddOpen = true;
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
      id_forma_pago:$('#id_forma_pago').val(),
      id_vendedor:$('#id_vendedor').val(),
      id_uso_cfdi:$('#id_uso_cfdi').val(),
      email:$scope.email,
      num_proveedor:$scope.num_proveedor,
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
            ID_CLIENTE:res.data[0].crea_cliente
          };
          $scope.lstCliente.push(row);
          $scope.cancelCliente();
          $scope.getNextDocClte();
          alert('El cliente se insertó correctamente');
        }
      }).catch(function(err) {
        console.log(err);
      });
    }else{
      $http.put(pathClte+'update/'+$scope.idCliente, dataClte).
      then(function(res)
    	{
    		if(res.status==200)
    		{
          row = {
            CLAVE:$scope.clave,
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            CURP:$scope.curp,
            ID_CLIENTE:$scope.idCliente
          };
          $scope.lstCliente[$scope.indexRowCliente] = row;
          $scope.msjBoton = 'Agregar';
    			$scope.cancelCliente();
          $scope.selectRowCliente($scope.lstCliente[$scope.indexRowCliente].CLAVE,$scope.indexRowCliente,$scope.lstCliente[$scope.indexRowCliente].ID_CLIENTE);
          alert(res.data.value);
    		}
    	}).catch(function(err)
    	{
    		console.log(err);
    	});
    }
  }

  $scope.borraCliente = function()
  {
    $http.delete(pathClte+'delete/'+$scope.idCliente).
  	then(function(res){
      console.log(res);
  		if(res.status==200)
  		{
  			if(res.data.value=='OK')
  			{
          $scope.lstCliente.splice($scope.indexRowCliente,1);
          $scope.selectRowCliente($scope.lstCliente[0].CLAVE,0,$scope.lstCliente[0].ID_CLIENTE);
  				alert('Cliente elimnado exitosamente');
          $scope.modalBorraClte = false;
  			}
  		}
  	}).catch(function(err){
  		console.log(err)
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
        $scope.num_proveedor = res.data[0].NUM_PROVEEDOR.trim();
        $scope.notas = res.data[0].NOTAS;
        $scope.dcredito = res.data[0].DIAS_CREDITO;
        $("#id_tipo_cliente").val(res.data[0].ID_TIPO_CLIENTE);
        $("#revision").val(res.data[0].ID_REVISION);
        $("#pagos").val(res.data[0].ID_PAGOS);
        $("#id_forma_pago").val(res.data[0].ID_FORMA_PAGO);
        $("#id_vendedor").val(res.data[0].ID_VENDEDOR);
        $("#id_uso_cfdi").val(res.data[0].ID_USO_CFDI);
      }
    }).catch(function(err)
    {
      console.log(err);
    });
    $scope.isAddOpen = true;
    $scope.msjBoton = 'Actualizar';
  }

  $scope.preguntaElimnaCliente = function()
  {
  	$scope.clteBorrar = $scope.lstCliente[$scope.indexRowCliente].NOMBRE;
    $scope.modalBorraClte = true;
  }

  $scope.cerrarBorraCliente = function()
  {
    $scope.clteBorrar = '';
    $scope.modalBorraClte = false;
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
  	$('#id_forma_pago').val('1');
  	$('#id_vendedor').val('1');
  	$('#id_uso_cfdi').val('1');
  	$scope.email = '';
  	$scope.num_proveedor = '';
  	$scope.notas = '';
    $scope.dcredito = 0;
  }

});
