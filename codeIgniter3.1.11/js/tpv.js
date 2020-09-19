app.controller('myCtrlTpv', function($scope,$http,$interval)
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
  $scope.totalBruto = 0.00;
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
  $scope.lstPrdSucExis = [];
  $scope.lstVentas = [];
  $scope.idSelCompra = 0;
  $scope.idVenta = 0;
  $scope.indexRowCompra = 0;
  $scope.importeNeto = 0;
  $scope.descuento = 0;
  $scope.dsctoValor = 0;
  $scope.impuestos = 0;
  $scope.nombre_cliente = 'Ventas Mostrador';
  $scope.rfc_cliente = '';
  $scope.idcliente = 0;
  $scope.claveclte = 'C0000';
  $scope.fechaCorte = '';
  $scope.pagos = '';
  $scope.tipopago = '';
  $scope.noCaja = 1;
  $scope.nombre_vendedor = '';
  $scope.idvendedor = '';
  $scope.rgstracompra = false;
  $scope.agregaProd = true;
  $scope.modalConsProdSuc = false;
  $scope.modalVerifProdSuc = false;
  $scope.isOperaciones = false;
  $scope.isVerifExis = false;
  $scope.pago_tarjeta = 0.00;
  $scope.pago_efectivo = 0.00;
  $scope.pago_cheque = 0.00;
  $scope.pago_vales = 0.00;
  $scope.promocion = '';
  $scope.modalVerfClte = false;
  $scope.showLstClte = false;
  $scope.btnVerifClte = 'Actualizar';
  $scope.idempresa = '';
  $scope.idsucursal = '';
  $scope.aniofiscal = '';
  $scope.req_factura = false;
  $scope.modalReqFact = false;
  $scope.lstMoneda = [];
  $scope.lstMetpago = [];
  $scope.lstFormpago = [];
  $scope.lstUsocfdi = [];
  $scope.lstTipopago = [];

  $scope.fact = {
    idCliente:'',
    idventa:'',
    cliente:'',
    rfc:'',
    serie:'',
    folio:'',
    usocfdi:'',
    moneda:'MXN',
    tipocambio:1,
    formapago:"01",
    metodopago:'PUE',
    tipopago:1,
    req_factura:false,
    idempresa:'',
    idsucursal:'',
    isavailable:false,
    isfacturable:false,
    documento:''
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
      $('#regcompra').prop('disabled',true);
      $('#codigo_prodto').prop('disabled',true);
      var hoy = new Date();
      $scope.fechaCorte = hoy.getDate()+'/'+hoy.getMonth()+'/'+hoy.getFullYear();
      $http.get(pathAcc+'getdata',{responseType:'json'}).
      then(function(res){
        if(res.data.value=='OK'){
          $scope.idempresa = res.data.idempresa;
          $scope.idsucursal = res.data.idsucursal;
          $scope.aniofiscal = res.data.aniofiscal;
          $scope.fact.idempresa = res.data.idempresa;
          $scope.fact.idsucursal = res.data.idsucursal;
          $scope.getsucdisponible();
          $scope.getNextDocTpv();
          $scope.getNextDocFact();
        }
      }).catch(function(err){
        console.log(err);
      });      
      $scope.getUsoCfdi();
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

  $scope.setSelected = function(indexRowCompra,idSelCompra)
  {
    $scope.idSelCompra = idSelCompra;
    $scope.indexRowCompra = indexRowCompra;
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

  $scope.capturaRapida = function()
  {
    if($scope.captura_rapida)
    {
      $('#prod_desc').prop('disabled',true);
      $('#codigo_prodto').prop('disabled',false);
      $scope.counter = 1;
  		$scope.cantidad = $scope.counter;
    }else {
      {
        $('#prod_desc').prop('disabled',false);
        $('#codigo_prodto').prop('disabled',true);
        $scope.counter = 0;
    		$scope.cantidad = $scope.counter;
      }
    }
  }

  $scope.buscaprodbycodigo = function(event)
  {
    if(event.keyCode==13)
    {
      $http.get(pathProd+'prodbycode/'+$scope.codigo_prodto,{responseType: 'json'}).
      then(function(res)
      {
        if(res.data != false)
        {
          $scope.prod_desc = res.data[0].DESCRIPCION;
          $scope.precio = Number(res.data[0].PRECIO_LISTA).toFixed(2);
          $scope.unidad = res.data[0].UNIDAD_MEDIDA;
          $scope.imagePath = res.data[0].IMAGEN;
          $scope.iva = res.data[0].IVA;
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
    	})
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

    $scope.calculaValoresMostrar = function()
    {
      $scope.total = 0;
      $scope.importeNeto = 0;
      $scope.impuestos = 0;
      $scope.dsctoValor = 0;
      $scope.esPromo = false;
      $scope.esDscto = false;

      for(var i=0;i<$scope.lstProdCompra.length;i++)
      {
        $scope.total += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) ;
        $scope.dsctoValor += Number($scope.lstProdCompra[i].CANTIDAD) * Number ($scope.lstProdCompra[i].PRECIO_LISTA) * ($scope.lstProdCompra[i].ESDSCTO ? Number($scope.lstProdCompra[i].DESCUENTO/100) : 0);
        $scope.importeNeto = $scope.total   / (1+Number($scope.lstProdCompra[i].IVA/100));
        $scope.impuestos = ($scope.importeNeto) * Number($scope.lstProdCompra[i].IVA/100);
      }
      $scope.total = $scope.total - $scope.dsctoValor;
    }

    $scope.buscacliente = function(event)
    {
      var searchword;
      searchword = $scope.nombre_cliente != '' ? $scope.nombre_cliente : 'vacio';
      $http.get(pathClte+'loadbynombre/'+$scope.idempresa +'/'+searchword).
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
      $scope.claveclte = $scope.lstCliente[indxRowClte].CLAVE.trim();
      $scope.nombre_cliente = $scope.lstCliente[indxRowClte].NOMBRE;
      $scope.rfc_cliente = $scope.lstCliente[indxRowClte].RFC.trim();
      $scope.fact.usocfdi = $scope.lstCliente[indxRowClte].USO_CFDI;
      $scope.cliente.id_uso_cfdi = $scope.lstCliente[indxRowClte].ID_USO_CFDI;      
      $scope.idcliente = $scope.lstCliente[indxRowClte].ID_CLIENTE;           
      $scope.fact.idCliente = $scope.lstCliente[indxRowClte].ID_CLIENTE;
      $scope.cliente.dcredito = $scope.lstCliente[indxRowClte].DIAS_CREDITO;
      $scope.lstCliente = [];
      $scope.showLstClte = false;      
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
          $('#listaVendedores').show();
        }else {
          $scope.lstVendedor = [];
          $('#listaVendedores').hide();
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
      $scope.idvendedor = $scope.lstVendedor[indxRowClte].ID_VENDEDOR;
      $scope.lstVendedor = [];
      $('#listaVendedores').hide();
    }

    $scope.closeVendSearch = function()
    {
      $scope.lstVendedor = [];
      $('#listaVendedores').hide();
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

      if($scope.nombre_cliente != ''){
        $scope.getMoneda();
        $scope.getMetPago();
        $scope.getFormPago();  
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
        idtarjea:$('#idtarjea').val()=='' ? null : $('#idtarjea').val(),
        idbanco:$('#banco').val()=='' ? null : $('#banco').val(),
        idvales:$('#idvales').val()=='' ? null : $('#idvales').val(),
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
        fvencimiento:formatDateInsert(nextdate),
        idvendedor:$scope.idvendedor =='' ? null : $scope.idvendedor,
        idempresa:$scope.idempresa,
        aniofiscal:$scope.aniofiscal,
        idsucursal:$scope.idsucursal,
        formapago:null,
        usocfdi:scope.fact.req_factura ? $scope.fact.usocfdi : null,
        metodopago:scope.fact.req_factura ? $scope.fact.metodopago : null
      };

      if($scope.fact.tipopago==2){
        $http.post(pathFactura+'savefactura',dataFact)
            .then(res=>{
              dataVenta.idfactura = res.data[0].registra_factura;
              $http.post(pathTpv+'registraventa',dataVenta).
                  then(function(res)
                  {
                    $scope.idVenta = res.data[0].registra_venta;
                    $scope.registraVentaProd();
                    if($scope.fact.req_factura){
                      $scope.registraFactura(); 
                    }
                    swal('La venta se registro exitosamente','Felicidades!','success');       
                    $scope.limpiaCompra();
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
          console.log(res);
          $scope.idVenta = res.data[0].registra_venta;
          console.log($scope.idVenta);
          $scope.registraVentaProd();
          if($scope.fact.req_factura){
            $scope.registraFactura(); 
          }
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
      $scope.idcliente = 0;
      $scope.nombre_cliente = 'Ventas Mostrador';
      $scope.rfc_cliente = '';
      $scope.claveclte = 'C0000';
      $scope.idvendedor = '';
      $scope.nombre_vendedor = '';
      $scope.pago_efectivo = 0;
      $scope.pago_tarjeta = 0.0;
      $scope.pago_cheque = 0.0;
      $scope.pago_vales = 0.0;
      $scope.impuestos = 0.0;
      $scope.importeNeto = 0.0;
      $scope.rgstracompra = false;
      $scope.cliente.dcredito = 0;
      $('#regcompra').prop('disabled',true);
      $scope.getNextDocTpv(); 
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
          tipops:$scope.tipo_ps
        }
        $http.put(pathTpv+'registraventaprod',vntaProd).
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

    $scope.registraFactura = function(){
      $scope.fact.cliente = $scope.nombre_cliente;
      $scope.fact.rfc = $scope.rfc_cliente;
      $scope.fact.idventa = $scope.idVenta;
      $scope.fact.folio = $scope.docto;
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

  $scope.VerificarCliente = function()
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
            $scope.cliente.telefono = res.data[0].TELEFONO;
            $scope.cliente.cp = res.data[0].CP;
            $scope.cliente.contacto = res.data[0].CONTACTO;
            $scope.cliente.rfc = res.data[0].RFC;
            $scope.cliente.curp = res.data[0].CURP;
            $('#id_tipo_cliente').val(res.data[0].ID_TIPO_CLIENTE);
            $('#revision').val(res.data[0].ID_REVISION);
            $('#pagos').val(res.data[0].ID_PAGOS);
            $('#id_forma_pago').val(res.data[0].ID_FORMA_PAGO);
            $('#id_vendedor').val(res.data[0].ID_VENDEDOR);
            $('#id_uso_cfdi').val(res.data[0].ID_USO_CFDI);
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
    $scope.cliente.id_vendedor=$('#id_vendedor').val();
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
       $http.put(pathClte+'update/'+$scope.idcliente, $scope.cliente).
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

  $scope.verificaExistencia = function()
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

    $scope.closeVerifProdSuc = function()
    {
      $scope.modalVerifProdSuc = false;
      $scope.lstPrdSucExis = [];
    }

    $scope.imprimeCompra = function()
    {

    }

    $scope.cancelVenta = function()
    {
      $scope.rgstracompra = false;
      $scope.fact.req_factura = false;
    }

    $scope.closeReqFact = function(){
      $scope.modalReqFact = false;
    }

    $scope.abreOperaciones = function(){
      $scope.getNextDocFact();
      $scope.isOperaciones = true;
      var hoy = new Date();
      var dia = hoy.getDate() > 9 ? hoy.getDate() : '0'+hoy.getDate();
      var mes = (hoy.getMonth()+1) > 9 ? (hoy.getMonth()+1) : '0'+(hoy.getMonth()+1);
      var year = hoy.getFullYear();
      $http.get(pathTpv+'getdataoper/'+$scope.aniofiscal+'/'+(mes+'-'+dia+'-'+year+' 00:00:00')+'/'+(mes+'-'+dia+'-'+year+' 23:59:59'))
          .then(res=>{
              if(res.data.ventas.length > 0){
                $scope.lstVentas = res.data.ventas;
                $scope.pagos = res.data.pagos;
                $scope.tipopago = res.data.tipopago;
              }
          })
          .catch(err => {
            console.log(err);
          });
    }

    $scope.closeOperaciones = function(){
      $scope.isOperaciones = false;
      $scope.lstVentas = [];
      $scope.pagos = '';
      $scope.tipopago = '';
    }

    $scope.corteCaja = function(){
      var dataFactura = {
        documento: $scope.fact.documento,
        ffactura: formatDateInsert(new Date()),
        idcliente: 0,
        importe: 0,
        saldo: 0,
        tipopago:1,
        frevision:null,
        fvencimiento:null,
        idvendedor:0,
        idempresa:$scope.idempresa,
        aniofiscal:$scope.aniofiscal,
        idsucursal:$scope.idsucursal
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
      });
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
