app.controller('myCtrlTpv', function($scope,$http,$interval,$routeParams)
{
  $scope.fecha = formatDatePrint(new Date());
  $scope.fechaPantalla = formatDatePantalla(new Date());
  $scope.hora = DisplayCurrentTime();
  $scope.docto = '';
  $scope.doctoTmp = '';
  $scope.esPromo = false;
  $scope.esDscto = false;
  $scope.sortDir = true;
	$scope.counter = 0;
	$scope.total = 0.00;
  $scope.subtotal = 0;
  $scope.cambio = 0.00;
	$scope.cantidad = 0;
  $scope.cantProd = 0;
  $scope.iva = 0;
  $scope.prod_desc = '';
  $scope.codigo_prodto = '';
  $scope.id_producto = '';
  $scope.qtyProdSuc = -1;
  $scope.tipo_ps = '';
  $scope.lstProdBusqueda = [];
  $scope.lstProdCompra = [];
  $scope.lstCliente = [];
  $scope.lstVendedor = [];
  $scope.lstVendedorVerif = [];
  $scope.lstPrdSucExis = [];
  $scope.lstVentas = [];
  $scope.idSelCompra = 0;
  $scope.idVenta = 0;
  $scope.indexRowCompra = -1;
  $scope.importeNeto = 0;
  $scope.descuento = 0;
  $scope.dsctoValor = 0;
  $scope.impuestos = 0;
  $scope.nombre_cliente = '';
  $scope.rfc_cliente = '';
  $scope.idcliente = 0;
  $scope.claveclte = '';
  $scope.fechaCorte = '';
  $scope.fechaTicket = '';
  $scope.pagos = '';
  $scope.tipopago = '';
  $scope.noCaja = 1;
  $scope.nombre_vendedor = '';
  $scope.idvendedor = '';
  $scope.rgstracompra = false;
  $scope.agregaProd = true;
  $scope.modalConsProdSuc = false;
  $scope.modalVerifProdSuc = false;
  $scope.modalAddDscnt = false;
  $scope.isOperaciones = false;
  $scope.isVerifExis = false;
  $scope.pago_tarjeta = 0.00;
  $scope.pago_efectivo = 0.00;
  $scope.pago_cheque = 0.00;
  $scope.pago_vales = 0.00;
  $scope.formaPago = '';
  $scope.promocion = '';
  $scope.modalVerfClte = false;
  $scope.showLstClte = false;
  $scope.btnVerifClte = 'Actualizar';
  $scope.idempresa = '';
  $scope.idsucursal = '';
  $scope.aniofiscal = '';
  $scope.req_factura = false;
  $scope.modalReqFact = false;
  $scope.listaVendedores = false;
  $scope.lstMoneda = [];
  $scope.lstMetpago = [];
  $scope.lstFormpago = [];
  $scope.lstUsocfdi = [];
  $scope.lstTipopago = [];
  $scope.lstBancos = [];
  $scope.lstTarjetas = [];
  $scope.lstVales = [];
  $scope.idvales = '0';
  $scope.idtarjeta = '0';
  $scope.idbanco = '0';
  idventasmostrador = 0;
  $scope.idOpSel = -1;
  $scope.idxOperacion = -1;
  $scope.idUsuario = '';
  $scope.idProceso = $routeParams.idproc;
  $scope.permisos = {
    alta: false,
    baja: false,
    modificacion:false,
    consulta:false
  };
  $scope.proddscnt ={
    producto:undefined,
    precio:undefined,
    descuento:0,
    descuentoTodos:0
  }
  $scope.empresa ={
    NOMBRE:'',
    DOMICILIO:'',
    RFC:''
  };
  $scope.fact = {
    idfactura:'',
    idCliente:'',
    idventa:'',
    cliente:'',
    rfc:'',
    serie:'',
    folio:'',
    usocfdi:'',
    usocfdicodigo:'',
    moneda:'MXN',
    tipocambio:1,
    formapago:"01",
    metodopago:1,
    tipopago:1,
    req_factura:false,
    idempresa:'',
    idsucursal:'',
    isavailable:false,
    isfacturable:false,
    documento:'',
    regimenfiscal:''
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
    formapago:"",
    id_vendedor:"",
    id_uso_cfdi:"",
    idempresa:""
  };
  $scope.init = function()
  {
      $('#regcompra').prop('disabled',true);
      var hoy = new Date();
      $scope.fechaCorte = hoy.getDate()+'/'+hoy.getMonth()+'/'+hoy.getFullYear();
      $http.get(pathAcc+'getdata',{responseType:'json'}).
      then(function(res){
        if(res.data.value=='OK'){
          $scope.idempresa = res.data.idempresa;
          $scope.idsucursal = res.data.idsucursal;
          $scope.aniofiscal = res.data.aniofiscal;
          $scope.idUsuario = res.data.idusuario;
          $scope.fact.aniofiscal = res.data.aniofiscal;
          $scope.fact.idempresa = res.data.idempresa;
          $scope.fact.idsucursal = res.data.idsucursal;
          $scope.getsucdisponible();
          $scope.getNextDocTpv();
          $scope.getNextDocFact();
          $scope.getEmpresa();
          $scope.permisos();
          $scope.getvendedores();
          $scope.getIdClienteVentasMostrador();
        }
      }).catch(function(err){
        console.log(err);
      });      
      $scope.getUsoCfdi();
      $scope.getbanco();
      $scope.gettarjetas();
      $scope.getvales();
      $scope.getFormPago();
  }
  
  $scope.getIdClienteVentasMostrador = () =>{
    $http.get(pathClte+'getidvtsmostr/'+$scope.idempresa)
    .then(res=>{
      if(res.data){
        idventasmostrador = res.data[0].ID_CLIENTE;
        $scope.idcliente = res.data[0].ID_CLIENTE;
        $scope.claveclte = res.data[0].CLAVE.trim();
        claveCliente = res.data[0].CLAVE;
        $scope.nombre_cliente = res.data[0].NOMBRE;
        nombreCliente = res.data[0].NOMBRE;
      }
    }).catch();
  }

  $scope.getNextDocTpv = function(){
		$http.get(pathUtils+'incremento/TPVS/'+$scope.idempresa+'/7').
		then(function(res)
		{
			if(res.data.length > 0)
			{
				$scope.docto = res.data[0].VALOR;
				$scope.doctoTmp = res.data[0].VALOR;
			}
		}).catch(function(err)
		{
			console.log(err);
		});
	}

  $scope.getNextDocFact = function(){
		$http.get(pathUtils+'incremento/FACT/'+$scope.idempresa+'/7').
		then(function(res)
		{
			if(res.data.length > 0)
			{
				$scope.fact.documento = res.data[0].VALOR;
			}
		}).catch(function(err)
		{
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
    $http.get(pathUtils+'gettipopago',{responseType:'json'})
          .then( res => {
            if(res.data.length > 0){
              $scope.lstTipopago = res.data;
              $scope.fact.tipopago = $scope.cliente.dcredito == 0 || $scope.cliente.dcredito == '' ? 1 : 2;
            }
          })
          .catch(err => {
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

  $scope.getvendedores =() =>{
    $http.get(pathVend+'getvendedores/'+$scope.fact.idempresa+'/vacio',{responseType:'json'}).
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

  $scope.getsucdisponible = function(){
    $http.get(pathCreaFact+'getdatacfdi/'+$scope.idempresa+'/'+$scope.idsucursal,{responseType:'json'}).
    then(res => {
      if(res.data.status == 'ok'){
        $scope.fact.isavailable = true;
      }else{
        $scope.fact.isavailable = false;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.getbanco = function(){
    $http.get(pathUtils+'getbancos',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstBancos = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.gettarjetas = function(){
    $http.get(pathUtils+'gettarjetas',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstTarjetas = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.getEmpresa = function(){
    $http.get(pathEmpr+'loadbyid/'+$scope.idempresa,{responseType:'json'}).
      then(res => {
        if(res.data.length > 0){
          $scope.empresa.NOMBRE = res.data[0].NOMBRE;
          $scope.empresa.DOMICILIO = res.data[0].DOMICILIO;
          $scope.empresa.RFC = res.data[0].RFC;
          $scope.fact.regimenfiscal = res.data[0].REGIMEN;
        }
      }).
      catch(err => {
        console.log(err);
      })
  }

  $scope.getvales = function(){
    $http.get(pathUtils+'getvales',{responseType:'json'}).
    then(res => {
      if(res.data.length > 0){
        $scope.lstVales = res.data;
      }
    }).catch(err =>	{
			console.log(err);
		});
  }

  $scope.setSelected = function(indexRowCompra,idSelCompra)
  {
    $scope.idSelCompra = idSelCompra;
    $scope.indexRowCompra = indexRowCompra;
  }

  $scope.setSelectedDscnt = function(indexRowCompra)
  {
    $scope.indexRowCompra = indexRowCompra;
    $scope.proddscnt.producto = $scope.lstProdCompra[indexRowCompra].DESCRIPCION;
    $scope.proddscnt.precio= $scope.lstProdCompra[indexRowCompra].PRECIO_LISTA;
    $scope.proddscnt.descuento = $scope.lstProdCompra[indexRowCompra].DESCUENTO;
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
      $http.get(pathProd+'prodbycode/'+$scope.codigo_prodto+'/'+$scope.idempresa+'/'+$scope.idsucursal,{responseType: 'json'}).
      then(function(res)
      {
        if(res.data != false)
        {
          $scope.prod_desc = res.data[0].DESCRIPCION;
          $scope.precio = Number(res.data[0].PRECIO_LISTA).toFixed(2);
          $scope.unidad = res.data[0].UNIDAD_MEDIDA;
          $scope.imagePath = res.data[0].IMAGEN;
          $scope.iva = res.data[0].IVA;
          $scope.id_producto = res.data[0].ID_PRODUCTO;
          $scope.tipo_ps = res.data[0].TIPO_PS;
          $scope.codigo_prodto = res.data[0].CODIGO;
          $scope.cantProd = res.data[0].STOCK;
          $scope.esPromo = res.data[0].ES_PROMO == 't' ? true:false;
          $scope.esDscto = res.data[0].ES_DESCUENTO == 't' ? true:false;
          $scope.descuento = res.data[0].PRECIO_DESCUENTO;
          $scope.promocion = res.data[0].PRECIO_PROMO;

          if($scope.tipo_ps =='S')
          {
            $scope.isVerifExis = false;
          }else if($scope.tipo_ps =='P')
          {
            $scope.isVerifExis = true;
          }
          if($scope.imagePath!='')
          {
            $('#imgfig').show();
          }
        }else
        {
          swal('No existe un producto y/o servicio con el código '+$scope.codigo_prodto);
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
    	$('#dispsearch').show();
    	var searchword = $scope.prod_desc !='' ? $scope.prod_desc : 'vacio';
    	$http.get(pathTpv+'getitems/'+$scope.idempresa+'/'+searchword+'/V', {responseType: 'json'}).
    	then(function(res)
    	{
    		if(res.status == '200')
    		{
          $scope.lstProdBusqueda = res.data;
    		}
    	}).catch(function(err)
    	{
    		console.log(err);
    	});
    }

    $scope.selectProdBus = function(idxRowListaBusq)
    {
      $scope.codigo_prodto = $scope.lstProdBusqueda[idxRowListaBusq].CODIGO;
      $scope.prod_desc = $scope.lstProdBusqueda[idxRowListaBusq].DESCRIPCION;
      $scope.unidad = $scope.lstProdBusqueda[idxRowListaBusq].UNIDAD_MEDIDA;
      $scope.precio = $scope.lstProdBusqueda[idxRowListaBusq].PRECIO_LISTA;
      $scope.id_producto = $scope.lstProdBusqueda[idxRowListaBusq].ID_PRODUCTO;
      $scope.imagePath = $scope.lstProdBusqueda[idxRowListaBusq].IMAGEN;
      $scope.iva = $scope.lstProdBusqueda[idxRowListaBusq].IVA;
      $scope.cantProd = $scope.lstProdBusqueda[idxRowListaBusq].STOCK;
      $scope.esPromo = $scope.lstProdBusqueda[idxRowListaBusq].ES_PROMO == 't' ? true:false;
      $scope.esDscto = $scope.lstProdBusqueda[idxRowListaBusq].ES_DESCUENTO == 't' ? true:false;
      $scope.descuento = $scope.lstProdBusqueda[idxRowListaBusq].PRECIO_DESCUENTO;
      $scope.promocion = $scope.lstProdBusqueda[idxRowListaBusq].PRECIO_PROMO;
      $scope.tipo_ps = $scope.lstProdBusqueda[idxRowListaBusq].TIPO_PS;

      if($scope.imagePath!='')
      {
        $('#imgfig').show();
      }
      $('#mencant').prop('disabled',false);
      $('#cantidad').prop('disabled',false);
      $('#mascant').prop('disabled',false);
      $scope.closeDivSearch();

      $http.get(pathTpv+'getitemsbyprodsuc/'+$scope.id_producto+'/'+$scope.idsucursal).
      then(function(res)
      {
        if(res.data.length > 0)
        {
          $scope.qtyProdSuc = res.data[0].STOCK;
          //$scope.tipo_ps = res.data[0].TIPO_PS;
        }else {
          $scope.qtyProdSuc = 0;
        }
        if($scope.qtyProdSuc == 0 && $scope.tipo_ps == 'P')
        {
          $scope.agregaProd = false;
        }
        if($scope.tipo_ps =='S')
        {
          $scope.isVerifExis = false;
        }else if($scope.tipo_ps =='P')
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
    	$('#dispsearch').hide();
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
      if(Number($scope.cantidad) > Number($scope.cantProd) && $scope.tipo_ps == 'P')
      {
        swal('La cantidad de productos ['+$scope.cantidad+'] seleccionados es mayor a la existencia', 'En existencia['+$scope.cantProd+']','warning');
        $scope.cantidad = $scope.cantProd;
        $('#cantidad').focus();
        return;
      }
      $('#regcompra').prop('disabled',false);
      importe = $scope.esDscto ?  Number($scope.cantidad * $scope.precio * (1-$scope.descuento/100)) : Number($scope.cantidad * $scope.precio);
      var dataCompra =
      {
          DESCRIPCION:$scope.prod_desc,
          CANTIDAD:$scope.cantidad,
          PRECIO_LISTA:$scope.precio,
          IMPORTE:importe,
          CODIGO:$scope.codigo_prodto,
          UNIDAD:$scope.unidad,
          IMG:$scope.imagePath,
          IVA:$scope.iva,
          ID_PRODUCTO:$scope.id_producto,
          ESPROMO:$scope.esPromo,
          ESDSCTO:$scope.esDscto,
          PRECIO_PROMO:$scope.promocion,
          DESCUENTO:$scope.descuento,
          TIPO_PS:$scope.tipo_ps
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
      $scope.codigo_prodto = '';
      $scope.prod_desc = '';
      $scope.unidad = '';
      $scope.precio = '';
      $scope.imagePath = '';
      $scope.cantidad = 0;
      $scope.counter = 0;
      $scope.id_producto = '';
      $scope.qtyProdSuc = -1;
      $scope.esDscto = false;
      $scope.esPromo = false;
      $scope.agregaProd = true;
      $scope.isVerifExis = false;
      $('#imgfig').hide();
      $('#updtTblComp').val('F');
    }

    $scope.editaProducto = function()
    {
      $scope.id_producto = $scope.lstProdCompra[$scope.indexRowCompra].ID_PRODUCTO;
      $scope.codigo_prodto = $scope.lstProdCompra[$scope.indexRowCompra].CODIGO;
      $scope.prod_desc = $scope.lstProdCompra[$scope.indexRowCompra].DESCRIPCION;
      $scope.unidad = $scope.lstProdCompra[$scope.indexRowCompra].UNIDAD;
      $scope.precio = $scope.lstProdCompra[$scope.indexRowCompra].PRECIO_LISTA;
      $scope.cantidad = $scope.lstProdCompra[$scope.indexRowCompra].CANTIDAD;
      $scope.counter = $scope.cantidad;
      $scope.imagePath = $scope.lstProdCompra[$scope.indexRowCompra].IMG;
      $scope.esPromo = $scope.lstProdCompra[$scope.indexRowCompra].ESPROMO;
      $scope.esDscto = $scope.lstProdCompra[$scope.indexRowCompra].ESDSCTO;
      $scope.promocion = $scope.lstProdCompra[$scope.indexRowCompra].PRECIO_PROMO;
      $scope.descuento = $scope.lstProdCompra[$scope.indexRowCompra].DESCUENTO;
      $scope.tipo_ps = $scope.lstProdCompra[$scope.indexRowCompra].TIPO_PS;
      if($scope.tipo_ps == 'P')
      {
        $scope.isVerifExis = true;
      }else {
        $scope.isVerifExis = false;
      }

      if($scope.imagePath!='')
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
        $('#regcompra').prop('disabled',true);
      }
    }

    $scope.calculaValoresMostrar = () =>
    {
      $scope.subtotal = 0;
      $scope.total = 0;
      $scope.importeNeto = 0;
      $scope.impuestos = 0;
      $scope.dsctoValor = 0;
      $scope.esPromo = false;
      $scope.esDscto = false;

      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        valUnit = Number ($scope.lstProdCompra[i].PRECIO_LISTA) / (1+Number($scope.lstProdCompra[i].IVA/100));
        $scope.subtotal += Number($scope.lstProdCompra[i].CANTIDAD) * valUnit;
        $scope.dsctoValor += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) * ($scope.lstProdCompra[i].ESDSCTO ? Number($scope.lstProdCompra[i].DESCUENTO/100) : 0);
        $scope.impuestos += Number($scope.lstProdCompra[i].CANTIDAD) * valUnit * Number($scope.lstProdCompra[i].IVA/100);
      }
      $scope.total = Number($scope.subtotal - $scope.dsctoValor + $scope.impuestos).toFixed(2);
      
    }

    $scope.buscacliente = function(event)
    {
      var searchword;
      searchword = $scope.nombre_cliente != '' ? $scope.nombre_cliente : 'vacio';
      $http.get(pathClte+'loadbynombre/'+$scope.idempresa +'/'+$scope.aniofiscal+'/'+searchword).
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
      $scope.rfc_cliente = $scope.lstCliente[indxRowClte].RFC != null ? $scope.lstCliente[indxRowClte].RFC.trim() : '';
      //$scope.fact.usocfdi = $scope.lstCliente[indxRowClte].USO_CFDI;
      $scope.cliente.id_uso_cfdi = Number($scope.lstCliente[indxRowClte].ID_USO_CFDI);   
      $scope.fact.usocfdi = Number($scope.lstCliente[indxRowClte].ID_USO_CFDI);
      $scope.idcliente = Number($scope.lstCliente[indxRowClte].ID_CLIENTE);           
      $scope.fact.idCliente = Number($scope.lstCliente[indxRowClte].ID_CLIENTE);
      $scope.cliente.dcredito = Number($scope.lstCliente[indxRowClte].DIAS_CREDITO);
      $scope.cliente.id_forma_pago = Number($scope.lstCliente[indxRowClte].ID_FORMA_PAGO);
      $scope.cliente.formapago = $scope.lstCliente[indxRowClte].FORMA_PAGO;
      $scope.nombre_vendedor = $scope.lstCliente[indxRowClte].VENDEDOR;
      $scope.idvendedor = $scope.lstCliente[indxRowClte].ID_VENDEDOR;
      $scope.lstCliente = [];
      $scope.showLstClte = false;      
    }

    $scope.buscacodcliente = (event) =>{
      if(event.keyCode==13){
        $http.get(pathClte+'findbycode/'+$scope.claveclte+'/'+$scope.idempresa)
        .then(res=>{
          if(res.data){
            $scope.claveclte = res.data[0].CLAVE.trim();
            $scope.nombre_cliente =res.data[0].NOMBRE;
            $scope.rfc_cliente = res.data[0].RFC!= null ? res.data[0].RFC.trim() : '';
            $scope.cliente.id_uso_cfdi = Number(res.data[0].ID_USO_CFDI);   
            $scope.fact.usocfdi = Number($scope.lstCliente[indxRowClte].ID_USO_CFDI);      
            $scope.idcliente = Number(res.data[0].ID_CLIENTE);
            $scope.fact.idCliente = Number(res.data[0].ID_CLIENTE);
            $scope.cliente.dcredito = Number(res.data[0].DIAS_CREDITO);
            $scope.cliente.id_forma_pago = Number(res.data[0].ID_FORMA_PAGO);
            $scope.cliente.formapago = res.data[0].FORMA_PAGO;
            $scope.nombre_vendedor = $scope.lstCliente[indxRowClte].VENDEDOR;
            $scope.idvendedor = $scope.lstCliente[indxRowClte].ID_VENDEDOR;
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
      $http.get(pathVend+'getvendedores/'+$scope.idempresa+'/'+searchword).
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
      $scope.idvendedor = $scope.lstVendedor[indxRowClte].ID_VENDEDOR;
      $scope.nombre_vendedor = $scope.lstVendedor[indxRowClte].NOMBRE;
      $scope.closeVendSearch();
    }

    $scope.buscacodvendedor = (event) =>{
      if(event.keyCode==13){
        $http.get(pathVend+'findvendbyid/'+$scope.idvendedor+'/'+$scope.idempresa)
        .then(res=>{
          if(res.data){
            $scope.idvendedor = res.data[0].ID_VENDEDOR;
            $scope.nombre_vendedor = res.data[0].NOMBRE;
          }else{
            swal('No existe el vendedor con codigo '+$scope.idvendedor+', puede hacer la búsqueda por nombre');
            $scope.idvendedor = '';
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

    $scope.iniciaRegistrarCompra = function()
    {
      
      if($scope.docto == '')
      {
        swal('El campo Documento debe estar lleno');
        $('#docto').focus();
        return;
      }

      if($scope.lstProdCompra.length == 0)
      {
        swal('Debe agregar al menos un producto');
        return;
      }
      $scope.getTipoPago();

      if($scope.rfc_cliente != ''){
        $scope.getMoneda();
        $scope.getMetPago();
        //$scope.getFormPago();  
        $scope.fact.isfacturable = true;    
      }else{
        $scope.fact.isfacturable = false;
      }
      
      $scope.rgstracompra = true;
    }

    
    $scope.calculaCambio = function()
    {
      if(isNaN($scope.pago_efectivo))
      {
        swal('Sólo se permiten números');
        $scope.pago_efectivo = 0.00;
        $scope.cambio = 0.00;
        $('#pago_efectivo').focus();
        return;
      }else if($scope.pago_efectivo < 0)
      {
        swal('Sólo puede introducir números positivos');
        $scope.pago_efectivo = 0.00;
        $('#pago_efectivo').focus();
      }else if($scope.pago_efectivo == 0)
      {
        $scope.cambio = 0.00;
      }else if($scope.pago_efectivo < $scope.total)
      {
        $scope.cambio = 0.00;
      }else if($scope.pago_efectivo > $scope.total){
        $scope.cambio = $scope.pago_efectivo - $scope.total;
      }

    }

    $scope.registraCompra = function()
    { 
      if(Number($scope.pago_efectivo) - Number($scope.cambio) + Number($scope.pago_tarjeta) + Number($scope.pago_cheque) + Number($scope.pago_vales) > Number($scope.total))
      {
        swal('La cantidad a cobrar es mayor que el Importe Total','Verifique las cantidades','warning');
        return;
      }
      if($scope.fact.tipopago==1 &&  Number($scope.pago_efectivo) + Number($scope.pago_tarjeta) + Number($scope.pago_cheque) + Number($scope.pago_vales) < Number($scope.total))
      {
        swal('La cantidad a cobrar es menor que el Importe Total, no se puede registrar la compra');
        return;
      }
      
      var dataVenta =
      {
        documento:$scope.docto,
        idcliente:$scope.idcliente != '' ? $scope.idcliente : null,
        idvendedor:$scope.idvendedor =='' ? null : $scope.idvendedor,
        fechaventa:formatDateInsert(new Date()),
        aniofiscal:$scope.aniofiscal,
        idempresa:$scope.idempresa,
        idtipopago:$scope.fact.tipopago,
        pagoefectivo:$scope.pago_efectivo,
        pagotarjeta:$scope.pago_tarjeta,
        pagocheques:$scope.pago_cheque,
        pagovales:$scope.pago_vales,
        idtarjeta: $scope.idtarjeta,
        idbanco:$scope.idbanco,
        idvales:$scope.idvales,
        importe:$scope.total,
        cambio:$scope.cambio,
        idsucursal:$scope.idsucursal,
        facturado: "false",
        idfactura:null
      }
      var nextdate = new Date(new Date().getTime()+ $scope.cliente.dcredito*1000*60*60*24);
      var dataFact = 
      {
        documento: $scope.fact.documento,
        ffactura: formatDateInsert(new Date()),
        idcliente: $scope.idcliente,
        importe: $scope.total,
        saldo:$scope.total,
        tipopago:$scope.fact.tipopago,
        frevision:formatDateInsert(new Date()),
        fvencimiento:$scope.cliente.dcredito>0?formatDateInsert(nextdate):null,
        idvendedor:$scope.idvendedor =='' ? null : $scope.idvendedor,
        idempresa:$scope.idempresa,
        aniofiscal:$scope.aniofiscal,
        idsucursal:$scope.idsucursal,
        formapago: $scope.fact.req_factura ? $scope.cliente.formapago : null,
        usocfdi:$scope.fact.req_factura ? $scope.fact.usocfdi : null,
        metodopago:$scope.fact.req_factura ? $scope.fact.metodopago : null,
        contacto:null,
        idmoneda:1,
        idpedido:null
      };

      if($scope.fact.req_factura){
        $http.post(pathFactura+'savefactura',dataFact)
          .then(res=>{
            dataVenta.idfactura = res.data[0].registra_factura;
            $scope.fact.idfactura = dataVenta.idfactura;
            $http.post(pathTpv+'registraventa',dataVenta).
                then(function(res)
                {
                  $scope.idVenta = res.data[0].registra_venta;
                  $scope.registraVentaProd();
                  if($scope.fact.req_factura){
                    $scope.registraFactura(); 
                  }
                  $scope.imprimeCompra();      
                  $scope.limpiaCompra();
                  swal('La venta se registro exitosamente','Felicidades!','success');
                })
                .catch(function(err)
                {
                  console.log(err);
                });
          })
          .catch(err =>{
            console.log(err);
          });
      }else{
        $http.post(pathTpv+'registraventa',dataVenta).
        then(function(res)
        {
          $scope.idVenta = res.data[0].registra_venta;
          $scope.registraVentaProd();
          
          /*if($scope.fact.req_factura){
            $scope.registraFactura(); 
          }*/
          $scope.imprimeCompra();
          swal('La venta se registro exitosamente','Felicidades!','success');    
          $scope.limpiaCompra();
        }).
        catch(function(err)
        {
          console.log(err);
        });
    }
     
  }

  $scope.limpiaCompra = function(){
    $scope.lstProdCompra = [];
    $scope.total = 0.0;
    $scope.cambio = 0.0;
    $scope.docto = '';
    $scope.idcliente = idventasmostrador;
    $scope.nombre_cliente = nombreCliente;
    $scope.rfc_cliente = '';
    $scope.claveclte = claveCliente;
    $scope.idvendedor = '';
    $scope.nombre_vendedor = '';
    $scope.pago_efectivo = 0;
    $scope.pago_tarjeta = 0.0;
    $scope.pago_cheque = 0.0;
    $scope.pago_vales = 0.0;
    $scope.impuestos = 0.0;
    $scope.importeNeto = 0.0;
    $scope.subtotal = 0;
    $scope.rgstracompra = false;
    $scope.cliente.dcredito = 0;
    $scope.idbanco = '0';
    $scope.idtarjeta = '0';
    $scope.idvales = '0';
    $('#regcompra').prop('disabled',true);
    $scope.getNextDocTpv(); 
  }

  $scope.limpiaCancelVenta = function(){
    $scope.pago_efectivo = 0;
    $scope.pago_tarjeta = 0.0;
    $scope.pago_cheque = 0.0;
    $scope.pago_vales = 0.0;
    $scope.idbanco = '0';
    $scope.idtarjeta = '0';
    $scope.idvales = '0';
  }

    $scope.registraVentaProd = function()
    {
      var vntaProd = {};
      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        vntaProd =
        {
          idventa:$scope.idVenta,
          idProducto:$scope.lstProdCompra[i].ID_PRODUCTO,
          cantidad:$scope.lstProdCompra[i].CANTIDAD,
          precio:$scope.lstProdCompra[i].PRECIO_LISTA,
          importe:$scope.lstProdCompra[i].IMPORTE,
          idsucursal:$scope.idsucursal,
          tipops:$scope.tipo_ps,
          documento: $scope.fact.documento,
          caja:1,
          idempresa:$scope.idempresa,
          aniofiscal:$scope.aniofiscal,
          idcliente:$scope.idcliente,
          idproveedor:null,
          idusuario:null,
          idmoneda:1, //la venta siempre es en pesos
          descuento:$scope.lstProdCompra[i].DESCUENTO == null ? 0 : $scope.lstProdCompra[i].DESCUENTO
        }
        $http.post(pathTpv+'registraventaprod',vntaProd).
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

    $scope.registraFactura = ()=>{
      $scope.fact.cliente = $scope.nombre_cliente;
      $scope.fact.rfc = $scope.rfc_cliente.trim();
      $scope.fact.idventa = $scope.idVenta;
      $scope.fact.metodopago = $scope.lstMetpago[$scope.fact.metodopago - 1].MET_PAGO;
      $scope.fact.usocfdicodigo = $scope.lstUsocfdi[$scope.fact.usocfdi - 1].CLAVE;
      $scope.fact.formapago = $scope.cliente.formapago;
      $scope.fact.folio = $scope.fact.documento;
      $http.post(pathCreaFact+'creacfdi',$scope.fact)
      .then(res =>{
        if(res.data.status=="success"){
          swal('La venta se registro exitosamente', 'Se creó la factura','success');
        }else{
          swal('Error al generar la factura:\n'+res.data.error,'No se pudo generar la factura','error');
        }
      })
      .catch(function(err)
      {
        console.log(err);
      });
    }

  $scope.VerificarCliente = ()=>
  {
      if($scope.claveclte != '')
      {
        $http.get(pathClte+'loadbyidverfi/'+$scope.idcliente,{responseType:'json'}).
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
            //$('#id_forma_pago').val(res.data[0].ID_FORMA_PAGO);
            $scope.cliente.id_forma_pago = res.data[0].ID_FORMA_PAGO;
            $scope.cliente.formapago = res.data[0].FORMA_PAGO;
            $scope.cliente.id_vendedor = res.data[0].ID_VENDEDOR;
            $scope.cliente.id_uso_cfdi = Number(res.data[0].ID_USO_CFDI);
            $scope.cliente.dcredito = res.data[0].DIAS_CREDITO;
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

  $scope.closeVerifClte = ()=>
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

  $scope.enviaDatosCliente = ()=>
  {
    $scope.cliente.id_tipo_cliente=$('#id_tipo_cliente').val();
    $scope.cliente.revision=$('#revision').val();
    $scope.cliente.pagos=$('#pagos').val();
    //$scope.cliente.id_forma_pago=$('#id_forma_pago').val();
    $scope.cliente.idempresa = $scope.idempresa;

    if($scope.btnVerifClte == 'Agregar')
    {
      $http.get(pathUtils+'incremento/CLTE/'+$scope.idempresa+'/4').
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
       $http.put(pathClte+'updatecliente/'+$scope.idcliente, $scope.cliente).
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

  $scope.verificaExistencia = ()=>
  {
    $scope.modalVerifProdSuc = true;
    $http.get(pathTpv+'getproductosforsucursal/'+$scope.id_producto,{responseType:'json'}).
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

  $scope.addDescuento = ()=>{
    $scope.modalAddDscnt = true;
  }

  $scope.closeVerifProdSuc = ()=>
  {
    $scope.modalVerifProdSuc = false;
    $scope.lstPrdSucExis = [];
  }

    
  $scope.imprimeCompra = ()=>
  {
    if($scope.pago_efectivo > 0){
      $scope.formaPago = 'Efectivo';
    }else if($scope.pago_tarjeta > 0){
      $scope.formaPago = 'Tarjeta';
    }else if($scope.pago_vales){
      $scope.formaPago = 'Vales';
    }else if($scope.pago_cheque){
      $scope.formaPago = 'Cheque';
    }
    
    var h = new Date();
    var ft = document.getElementById("fechaTicket");
    ft.innerHTML = formatDateInsert(h);
    var ficha = document.getElementById('ticket');
    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write( ficha.innerHTML );
    ventimp.document.close();
    ventimp.print();
    ventimp.close();
  }

  $scope.closeAddDscnt = () =>{
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

  $scope.cancelVenta = () =>
  {
    $scope.rgstracompra = false;
    $scope.fact.req_factura = false;
    $scope.limpiaCancelVenta();
  }

  $scope.closeReqFact = ()=>{
    $scope.modalReqFact = false;
  }

  $scope.abreOperaciones = ()=>{
    $scope.getNextDocFact();
    $scope.isOperaciones = true;
    var hoy = new Date();
    var dia = hoy.getDate() > 9 ? hoy.getDate() : '0'+hoy.getDate();
    var mes = (hoy.getMonth()+1) > 9 ? (hoy.getMonth()+1) : '0'+(hoy.getMonth()+1);
    var year = hoy.getFullYear();
    $http.get(pathTpv+'getdataoper/'+$scope.idempresa+'/'+$scope.aniofiscal+'/'+(mes+'-'+dia+'-'+year+' 00:00:00')+'/'+(mes+'-'+dia+'-'+year+' 23:59:59'))
        .then(res=>{
              $scope.lstVentas = res.data.ventas ? res.data.ventas : [];
              $scope.pagos = res.data.pagos;
              $scope.tipopago = res.data.tipopago;
              $scope.cancelados = res.data.cancelados.TOTAL;
        })
        .catch(err => {
          console.log(err);
        });
  }

  $scope.closeOperaciones = ()=>{
    $scope.isOperaciones = false;
    $scope.lstVentas = [];
    $scope.pagos = '';
    $scope.tipopago = '';
    $scope.idOpSel = -1;
    $scope.idxOperacion = -1;
    $scope.limpiaCompra();
  }

  $scope.corteCaja = ()=>{
    if($scope.lstVentas.length === 0){
      swal('No hay movimientos para hacer el corte de caja');
      return;
    }
    var dataFactura = {
      documento: $scope.fact.documento,
      ffactura: formatDateInsert(new Date()),
      idcliente: idventasmostrador, 
      importe: 0,
      saldo: 0,
      tipopago:1,
      frevision:null,
      fvencimiento:null,
      idvendedor:0,
      idempresa:$scope.idempresa,
      aniofiscal:$scope.aniofiscal,
      idsucursal:$scope.idsucursal,
      formapago:null,
      usocfdi:null,
      metodopago:null,
      contacto:null,
      idmoneda:1,
      idpedido:null
    };
    
    swal({
      title: "Se va a realizar el corte de caja",
      text: "Continuar?",
      icon: "warning",
      buttons: [true,true],
      dangerMode: true,
    })
    .then(yes=>{
      if(yes){
        for(var i = 0; i< $scope.lstVentas.length ; i++){
          if($scope.lstVentas[i].ID_FACTURA == 0){
            dataFactura.importe += $scope.lstVentas[i].IMPORTE;
          }
        }
        if(dataFactura.importe > 0){
          $http.post(pathFactura+'savefactura',dataFactura)
            .then(res=>{
              var facturaCreada = res.data[0].registra_factura;              
              for(var i = 0; i< $scope.lstVentas.length ; i++){
                
                $http.put(pathTpv+'updventa/'+$scope.lstVentas[i].ID_VENTA+'/'+facturaCreada)
                .then(res => {
                  //se actualizo la venta
                })
                .catch(err => {
                  console.log(err);
                });
              }
            swal('Se ha hecho el corte de caja','Ok','success');
            $scope.lstVentas = [];
            $scope.closeOperaciones();           
              
            })
            .catch(err =>{
              console.log(err);
            });
          }
        }
      });
    }

    $scope.selOperacion = (idOperacion, index)=>{
      $scope.idOpSel = idOperacion;
      $scope.idxOperacion = index;
      $scope.lstProdCompra = [];
      $http.get(pathTpv+'datoimprtkt/'+$scope.idOpSel)
      .then(res =>{
        if(res.data){
          $scope.lstProdCompra = res.data.ventas;
          $scope.docto = res.data.datos.DOCUMENTO.trim();
          $scope.nombre_cliente = res.data.datos.NOMBRE;
          $scope.pago_efectivo = res.data.datos.PAG_EFECTIVO;
          $scope.pago_tarjeta = res.data.datos.PAG_TARJETA;
          $scope.pago_vales = res.data.datos.PAG_VALES;
          $scope.pago_cheque = res.data.datos.PAG_CHEQUE; 
          $scope.calculaValoresMostrar();       
        }
      })
      .catch(err=>{
        console.log(err);
      });
    }


    $scope.eliminaOperacion = () =>{
      swal({
        title: "Esta seguro que desea eliminar la operación, una vez hecho esto, no se podrá recuperar!",
        text: "Continuar?",
        icon: "warning",
        buttons: [true,true],
        dangerMode: true,
      })
      .then(answer=>{
        if(answer){
          $http.delete(pathTpv+'deloperacion/'+$scope.idOpSel)
          .then(res =>{
            $scope.abreOperaciones();
            swal('Se ha eliminado la operación con éxito!');
          })
          .catch(err=>{

          });
        }
      })
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
