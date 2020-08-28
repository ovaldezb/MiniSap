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
  $scope.idSelCompra = 0;
  $scope.idVenta = 0;
  $scope.indexRowCompra = 0;
  $scope.importeNeto = 0;
  $scope.descuento = 0;
  $scope.dsctoValor = 0;
  $scope.impuestos = 0;
  $scope.nombre_cliente = '';
  $scope.rfc_cliente = '';
  $scope.idcliente = '';
  $scope.claveclte = '';
  $scope.idvendedor = '';
  $scope.nombre_vendedor = '';
  $scope.idvendedor = '';
  $scope.rgstracompra = false;
  $scope.agregaProd = true;
  $scope.modalConsProdSuc = false;
  $scope.modalVerifProdSuc = false;
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
    req_factura:false,
    idempresa:'',
    idsucursal:'',
    isavailable:false,
    isfacturable:false
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
			alert('Sólo se aceptan números');
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
          alert('No existe un producto y/o servicio con el código '+$scope.codigo_prodto);
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
        alert('La cantidad debe ser mayor a 0');
        $('#cantidad').focus();
        return;
      }
      if(Number($scope.cantidad) > Number($scope.cantProd) && $scope.tipo_ps == 'P')
      {
        alert('La cantidad de productos ['+$scope.cantidad+'] seleccionados es mayor a la existencia ['+$scope.cantProd+']');
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
        alert('El campo Documento debe estar lleno');
        $('#docto').focus();
        return;
      }

      if($scope.lstProdCompra.length == 0)
      {
        alert('Debe agregar al menos un producto');
        return;
      }
      
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
        alert('Sólo se permiten números');
        $scope.pago_efectivo = 0.00;
        $scope.cambio = 0.00;
        $('#pago_efectivo').focus();
        return;
      }else if($scope.pago_efectivo < 0)
      {
        alert('Sólo puede introducir números positivos');
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
        alert('La cantidad a cobrar es mayor que el Importe Total');
        return;
      }
      if(Number($scope.pago_efectivo) + Number($scope.pago_tarjeta) + Number($scope.pago_cheque) + Number($scope.pago_vales) < Number($scope.total))
      {
        alert('La cantidad a cobrar es menor que el Importe Total, no se puede registrar la compra');
        return;
      }
      
      var dataVenta =
      {
        documento:$scope.docto,
        idcliente:$scope.idcliente,
        idvendedor:$scope.idvendedor,
        fechaventa:formatDateInsert(new Date()),
        aniofiscal:$scope.aniofiscal,
        idempresa:$scope.idempresa,
        idformapago:$scope.fact.formapago,
        pagoefectivo:$scope.pago_efectivo,
        pagotarjeta:$scope.pago_tarjeta,
        pagocheques:$scope.pago_cheque,
        pagovales:$scope.pago_vales,
        idtarjea:$('#idtarjea').val()=='' ? null : $('#idtarjea').val(),
        idbanco:$('#banco').val()=='' ? null : $('#banco').val(),
        idvales:$('#idvales').val()=='' ? null : $('#idvales').val(),
        importe:$scope.total,
        cambio:$scope.cambio,
        idsucursal:$scope.idsucursal
      }

      $http.put(pathTpv+'registraventa',dataVenta).
      then(function(res)
      {
        $scope.idVenta = res.data[0].registra_venta;
        $scope.registraVentaProd();
        if($scope.fact.req_factura){
          $scope.registraFactura(); 
        }else{
          alert('La venta se registro exitosamente');
        }        
        $scope.lstProdCompra = [];
        $scope.total = 0.0;
        $scope.cambio = 0.0;
        $scope.docto = '';
        $scope.idcliente = '';
        $scope.nombre_cliente = '';
        $scope.rfc_cliente = '';
        $scope.claveclte = '';
        $scope.idvendedor = '';
        $scope.nombre_vendedor = '';
        $scope.pago_efectivo = 0;
        $scope.pago_tarjeta = 0.0;
        $scope.pago_cheque = 0.0;
        $scope.pago_vales = 0.0;
        $scope.impuestos = 0.0;
        $scope.importeNeto = 0.0;
        $scope.rgstracompra = false;
        $('#regcompra').prop('disabled',true);
        $scope.getNextDocTpv();
        
      }).
      catch(function(err)
      {
        console.log(err);
      });
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
              alert('La venta se registro exitosamente y se creó la factura');
            }else{
              alert('Error al generar la factura:\n'+res.data.error);
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
    $scope.cliente.id_vendedor=$('#id_vendedor').val();
    //$scope.cliente.id_uso_cfdi=$('#id_uso_cfdi').val();
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
          alert('Se agregó correctamente el cliente');
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
         alert('Se actualizó correctamente el cliente');
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

    /*$scope.orderByMe = function(valorOrden)
    {
      $scope.orderBy = valorOrden;
      $scope.sortDir = !$scope.sortDir;
    }*/

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
