app.controller('myCtrlEmpresa', function($scope,$http)
{
  $scope.lstEmpresas = [];
  $scope.isDivEmpActivo = false;
  $scope.isAvsoBrrarActv = false;
  $scope.sortDir = false;
  $scope.idSelEmp = '';
  $scope.indexRowEmp = '';
  $scope.idEmpresa = '';
  $scope.descEmpBorrar = '';
  $scope.actnBton = 'Agregar';
  $scope.nombre = '';
  $scope.domicilio = '';
  $scope.rfc = '';
  $scope.ejercicio_fiscal = '';
  $scope.dig1 = '';
  $scope.dig2 = '';
  $scope.dig3 = '';
  $scope.dig4 = '';
  $scope.cuenta_resultado = '';
  $scope.resultado_anterior = '';

  $scope.init = function()
  {
    $http.get(pathEmpr+'load', {responseType: 'json'})
  	.then(function(res)
  	{
  		if(res.data.length > 0)
  		{
  			$scope.lstEmpresas = res.data;
        $scope.selectRowEmpresa($scope.lstEmpresas[0].RFC,0,$scope.lstEmpresas[0].ID_EMPRESA);
  		}else {
        $scope.lstEmpresas = [];
      }
  	})
  	.catch(function(err) {
  		console.log(err);
  	});
  }

  $scope.validaEF = function()
  {
    if(isNaN($scope.ejercicio_fiscal))
    {
      alert('Sólo se aceptan números');
      $scope.ejercicio_fiscal = '';
      $('#ef').focus();
    }
  }

  $scope.validaCR = function()
  {
    if(isNaN($scope.cuenta_resultado))
    {
      $('#cuenta_resultado').focus();
      $scope.cuenta_resultado = '';
      alert('Sólo se aceptan números');
    }
  }

  $scope.validaRA = function()
  {
    if(isNaN($scope.resultado_anterior))
    {
      $('#resultado_anterior').focus();
      $scope.resultado_anterior = '';
      alert('Sólo se aceptan números');
    }
  }

  $scope.orderByMe = function(x) {
      $scope.myOrderBy = x;
      $scope.sortDir = !$scope.sortDir;
  }

  $scope.selectRowEmpresa = function(idSelEmp, indexRowEmp,idEmpresa)
  {
    $scope.idSelEmp = idSelEmp;
    $scope.indexRowEmp = indexRowEmp;
    $scope.idEmpresa = idEmpresa;
    console.log($scope.idSelEmp+' '+$scope.indexRowEmp+' '+$scope.idEmpresa);
    console.log($scope.lstEmpresas[$scope.indexRowEmp].NOMBRE);
    var tabla = document.getElementById("tablaempresas");
    var row = tabla.getElementsByTagName("tr")[0];
    console.log(row);
  }

  $scope.preguntaEliminar = function()
  {
    $scope.descEmpBorrar = $scope.lstEmpresas[$scope.indexRowEmp].NOMBRE;
    $scope.isAvsoBrrarActv = true;
  }

  $scope.openDivAgregar = function()
  {
    $scope.isDivEmpActivo = true;
  }

  $scope.update = function()
  {
  	$http.get(pathEmpr+'loadbyid/'+$scope.idEmpresa, {responseType: 'json'}).
    then(function(res)
  	{
      console.log(res);
  		if(res.data.length > 0)
  		{
  			$scope.nombre = res.data[0].NOMBRE;
  			$scope.domicilio = res.data[0].DOMICILIO;
  			$scope.rfc = res.data[0].RFC;
  			$scope.ejercicio_fiscal = res.data[0].EJER_FISC;
  			$('#regimen').val(res.data[0].ID_REGIMEN);
  			$scope.cuenta_resultado = res.data[0].CUENTA_RESULTADO;
  			$scope.resultado_anterior = res.data[0].RESULTADO_ANTERIOR;
        var digxcta = res.data[0].DIGITO_X_CUENTA.toString().split("");
  			$scope.dig1 = digxcta[0];
  			$scope.dig2 = digxcta[1];
  			$scope.dig3 = digxcta[2];
  			$scope.dig4 = digxcta[3];
        $scope.isDivEmpActivo = true;
        $scope.actnBton = 'Actualizar';
  		}
  	}).catch(function(err)
  	{
  		console.log(err);
  	});
  }

  $scope.submitForm = function()
  {
    var dataEmpresa =
    {
      nombre:$scope.nombre,
      domicilio:$scope.domicilio,
      rfc:$scope.rfc,
      ejercicio_fiscal:$scope.ejercicio_fiscal,
      regimen:$('#regimen').val(),
      digxcta:$scope.dig1+$scope.dig2+$scope.dig3+$scope.dig4,
      cuenta_resultado:$scope.cuenta_resultado,
      resultado_anterior:$scope.resultado_anterior,
      idempresa:$scope.idEmpresa
    };

    if($scope.actnBton == 'Agregar')
    {
      $http.post(pathEmpr+'save',dataEmpresa).
      then(function(res)
      {
        if(res.data.length > 0) {
          var nvaEmpresa =
          {
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            ID_EMPRESA:res.data[0].crea_empresa
          };
          $scope.lstEmpresas.push(nvaEmpresa);
          alert('La nueva empresa ha sido almacenada');
          $scope.cancelar();
        }
    	}).catch(function(err) {
    		console.log(err);
    	});
    }else {
      $http.put(pathEmpr+'update/'+$scope.idEmpresa,dataEmpresa).
      then(function(res)
    	{
    		if(res.status== 200 && res.data.value == 'OK')
    		{
          var dataUpdate =
          {
            NOMBRE:$scope.nombre,
            RFC:$scope.rfc,
            ID_EMPRESA:$scope.idEmpresa
          };
          $scope.lstEmpresas[$scope.indexRowEmp] = dataUpdate;
          $scope.selectRowEmpresa($scope.lstEmpresas[0].RFC,0,$scope.lstEmpresas[0].ID_EMPRESA);
    			alert('La empresa se actualizó correctamente');
    			$scope.cancelar();
    		}
    	}).
      catch(function(err)
    	{
    		console.log(err);
    	});
    }
  }

  $scope.eliminar = function()
  {
    $http.delete(pathEmpr+'delete/'+$scope.idEmpresa).
  	then(function(res){
  		if(res.status==200 && res.data.value=='OK')
  		{
        $scope.lstEmpresas.splice($scope.indexRowEmp,1);
        $scope.cancelar();
  		   alert('Empresa elimnada exitosamente');
  		}else {
        alert('Ocurrió un problema, no se pudo eliminar la Empresa');
        console.log(res);
      }
      $scope.closeAvisoBorrar();
  	}).catch(function(err){
  		console.log(err)
  	});
  }

  $scope.closeAvisoBorrar = function()
  {
    $scope.isAvsoBrrarActv = false;
  }

  $scope.cancelar = function()
  {
    $scope.isDivEmpActivo = false;
    $scope.actnBton = 'Agregar';
    $('#regimen').val(1);
    $scope.nombre = '';
    $scope.domicilio = '';
    $scope.rfc = '';
    $scope.ejercicio_fiscal = '';
    $scope.dig1 = '';
    $scope.dig2 = '';
    $scope.dig3 = '';
    $scope.dig4 = '';
    $scope.cuenta_resultado = '';
    $scope.resultado_anterior = '';
  }

});
