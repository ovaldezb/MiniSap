
app.controller('myCtrlProveedor', function($scope,$http,$routeParams)
{
  $scope.lstProveedor = [];
  $scope.lstFactProv = [];
  $scope.proveedor = '';
  $scope.totalImporFact = 0;
  $scope.totalSaldoFact = 0;
  $scope.isDivProvActivo = false;
  $scope.modalDetalleProv = false;
  $scope.descProvBorrar = '';
  $scope.btnAccion = 'Agregar';
  $scope.clave = '';
  $scope.nombre = '';
  $scope.domicilio = '';
  $scope.cp = '';
  $scope.telefono = '';
  $scope.contacto = '';
  $scope.rfc = '';
  $scope.curp = '';
  $scope.dias_cred = '';
  $scope.cuenta = '';
  $scope.email = '';
  $scope.notas = '';
  $scope.idSelProv = '';
  $scope.indexRowProv = '';
  $scope.idProveedor = -1;
  $scope.sortDir = true;
  $scope.myOrderBy = '';
  $scope.idempresa = '';
  $scope.idUsuario = '';
  $scope.idProceso = $routeParams.idproc;
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };

  $scope.init = function()
  {
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idempresa = res.data.idempresa;
        $scope.idUsuario = res.data.idusuario;
        $scope.aniofiscal = res.data.aniofiscal;
        $scope.getDataInit();
        $scope.permisos();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getDataInit = function(){
    $http.get(pathPrve+'loadByEmpresa/'+$scope.idempresa+'/'+$scope.aniofiscal, {responseType: 'json'}).
    then(function(res)
  	{
      console.log(res.data);
  		if(res.data.length > 0)
  		{
  			$scope.lstProveedor = res.data;
        //$scope.selectRowProveedor($scope.lstProveedor[0].RFC,0,$scope.lstProveedor[0].ID_PROVEEDOR);
  		}else {
        $scope.lstProveedor = [];
      }
  	})
  	.catch(function(err) {
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
  
  $scope.selectRowProveedor = function(indexRowProv,idProveedor)
  {
    $scope.indexRowProv = indexRowProv;
    $scope.idProveedor = idProveedor;
  }

  $scope.openDivAgregar = function()
  {
    $scope.isDivProvActivo = true;
    $http.get(pathUtils+'incremento/PROV/'+$scope.idempresa+'/5').
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.clave = res.data[0].VALOR;
      }
    }).catch(function(err)
    {

    });
  }

  $scope.update = function()
  {
  	$http.get(pathPrve+'loadbyid/'+$scope.idProveedor, {responseType: 'json'}).
    then(function(res)
  	{
  		if(res.data.length > 0)
  		{
  			$scope.clave = res.data[0].CLAVE.trim();
  			$scope.nombre = res.data[0].NOMBRE.trim();
  			$scope.domicilio = res.data[0].DOMICILIO;
  			$scope.cp = res.data[0].CP;
  			$scope.telefono = res.data[0].TELEFONO;
  			$scope.contacto = res.data[0].CONTACTO;
  			$scope.rfc = res.data[0].RFC;
  			$scope.curp = res.data[0].CURP;
  			$('#id_tipo_prov').val(res.data[0].ID_CATEGORIA_PROV);
  			$scope.dias_cred = Number(res.data[0].DIAS_CRED);
  			$('#id_tipo_alc_prov').val(res.data[0].ID_TIPO_ALC_PROV);
  			$('#banco').val(res.data[0].ID_BANCO);
  			$scope.cuenta = Number(res.data[0].CUENTA);
  			$scope.email = res.data[0].EMAIL;
  			$scope.notas = res.data[0].NOTAS;
        $scope.isDivProvActivo = true;
        $scope.btnAccion = 'Actualizar';
  		}
  	}).catch(function(err)
  	{
  		console.log(err);
  	});
  }

  $scope.submitForm = function()
  {
    var dataProveedor =
    {
      clave:$scope.clave,
  		nombre:$scope.nombre,
  		domicilio:$scope.domicilio,
  		cp:$scope.cp,
  		telefono:$scope.telefono,
  		contacto:$scope.contacto,
  		rfc:$scope.rfc,
  		curp:$scope.curp,
  		id_tipo_prov:$('#id_tipo_prov').val(),
  		dias_cred:$scope.dias_cred,
  		id_tipo_alc_prov:$('#id_tipo_alc_prov').val(),
  		banco:$('#banco').val(),
  		cuenta:$scope.cuenta,
  		email:$scope.email,
  		notas:$scope.notas,
      idempresa:$scope.idempresa
    };

    if($scope.btnAccion == 'Agregar')
    {
      $http.post(pathPrve+'save', dataProveedor).
      then(function(res)
      {
    		if(res.status==200) {
          var dataRowProvA =
          {
            CLAVE:$scope.clave,
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            ID_PROVEEDOR:res.data[0].crea_proveedor
          };
          $scope.lstProveedor.push(dataRowProvA);
    			swal('El nuevo proveedor ha sido almacenado');
    			$scope.cancelar();
    		}
    	}).catch(function(err) {
    		console.log(err);
    	});

    }else {
      $http.put(pathPrve+'update/'+$scope.idProveedor,dataProveedor).
      then(function(res)
    	{
    		if(res.status==200 && res.data.value=='OK')
    		{
          $scope.selectRowProveedor($scope.lstProveedor[$scope.indexRowProv].RFC,$scope.indexRowProv,$scope.lstProveedor[$scope.indexRowProv].ID_PROVEEDOR);
    			swal('El proveedor se actualizó correctamente');
    		}else
    		{
    			swal('Error,  no se puedo actualizar el proveedor');
    		}
    		$scope.cancelar();
    	}).catch(function(err)
    	{
    		console.log(err);
    	});
    }
  }

  $scope.muestraDetalle =()=>{
    $scope.modalDetalleProv = true;
    $scope.proveedor = $scope.lstProveedor[$scope.indexRowProv].NOMBRE;
    $http.get(pathPrve+'comprasprov/'+$scope.idProveedor+'/'+$scope.aniofiscal)
    .then(res=>{
      $scope.lstFactProv = res.data;
      res.data.forEach(elem =>{
        $scope.totalImporFact += elem.IMPORTE;
        $scope.totalSaldoFact += elem.SALDO;
      });
    }).catch();
  }

  $scope.eliminar = function()
  {
    if($scope.idProveedor == -1){
      swal('Debe seleccionar un Proveedor');
      return;
    }
    swal({
      title: "Esta seguro que desea eliminar el Proveedor "+$scope.lstProveedor[$scope.indexRowProv].NOMBRE,
      text: "Una vez eliminado, no se podrá recuperar!",
      icon: "warning",
      buttons: [true,true],
      dangerMode: true,
    })
    .then((willDelete) => {
      if(willDelete){
        $http.delete(pathPrve+'delete/'+$scope.idProveedor).
        then(function(res){
          if(res.status==200 && res.data.value=='OK')
          {
            $scope.lstProveedor.splice($scope.indexRowProv,1);
            $scope.selectRowProveedor($scope.lstProveedor[0].RFC,0,$scope.lstProveedor[0].ID_PROVEEDOR);
            $scope.closeAvisoBorrar();
            swal('Cliente elimnado exitosamente');
          }
        }).catch(function(err){
          console.log(err)
        })
      }
    });
  }

  $scope.orderByMe = function(valor)
  {
    $scope.myOrderBy = valor;
    $scope.sortDir = !$scope.sortDir;
  }

  $scope.closeAvisoBorrar = function()
  {
    $scope.modalDetalleProv = false;
    $scope.lstFactProv = [];
    $scope.totalImporFact = 0;
    $scope.totalSaldoFact = 0;
  }

  $scope.cancelar = function()
  {
    $scope.clave = '';
    $scope.nombre = '';
    $scope.domicilio = '';
    $scope.cp = '';
    $scope.telefono = '';
    $scope.contacto = '';
    $scope.rfc = '';
    $scope.curp = '';
    $('#id_tipo_prov').val(1);
    $scope.dias_cred = '';
    $('#id_tipo_alc_prov').val(1);
    $('#banco').val(1);
    $scope.cuenta = '';
    $scope.email = '';
    $scope.notas = '';
    $scope.isDivProvActivo = false;
    $scope.btnAccion = 'Agregar';
  }

});
