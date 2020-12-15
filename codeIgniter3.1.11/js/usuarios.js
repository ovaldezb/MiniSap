app.controller('myCtrlUsuarios', function($scope,$http,$routeParams)
{
  $scope.lstProcesos = [];
  $scope.lstEmprPerm = [];
  $scope.lstModulos = [];
  $scope.lstUsuarios = [];
  $scope.lstModlUser = [];
  $scope.modlAddUser = false;
  $scope.modlDelUser = false;
  $scope.idUsuario = '';
  $scope.indexUsr = '';
  $scope.btnAccion = 'Aceptar';
  $scope.allModules = true;
  $scope.nomModule = '';
  $scope.userElim = '';
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
        $scope.idUsuario = res.data.idusuario;
        $scope.getUsers();
        $scope.permisos();
      }
    }).catch(function(err){
      console.log(err);
    });
  }

  $scope.getUsers = function(){
    $http.get(pathUsr+'getusrs',{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.lstUsuarios = res.data;
      }
    })
    .catch(function(err)
    {
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

  $scope.agregarUsuario = function()
  {
    $scope.modlAddUser = true;
    $http.get(pathUsr+'allempr',{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length>0)
      {
        for(var i=0;i<res.data.length;i++)
        {
          res.data[i].PERMITIDO = JSON.parse(res.data[i].PERMITIDO);
        }
        $scope.lstEmprPerm = res.data;
      }
    }).catch(function(err)
    {
      console.log(err);
    });

    $http.get(pathUsr+'allmoduls',{responseType:'json'}).
    then(function(res)
    {
        if(res.data.length > 0)
        {
          for(var i=0;i<res.data.length;i++)
          {
            res.data[i].PERMITIDO = JSON.parse(res.data[i].PERMITIDO);
          }
          $scope.lstModulos  = res.data;
        }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.enviaUsuario = function()
  {
    if($scope.btnAccion =='Aceptar')
    {
      if($scope.password == '' || $scope.cpassword=='')
      {
        swal('La contraseña y su confirmación son requeridas','Aviso','warning');
        return;
      }
      if($scope.password != $scope.cpassword)
      {
        swal('La contraseña no coincide');
        $scope.password ='';
        $scope.cpassword = '';
        return;
      }
      $scope.guardaUsuario();
    }else {
      if($scope.password != '' && $scope.password != $scope.cpassword)
      {
        swal('La contraseña no coincide');
        $scope.password ='';
        $scope.cpassword = '';
        return;
      }
      $scope.deleteModuloUsr();
      $scope.deleteEmpPerm();
      $scope.actualizaUsuario();
      $scope.lstModlUser = [];
      for(var i=0;i<$scope.lstModulos.length ; i++)
      {
        if($scope.lstModulos[i].PERMITIDO)
        {
          $scope.lstModlUser.push($scope.lstModulos[i]);
        }
      }
    }
    $scope.cerrarAddUser();
  }

  $scope.guardaUsuario = function()
  {
    var usrData = {
      nombre:$scope.nombre,
      usrname:$scope.username,
      paswd:$scope.password
    };
    var usrDataRow = {
      ID_USUARIO:'',
      NOMBRE:$scope.nombre,
      CLAVE_USR:$scope.username
    };
    $http.put(pathUsr+'saveusr',usrData).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        if(res.data[0].crea_usuario == -1)
        {
          swal('El usuario '+$scope.username+' ya existe, favor de elegir otro','Aviso','warning');
          return;
        }else {
          $scope.idUsuario = res.data[0].crea_usuario;
          usrDataRow.ID_USUARIO = res.data[0].crea_usuario;
          console.log(usrDataRow);
          $scope.lstUsuarios.push(usrDataRow);
          console.log($scope.lstUsuarios);
          $scope.insertaModulos();
          $scope.insertaEmpPerm();
          $scope.selectUsr($scope.idUsuario,$scope.lstUsuarios.length-1);
          $scope.cerrarAddUser();
          swal('El usuario se insertó correctamente','Exito','success');
        }
      }
    }).
    catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.actualizaUsuario = function()
  {
    var usrData = {
      idusuario:$scope.idUsuario,
      nombre:$scope.nombre,
      usrname:$scope.username,
      paswd:$scope.password==undefined ? 'false':$scope.password,
      updtpwd:$scope.password==undefined ? 'false' : 'true'
    };
    var usrDataRow = {
      ID_USUARIO:$scope.idUsuario,
      NOMBRE:$scope.nombre,
      CLAVE_USR:$scope.username
    };
    $http.put(pathUsr+'updtusuario',usrData).
    then(function(res)
    {
      if(res.data[0].actualiza_usuario == -1)
      {
        swal('El usuario '+$scope.username+' ya existe, favor de elegir otro');
        return;
      }else {
        swal('El usuario se actualizó correctamente','Exito','success');
        $scope.lstUsuarios[$scope.indexUsr] = usrDataRow;
        $scope.cleanUsr();
        $scope.cerrarAddUser();
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.deleteModuloUsr = function()
  {
    $http.delete(pathUsr+'elimmodusr/'+$scope.idUsuario).
    then(function(res)
    {
      $scope.insertaModulos();
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.deleteEmpPerm = function()
  {
    $http.delete(pathUsr+'elimemperm/'+$scope.idUsuario).
    then(function(res)
    {
      $scope.insertaEmpPerm();
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.selectUsr = function(id_usuario,index)
  {
    $scope.idUsuario = id_usuario;
    $scope.indexUsr = index;
    $http.get(pathUsr+'getmodulsusr/'+id_usuario,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.lstModlUser = res.data;
      }
    }).
    catch(function(err)
    {
      console.log(err);
    });
    $http.get(pathUsr+'getprocsusr/'+id_usuario,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        for(var i =0;i<res.data.length;i++)
        {
          res.data[i].P = JSON.parse(res.data[i].P);
          res.data[i].A = JSON.parse(res.data[i].A);
          res.data[i].B = JSON.parse(res.data[i].B);
          res.data[i].M = JSON.parse(res.data[i].M);
          res.data[i].C = JSON.parse(res.data[i].C);
        }
        $scope.lstProcesos = res.data;
      }

    }).catch(function(err)
    {
      console.log(err);
    });
    $scope.allModules = true;
  }

  $scope.insertaModulos = function()
  {
    for(var i=0;i<$scope.lstModulos.length;i++)
    {
      if($scope.lstModulos[i].PERMITIDO)
      {
        $http.post(pathUsr+'insrtmdls/'+$scope.idUsuario+'/'+$scope.lstModulos[i].ID_MODULO).
        then(function(res)
        {
          //console.log(res);
        }).
        catch(function(err)
        {
          console.log(err);
        });
      }
    }
  }

  $scope.insertaEmpPerm = function()
  {
    for(var i=0;i<$scope.lstEmprPerm.length;i++)
    {
      if($scope.lstEmprPerm[i].PERMITIDO)
      {
        $http.post(pathUsr+'insrtempperm/'+$scope.idUsuario+'/'+$scope.lstEmprPerm[i].ID_EMPRESA).
        then(function(res)
        {
          //console.log(res.data);
        }).
        catch(function(err)
        {
          console.log(err);
        });
      }
    }
  }

  $scope.visualizaUsr = function()
  {
    if($scope.idUsuario =='')
    {
      swal('Seleccione un usuario');
      return;
    }
    $scope.modlAddUser = true;
    $scope.btnAccion = 'Actualizar';
    $http.get(pathUsr+'getusrbyid/'+$scope.idUsuario,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length > 0)
      {
        $scope.nombre = res.data[0].NOMBRE;
        $scope.username = res.data[0].CLAVE_USR;
      }
    }).
    catch(function(err)
    {
      console.log(err);
    });

    $http.get(pathUsr+'getallmodulsusr/'+$scope.idUsuario,{responseType:'json'}).
    then(function(res)
    {
        if(res.data.length > 0)
        {
          for(var i=0;i<res.data.length;i++)
          {
            res.data[i].PERMITIDO = JSON.parse(res.data[i].PERMITIDO);
          }
          $scope.lstModulos  = res.data;
        }
    }).catch(function(err)
    {
      console.log(err);
    });

    $http.get(pathUsr+'emppermusr/'+$scope.idUsuario,{responseType:'json'}).
    then(function(res)
    {
      if(res.data.length>0)
      {
        for(var i=0;i<res.data.length;i++)
        {
          res.data[i].PERMITIDO = JSON.parse(res.data[i].PERMITIDO);
        }
        $scope.lstEmprPerm = res.data;
      }
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.actualizaUsrProc = function()
  {
    if($scope.idUsuario =='')
    {
      swal('Seleccione un usuario');
      return;
    }
    $http.delete(pathUsr+'eliminausrproc/'+$scope.idUsuario).
    then(function(res)
    {
      for(var i=0;i<$scope.lstProcesos.length;i++)
      {
        if($scope.lstProcesos[i].P)
        {
          $http.put(pathUsr+'insrtprocusr/'+
                    $scope.idUsuario+'/'+
                    $scope.lstProcesos[i].ID_PROCESO+'/'+
                    $scope.lstProcesos[i].P+'/'+
                    $scope.lstProcesos[i].A+'/'+
                    $scope.lstProcesos[i].B+'/'+
                    $scope.lstProcesos[i].M+'/'+
                    $scope.lstProcesos[i].C
                  ).
          then(function(res)
          {

          }).
          catch(function(err)
          {
            console.log(err);
          });
        }
      }
      swal('Se actualizaron los permisos','Exito','success')
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.preguntaElimUser = function()
  {
    $scope.modlDelUser = true;
    $scope.userElim = $scope.lstUsuarios[$scope.indexUsr].NOMBRE.trim();
  }

  $scope.eliminarUsuario = function()
  {
    $http.delete(pathUsr+'eliminausr/'+$scope.idUsuario).
    then(function(res)
    {
      swal('El usuario se eliminó','Exito','success');
      $scope.lstUsuarios.splice($scope.indexUsr,1);
      $scope.lstModlUser = [];
      $scope.lstProcesos = [];
      $scope.cerrarElimUser();
    }).catch(function(err)
    {
      console.log(err);
    });
  }

  $scope.cerrarElimUser = function()
  {
    $scope.modlDelUser = false;
    $scope.userElim = '';
  }

  $scope.selectRowModl = function(indx)
  {
    $scope.lstModulos[indx].PERMITIDO = !$scope.lstModulos[indx].PERMITIDO;
  }

  $scope.selectRowEmpr = function(indx)
  {
    $scope.lstEmprPerm[indx].PERMITIDO = !$scope.lstEmprPerm[indx].PERMITIDO;
  }

  $scope.cerrarAddUser = function()
  {
    $scope.modlAddUser = false;
    $scope.btnAccion ='Aceptar';
    $scope.cleanUsr();
  }

  $scope.alertRowCell = function(evento,indx)
  {
    var cell = evento.target;
    switch (cell.cellIndex) {
      case 2:
        $scope.lstProcesos[indx].A = !$scope.lstProcesos[indx].A;
        break;
      case 3:
        $scope.lstProcesos[indx].B = !$scope.lstProcesos[indx].B;
        break;
      case 4:
        $scope.lstProcesos[indx].M = !$scope.lstProcesos[indx].M;
        break;
      case 5:
        $scope.lstProcesos[indx].C = !$scope.lstProcesos[indx].C;
        break;
      default:
    }
  }

  $scope.doFilter1 = function(nombre)
  {
    if(nombre=='all')
    {
      $scope.allModules = true;
      $scope.nomModule = '';
    }else {
      $scope.allModules = false;
      $scope.nomModule = nombre;
    }
  }

  $scope.cleanUsr = function()
  {
    $scope.nombre = '';
    $scope.username = '';
    $scope.password = '';
    $scope.cpassword = '';
  }

  $scope.cambiaPermiso = function(indx)
  {
    $scope.lstProcesos[indx].P = !$scope.lstProcesos[indx].P;
    if(!$scope.lstProcesos[indx].P)
    {
      $scope.lstProcesos[indx].A = false;
      $scope.lstProcesos[indx].B = false;
      $scope.lstProcesos[indx].M = false;
      $scope.lstProcesos[indx].C = false;
    }
  }

});
