app.controller('myCtrlProducto', function($scope,$http,$routeParams)
{
  $scope.codigo = '';
  $scope.nombre = '';
  $scope.lstPrdcts = [];
  $scope.lstItemsSAT = [];
  $scope.lstUndadSAT = [];
  $scope.lstDetailProd = [];
  $scope.idSelProd = '';
  $scope.indexRowProd = '';
  $scope.idProducto = '';
  $scope.producto = '';
  $scope.prodDetailTot = 0;
  $scope.sortDir = false;
  $scope.modalDetalleProd = false;
  $scope.isNobrrarActive = false;
  $scope.isAvsoBrrarActv = false;
  $scope.isMainDivPrdcto = false;
  $scope.isCFDIBusqueda = false;
  $scope.isUndSATBusqda = false;
  $scope.myOrderBy = '';
  $scope.tipops = 'P';
  $scope.descprodborrar = '';
  $scope.btnAccion = 'Agregar';
  $scope.equivalencia = '';
  $scope.codigocfdi = '';
  $scope.unidad_sat = '';
  $scope.preciolista = '';
  $scope.preciopromo = '';
  $scope.preciodescnt = '';
  $scope.maxstock = '';
  $scope.minstock = '';
  $scope.notas = '';
  $scope.iva = '';
  $scope.ieps = '';
  $scope.esdescnt = false;
  $scope.esequiv = false;
  $scope.espromo = false;
  $scope.estasaexenta = false;
  $scope.tipops_msg = 'PRODUCTO';
  $scope.idempresa = '';
  $scope.idemprcodigo = '';
  $scope.idsucursal = '';
  $scope.linea = '';
  $scope.lstlinea = [];
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
        $scope.idemprcodigo = res.data.id_empr_codigo;
        $scope.idsucursal = res.data.idsucursal;
        $scope.idUsuario = res.data.idusuario;
        $scope.getDataInit();
        $scope.getDataLinea();
        $scope.permisos();
      }
    }).catch(function(err){
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

  $scope.getDataInit = function(){
    $http.get(pathProd+'load/'+$scope.idempresa,{responseType: 'json'}).
    then(function(res)
  	{
  		if(res.data.length > 0)
  		{
  			$scope.lstPrdcts = res.data;
        $scope.selectRowProducto($scope.lstPrdcts[0].CODIGO,0,$scope.lstPrdcts[0].ID_PRODUCTO);
  		}else
  		{
  			$scope.lstPrdcts = [];
  		}
  	})
  	.catch(function(err) {
  		console.log(err);
    });    
  }

  $scope.getDataLinea = function()
  {
    $http.get(pathLinea + $scope.idempresa,{responseType:'json'}).
    then((res)=>{
      if(res.data.length > 0){
        $scope.lstlinea = res.data;
        $scope.linea = res.data[0].ID_LINEA;
      }
    }).catch((err)=>{
      console.log(err);
    });
  }

  $scope.selectRowProducto = function(idSelCompra, indexRowCompra,idProducto)
  {
    $scope.idSelProd = idSelCompra;
    $scope.indexRowProd = indexRowCompra;
    $scope.idProducto = idProducto;
  }

  $scope.orderByMe = function(x) {
      $scope.myOrderBy = x;
      $scope.sortDir = !$scope.sortDir;
  }

  $scope.preguntaEliminar =function()
  {
    if(	$scope.lstPrdcts[$scope.indexRowProd].STOCK != 0)
    {
      $scope.isNobrrarActive = true;
    }else
    {
      $scope.isAvsoBrrarActv = true;
      $scope.descprodborrar = $scope.lstPrdcts[$scope.indexRowProd].DESCRIPCION;
    }
  }

  $scope.closeModalNoBorrar = function()
  {
  	$scope.isNobrrarActive = false;
  }

  $scope.closeAvisoBorrar = function()
  {
  	$scope.isAvsoBrrarActv = false;
  }

  $scope.update = function()
  {
  	showImg('F');
  	$http.get(pathProd+'loadbyid/'+$scope.idProducto, {responseType: 'json'}).
    then(function(res)
  	{
      if(res.data.length > 0)
      {
        $scope.codigo = res.data[0].CODIGO.trim();
        $scope.nombre = res.data[0].DESCRIPCION.trim();
        $scope.linea =  res.data[0].ID_LINEA;
        $('#umedida').val(res.data[0].UNIDAD_MEDIDA.trim());
        $scope.esequiv = res.data[0].ES_COMPUESTO=='t'?true:false;
        $scope.equivalencia = res.data[0].EQUIVALENCIA;
        $scope.codigocfdi = res.data[0].COD_CFDI;
        $scope.unidad_sat = res.data[0].UNIDAD_SAT;
        $scope.preciolista = res.data[0].PRECIO_LISTA;
        $('#moneda').val(res.data[0].ID_MONEDA);
        $scope.espromo = res.data[0].ES_PROMO=='t'?true:false;
        $scope.preciopromo = res.data[0].PRECIO_PROMO;
        $scope.esdescnt = res.data[0].ES_DESCUENTO=='t'?true:false;
        $scope.preciodescnt = res.data[0].PRECIO_DESCUENTO;
        $scope.maxstock = res.data[0].MAX_STOCK;
        $scope.minstock = res.data[0].MIN_STOCk;
        $scope.estasaexenta = res.data[0].TASA_EXENTA=='t'?true:false;
        $scope.notas = res.data[0].NOTAS;
        $scope.iva = res.data[0].IVA;
        $('#idieps').val(res.data[0].ID_IEPS);
        $scope.ieps = res.data[0].IEPS;
        if(res.data[0].IMAGEN!=null && res.data[0].IMAGEN!=''){
        	document.getElementById('imgsrc').src = res.data[0].IMAGEN;
          document.getElementById('img_name').value = res.data[0].IMAGEN;
        	showImg('True');
        }else
        {
        	showImg('False');
        }
        $scope.isMainDivPrdcto = true;
        $scope.btnAccion = 'Actualizar';
      }
  	}).catch(function(err)
  	{
  		console.log(err);
  	});

  }

  $scope.submitForm = function()
  {
  	var day = new Date();
    var dataProdUpdt =
    {
      codigo:$scope.codigo,
  		nombre:$scope.nombre,
  		linea:$scope.linea,
  		unidadmedida:$('#umedida').val(),
  		esequiv:String($scope.esequiv),
  		equivalencia:$scope.equivalencia,
  		codigocfdi:$scope.codigocfdi,
  		unidad_sat:$scope.unidad_sat,
  		preciolista:$scope.preciolista,
  		ultact:formatDateInsert(day),
  		moneda:$('#moneda').val(),
  		iva:$scope.iva,
  		idieps:$('#idieps').val(),
  		ieps:$scope.ieps,
  		espromo:String($scope.espromo),
  		preciopromo:$scope.preciopromo,
  		esdescnt:String($scope.esdescnt),
  		preciodescnt:$scope.preciodescnt,
  		maxstock:$scope.maxstock,
  		minstock:$scope.minstock,
  		estasaexenta:String($scope.estasaexenta),
  		notas:$scope.notas,
  		img:$('#img_name').val(),
      idempresa:$scope.idempresa,
      idscursal:$scope.idsucursal,
      tipops:$('input[name="tipo"]:checked').val()
    };

    if($scope.btnAccion == 'Agregar')
    {
      var nvoProd ={};
      $http.post(pathProd+'save', dataProdUpdt).
      then(function(res)
      {
        console.log(res);
        
        if(res.data.length > 0)
        {
          nvoProd =
          {
            DESCRIPCION:$scope.nombre,
            CODIGO:$scope.codigo,
            PRECIO_LISTA:$scope.preciolista,
            STOCK:'0',
            ID_PRODUCTO:res.data[0].crea_producto
          };
          $scope.lstPrdcts.push(nvoProd);
          swal('El producto se insertó correctamente '+res.data[0].crea_producto);
      		$scope.cancelar();
        }
      }).
      catch(function(err)
      {
        console.log(err);
      });
    }else {
      var actProd ={};
      $http.put(pathProd+'update/'+$scope.idProducto,dataProdUpdt).
      then(function(res)
    	{
    		if(res.status==200 && res.data.value=='OK')
    		{
          actProd =
          {
            DESCRIPCION:$scope.nombre,
            CODIGO:$scope.codigo,
            PRECIO_LISTA:$scope.preciolista,
            STOCK:$scope.lstPrdcts[$scope.indexRowProd].STOCK,
            ID_PRODUCTO:$scope.lstPrdcts[$scope.indexRowProd].ID_PRODUCTO
          };
          $scope.lstPrdcts[$scope.indexRowProd] = actProd;
    			swal('El producto se actualizó correctamente');
          $scope.btnAccion = 'Agregar';
          $scope.selectRowProducto($scope.lstPrdcts[0].CODIGO,0,$scope.lstPrdcts[0].ID_PRODUCTO);
      		$scope.cancelar();
    		}else
    		{
    			swal('Error,  no se puedo actualizar el producto');
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
  	$http.delete(pathProd+'delete/'+$scope.idProducto).
  	then(function(res){
  		if(res.status==200)
  		{
  			if(res.data.value=='OK')
  			{
  				$scope.lstPrdcts.splice($scope.indexRowProd,1);
          $scope.selectRowProducto($scope.lstPrdcts[0].CODIGO,0,$scope.lstPrdcts[0].ID_PRODUCTO);
  				swal('Producto elimnado exitosamente');
          $scope.closeAvisoBorrar();
  			}
  		}
  	}).catch(function(err){
  		console.log(err)
  	})
  }

  $scope.openImageWnd = function(event)
  {
    if(event.clientX==0) return;
  	if($scope.codigo =='')
  	{
  		swal('Para cargar una imagen, debe ingresar el código del producto primero.');
  		$('#codigo').focus();
  		return;
  	}else
  	{
  		var winchild = popupwindow(pathUpld+$scope.codigo+'/'+$scope.idemprcodigo,'Cargar Imagen',500,200);
  	}
  }

  $scope.ejecutagetitem = function(event)
  {
  if(event.keyCode == 13 && $scope.cfdidesc!=''){
      $scope.getItemsSAT();
    }
  }

  $scope.getItemsSAT = function()
  {
    if($scope.cfdidesc == '')
    {
      return;
    }

  	$http.get(pathProd+'items/'+$scope.cfdidesc).
  	then(function(res)
    {
        if(res.data.length > 0)
        {
          $scope.lstItemsSAT = res.data;
          $scope.isCFDIBusqueda = true;
        }else {
          $scope.lstItemsSAT = [];
        }
    }).catch(function(err)
    {
    		console.log(err);
    });
  }

  $scope.selectRowItemSAT = function(index)
  {
    $scope.codigocfdi = $scope.lstItemsSAT[index].CLAVE;
    $scope.cfdidesc = $scope.lstItemsSAT[index].DESCRIPCION;
    $scope.cierraCFDI();
  }

  $scope.cierraCFDI = function()
  {
    $scope.lstItemsSAT = [];
    $scope.isCFDIBusqueda = false;
  }

  $scope.ejecutagetunidadsat = function(event)
  {
    if(event.keyCode == 13 && $scope.unidaddesc != '')
    {
      $scope.getUnidadSAT();
    }
  }

  $scope.getUnidadSAT = function()
  {
    if($scope.unidaddesc == '')
    {
      return;
    }
  	$http.get(pathProd+'unidadsat/'+$scope.unidaddesc).
  	then(function(res)
    {
  		if(res.data.length > 0)
  		{
  			$scope.lstUndadSAT =  res.data;
        $scope.isUndSATBusqda = true;
  		}else {
        $scope.lstUndadSAT = [];
      }

  	}).
    catch(function(err)
  	{
  		console.log(err);
  	})
  }

  $scope.selectUnidadSAT = function(index)
  {
    $scope.unidad_sat = $scope.lstUndadSAT[index].CLAVE;
    $scope.unidaddesc = $scope.lstUndadSAT[index].DESCRIPCION;
    $scope.cierraUnidadSAT();
  }

  $scope.cierraUnidadSAT = function()
  {
    $scope.lstUndadSAT = [];
    $scope.isUndSATBusqda = false;
  }

  $scope.cleanup = function()
  {
  	$scope.codigo = '';
  	$scope.nombre = '';
  	$scope.linea = $scope.lstlinea[0].ID_LINEA;
  	$('#umedida').val('Pieza');
    $scope.esdescnt = false;
    $scope.esequiv = false;
  	$scope.equivalencia = '';
  	$scope.codigocfdi = '';
  	$scope.preciolista = '';
  	$('#moneda').val(1);
  	$scope.espromo = false;
  	$scope.iva = '';
  	$scope.preciopromo = '';
  	$scope.preciodescnt = '';
  	$scope.maxstock = 0;
  	$scope.minstock = 0;
  	$scope.estasaexenta = false;
  	$scope.notas = '';
  	$scope.cfdidesc = '';
  	$scope.unidaddesc = '';
    $scope.unidad_sat = '';
  	document.getElementById('imgsrc').src = '';
  	document.getElementById('img_name').value = '';
  }

  $scope.openDivAgregar = function()
  {
    $scope.isMainDivPrdcto = true;
    $scope.btnAccion = 'Agregar';
  }

  $scope.cancelar = function()
  {
    $scope.isMainDivPrdcto = false;
    showImg('False');
    $scope.cleanup();
  }

  $scope.verdetalle = () =>{
    $scope.modalDetalleProd = true;
    $http.get(pathProd+'proddetailid/'+$scope.idProducto)
    .then(res =>{
      if(res.data){
        $scope.lstDetailProd = res.data;
        res.data.forEach(elem =>{
          $scope.prodDetailTot += elem.MOV;
        });
        $scope.producto = $scope.lstPrdcts[$scope.indexRowProd].DESCRIPCION.trim();
      }
    })
    .catch(err =>{

    });

  }

  $scope.cerrarDetalleProd = ()=>{
    $scope.modalDetalleProd = false;
    $scope.lstDetailProd = [];
    $scope.prodDetailTot = 0;
  }

  $scope.selecTPS = function()
  {
    if($scope.tipops=='P')
    {
      $("input[name='tipo'][value='P']").prop('checked', true);
      $('#maxstock').prop('disabled',false);
      $('#minstock').prop('disabled',false);
      $scope.maxstock = '';
      $scope.minstock = '';
      $scope.tipops_msg = 'PRODUCTO';
    }else
    {
      $("input[name='tipo'][value='S']").prop('checked', true);
      $('#maxstock').prop('disabled',true);
      $('#minstock').prop('disabled',true);
      $scope.maxstock = 0;
      $scope.minstock = 0;
      $scope.tipops_msg = 'SERVICIO';
    }
  }

  $scope.validarcodigo = function(){
    if($scope.btnAccion == 'Agregar'){
      $http.get(pathProd+'prodbycode/'+$scope.codigo+'/'+$scope.idempresa+'/'+$scope.idsucursal)
        .then(res=>{
          if(res.data.length==1){
            swal("El código de producto "+$scope.codigo+" ya existe","Verifique el código","warning");
            $scope.codigo = '';
          }
        })
        .catch(err=>{
          console.log(err);
        });
      }
  }

});

function setValue(valor,idemprcodigo)
{
  document.getElementById('imgsrc').src='../uploads/'+idemprcodigo+'/'+ valor;
	document.getElementById('img_name').value = '../uploads/'+idemprcodigo+'/'+valor;
}

function showImg(flag)
{
  var x = document.getElementById("imgsrc");
	var y = document.getElementById('img_name');
	if(flag=='True')
	{
		x.style.display = "block";
	}else
	{
		x.src = '';
		x.style.display = "none";
		y.value = '';
	}
}

function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'titlebar=0, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

function toUpper(nombre)
{
	nombre.value = nombre.value.toUpperCase();
}
