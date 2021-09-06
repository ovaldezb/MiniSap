app.controller('myCtrlEmpresa', function($scope,$http,$routeParams)
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
  $scope.idUsuario = '';
  $scope.idProceso = $routeParams.idproc;
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.emp = {
    nombre:'',
    domicilio:'',
    rfc:'',
    cp:'',
    ejercicio_fiscal:'',
    dig1:'',
    dig2:'',
    dig3:'',
    dig4:'',
    digxcta:'',
    cuenta_resultado:'',
    resultado_anterior:'',
    regimen:'',
    idEmpresa:'',
    telefono:'',
    email:'',
    redessociales:'',
    mensaje:''
  }

  $scope.init = function()
  {
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        //$scope.idempresa = res.data.idempresa;
        $scope.idUsuario = res.data.idusuario;
        $scope.getEmpresas();
        $scope.permisos();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getEmpresas = function(){
    $http.get(pathEmpr+'load', {responseType: 'json'})
  	.then(function(res)
  	{
  		if(res.data.length > 0)
  		{
  			$scope.lstEmpresas = res.data;
        $scope.selectRowEmpresa($scope.lstEmpresas[0].RFC,0,$scope.lstEmpresas[0].ID_EMPRESA);
        $scope.permisos();
  		}else {
        $scope.lstEmpresas = [];
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

  $scope.validaEF = function()
  {
    if(isNaN($scope.ejercicio_fiscal))
    {
      swal('Sólo se aceptan números');
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
      swal('Sólo se aceptan números');
    }
  }

  $scope.validaRA = function()
  {
    if(isNaN($scope.resultado_anterior))
    {
      $('#resultado_anterior').focus();
      $scope.resultado_anterior = '';
      swal('Sólo se aceptan números');
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
  		if(res.data.length > 0)
  		{
  			$scope.emp.nombre = res.data[0].NOMBRE;
  			$scope.emp.domicilio = res.data[0].DOMICILIO;
        $scope.emp.rfc = res.data[0].RFC;
        $scope.emp.cp = res.data[0].CP;
  			$scope.emp.ejercicio_fiscal = res.data[0].EJER_FISC;
  			$('#regimen').val(res.data[0].ID_REGIMEN);
  			$scope.emp.cuenta_resultado = res.data[0].CUENTA_RESULTADO;
  			$scope.emp.resultado_anterior = res.data[0].RESULTADO_ANTERIOR;
        var digxcta = String(res.data[0].DIGITO_X_CUENTA).split("");
  			$scope.emp.dig1 = digxcta[0];
  			$scope.emp.dig2 = digxcta[1];
  			$scope.emp.dig3 = digxcta[2];
  			$scope.emp.dig4 = digxcta[3];
        $scope.emp.telefono = res.data[0].TELEFONO;
        $scope.emp.email = res.data[0].EMAIL;
        $scope.emp.redessociales = res.data[0].RRSS;
        $scope.emp.mensaje = res.data[0].MENSAJE;
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
    $scope.emp.idEmpresa = $scope.idEmpresa;
    $scope.emp.digxcta = $scope.emp.dig1+$scope.emp.dig2+$scope.emp.dig3+$scope.emp.dig4;
    $scope.emp.regimen = $('#regimen').val();

    if($scope.actnBton == 'Agregar')
    {
      $http.post(pathEmpr+'save',$scope.emp).
      then(function(res)
      {        
        if(res.data.length > 0) {
          var nvaEmpresa =
          {
            NOMBRE:$scope.emp.nombre,
            RFC:$scope.emp.rfc,
            CP:$scope.emp.cp,
            ID_EMPRESA:res.data[0].crea_empresa
          };
          $scope.lstEmpresas.push(nvaEmpresa);
          swal('La nueva empresa ha sido almacenada');
          $scope.cancelar();
        }
    	}).catch(function(err) {
    		console.log(err);
    	});
    }else {
      $http.put(pathEmpr+'update/'+$scope.idEmpresa,$scope.emp).
      then(res =>
    	{
    		if(res.status== 200 && res.data.value == 'OK')
    		{
          $scope.getEmpresas();
          $scope.selectRowEmpresa($scope.lstEmpresas[0].RFC,0,$scope.lstEmpresas[0].ID_EMPRESA);
    			swal('La empresa se actualizó correctamente');
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
  		   swal('Empresa elimnada exitosamente');
  		}else {
        swal('Ocurrió un problema, no se pudo eliminar la Empresa');
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
    $scope.emp.nombre = '';
    $scope.emp.domicilio = '';
    $scope.emp.rfc = '';
    $scope.emp.cp = '';
    $scope.emp.ejercicio_fiscal = '';
    $scope.emp.dig1 = '';
    $scope.emp.dig2 = '';
    $scope.emp.dig3 = '';
    $scope.emp.dig4 = '';
    $scope.emp.cuenta_resultado = '';
    $scope.emp.resultado_anterior = '';
    $scope.emp.telefono = '';
    $scope.emp.email = '';
    $scope.emp.redessociales = '';
    $scope.emp.mensaje = '';
  }

});
