app.controller('myCtrlFacturacion', function($scope,$http,$interval,$routeParams)
{
  $scope.fecha = formatDatePrint(new Date());
  $scope.fechaPantalla = formatDatePantalla(new Date());
  $scope.hora = DisplayCurrentTime();
  $scope.doctoTmp = '';
  $scope.idVendedor = '';
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
  $scope.idDocumento = '';
  $scope.indexRowFactura = -1;
  $scope.indexRowPedido = -1;
  $scope.idPedido = 0;
  $scope.importeNeto = 0;
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
  $scope.showEliminaFactura = false;
  $scope.modalVerfClte = false;
  $scope.showLstClte = false;
  $scope.isLstVendedor = false;
  $scope.dispsearch = false;
  $scope.btnVerifClte = 'Actualizar';
  $scope.tcaptura = 'D';
  $scope.lstMoneda = [];
  $scope.lstMetpago = [];
  $scope.lstFormpago = [];
  $scope.lstTipopago = [];
  $scope.lstUsocfdi = [];
  $scope.lstFacturas = [];
  $scope.isCapturaFactura = false;
  $scope.regfactura = true;
  $scope.isImprimir = true;
  $scope.pregElimiPedi = false;
  $scope.modalAddDscnt = false;
  $scope.doctoEliminar = '';
  $scope.idUsuario = '';
  $scope.showInputData = false;
  $scope.idProceso = $routeParams.idproc;
  $scope.proddscnt ={
    producto:undefined,
    precio:undefined,
    descuento:0,
    descuentoTodos:0
  }
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.factura = {
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
    fpago : '01',
    mpago : 1,
    iva : '',
    descuento : 0,
    cfdi:1,
    bruto:0
  };

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
      promocion : 0,
      tipo_ps:''
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
  $scope.init = function()
  {
    $scope.regfactura = true;
    $http.get(pathAcc+'getdata',{responseType:'json'}).
    then(function(res){
      if(res.data.value=='OK'){
        $scope.idUsuario = res.data.idusuario;
        $scope.factura.idempresa = res.data.idempresa;
        $scope.factura.idsucursal = res.data.idsucursal;
        $scope.factura.aniofiscal = res.data.aniofiscal;
        $scope.getfacturas();
        $scope.getNextDocTpv();
        $scope.getUsoCfdi();
        $scope.getMoneda();
        $scope.getFormPago();
        $scope.getTipoPago();
        $scope.getMetPago();
        $scope.permisos();
      }
    }).catch(function(err){
      console.log(err);
    });      
  }
  
  $scope.getNextDocTpv = function(){
		$http.get(pathUtils+'incremento/TPVS/'+$scope.factura.idempresa+'/7').
		then(function(res)
		{
			if(res.data.length > 0)
			{
				$scope.factura.docto = res.data[0].VALOR;
				$scope.doctoTmp = res.data[0].VALOR;
			}
		}).catch(function(err)
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

  $scope.getfacturas = function(){
    $http.get(pathFactura+'getfacturas/'+$scope.factura.idempresa+'/'+$scope.factura.aniofiscal,{responseType:'json'})
        .then(res => {
          if(res.data.length > 0){
            $scope.lstFacturas = res.data;
          }
        })
        .catch(err => {
          console.log(err);
        });
  }

  $scope.getFacturaDetalleyId = function(idFactura){
    $http.get(pathFactura+'getfactdetbyid/'+idFactura,{responseType:'json'})
        .then(res => {
          if(res.data.length > 0){
            $scope.lstProdCompra = res.data;
            $scope.calculaValoresMostrar();
            $scope.regfactura = true;
          }
        })
        .catch(err => {
          console.log(err);
        });
  }

  $scope.eliminarFactura = function(){
    $http.delete(pathFactura+'eliminafact/'+$scope.lstFacturas[$scope.indexRowFactura].ID_VENTA+'/'+$scope.factura.idsucursal)
    .then(res=>{
      $scope.lstFacturas.splice($scope.indexRowFactura,1);
      $scope.showEliminaFactura = false;
    })
    .catch(err =>{
      console.log(err);
    });
  }

  $scope.entrydata = function(){
    if($scope.tcaptura == 'P' || $scope.tcaptura == 'p'){
      $scope.showInputData = true;
      $scope.getpedidos();
    }
  }

  $scope.closeInputData = function(){
    $scope.showInputData = false;
  }

  $scope.abreFactura = function(){
    $scope.isCapturaFactura = true;
    $scope.regfactura = true;
    $scope.factura.docto = $scope.lstFacturas[$scope.indexRowFactura].DOCUMENTO;
    $scope.nombre_cliente = $scope.lstFacturas[$scope.indexRowFactura].CLIENTE;
    $scope.claveclte = $scope.lstFacturas[$scope.indexRowFactura].CLAVE;
    $scope.factura.idvendedor = $scope.lstFacturas[$scope.indexRowFactura].ID_VENDEDOR;
    $scope.nombre_vendedor = $scope.lstFacturas[$scope.indexRowFactura].VENDEDOR;
    $scope.claveclte = $scope.lstFacturas[$scope.indexRowFactura].CLAVE;
    $scope.getFacturaDetalleyId($scope.lstFacturas[$scope.indexRowFactura].ID_FACTURA);
  }

  $scope.agregaFactura = function(){
    $scope.isCapturaFactura = true;
    $scope.isImprimir = false;
  }

  $scope.cierraFactura = function(){
    $scope.isCapturaFactura = false;
    $scope.isImprimir = true;
    $scope.limpiar();
  }

  $scope.VerificarCliente = function()
  {
      if($scope.claveclte != '')
      {
        $http.get(pathClte+'loadbyidverfi/'+$scope.factura.idcliente,{responseType:'json'}).
        then(function(res)
        {
          if(res.data.length > 0)
          {
            $scope.cliente.clave = res.data[0].CLAVE;
            $scope.cliente.nombre = res.data[0].NOMBRE;
            $scope.cliente.domicilio = res.data[0].DOMICILIO;
            $scope.cliente.telefono = res.data[0].TELEFONO;
            $scope.cliente.cp = res.data[0].CP;
            $scope.cliente.contacto = res.data[0].CONTACTO;
            $scope.cliente.rfc = res.data[0].RFC;
            $scope.cliente.curp = res.data[0].CURP;
            $('#id_tipo_cliente').val(res.data[0].ID_TIPO_CLIENTE);
            $('#revision').val(res.data[0].ID_REVISION);
            $('#pagos').val(res.data[0].ID_PAGOS);
            $('#id_forma_pago').val(res.data[0].ID_FORMA_PAGO);
            $scope.idVendedor =res.data[0].ID_VENDEDOR;
            $('#id_uso_cfdi').val(res.data[0].ID_USO_CFDI);
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
    $scope.cliente.id_forma_pago=$('#id_forma_pago').val();
    $scope.cliente.id_vendedor=$scope.idVendedor;
    //$scope.cliente.id_uso_cfdi=$('#id_uso_cfdi').val();
    $scope.cliente.idempresa = $scope.factura.idempresa;

    if($scope.btnVerifClte == 'Agregar')
    {
      $http.get(pathUtils+'incremento/CLTE/'+$scope.factura.idempresa+'/4').
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
       $http.put(pathClte+'update/'+$scope.factura.idcliente, $scope.cliente).
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

  $scope.getpedidos = function(){
    $http.get(pathPedi+'getpedidos/'+$scope.factura.idempresa+'/'+$scope.factura.aniofiscal)
      .then(res =>{
        if(res.data.length > 0 ){
          $scope.lstPedidos = res.data;
        }
      });
  }

  $scope.seleccionarPedido = function(){
    $http.get(pathPedi+'getpedidobyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO)
        .then(res=>{
          $scope.factura.docto = res.data[0].DOCUMENTO;
          $scope.claveclte = res.data[0].CLAVE;
          $scope.nombre_cliente = res.data[0].CLIENTE;
          $scope.factura.idcliente = res.data[0].ID_CLIENTE;
          $scope.factura.idvendedor = res.data[0].ID_VENDEDOR;
          $scope.nombre_vendedor = res.data[0].VENDEDOR;
          $scope.factura.contacto = res.data[0].CONTACTO;
          $scope.factura.tpago = res.data[0].ID_TIPO_PAGO;
          $scope.factura.fpago = res.data[0].ID_FORMA_PAGO < 10 ? '0'+res.data[0].ID_FORMA_PAGO : res.data[0].ID_FORMA_PAGO+'';
          $scope.factura.cuenta = res.data[0].CUENTA == null ? '' : res.data[0].CUENTA.trim();
          $scope.factura.idmoneda = res.data[0].ID_MONEDA;
          $scope.factura.dias = res.data[0].DIAS;
          //$scope.factura.tpago = res.data[0].ID_TIPO_PAGO > 10 ? '0'+res.data[0].ID_TIPO_PAGO : res.data[0].ID_TIPO_PAGO+'';
          $scope.idPedido = $scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO;
        })
        .catch(err=>{
          console.log(err);
        });
    
    $http.get(pathPedi+'getpedidetallebyid/'+$scope.lstPedidos[$scope.indexRowPedido].ID_PEDIDO)
        .then(res=>{
          $scope.lstProdCompra = res.data;
          $scope.calculaValoresMostrar();
        })
        .catch(err=>{
          console.log(err);
        });
    $scope.closeInputData();
  }

  $scope.selectRowFactura = function(docuento, indexRowFactura)
  {
    $scope.idDocumento = docuento;
    $scope.indexRowFactura = indexRowFactura;
  }

  $scope.selectRowPedido = function(docuento, indexRowPedido)
  {
    $scope.idDocumento = docuento;
    $scope.indexRowPedido = indexRowPedido;
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
      $http.get(pathProd+'prodbycode/'+$scope.producto.codigo_prodto+'/'+$scope.factura.idempresa+'/'+$scope.factura.idsucursal,{responseType: 'json'}).
      then(function(res)
      {
        if(res.data != false)
        {
          $scope.producto.prod_desc = res.data[0].DESCRIPCION;
          $scope.producto.precio = Number(res.data[0].PRECIO_LISTA).toFixed(2);
          $scope.producto.unidad = res.data[0].UNIDAD_MEDIDA;
          $scope.producto.imagePath = res.data[0].IMAGEN;
          $scope.producto.iva = res.data[0].IVA;
          $scope.producto.codigo_prodto = res.data[0].CODIGO;
          $scope.producto.id_producto = res.data[0].ID_PRODUCTO;
      
          $scope.producto.cantProd = res.data[0].STOCK;
          $scope.producto.esPromo = res.data[0].ES_PROMO == 't' ? true:false;
          $scope.producto.esDscto = res.data[0].ES_DESCUENTO == 't' ? true:false;
          $scope.producto.descuento = res.data[0].PRECIO_DESCUENTO;
          $scope.producto.promocion = res.data[0].PRECIO_PROMO;
          $scope.producto.tipo_ps = res.data[0].TIPO_PS;
          if($scope.producto.imagePath!='')
          {
            $('#imgfig').show();
          }
          if($scope.producto.tipo_ps =='S'){
            $scope.isVerifExis = false;
          }else if($scope.producto.tipo_ps =='P'){
            $scope.isVerifExis = true;
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
    	$http.get(pathTpv+'getitems/'+$scope.factura.idempresa+'/'+searchword+'/V', {responseType: 'json'}).
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
      $scope.producto.tipo_ps = $scope.lstProdBusqueda[idxRowListaBusq].TIPO_PS;
      if($scope.imagePath!='')
      {
        $('#imgfig').show();
      }
      $('#mencant').prop('disabled',false);
      $('#cantidad').prop('disabled',false);
      $('#mascant').prop('disabled',false);
      $scope.closeDivSearch();

      $http.get(pathTpv+'getitemsbyprodsuc/'+$scope.producto.id_producto+'/'+$scope.factura.idsucursal).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.qtyProdSuc = res.data[0].STOCK;
        }else {
          $scope.qtyProdSuc = 0;
        }
        if($scope.qtyProdSuc == 0 && $scope.producto.tipo_ps == 'P')
        {
          $scope.agregaProd = false;
        }  
        if($scope.producto.tipo_ps =='S')
        {
          $scope.isVerifExis = false;
        }else if($scope.producto.tipo_ps =='P')
        {
          $scope.isVerifExis = true;
        }
        
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
      if(!$scope.agregaProd){
        swal('Actualmente no cuenta con existencia de este producto, en esta sucursal. Consulte existencias');
        return;
      }
      if(Number($scope.cantidad) > Number($scope.producto.cantProd) && $scope.producto.tipo_ps == 'P')
      {
        swal('La cantidad de productos ['+$scope.cantidad+'] seleccionados es mayor a la existencia ['+$scope.producto.cantProd+']');
        $scope.cantidad = $scope.producto.cantProd;
        $('#cantidad').focus();
        return;
      }
      
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
          DESCUENTO:$scope.producto.descuento,
          TIPO_PS:$scope.producto.tipo_ps
      };

      if($('#updtTblComp').val()=='T'){
        $scope.lstProdCompra[$scope.indexRowCompra] = dataCompra;
        $('#updtTblComp').val('F')
      }else
      {
          $scope.lstProdCompra.push(dataCompra);
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
      $scope.producto.tipo_ps = $scope.lstProdCompra[$scope.indexRowCompra].TIPO_PS;
     
      if($scope.producto.tipo_ps == 'P')
      {
        $scope.isVerifExis = true;
      }else {
        $scope.isVerifExis = false;
      }

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
        $scope.regfactura = true;
      }
    }

    $scope.calculaValoresMostrar = function()
    {
      $scope.factura.bruto = 0;
      $scope.factura.total = 0;
      $scope.importeNeto = 0;
      $scope.impuestos = 0;
      $scope.dsctoValor = 0;
      $scope.producto.esPromo = false;
      $scope.producto.esDscto = false;
      
      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        $scope.factura.bruto += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) ;
        //$scope.factura.total += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) ;
        $scope.dsctoValor += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) * ($scope.lstProdCompra[i].ESDSCTO ? Number($scope.lstProdCompra[i].DESCUENTO/100) : 0);
        $scope.importeNeto += (Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) - (Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) * ($scope.lstProdCompra[i].ESDSCTO ? Number($scope.lstProdCompra[i].DESCUENTO/100) : 0)) )   / (1+Number($scope.lstProdCompra[i].IVA/100));
        $scope.impuestos += (Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) - (Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) * ($scope.lstProdCompra[i].ESDSCTO ? Number($scope.lstProdCompra[i].DESCUENTO/100) : 0)) )/ (1+Number($scope.lstProdCompra[i].IVA/100)) * Number($scope.lstProdCompra[i].IVA/100);
      }
      $scope.factura.total = $scope.factura.bruto - $scope.dsctoValor;
      $scope.regfactura = $scope.factura.total <= 0;
    }

    $scope.buscacliente = function(event)
    {
      var searchword;
      searchword = $scope.nombre_cliente != '' ? $scope.nombre_cliente : 'vacio';
      $http.get(pathClte+'loadbynombre/'+$scope.factura.idempresa +'/'+ $scope.factura.aniofiscal+'/'+searchword).
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
      $scope.rfc_cliente = $scope.lstCliente[indxRowClte].RFC;
      $scope.cliente.id_uso_cfdi = $scope.lstCliente[indxRowClte].ID_USO_CFDI;      
      $scope.factura.idcliente = $scope.lstCliente[indxRowClte].ID_CLIENTE;           
      $scope.lstCliente = [];
      $scope.showLstClte = false;      
    }

    $scope.buscacodcliente = (event) =>{
      if(event.keyCode==13){
        $http.get(pathClte+'findbycode/'+$scope.claveclte+'/'+$scope.factura.idempresa)
        .then(res=>{
          if(res.data){
            $scope.claveclte = res.data[0].CLAVE.trim();
            $scope.nombre_cliente =res.data[0].NOMBRE;
            $scope.rfc_cliente = res.data[0].RFC;
            $scope.cliente.id_uso_cfdi = res.data[0].ID_USO_CFDI;              
            $scope.factura.idcliente = res.data[0].ID_CLIENTE;
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
      $http.get(pathVend+'getvendedores/'+$scope.factura.idempresa+'/'+searchword).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.lstVendedor = res.data;
          $scope.isLstVendedor = true;
        }else {
          $scope.lstVendedor = [];
          $scope.isLstVendedor = false;
        }
      }).catch(function(err)
      {
        console.log(err);
      });
    }

    $scope.getvendedores = function(){
      $http.get(pathVend+'getvendedores/'+$scope.factura.idempresa+'/vacio').
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.lstVendedorVer = res.data;
        }else {
          $scope.lstVendedorVer = [];
        }
      }).catch(function(err)
      {
        console.log(err);
      });
    }

    $scope.cambioTpago = function(){
      if($scope.factura.tpago == 1){
        $scope.factura.dias = '';
      }
    }

    $scope.seleccionaVendedor = function(indxRowClte)
    {
      $scope.factura.idvendedor = $scope.lstVendedor[indxRowClte].ID_VENDEDOR;
      $scope.nombre_vendedor = $scope.lstVendedor[indxRowClte].NOMBRE;
      $scope.closeVendSearch();
    }

    $scope.buscacodvendedor = (event) =>{
      if(event.keyCode==13){
        $http.get(pathVend+'findvendbyid/'+$scope.factura.idvendedor+'/'+$scope.factura.idempresa)
        .then(res=>{
          if(res.data){
            $scope.factura.idvendedor = res.data[0].ID_VENDEDOR;
            $scope.nombre_vendedor = res.data[0].NOMBRE;
          }else{
            swal('No existe el vendedor con codigo '+$scope.factura.idvendedor+', puede hacer la búsqueda por nombre');
            $scope.factura.idvendedor = '';
            $scope.nombre_vendedor = ''; 
            return;
          }
        })
      }
    }

    $scope.closeVendSearch = function()
    {
      $scope.lstVendedor = [];
      $scope.isLstVendedor = false;
    }
    
    $scope.registraFactura = function()
    { 
      var dataVenta =
      {
        documento:$scope.factura.docto,
        idcliente:$scope.factura.idcliente,
        idvendedor:$scope.factura.idvendedor,
        fechaventa:formatDateInsert(new Date()),
        aniofiscal:$scope.factura.aniofiscal,
        idempresa:$scope.factura.idempresa,
        idtipopago:$scope.factura.tpago,
        pagoefectivo:$scope.factura.tpago == 1 ? $scope.factura.total : 0,
        pagotarjeta:0,
        pagocheques:0,
        pagovales:0,
        idtarjea:null,
        idbanco:null,
        idvales:null,
        idtarjeta:null,
        idbanco:null,
        idvales:null,
        importe:$scope.factura.total,
        cambio:0,
        idsucursal:$scope.factura.idsucursal,
        facturado:'true',
        idfactura:null
      }
      var nextdate = new Date(new Date().getTime()+ $scope.factura.dias*1000*60*60*24);
      var dataFact = 
      {
        documento: $scope.factura.docto,
        ffactura: formatDateInsert(new Date()),
        idcliente: $scope.factura.idcliente,
        importe: $scope.factura.total,
        saldo:$scope.factura.total,
        tipopago:$scope.factura.tpago,
        frevision:formatDateInsert(new Date()),
        fvencimiento:formatDateInsert(nextdate),
        idvendedor:$scope.factura.idvendedor =='' ? null : $scope.factura.idvendedor,
        idempresa:$scope.factura.idempresa,
        aniofiscal:$scope.factura.aniofiscal,
        idsucursal:$scope.factura.idsucursal,
        formapago:$scope.factura.fpago,
        usocfdi:$scope.factura.cfdi,
        metodopago:$scope.factura.mpago,
        contacto:$scope.factura.contacto
      };

      $http.post(pathFactura+'savefactura',dataFact)
          .then( res => {
            dataVenta.idfactura = res.data[0].registra_factura;
            $http.put(pathTpv+'registraventa',dataVenta)
              .then(function(res)
              {
                $scope.idVenta = res.data[0].registra_venta;
                $scope.registraVentaProd();
                $scope.updatePedido();
                $scope.getfacturas();
                swal('La factura se registró exitosamente','Felicidades!','success');     
                $scope.limpiar();
                $scope.getNextDocTpv(); 
              })
              .catch(function(err)
              {
                console.log(err);
              });
          })
          .catch(err => {
            console.log(err);
          });
    }

    $scope.updatePedido = function(){
      $http.put(pathPedi+'updatepedido/'+$scope.idPedido+"/true")
        .then(res=>{
          //console.log("se actualizo el pedido")
        })
        .catch(err=>{
          console.log(err);
        });
    }

    $scope.registraVentaProd = function()
    {
      var ventaProd = {};
      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        ventaProd =
        {
          idventa:$scope.idVenta,
          idProducto:$scope.lstProdCompra[i].ID_PRODUCTO,
          cantidad:$scope.lstProdCompra[i].CANTIDAD,
          precio:$scope.lstProdCompra[i].PRECIO_LISTA,
          importe:$scope.lstProdCompra[i].IMPORTE,
          idsucursal:$scope.factura.idsucursal,
          tipops:$scope.lstProdCompra[i].TIPO_PS,
          documento:$scope.factura.docto,
          caja:1,
          idempresa:$scope.factura.idempresa,
          aniofiscal:$scope.factura.aniofiscal,
          idcliente:$scope.factura.idcliente,
          idproveedor:null,
          idusuario:$scope.idUsuario,
          idmoneda:1, //En la factura no se pide moneda, se pone por dafault peso,
          descuento:$scope.lstProdCompra[i].DESCUENTO == null ? 0 : $scope.lstProdCompra[i].DESCUENTO
        }
        $http.post(pathTpv+'registraventaprod',ventaProd)
          .then(function(res){
            /*se insertó con éxito*/
          })
          .catch(function(err){
          console.log(err);
        });
      }
    }

    $scope.setSelectedDscnt = function(indexRowCompra)
  {
    $scope.indexRowCompra = indexRowCompra;
    $scope.proddscnt.producto = $scope.lstProdCompra[indexRowCompra].DESCRIPCION;
    $scope.proddscnt.precio= $scope.lstProdCompra[indexRowCompra].PRECIO_LISTA;
    $scope.proddscnt.descuento = $scope.lstProdCompra[indexRowCompra].DESCUENTO;
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

  $scope.closeVerifProdSuc = function(){
    $scope.modalVerifProdSuc = false;
  }

  $scope.preguntaElimnaFactura = function(){
    $scope.factura.docto = $scope.lstFacturas[$scope.indexRowFactura].DOCUMENTO;
    $scope.showEliminaFactura = true;
  }

  $scope.closeEliminaFactura = function(){
    $scope.showEliminaFactura = false;
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

    $scope.limpiar = function(){
        $scope.lstProdCompra = [];
        $scope.total = 0.0;
        $scope.cambio = 0.0;
        $scope.factura.docto = '';
        $scope.factura.idcliente = '';
        $scope.nombre_cliente = '';
        $scope.rfc_cliente = '';
        $scope.claveclte = '';
        $scope.factura.idvendedor = '';
        $scope.nombre_vendedor = '';
        $scope.impuestos = 0.0;
        $scope.importeNeto = 0.0;
        $scope.regfactura = true;
        $scope.factura.contacto = '';
        $scope.factura.dias = '';
        $scope.factura.cuenta = '';
        $scope.factura.idmoneda = 1;
        $scope.factura.total = '';
        $scope.tcaptura = 'D';
        $scope.getNextDocTpv();
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
