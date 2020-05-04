var path = '/codeigniter3.1.11/proveedor/';
var path1 = '/codeigniter3.1.11/utils/';
var app = angular.module('myProveedor', []);
app.controller('myCtrlProveedor', function($scope,$http)
{
  $scope.lstProveedor = [];
  $scope.isDivProvActivo = false;
  $scope.isAvsoBrrarActv = false;
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
  $scope.idProveedor = '';
  $scope.sortDir = true;
  $scope.myOrderBy = '';

  $scope.init = function()
  {
  	$http.get(path+'load', {responseType: 'json'}).
    then(function(res)
  	{
  		if(res.data.length > 0)
  		{
  			$scope.lstProveedor = res.data;
        $scope.selectRowProveedor($scope.lstProveedor[0].RFC,0,$scope.lstProveedor[0].ID_PROVEEDOR);
  		}else {
        $scope.lstProveedor = [];
      }
  	})
  	.catch(function(err) {
  		console.log(err);
  	});
  }

  $scope.selectRowProveedor = function(idSelProv,indexRowProv,idProveedor)
  {
    $scope.idSelProv = idSelProv;
    $scope.indexRowProv = indexRowProv;
    $scope.idProveedor = idProveedor;
  }

  $scope.openDivAgregar = function()
  {
    $scope.isDivProvActivo = true;
    $http.get(path+'incremento/PROV/'+$('#idempresa').val()+'/5').
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
  	$http.get(path+'loadbyid/'+$scope.idProveedor, {responseType: 'json'}).
    then(function(res)
  	{
  		if(res.data.length > 0)
  		{
  			$scope.clave = res.data[0].CLAVE.trim();
  			$scope.nombre = res.data[0].NOMBRE.trim();
  			$scope.domicilio = res.data[0].DOMICILIO;
  			$scope.cp = res.data[0].CP;
  			$scope.telefono = res.data[0].TELEFONO.trim();
  			$scope.contacto = res.data[0].CONTACTO.trim();
  			$scope.rfc = res.data[0].RFC.trim();
  			$scope.curp = res.data[0].CURP.trim();
  			$('#id_tipo_prov').val(res.data[0].ID_CATEGORIA_PROV);
  			$scope.dias_cred = Number(res.data[0].DIAS_CRED);
  			$('#id_tipo_alc_prov').val(res.data[0].ID_TIPO_ALC_PROV);
  			$('#banco').val(res.data[0].ID_BANCO);
  			$scope.cuenta = Number(res.data[0].CUENTA.trim());
  			$scope.email = res.data[0].EMAIL.trim();
  			$scope.notas = res.data[0].NOTAS.trim();
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
      idempresa:$('#idempresa').val()
    };

    //console.log(dataProveedor);
    if($scope.btnAccion == 'Agregar')
    {
      $http.post(path+'save', dataProveedor).
      then(function(res)
      {
        //$('#message').html(res.data);
        //console.log(res);
    		if(res.status==200) {
          var dataRowProvA =
          {
            CLAVE:$scope.clave,
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            ID_PROVEEDOR:res.data[0].crea_proveedor
          };
          $scope.lstProveedor.push(dataRowProvA);
    			alert('El nuevo proveedor ha sido almacenado');
    			$scope.cancelar();
    		}
    	}).catch(function(err) {
    		console.log(err);
    	});

    }else {
      $http.put(path+'update/'+$scope.idProveedor,dataProveedor).
      then(function(res)
    	{
    		if(res.status==200 && res.data.value=='OK')
    		{
          var dataRowProvU =
          {
            CLAVE:$scope.clave,
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            ID_PROVEEDOR:$scope.idProveedor
          };
          $scope.lstProveedor[$scope.indexRowProv] = dataRowProvU;
          $scope.selectRowProveedor($scope.lstProveedor[$scope.indexRowProv].RFC,$scope.indexRowProv,$scope.lstProveedor[$scope.indexRowProv].ID_PROVEEDOR);
    			alert('El proveedor se actualizó correctamente');
    		}else
    		{
    			alert('Error,  no se puedo actualizar el proveedor');
    		}
    		$scope.cancelar();
    	}).catch(function(err)
    	{
    		console.log(err);
    	});
    }
  }

  $scope.preguntaEliminar = function()
  {
    $scope.isAvsoBrrarActv = true;
    $scope.descProvBorrar = $scope.lstProveedor[$scope.indexRowProv].NOMBRE;
  }

  $scope.eliminar = function()
  {
  	$http.delete(path+'delete/'+$scope.idProveedor).
  	then(function(res){
  		if(res.status==200 && res.data.value=='OK')
  		{
        $scope.lstProveedor.splice($scope.indexRowProv,1);
        $scope.selectRowProveedor($scope.lstProveedor[0].RFC,0,$scope.lstProveedor[0].ID_PROVEEDOR);
        $scope.closeAvisoBorrar();
				alert('Cliente elimnado exitosamente');
  		}
  	}).catch(function(err){
  		console.log(err)
  	})
  }

  $scope.orderByMe = function(valor)
  {
    $scope.myOrderBy = valor;
    $scope.sortDir = !$scope.sortDir;
  }

  $scope.closeAvisoBorrar = function()
  {
    $scope.isAvsoBrrarActv = false;
    $scope.descProvBorrar = '';
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
