app.controller('myCtrlPedi', function($scope,$http,$interval,$routeParams)
{
  const rowNumFill = 14;
  $scope.fecha = formatDatePrint(new Date());
  $scope.fechaPantalla = formatDatePantalla(new Date());
  $scope.hora = DisplayCurrentTime();
  $scope.doctoTmp = '';
  $scope.sortDir = true;
	$scope.counter = 0;
  $scope.totalBruto = 0.00;
  $scope.cambio = 0.00;
  $scope.cantidad = 0;
  $scope.idSelCompra = '';
  $scope.indexRowCompra = -1;
  $scope.qtyProdSuc = -1;
  $scope.lstProdBusqueda = [];
  $scope.lstProdCompra = [];
  $scope.lstCliente = [];
  $scope.lstVendedor = [];
  $scope.lstPrdSucExis = [];
  $scope.lstDomis = [];
  $scope.lstComplemento = [];
  $scope.idDocumento = '';
  $scope.indexRowPedido = -1;
  $scope.idPedido = 0;
  $scope.dsctoValor = 0;
  $scope.impuestos = 0;
  $scope.nombre_cliente = '';
  $scope.rfc_cliente = '';
  $scope.claveclte = '';
  $scope.nombre_vendedor = '';
  $scope.agregaProd = true;
  $scope.modalConsProdSuc = false;
  $scope.modalVerifProdSuc = false;
  $scope.isVerifExis = false;
  $scope.modalVerfClte = false;
  $scope.showLstClte = false;
  $scope.isImprimir = false;
  $scope.isActualiza = false;
  $scope.isRegistra = true;
  $scope.btnVerifClte = 'Actualizar';
  $scope.lstMoneda = [];
  $scope.lstMetpago = [];
  $scope.lstFormpago = [];
  $scope.lstTipopago = [];
  $scope.lstUsocfdi = [];
  $scope.lstPedidos = [];
  $scope.isCapturaPedido = false;
  $scope.regpedido = true;
  $scope.imprimir = true;
  $scope.pregElimiPedi = false;
  $scope.doctoEliminar = '';
  $scope.fechaentrega = '';
  $scope.fechapedido = '';
  $scope.idUsuario = '';
  $scope.listaVendedores = false;
  $scope.modalAddDscnt = false;
  $scope.dispsearch = false;
  $scope.showProgressBar = false;
  $scope.estatus = true;
  $scope.mpago_style = {background:'pink'};
  $scope.idProceso = $routeParams.idproc;
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.pedido = {
    docto : '',
    idcliente : '',
    idvendedor : '',
    fechapedido : '',
    aniofiscal : '',
    idempresa : '',
    total : '',
    idsucursal : '',
    idmoneda : 1,
    contacto : '',
    cuenta: '',
    dias : '',
    tpago : 1,
    fpago : 1,
    fechaentrega:'',
    domi:'',
    bruto:'',
    comentarios:'',
    subtotal:0
  };

  $scope.domientrega = {
    calle:'',
    colonia:'',
    cp:'',
    ciudad:'',
    contacto:''
  }
  $scope.producto = {
      codigo_prodto: '',
      prod_desc : '',
      unidad : '',
      precio : '',
      id_producto : '',
      imagePath : '',
      iva : '',
      cantProd : '',
      esPromo : false,
      esDscto : false,
      descuento : 0,
      promocion : 0
  };

  $scope.empresa ={
    nombre : '',
    domicilio:'',
    rfc:''
  };

  $scope.cliente = {
    clave:"",
    nombre:"",
    domicilio:"",
    telefono:"",
    cp:"",
    contacto:"",
    rfc:"",
    curp:"",
    dcredito:0,
    email:"",
    num_proveedor:"",
    notas:"",
    id_tipo_cliente:"",
    revision:"",
    pagos:"",
    id_forma_pago:"",
    id_vendedor:"",
    id_uso_cfdi:"",
    idempresa:""
  };

  $scope.proddscnt ={
    producto:undefined,
    precio:undefined,
    descuento:0,
    descuentoTodos:0
  }

  $scope.init = function()
  {
      var foopicker = new FooPicker({
      id: 'fechaentrega',
      dateFormat: 'dd/MM/yyyy'
      });
      $scope.isRegistra = true;
      $scope.fechapedido = formatDatePrint(new Date());
      $http.get(pathAcc+'getdata',{responseType:'json'}).
      then(function(res){
        if(res.data.value=='OK'){
          $scope.pedido.idempresa = res.data.idempresa;
          $scope.pedido.idsucursal = res.data.idsucursal;
          $scope.pedido.aniofiscal = res.data.aniofiscal;
          $scope.idUsuario = res.data.idusuario;
          $scope.getNextDocPedido();
          $scope.getpedidos();
          $scope.permisos();
          $scope.getvendedores();
          $scope.getEmpresa();
        }
      }).catch(function(err){
        console.log(err);
      });      
      $scope.getUsoCfdi();
      $scope.getMetPago();
      $scope.getMoneda();
      $scope.getFormPago();
      $scope.getTipoPago();
      $scope.lstComplemento = [];
      for(var i=0;i<(rowNumFill-$scope.lstProdCompra.length);i++){
        let p = {
          val:0
        };
        p.val = i;
        $scope.lstComplemento.push(p);
      }
  }

  $scope.getpedidos = function(){
    $http.get(pathPedi+'getpedidostotales/'+$scope.pedido.idempresa+'/'+$scope.pedido.aniofiscal,{responseType:'json'})
        .then(res => {
          if(res.data.length > 0){
            $scope.lstPedidos = res.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
  }
  
  $scope.getNextDocPedido = function(){
		$http.get(pathUtils+'incremento/PEDI/'+$scope.pedido.idempresa+'/7').
		then(function(res)
		{
			if(res.data.length > 0)
			{
				$scope.pedido.docto = res.data[0].VALOR;
				$scope.doctoTmp = res.data[0].VALOR;
			}
		}).catch(function(err)
		{
			console.log(err);
		});
	}

  $scope.getvendedores =() =>{
    $http.get(pathVend+'getvendedores/'+$scope.pedido.idempresa+'/vacio',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstVendedorVerif = res.data;
      }
    }).catch(err =>	{
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

  $interval(function () {
        $scope.hora = DisplayCurrentTime();
    }, 1000);

  $scope.getMoneda = function(){
    $http.get(pathUtils+'getmoneda',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstMoneda = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.getMetPago = function(){
    $http.get(pathUtils+'getmetpag',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstMetpago = res.data;
      }
    }).catch(err =>	{
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

  $scope.getTipoPago = function(){
    $http.get(pathUtils+'gettipopago',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstTipopago = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.getUsoCfdi = function(){
    $http.get(pathUtils+'getusocfdi',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstUsocfdi = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.VerificarCliente = function()
  {
      if($scope.claveclte != '')
      {
        $http.get(pathClte+'loadbyidverfi/'+$scope.pedido.idcliente,{responseType:'json'}).
        then(function(res)
        {
          if(res.data.length > 0)
          {
            $scope.cliente.clave = res.data[0].CLAVE;
            $scope.cliente.nombre = res.data[0].NOMBRE;
            $scope.cliente.domicilio = res.data[0].DOMICILIO;
            $scope.cliente.telefono = Number(res.data[0].TELEFONO);
            $scope.cliente.cp = Number(res.data[0].CP);
            $scope.cliente.contacto = res.data[0].CONTACTO;
            $scope.cliente.rfc = res.data[0].RFC;
            $scope.cliente.curp = res.data[0].CURP;
            $('#id_tipo_cliente').val(res.data[0].ID_TIPO_CLIENTE);
            $('#revision').val(res.data[0].ID_REVISION);
            $('#pagos').val(res.data[0].ID_PAGOS);
            $scope.cliente.id_forma_pago = res.data[0].ID_FORMA_PAGO;
            $scope.cliente.id_vendedor = res.data[0].ID_VENDEDOR.toString();
            $scope.cliente.id_uso_cfdi = Number(res.data[0].ID_USO_CFDI);
            $scope.cliente.email = res.data[0].EMAIL;
            $scope.cliente.num_proveedor = res.data[0].NUM_PROVEEDOR;
            $scope.cliente.notas = res.data[0].NOTAS;
            $scope.btnVerifClte = 'Actualizar';
          }
        }).
        catch(function(err)
        {
          console.log(err);
        });
      }else{
        $scope.btnVerifClte = 'Agregar';
        $scope.cliente.nombre = $scope.nombre_cliente;
      }
      $scope.modalVerfClte = true;
  }

  $scope.setSelected = function(indexRowCompra,idSelCompra)
  {
    $scope.idSelCompra = idSelCompra;
    $scope.indexRowCompra = indexRowCompra;
  }

  $scope.closeVerifClte = function()
  {
    $scope.cliente.clave = '';
    $scope.cliente.nombre = '';
    $scope.cliente.domicilio = '';
    $scope.cliente.telefono = '';
    $scope.cliente.cp = '';
    $scope.cliente.contacto = '';
    $scope.cliente.rfc = '';
    $scope.cliente.curp = '';
    $scope.cliente.id_tipo_cliente = '';
    $scope.cliente.dcredito = 0;
    $scope.cliente.revision = '';
    $scope.cliente.pagos = '';
    $scope.cliente.id_forma_pago = '';
    $scope.cliente.id_vendedor = '';
    $scope.cliente.id_uso_cfdi = '';
    $scope.cliente.email = '';
    $scope.cliente.num_proveedor = '';
    $scope.cliente.notas = '';
    $scope.modalVerfClte = false;
  }

  $scope.enviaDatosCliente = function()
  {
    $scope.cliente.id_tipo_cliente=$('#id_tipo_cliente').val();
    $scope.cliente.revision=$('#revision').val();
    $scope.cliente.pagos=$('#pagos').val();
    $scope.cliente.idempresa = $scope.pedido.idempresa;

    if($scope.btnVerifClte == 'Agregar')
    {
      $http.get(pathUtils+'incremento/CLTE/'+$scope.pedido.idempresa+'/4').
      then(function(res)
      {
        $scope.cliente.clave = res.data[0].VALOR;
        $http.post(pathClte+'save', $scope.cliente).
        then(function(res)
        {
          swal('Se agregó correctamente el cliente');
          $scope.claveclte = $scope.cliente.clave;
          $scope.closeVerifClte();
        }).catch(function(err) {
          console.log(err);
        });
      }).catch(function(err)
      {
        console.log(err);
      });
     }else{
       $http.put(pathClte+'updatecliente/'+$scope.pedido.idcliente, $scope.cliente).
       then(function(res)
       {
         swal('Se actualizó correctamente el cliente');
         $scope.closeVerifClte();
       }).catch(function(err)
       {
         console.log(err);
       });
    }
  }

  $scope.getDomicilios = () =>{
    $http.get(pathClte+'getdomis/'+$scope.pedido.idcliente)
    .then(res=>{
      $scope.lstDomis = res.data;
    })
  }

  $scope.getEmpresa = () =>{
    $http.get(pathEmpr+'loadbyid/'+$scope.pedido.idempresa,{responseType:'json'})
    .then(res => {
      if(res.data.length > 0){
        $scope.empresa.nombre = res.data[0].NOMBRE;
        $scope.empresa.domicilio = res.data[0].DOMICILIO;
        $scope.empresa.rfc = res.data[0].RFC;
      }
    })
    .catch(err =>{
      console.log(err);
    });
  }

  $scope.selectRowPedido = function(docuento, indexRowPedido)
  {
    $scope.idDocumento = docuento;
    $scope.indexRowPedido = indexRowPedido;
    $scope.estatus = $scope.indexRowPedido != -1 && $scope.lstPedidos[indexRowPedido].ESTATUS ==='ACTIVO' && $scope.lstPedidos[indexRowPedido].VENDIDO === 'f';
  }

  $scope.manualenter = function()
	{
		if(!isNaN($scope.cantidad))
		{
			$scope.counter = Number($scope.cantidad);
		}else
		{
			$scope.cantidad = $scope.counter;
			swal('Sólo se aceptan números');
		}
	}

	$scope.increase = function()
	{
		$scope.counter += 1;
		$scope.cantidad = $scope.counter;
	}
	$scope.decrease = function()
	{
		if($scope.counter > 0)
		{
			$scope.counter = $scope.counter - 1;
			$scope.cantidad = $scope.counter;
		}
	}

  $scope.buscaprodbycodigo = function(event)
  {
    if(event.keyCode==13)
    {
      $http.get(pathProd+'prodbycode/'+$scope.producto.codigo_prodto+'/'+$scope.pedido.idempresa+'/'+$scope.pedido.idsucursal,{responseType: 'json'}).
      then(function(res)
      {
        if(res.data != false)
        {
          $scope.producto.prod_desc = res.data[0].DESCRIPCION;
          $scope.producto.precio = Number(res.data[0].PRECIO_LISTA).toFixed(2);
          $scope.producto.unidad = res.data[0].UNIDAD_MEDIDA;
          $scope.producto.imagePath = res.data[0].IMAGEN;
          $scope.producto.iva = res.data[0].IVA;
          $scope.producto.id_producto = res.data[0].ID_PRODUCTO;
          $scope.producto.codigo_prodto = res.data[0].CODIGO;
          $scope.producto.imagePath = res.data[0].IMAGEN;
          $scope.producto.cantProd = res.data[0].STOCK;
          $scope.producto.esPromo = res.data[0].ES_PROMO == 't' ? true:false;
          $scope.producto.esDscto = res.data[0].ES_DESCUENTO == 't' ? true:false;
          $scope.producto.descuento = res.data[0].PRECIO_DESCUENTO;
          $scope.producto.promocion = res.data[0].PRECIO_PROMO;
          $scope.isVerifExis = true;
          if($scope.producto.imagePath!='')
          {
            $('#imgfig').show();
          }
        }else
        {
          swal('No existe un producto y/o servicio con el código '+$scope.producto.codigo_prodto);
        }
      }).
      catch();
      }
    }

    $scope.buscprodbydesc = function(event)
    {
      if(event.keyCode != 13)
      {
        return;
      }
    	$scope.dispsearch = true;
    	var searchword = $scope.producto.prod_desc !='' ? $scope.producto.prod_desc : 'vacio';
    	$http.get(pathTpv+'getitems/'+$scope.pedido.idempresa+'/'+searchword+'/V', {responseType: 'json'}).
    	then(function(res)
    	{
    		if(res.status == '200')
    		{
          $scope.lstProdBusqueda = res.data;
    		}
    	}).catch(function(err)
    	{
    		console.log(err);
    	})
    }

    $scope.selectProdBus = function(idxRowListaBusq)
    {
      $scope.producto.codigo_prodto = $scope.lstProdBusqueda[idxRowListaBusq].CODIGO;
      $scope.producto.prod_desc = $scope.lstProdBusqueda[idxRowListaBusq].DESCRIPCION;
      $scope.producto.unidad = $scope.lstProdBusqueda[idxRowListaBusq].UNIDAD_MEDIDA;
      $scope.producto.precio = $scope.lstProdBusqueda[idxRowListaBusq].PRECIO_LISTA;
      $scope.producto.id_producto = $scope.lstProdBusqueda[idxRowListaBusq].ID_PRODUCTO;
      $scope.producto.imagePath = $scope.lstProdBusqueda[idxRowListaBusq].IMAGEN;
      $scope.producto.iva = $scope.lstProdBusqueda[idxRowListaBusq].IVA;
      $scope.producto.cantProd = $scope.lstProdBusqueda[idxRowListaBusq].STOCK;
      $scope.producto.esPromo = $scope.lstProdBusqueda[idxRowListaBusq].ES_PROMO == 't' ? true:false;
      $scope.producto.esDscto = $scope.lstProdBusqueda[idxRowListaBusq].ES_DESCUENTO == 't' ? true:false;
      $scope.producto.descuento = $scope.lstProdBusqueda[idxRowListaBusq].PRECIO_DESCUENTO;
      $scope.producto.promocion = $scope.lstProdBusqueda[idxRowListaBusq].PRECIO_PROMO;
      
      if($scope.imagePath!='')
      {
        $('#imgfig').show();
      }
      $('#mencant').prop('disabled',false);
      $('#cantidad').prop('disabled',false);
      $('#mascant').prop('disabled',false);
      $scope.closeDivSearch();

      $http.get(pathTpv+'getitemsbyprodsuc/'+$scope.producto.id_producto+'/'+$scope.pedido.idsucursal).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.qtyProdSuc = res.data[0].STOCK;
        }else {
          $scope.qtyProdSuc = 0;
        }
        if($scope.qtyProdSuc == 0)
        {
          $scope.agregaProd = false;
        }  
        $scope.isVerifExis = true;
        
      }).catch(function(err)
      {
        console.log(err);
      });
    }

    $scope.closeDivSearch = function()
    {
      $scope.dispsearch = false;
    	$scope.lstProdBusqueda = [];
    }

    $scope.agregaProducto = function()
    {
      var importe = 0.0;
      var cantDscto = 0;
      if(Number($scope.cantidad) == 0)
      {
        swal('La cantidad debe ser mayor a 0');
        $('#cantidad').focus();
        return;
      }
      if(Number($scope.cantidad) > Number($scope.producto.cantProd) && $scope.tipo_ps == 'P')
      {
        swal('La cantidad de productos ['+$scope.cantidad+'] seleccionados es mayor a la existencia ['+$scope.producto.cantProd+']');
        $scope.cantidad = $scope.producto.cantProd;
        $('#cantidad').focus();
        return;
      }
      $scope.regpedido = false;
      importe = $scope.producto.esDscto ?  Number($scope.cantidad * $scope.producto.precio * (1-$scope.producto.descuento/100)) : Number($scope.cantidad * $scope.producto.precio);
      var dataCompra =
      {
          DESCRIPCION:$scope.producto.prod_desc,
          CANTIDAD:$scope.cantidad,
          PRECIO_LISTA:$scope.producto.precio,
          IMPORTE:importe,
          CODIGO:$scope.producto.codigo_prodto,
          UNIDAD_MEDIDA:$scope.producto.unidad,
          IMG:$scope.producto.imagePath,
          IVA:$scope.producto.iva,
          ID_PRODUCTO:$scope.producto.id_producto,
          ESPROMO:$scope.producto.esPromo,
          ESDSCTO:$scope.producto.esDscto,
          PRECIO_PROMO:$scope.producto.promocion,
          DESCUENTO:$scope.producto.descuento
      };

      if($('#updtTblComp').val()=='T'){
        $scope.lstProdCompra[$scope.indexRowCompra] = dataCompra;
        $('#updtTblComp').val('F')
      }else
      {
        $scope.lstProdCompra.push(dataCompra);
        $scope.lstComplemento = [];
        for(var i=0;i<(rowNumFill-$scope.lstProdCompra.length);i++){
          let p = {
            val:0
          };
          p.val = i;
          $scope.lstComplemento.push(p);
        }
      }
      $scope.calculaValoresMostrar();
      $scope.setSelected($scope.lstProdCompra[0].CODIGO,0);
      $scope.borraProdenProgreso();
      if($scope.captura_rapida)
      {
        $scope.counter = 1;
    		$scope.cantidad = $scope.counter;
      }else {
        $scope.counter = 0;
    		$scope.cantidad = $scope.counter;
      }
      $scope.qtyProdSuc = -1;
      $scope.isVerifExis = false;
    }

    $scope.borraProdenProgreso = function()
    {
      $scope.producto.codigo_prodto = '';
      $scope.producto.prod_desc = '';
      $scope.producto.unidad = '';
      $scope.producto.precio = '';
      $scope.producto.imagePath = '';
      $scope.cantidad = 0;
      $scope.counter = 0;
      $scope.producto.id_producto = '';
      $scope.qtyProdSuc = -1;
      $scope.esDscto = false;
      $scope.producto.esPromo = false;
      $scope.agregaProd = true;
      $scope.isVerifExis = false;
      $('#imgfig').hide();
      $('#updtTblComp').val('F');
    }

    $scope.editaProducto = function()
    {
      $scope.producto.id_producto = $scope.lstProdCompra[$scope.indexRowCompra].ID_PRODUCTO;
      $scope.producto.codigo_prodto = $scope.lstProdCompra[$scope.indexRowCompra].CODIGO;
      $scope.producto.prod_desc = $scope.lstProdCompra[$scope.indexRowCompra].DESCRIPCION;
      $scope.producto.unidad = $scope.lstProdCompra[$scope.indexRowCompra].UNIDAD;
      $scope.producto.precio = $scope.lstProdCompra[$scope.indexRowCompra].PRECIO_LISTA;
      $scope.cantidad = $scope.lstProdCompra[$scope.indexRowCompra].CANTIDAD;
      $scope.counter = $scope.cantidad;
      $scope.producto.imagePath = $scope.lstProdCompra[$scope.indexRowCompra].IMG;
      $scope.producto.esPromo = $scope.lstProdCompra[$scope.indexRowCompra].ESPROMO;
      $scope.producto.esDscto = $scope.lstProdCompra[$scope.indexRowCompra].ESDSCTO;
      $scope.producto.promocion = $scope.lstProdCompra[$scope.indexRowCompra].PRECIO_PROMO;
      $scope.producto.descuento = $scope.lstProdCompra[$scope.indexRowCompra].DESCUENTO;
      //$scope.tipo_ps = $scope.lstProdCompra[$scope.indexRowCompra].TIPO_PS;

      if($scope.producto.imagePath!='')
      {
        $('#imgfig').show();
      }
      $('#updtTblComp').val('T');
    }

    $scope.borraProducto = function()
    {
      $scope.lstProdCompra.splice($scope.indexRowCompra,1);
      $scope.calculaValoresMostrar();

      if($scope.lstProdCompra.length > 0)
      {
        $scope.setSelected($scope.lstProdCompra[0].CODIGO,0);
      }else{
        $scope.regpedido = true;
      }
    }

    $scope.calculaDescInd = () =>{
      if(isNaN($scope.proddscnt.descuento)){
        swal('Solo se aceptan numeros');
        return;
      }
      if($scope.proddscnt.descuento > 10 ){
        swal('No puede dar un descuento mayor al 10%','Pida ayuda a su superior','error');
      }
      $scope.lstProdCompra[$scope.indexRowCompra].IMPORTE = $scope.lstProdCompra[$scope.indexRowCompra].PRECIO_LISTA * $scope.lstProdCompra[$scope.indexRowCompra].CANTIDAD * (1-($scope.proddscnt.descuento / 100));
      $scope.lstProdCompra[$scope.indexRowCompra].DESCUENTO = $scope.proddscnt.descuento;
      $scope.lstProdCompra[$scope.indexRowCompra].ESDSCTO = $scope.proddscnt.descuento > 0;
      $scope.calculaValoresMostrar();
    }

    $scope.calculaDescTodos = () =>{
      $scope.lstProdCompra.map(prod =>{  
        prod.ESDSCTO = $scope.proddscnt.descuentoTodos > 0;
        prod.DESCUENTO = $scope.proddscnt.descuentoTodos ;
        prod.IMPORTE = prod.PRECIO_LISTA * prod.CANTIDAD * (1-($scope.proddscnt.descuentoTodos / 100));
      });
      $scope.calculaValoresMostrar();
    }

    $scope.setSelectedDscnt = function(indexRowCompra)
    {
      $scope.indexRowCompra = indexRowCompra;
      $scope.proddscnt.producto = $scope.lstProdCompra[indexRowCompra].DESCRIPCION;
      $scope.proddscnt.precio= $scope.lstProdCompra[indexRowCompra].PRECIO_LISTA;
      $scope.proddscnt.descuento = $scope.lstProdCompra[indexRowCompra].DESCUENTO;
      
    }

    $scope.calculaValoresMostrar = () =>
    {
      $scope.pedido.subtotal = 0;
      $scope.pedido.total = 0;
      $scope.impuestos = 0;
      $scope.dsctoValor = 0;
      $scope.producto.esPromo = false;
      $scope.producto.esDscto = false;

      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        valUnit = Number ($scope.lstProdCompra[i].PRECIO_LISTA) / (1+Number($scope.lstProdCompra[i].IVA/100));
        $scope.pedido.subtotal += Number($scope.lstProdCompra[i].CANTIDAD) * valUnit;
        $scope.dsctoValor += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) * ($scope.lstProdCompra[i].ESDSCTO ? Number($scope.lstProdCompra[i].DESCUENTO/100) : 0);
        $scope.impuestos += Number($scope.lstProdCompra[i].CANTIDAD) * valUnit * Number($scope.lstProdCompra[i].IVA/100);
      }
      $scope.pedido.total = $scope.pedido.subtotal - $scope.dsctoValor + $scope.impuestos;
    }

    $scope.buscacliente = function(event)
    {
      var searchword;
      searchword = $scope.nombre_cliente != '' ? $scope.nombre_cliente : 'vacio';
      $http.get(pathClte+'loadbynombre/'+$scope.pedido.idempresa +'/'+$scope.pedido.aniofiscal+'/'+searchword).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.lstCliente = res.data;
          $scope.showLstClte = true;
        }else {
          $scope.showLstClte = false;
        }
      }).catch(function(err)
      {
        console.log(err);
      });
    }

    $scope.seleccionaCliente = function(indxRowClte)
    {
      $scope.claveclte = $scope.lstCliente[indxRowClte].CLAVE;
      $scope.nombre_cliente = $scope.lstCliente[indxRowClte].NOMBRE;
      $scope.cliente.domicilio = $scope.lstCliente[indxRowClte].DOMICILIO;
      $scope.cliente.telefono = $scope.lstCliente[indxRowClte].TELEFONO;
      $scope.rfc_cliente = $scope.lstCliente[indxRowClte].RFC;
      $scope.cliente.id_uso_cfdi = $scope.lstCliente[indxRowClte].ID_USO_CFDI;      
      $scope.pedido.idcliente = $scope.lstCliente[indxRowClte].ID_CLIENTE; 
      $scope.pedido.fpago = $scope.lstCliente[indxRowClte].ID_FORMA_PAGO;
      $scope.pedido.tpago = $scope.lstCliente[indxRowClte].DIAS_CREDITO > 0 ? 2 : 1;
      $scope.pedido.mpago = $scope.lstCliente[indxRowClte].DIAS_CREDITO === 0 ? 1 : -1;
      $scope.nombre_vendedor = $scope.lstCliente[indxRowClte].VENDEDOR;
      $scope.pedido.idvendedor = $scope.lstCliente[indxRowClte].ID_VENDEDOR;
      $scope.lstCliente = [];
      $scope.showLstClte = false;      
      $scope.getDomicilios();
    }

    $scope.buscacodcliente = (event) =>{
      if(event.keyCode==13){
        $http.get(pathClte+'findbycode/'+$scope.claveclte+'/'+$scope.pedido.idempresa)
        .then(res=>{
          if(res.data){
            $scope.claveclte = res.data[0].CLAVE.trim();
            $scope.nombre_cliente =res.data[0].NOMBRE;
            $scope.rfc_cliente = res.data[0].RFC;
            $scope.cliente.id_uso_cfdi = res.data[0].ID_USO_CFDI;      
            $scope.cliente.telefono = Number(res.data[0].TELEFONO);
            $scope.cliente.domicilio = res.data[0].DOMICILIO;
            $scope.pedido.idcliente = res.data[0].ID_CLIENTE;  
            $scope.pedido.fpago = res.data[0].ID_FORMA_PAGO.toString();
            $scope.pedido.tpago = res.data[0].DIAS_CREDITO > 0 ? 2 : 1;
            $scope.pedido.mpago = res.data[0].DIAS_CREDITO === 0 ? 1 : -1;
            if(res.data[0].ID_VENDEDOR){
              $http.get(pathVend+'findvendbyid/'+res.data[0].ID_VENDEDOR+'/'+$scope.pedido.idempresa)
              .then(resp =>{
                if(resp){
                  $scope.pedido.idvendedor = resp.data[0].ID_VENDEDOR;
                  $scope.nombre_vendedor = resp.data[0].NOMBRE;
                }else{
                  $scope.pedido.idvendedor = '';
                  $scope.nombre_vendedor = '';
                }
              }).catch(err =>{
                console.log(err);
              });
            }else{
              $scope.pedido.idvendedor = '';
              $scope.nombre_vendedor = '';
            }
            
            $scope.getDomicilios();         
          }else{
            swal('No existe el cliente con codigo '+$scope.claveclte+', puede hacer la búsqueda por nombre');
            $scope.nombre_cliente = '';
            $scope.claveclte = '';
            return;
          }
        })
        .catch(err=>{

        });
      }
    }

    $scope.closeClteSearch = function()
    {
      $scope.lstCliente = [];
      $scope.showLstClte = false;
    }

    $scope.buscavendedor = function(event)
    {
      var searchword;
      searchword = $scope.nombre_vendedor != '' ? $scope.nombre_vendedor : 'vacio';
      $http.get(pathVend+'getvendedores/'+$scope.pedido.idempresa+'/'+searchword).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.lstVendedor = res.data;
          $scope.listaVendedores = true;
        }else {
          $scope.lstVendedor = [];
          $scope.listaVendedores = false;
        }
      }).catch(function(err)
      {
        console.log(err);
      });
    }

    $scope.seleccionaVendedor = function(indxRowClte)
    {
      $scope.pedido.idvendedor = $scope.lstVendedor[indxRowClte].ID_VENDEDOR;
      $scope.nombre_vendedor = $scope.lstVendedor[indxRowClte].NOMBRE;
      $scope.lstVendedor = [];
      $scope.listaVendedores = false;
    }

    $scope.buscacodvendedor = (event) =>{
      if(event.keyCode==13){
        $http.get(pathVend+'findvendbyid/'+$scope.pedido.idvendedor+'/'+$scope.pedido.idempresa)
        .then(res=>{
          if(res.data){
            $scope.pedido.idvendedor = res.data[0].ID_VENDEDOR;
            $scope.nombre_vendedor = res.data[0].NOMBRE;
          }else{
            swal('No existe el vendedor con codigo '+$scope.pedido.idvendedor+', puede hacer la búsqueda por nombre');
            $scope.pedido.idvendedor = '';
            $scope.nombre_vendedor = ''; 
            return;
          }
        })
      }
    }

    $scope.closeVendSearch = function()
    {
      $scope.lstVendedor = [];
      $scope.listaVendedores = false;
    }

    $scope.fecEntrega = function(){
      var e = jQuery.Event("keydown");
      e.which = 13; // # Some key code value
      $("#fechaInicio").trigger(e);
      $scope.pedido.fechaentrega = formatFecQuery($('#fechaentrega').val(),'ini');
      $scope.fechaentrega = formatFecPagodmy($('#fechaentrega').val());
    }
    
    $scope.registraPedido = function()
    { 
      if($scope.fechaentrega === ''){
        swal('Seleccione una Fecha de Entrega');
        return;
      }
      $scope.pedido.fechapedido = formatDateInsert(new Date());
      $scope.pedido.dias = $scope.pedido.dias == '' ? null : $scope.pedido.dias;
      $scope.pedido.cuenta = $scope.pedido.cuenta == '' ? null : $scope.pedido.cuenta;
      $scope.pedido.fechaentrega = $scope.pedido.fechaentrega == '' ? null :$scope.pedido.fechaentrega;
      $scope.pedido.idvendedor = $scope.pedido.idvendedor == '' ? null : $scope.pedido.idvendedor;
      //Esto bloquea el boton de pedido
      swal({
        title: "Esta seguro que desea guardar el Pedido?",
        text: $scope.pedido.docto,
        icon: "warning",
        buttons: [true,true],
        dangerMode: true,
      })
      .then((willAdd) => {
        if(willAdd){
          $scope.showProgressBar = true;
          $scope.regpedido = true;
          $http.post(pathPedi+'registrapedido',$scope.pedido).
          then(function(res)
          {
            $scope.idPedido = res.data[0].registra_pedido;
            $scope.registraPedidoProd();
            swal('El pedido se registro exitosamente');
            $scope.isImprimir = true;
            $scope.isRegistra = false;
            //$scope.limpiar();
            $scope.getpedidos();
            $scope.showProgressBar = false;
          }).
          catch(function(err)
          {
            swal('Ocurrio un error la guardar el pedido '+err.message);
          });
        }
      });
      
    }

    $scope.registraPedidoProd = function()
    {
      var pediProd = {};
      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        pediProd =
        {
          idpedido:$scope.idPedido,
          idProducto:$scope.lstProdCompra[i].ID_PRODUCTO,
          cantidad:$scope.lstProdCompra[i].CANTIDAD,
          precio:$scope.lstProdCompra[i].PRECIO_LISTA,
          importe:$scope.lstProdCompra[i].IMPORTE,
          descuento:$scope.lstProdCompra[i].DESCUENTO == null ? 0 : $scope.lstProdCompra[i].DESCUENTO
        }
        $http.put(pathPedi+'registrapedidoprod',pediProd).
        then(function(res)
        {
          /*se insertó con éxito*/
        }).
        catch(function(err)
        {
          console.log(err);
        });
      }
    }

    $scope.actualizaPedido = () =>{
      if($scope.fechaentrega === ''){
        swal('Seleccione una Fecha de Entrega');
        return;
      }
      $scope.pedido.fechaentrega = formatFecQuery($('#fechaentrega').val(),'ini');
      $scope.pedido.idvendedor = $scope.pedido.idvendedor == '' ? null : $scope.pedido.idvendedor;
      $scope.idPedido = $scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO;
      swal({
        title: "Esta seguro que desea actualizar el Pedido?",
        text: $scope.pedido.docto.toString(),
        icon: "warning",
        buttons: [true,true],
        dangerMode: true,
      })
      .then((willAdd) => {
        if(willAdd){
          $http.delete(pathPedi+'deletepedprodbyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO)
          .then(res =>{
            $http.put(pathPedi+'updatepedbyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO,$scope.pedido)
            .then(resp=>{
              $scope.idDocumento = '';
              $scope.indexRowPedido = -1;
              $scope.registraPedidoProd();
              $scope.getpedidos();
              $scope.isImprimir = true;
              $scope.isActualiza = false;
              swal('Se actualizó el pedido');
            })
            .catch(err=>{
              console.log(err);
            });
          })
          .catch(err=>{
            console.log(err);
          });
        }
      });
    }
    
    $scope.verificaExistencia = function()
    {
      $scope.modalVerifProdSuc = true;
      $http.get(pathTpv+'getproductosforsucursal/'+$scope.producto.id_producto,{responseType:'json'}).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.lstPrdSucExis = res.data;
        }
      }).
      catch(function(err)
      {
        console.log(err);
      });
    }

    $scope.selectMPago = () =>{
      $scope.mpago_style = {background:'lightgreen'};
    }

    $scope.addDescuento = ()=>{
      $scope.modalAddDscnt = true;
    }

    $scope.closeAddDscnt = ()=>{
      $scope.proddscnt.producto = undefined;
      $scope.modalAddDscnt = false;
      $scope.proddscnt.descuento = '';
      $scope.proddscnt.descuentoTodos = '';
      $scope.indexRowCompra = -1
    }

    $scope.escondeRenglon = () =>{
      $scope.proddscnt.producto = undefined;
      $scope.indexRowCompra = -1
    }

    $scope.abrePedido = function(){
      $http.get(pathPedi+'getpedidobyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO,{responseType:'json'})
          .then(res=>{
            if(res.data.length > 0){
              $scope.pedido.docto = res.data[0].DOCUMENTO;
              $scope.claveclte = res.data[0].CLAVE;
              $scope.nombre_cliente = res.data[0].CLIENTE;
              $scope.pedido.idvendedor  = res.data[0].ID_VENDEDOR;
              $scope.nombre_vendedor  = res.data[0].VENDEDOR;
              $scope.pedido.comentarios = res.data[0].COMENTARIOS;
              $scope.pedido.cuenta = res.data[0].CUENTA == null ? '' :res.data[0].CUENTA ;
              $scope.pedido.dias = res.data[0].DIAS == null ? '' :res.data[0].DIAS ;
              $scope.pedido.idmoneda = res.data[0].ID_MONEDA;
              $scope.pedido.tpago = res.data[0].ID_TIPO_PAGO;
              $scope.pedido.fpago = res.data[0].ID_FORMA_PAGO.toString();
              $scope.fechaentrega = res.data[0].FECHA_ENTREGA;
              $("#fechaentrega").val(res.data[0].FECHA_ENTREGA);
              $scope.rfc_cliente = res.data[0].RFC;
              $scope.pedido.mpago = res.data[0].ID_METODO_PAGO;
              $scope.cliente.domicilio = res.data[0].CLI_DOMICILIO;
              $scope.cliente.telefono = Number(res.data[0].TELEFONO);
              $scope.pedido.domi = res.data[0].DOMICILIO;
              $scope.isCapturaPedido = true;
              $scope.pedido.idcliente = res.data[0].ID_CLIENTE;
              $scope.domientrega.calle =res.data[0].CALLE;
              $scope.domientrega.colonia =res.data[0].COLONIA;
              $scope.domientrega.cp =res.data[0].CP;
              $scope.domientrega.ciudad =res.data[0].CIUDAD;
              $scope.domientrega.contacto =res.data[0].CONTACTO;
              $scope.isRegistra = false;
              $scope.getDomicilios();
              if(res.data[0].ESTATUS === 'ACTIVO' && res.data[0].VENDIDO === 'f'){
                $scope.regpedido = false;
                $scope.isImprimir = false;
                $scope.isActualiza = true;
              }else{
                $scope.isImprimir = true;
                $scope.isActualiza = false;
                
              }
            }
          })
          .catch(err=>{
            console.log(err);
          });

      $http.get(pathPedi+'getpedidetallebyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO,{responseType:'json'})
          .then(res=>{
            if(res.data.length > 0){
              $scope.lstProdCompra = res.data;
              $scope.calculaValoresMostrar();
            }
          })
          .catch(err=>{
            console.log(err);
          });
        
    }

    $scope.preguntaElimnaPedido = function(){
      $scope.doctoEliminar = $scope.lstPedidos[$scope.indexRowPedido].DOCUMENTO;
      $scope.pregElimiPedi = true;
    }

    $scope.cerrarEliminaPedido = function(){
      $scope.pregElimiPedi = false;
    }

    $scope.borraPedido = function(){
      swal({
        title: "Esta seguro que desea cancelar el pedido "+$scope.lstPedidos[$scope.indexRowPedido].DOCUMENTO,
        text: "Una vez cancelado, no se podrá recuperar!",
        icon: "warning",
        buttons: [true,true],
        dangerMode: true,
      })
      .then((willDelete) => {
        if(willDelete){
          $http.delete(pathPedi+'elimpedidobyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO)
          .then(res => {
            $scope.pregElimiPedi = false;
            $scope.indexRowPedido = -1;
            swal('Se eliminó el pedido!');
            $scope.getpedidos();
          })
          .catch(err => {
            console.log(err);
          });
        }
      })
      
    }

    $scope.orderByMe = function(valorOrden)
    {
      $scope.orderBy = valorOrden;
      $scope.sortDir = !$scope.sortDir;
    }

    $scope.agregaPEdido = function(){
      $scope.mpago_style = {background:'pink'};
      $scope.isCapturaPedido = true;
      $scope.getNextDocPedido();
    }

    $scope.cancelaPedido = function(){
      $scope.isCapturaPedido = false;
      $scope.isImprimir = false;
      $scope.limpiar();
    }
    
    $scope.closeVerifProdSuc = function(){
      $scope.modalVerifProdSuc = false;
    }

    $scope.cambiaDomicilio = () =>{
      let cmboIdx = $('#domicilios').prop('selectedIndex');
      $scope.domientrega.calle = $scope.lstDomis[cmboIdx].CALLE;
      $scope.domientrega.colonia = $scope.lstDomis[cmboIdx].COLONIA;
      $scope.domientrega.cp = $scope.lstDomis[cmboIdx].CP;
      $scope.domientrega.ciudad = $scope.lstDomis[cmboIdx].CIUDAD;
      $scope.domientrega.contacto = $scope.lstDomis[cmboIdx].CONTACTO;
    }

    $scope.imprimePedido = function(printSectionId) {
      
      var innerContents = document.getElementById(printSectionId).innerHTML;
      //var popupWinindow = window.open('', '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
      var popupWinindow = window.open(' ', 'popimpr');
      //popupWinindow.document.open();
      popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="style.css" /></head><body onload="window.print()">' + innerContents + '</html>');
      popupWinindow.print();
      popupWinindow.close();
    }

    $scope.limpiar = function(){
      $scope.lstProdCompra = [];
        $scope.total = 0.0;
        $scope.cambio = 0.0;
        $scope.pedido.docto = '';
        $scope.pedido.idcliente = '';
        $scope.nombre_cliente = '';
        $scope.rfc_cliente = '';
        $scope.claveclte = '';
        $scope.pedido.idvendedor = '';
        $scope.nombre_vendedor = '';
        $scope.impuestos = 0.0;
        $scope.regpedido = true;
        $scope.isRegistra = true;
        $scope.isImprimir = false;
        $scope.isActualiza = false;
        $scope.pedido.comentarios = '';
        $scope.pedido.fechaentrega = '';
        $scope.fechaentrega = '';
        $scope.pedido.dias = '';
        $scope.pedido.cuenta = '';
        $scope.pedido.idmoneda = 1;
        $scope.pedido.total = '';
        $scope.mpago_style = {background:'pink'};
    }

});

  function DisplayCurrentTime() {
      var date = new Date();
      var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
      var am_pm = date.getHours() >= 12 ? "PM" : "AM";
      hours = hours < 10 ? "0" + hours : hours;
      var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
      time = hours + ":" + minutes + " " + am_pm;
      return time;
    }
