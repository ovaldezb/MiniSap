app.controller('myCtrlCompras', function ($scope, $http,$routeParams) {
	$scope.counter = 0;
	$scope.cantidad = 0;
	$scope.iva = '';
	$scope.descuento = 0;
	$scope.subtotal = '';
	$scope.ivapaga = '';
	$scope.total = 0;
	$scope.precio = 0;
	$scope.precio_vta = 0;
	$scope.docprev = 'D';
	$scope.numdoc = '';
	$scope.numdocTmp = '';
	$scope.claveprov = '';
	$scope.idproveedor = '';
	$scope.proveedor = '';
	$scope.tipocambio = '';
	$scope.sortDir = false;
	$scope.data = null;
	$scope.listaproductos = [];
	$scope.lstaprdctbusq = [];
	$scope.lstaprovee = [];
	$scope.listaCompras = [];
	$scope.codigo = '';
	$scope.unidad = '';
	$scope.idSelected = 0;
	$scope.selectedImg = '';
	$scope.imagePath = '';
	$scope.idxRowProd;
	$scope.descripcion = '';
	$scope.contrarecibo = '';
	$scope.diascred = '';
	$scope.idProducto = '';
	$scope.desctoprod = 0;
	$scope.idSelCompra = 0;
	$scope.indexRowCompra = 0;
	$scope.msgBton = 'Cancelar'
	$scope.codigocompra = '';
	$scope.idcompra = 0;
	$scope.isAgregaCompra = false;
	$scope.idempresa = '';
	$scope.idsucursal = '';
	$scope.aniofiscal = '';
	$scope.notas = '';
	$scope.btnCompHide = true;
  $scope.buscaprov = false;
	$scope.idProceso = $routeParams.idproc;
	$scope.permisos = {
		alta: false,
		baja: false,
		modificacion:false,
		consulta:false
	};

	$scope.init = function () {
		var foopicker = new FooPicker({
			id: 'fechacompra',
			dateFormat: 'dd/MM/yyyy'
			});
		var foopicker = new FooPicker({
			id: 'fechapago',
			dateFormat: 'dd/MM/yyyy'
			});
		var hoy = new Date();
		$('#fechacompra').val(formatDatePrint(hoy));
		$('#fechapago').val(formatDatePrint(hoy));
		$http.get(pathAcc + 'getdata', { responseType: 'json' }).
			then(function (res) {
				if (res.data.value == 'OK') {
					$scope.idempresa = res.data.idempresa;
					$scope.aniofiscal = res.data.aniofiscal;
					$scope.idsucursal = res.data.idsucursal;
					$scope.idUsuario = res.data.idusuario;
					$scope.getDataInit();
					$scope.permisos();
				}

			}).catch(function (err) {
				console.log(err);
			});
	}

	$scope.getDataInit = function () {
		$http.get(pathCmpr + 'getcompras/' + $scope.idempresa + '/' + $scope.aniofiscal+'/'+$scope.idsucursal)
			.then(function (res) {
				if (res.data.length > 0) {
					$scope.listaCompras = res.data;
				}
			}).catch(function (err) {
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

	$scope.increase = function () {
		$scope.counter += 1;
		$scope.cantidad = $scope.counter;
	}
	
	$scope.decrease = function () {
		if ($scope.counter > 0) {
			$scope.counter = $scope.counter - 1;
			$scope.cantidad = $scope.counter;
		}
	}
	
	$scope.manualenter = function () {
		if (!isNaN($scope.cantidad)) {
			$scope.counter = Number($scope.cantidad);
		} else {
			$scope.cantidad = $scope.counter;
			swal('Sólo se aceptan números','','warning');
		}
	}

	$scope.buscaprovbyclave = function (event) {
		event.stopPropagation();
		if (event.keyCode == 13) {
			$http.get(pathPrve + 'getprvdorclave/' + $scope.claveprov +'/'+$scope.idempresa, {
				responseType: 'json'
			})
			.then(function (res) {
				if (res.status == '200') {
					if (res.data != false) {
						$scope.claveprov = res.data[0].CLAVE.trim();
						$scope.proveedor = res.data[0].NOMBRE.trim();
            $scope.idproveedor = res.data[0].ID_PROVEEDOR;
            
					} else {
						swal('La clave del proveedor no existe, puede hacer una búsqueda por nombre');
						$scope.claveprov = '';
						$scope.proveedor = '';
						$('#proveedor').focus();
					}
				}
			}).catch(function (err) {
				console.log(err);
			});
		}
	}

	$scope.buscaprovbynombre = function (event) {
		var searchword;
		if (event.keyCode == 13) {
			$scope.buscaprov = true;
			searchword = $scope.proveedor != '' ? $scope.proveedor : 'vacio';
			$http.get(pathPrve + 'getproveedores/' + $scope.idempresa + '/' + $scope.aniofiscal+'/'+ searchword, {
				responseType: 'json'
			}).
				then(function (res) {
					if (res.status == '200') {
						$scope.lstaprovee = res.data;
					}
				}).catch(function (err) {
					console.log(err);
				});
		}
	}

	$scope.selectProvee = function (indexRow) {
		$scope.claveprov = $scope.lstaprovee[indexRow].CLAVE.trim();
		$scope.idproveedor = $scope.lstaprovee[indexRow].ID_PROVEEDOR;
		$scope.proveedor = $scope.lstaprovee[indexRow].NOMBRE.trim();
		$scope.lstaprovee = [];
		$scope.buscaprov = false;
	}

	$scope.closeDivSearchProv = () =>{
		$scope.buscaprov = false;
	}

	$scope.lanzaBusquedaProducto = function (event) {
		var precio;
		var tabla, row, searchword, cell;
		if (event.keyCode == 13) {
			$('#dispsearch').show();
			searchword = $scope.descripcion != '' ? $scope.descripcion : 'vacio';
			$http.get(pathTpv + 'getitems/' + $scope.idempresa + '/' + searchword + '/C', { responseType: 'json' }).
				then(function (res) {
					if (res.status == '200') {
						$scope.lstaprdctbusq = res.data;
					}
				}).catch(function (err) {
					console.log(err);
				});
		}
	}

	$scope.buscaprodbycodigo = function (event) {
		if (event.keyCode == 13) {
			$http.get(pathProd + 'prodbycode/' + $scope.codigo+'/'+$scope.idempresa+'/'+$scope.idsucursal, { responseType: 'json' }).
				then(function (res) {
					if (res.data != false) {
						$scope.descripcion = res.data[0].DESCRIPCION;
						$scope.precio = res.data[0].PRECIO_COMPRA != null ? res.data[0].PRECIO_COMPRA : 0;
						$scope.precio_vta = res.data[0].PRECIO_LISTA;
						$scope.unidad = res.data[0].UNIDAD_MEDIDA;
						$scope.imagePath = res.data[0].IMAGEN;
						$scope.idProducto = res.data[0].ID_PRODUCTO;
            $scope.iva = res.data[0].IVA;
						$scope.counter = 1;
						$scope.cantidad = $scope.counter;
						if ($scope.imagePath != '') {
							$('#imgfig').show();
						}
					} else {
						swal('No existe un producto y/o servicio con el código ' + $scope.codigo);
					}
				}).
				catch();
		}
	}

	$scope.selectProdBus = function (index, imgsrc) {
		$scope.codigo = $scope.lstaprdctbusq[index].CODIGO;
		$scope.descripcion = $scope.lstaprdctbusq[index].DESCRIPCION;
		$scope.unidad = $scope.lstaprdctbusq[index].UNIDAD_MEDIDA;
		$scope.precio = $scope.lstaprdctbusq[index].PRECIO_COMPRA != null ? $scope.lstaprdctbusq[index].PRECIO_COMPRA : 0;
		$scope.precio_vta = $scope.lstaprdctbusq[index].PRECIO_LISTA;
		$scope.imagePath = $scope.lstaprdctbusq[index].IMAGEN != null ? $scope.lstaprdctbusq[index].IMAGEN : '';
		$scope.idProducto = $scope.lstaprdctbusq[index].ID_PRODUCTO;
    $scope.iva = $scope.lstaprdctbusq[index].IVA;
		$scope.lstaprdctbusq = [];
		$('#imgfig').show();
		$('#dispsearch').hide();
	}

	$scope.validaDcto = function () {
		if (isNaN($scope.desctoprod)) {
			swal('Sólo se aceptan números');
			$scope.desctoprod = 0;
			$('#desctoprod').focus();
		}
	}

	$scope.agregar = function () {
		
		if ($scope.iva < 0) {
			swal('Debe ingresar el IVA mayor o igual a cero');
			$('#iva').focus();
			return;
		}
		if ($scope.cantidad == 0) {
			swal('La cantidad debe ser mayor a 0');
			$('#cantidad').focus();
			return;
		}

		if ($scope.precio == 0) {
			swal('El precio debe ser mayor a 0');
			$('#precio').focus();
			return;
		}
		
		if($scope.precio > $scope.precio_vta){
			if(!confirm('El precio de compra es más alto que el precio de venta, esto implicaría una pérdida, el precio de venta está fijado en $'+$scope.precio_vta)){
		  	  return;
			}			
		}

		$('#imgfig').hide();
		var producto =
		{
			CODIGO: $scope.codigo,
			DESCRIPCION: $scope.descripcion,
			CANTIDAD: $scope.cantidad,
			UNIDAD: $scope.unidad,
			PRECIO: $scope.precio,
			IMPORTE: Number((1 - $scope.desctoprod / 100) * $scope.cantidad * $scope.precio).toFixed(2),
			IMG: $scope.imagePath,
			IDPRODUCTO: $scope.idProducto,
			DESCTO: $scope.desctoprod,
      IVA:$scope.iva
		};
		if ($('#updtTable').val() == 'F') {
			$scope.listaproductos.push(producto);
		} else {
			$scope.listaproductos[$scope.idxRowProd] = producto;
			$('#updtTable').val('F')
		}
		
		$scope.calculaValores();
		
		/*Hace el descuento*/
		
    
		$scope.cantidad = 0;
		$scope.counter = 0;
		//$scope.desctoprod = 0;		
		$scope.idProducto = '';
		$scope.limpiar();
		habilitar(true, true);
	}

  $scope.calculaValores = () =>{
    $scope.subtotal = 0;
    $scope.descuento = 0;
    $scope.ivapaga = 0;
    $scope.total = 0;
    for (var i = 0; i < $scope.listaproductos.length; i++) {
			$scope.subtotal += Number($scope.listaproductos[i].CANTIDAD) * Number($scope.listaproductos[i].PRECIO);
			$scope.descuento += Number($scope.listaproductos[i].DESCTO) * Number($scope.listaproductos[i].CANTIDAD) * Number($scope.listaproductos[i].PRECIO) / 100;
      $scope.ivapaga += (Number($scope.listaproductos[i].CANTIDAD) * Number($scope.listaproductos[i].PRECIO) - (($scope.listaproductos[i].DESCTO===undefined ? 0 : Number($scope.listaproductos[i].DESCTO)) * Number($scope.listaproductos[i].CANTIDAD) * Number($scope.listaproductos[i].PRECIO) / 100)) * Number($scope.listaproductos[i].IVA/100);
		}
    $scope.total = $scope.subtotal - $scope.descuento + $scope.ivapaga ;
  }

	$scope.borraproducto = function () {
		$scope.suma = 0;
		$scope.listaproductos.splice($scope.idxRowProd, 1);
    $scope.calculaValores();
		/*for (var i = 0; i < $scope.listaproductos.length; i++) {
			$scope.suma = Number(Number($scope.suma) + Number($scope.listaproductos[i].IMPORTE)).toFixed(2);
		}
		/*Hace el descuento*/
		/*$scope.suma = Number($scope.suma * Number(1 - $scope.descuento / 100)).toFixed(2);
		$scope.ivapaga = Number($scope.suma * $scope.iva / 100).toFixed(2);
		$scope.total = Number(Number($scope.suma) + Number($scope.ivapaga)).toFixed(2); */
	}

	$scope.validaIva = function () {
		if (isNaN($scope.iva)) {
			swal('Sólo se permiten números');
			$scope.iva = '';
			$('#iva').focus();
			return;
		}
		if ($scope.iva < 0) {
			swal('Sólo se permiten números positivos');
			$scope.iva = '';
			$('#iva').focus();
			return;
		}
	}

	$scope.setSelected = function (index, idSelected) {
		$scope.idSelected = idSelected;
		$scope.idxRowProd = index;
	};

	$scope.validaDescto = function () {
		if (isNaN($scope.descuento)) {
			swal('Sólo se permiten números');
			$scope.descuento = '';
			$('#descuento').focus();
		} else {
			/*$scope.suma = 0;
			for (var i = 0; i < $scope.listaproductos.length; i++) {
				$scope.suma += Number($scope.listaproductos[i].IMPORTE);
			}
			if ($scope.suma > 0) {
				$scope.suma = Number($scope.suma * Number(1 - $scope.descuento / 100)).toFixed(2);
				$scope.ivapaga = Number($scope.suma * $scope.iva / 100).toFixed(2);
				$scope.total = Number(Number($scope.suma) + Number($scope.ivapaga)).toFixed(2);
			}*/
		}
	}

	$scope.registrar = function () {
		var dataCompra;
		var hoy = new Date();
		if ($scope.claveprov == '') {
			swal('La clave del proveedor es necesaria');
			$('#claveprov').focus();
			return;
		}
		if ($scope.numdoc == '') {
			swal('El número de documento es requerido');
			$('#numdoc').focus();
			return;
		}
		if ($scope.listaproductos.length == 0) {
			swal('Debe agregar al menos un elemento a comprar');
			return;
		}

		var data = {
			documento: $scope.numdoc,
			claveprov: $scope.claveprov,
			feccompra: formatDateInsert(hoy),
			tipopago: $('#tipopago').val(),
			moneda: $('#moneda').val(),
			tipocambio: $scope.tipocambio != '' ? $scope.tipocambio : null,
			contrarec: $scope.contrarecibo,
			fecpago: formatFecPago($('#fechapago').val()),
			fecrevision: formatDateInsert(hoy),
			idempresa: $scope.idempresa,
			docprev: $scope.docprev,
			diascred: $('#tipopago').val() == 1 ? 0: $scope.diascred,
			importe: $scope.total,
			iva: $scope.iva,
			aniofiscal: $scope.aniofiscal,
			descuento: $scope.descuento,
			idsucursal: $scope.idsucursal,
			idproveedor: $scope.idproveedor,
			notas: $scope.notas,
      saldo:$scope.total
		};

		$http.put(pathCmpr + 'registracompra', data).
			then(function (res) {
				if (res.data.length > 0) {
					$scope.idcompra = res.data[0].inserta_compra;
					$scope.regtracompraprod();
					habilitar(true, true);					
          $scope.getDataInit();
					$scope.cantidad = 0;
					$scope.counter = 0;
					$scope.suma = 0;
					$scope.ivapaga = 0;
					$scope.total = 0;
					$scope.descuento = 0;
					$scope.precio = 0;
					$('#tipopago').val(1);
					$('#moneda').val(1);
					$scope.isAgregaCompra = false;
					$scope.limpiarBox1();
					swal('Se registro la compra exitosamente','Felicidades!','success');
				}
			}).
			catch(function (err) {
				console.log(err);
			});
	}

	$scope.regtracompraprod = function () {
		var i;
		for (i = 0; i < $scope.listaproductos.length; i++) {
			var dataInsProd = {
				idcompra: $scope.idcompra,
				idproducto: $scope.listaproductos[i].IDPRODUCTO,
				cantidad: $scope.listaproductos[i].CANTIDAD,
				unidadmedida: $scope.listaproductos[i].UNIDAD,
				preciounitario: $scope.listaproductos[i].PRECIO,
				importetotal: $scope.listaproductos[i].IMPORTE,
				dsctoprod: $scope.listaproductos[i].DESCTO,
				idsucursal: $scope.idsucursal,
				documento: $scope.numdoc,
				caja:1,
				idempresa:$scope.idempresa,
				aniofiscal:$scope.aniofiscal,
				idcliente:0,
				idproveedor:$scope.idproveedor,
				idusuario:$scope.idUsuario,
				idmoneda:$('#moneda').val()
			};
			$http.post(pathCmpr + 'regcompraprdcto/', dataInsProd)
			.then((res) => {					
					if (res.status == 200 && res.data.value == 'OK') {

					}
			})
			.catch((err) => {
				console.log(err);
			});
		}		
	}


	$scope.editaProducto = function () {
		console.log($scope.listaproductos[$scope.idxRowProd]);
		$scope.codigo = $scope.listaproductos[$scope.idxRowProd].CODIGO;
		$scope.descripcion = $scope.listaproductos[$scope.idxRowProd].DESCRIPCION + ' ';
		$scope.cantidad = $scope.listaproductos[$scope.idxRowProd].CANTIDAD;
		$scope.unidad = $scope.listaproductos[$scope.idxRowProd].UNIDAD.trim();
		$scope.desctoprod = $scope.listaproductos[$scope.idxRowProd].DESCTO;
		$scope.precio = $scope.listaproductos[$scope.idxRowProd].PRECIO;
		$scope.imagePath = $scope.listaproductos[$scope.idxRowProd].IMG;
		$scope.idProducto = $scope.listaproductos[$scope.idxRowProd].IDPRODUCTO;
		$('#imgfig').show();
		$('#updtTable').val('T');
		$scope.counter = $scope.cantidad;
		habilitarEdicion(false);
	}

	$scope.validaTC = function () {
		if (isNaN($scope.tipocambio)) {
			swal('Sólo se permiten números');
			$('#tipocambio').focus();
		}
	}

	$scope.orderByMe = function(x) {
		$scope.myOrderBy = x;
		$scope.sortDir = !$scope.sortDir;
	}

	$scope.selectRowCompra = function (idSelCompra, indexRowCompra) {		
		$scope.idSelCompra = idSelCompra;
		$scope.indexRowCompra = indexRowCompra;
	}

	$scope.despliegaCompra = function () {
		$scope.listaproductos = [];		
		$scope.isAgregaCompra = true;
		$('#barranavegacion').hide();
		$('#listacompras').hide();
		$('#barraProducto').hide();
		$scope.btnCompHide = false;
		$scope.msgBton = 'Cerrar'
		habilitar(true, true);
		habilitarBox1(true);

		$scope.docprev = $scope.listaCompras[$scope.indexRowCompra].TIPO_ORDENCOMPRA;
		$scope.numdoc = $scope.listaCompras[$scope.indexRowCompra].DOCUMENTO;
		$scope.claveprov = $scope.listaCompras[$scope.indexRowCompra].CLAVE_PROVEEDOR;
		$scope.proveedor = $scope.listaCompras[$scope.indexRowCompra].PROVEEDOR;
		$scope.descuento = $scope.listaCompras[$scope.indexRowCompra].DSCTOPROD != null ? $scope.listaCompras[$scope.indexRowCompra].DSCTOPROD : '';
		$('#fechacompra').val($scope.listaCompras[$scope.indexRowCompra].FECHA_COMPRA);
		$('#tipopago').val($scope.listaCompras[$scope.indexRowCompra].TIPO_PAGO);
		$scope.diascred = $scope.listaCompras[$scope.indexRowCompra].DIAS_PAGO == 0 ? '' : $scope.listaCompras[$scope.indexRowCompra].DIAS_PAGO;
		$scope.contrarecibo = $scope.listaCompras[$scope.indexRowCompra].CR;
		$('#fechapago').val($scope.listaCompras[$scope.indexRowCompra].FECHA_PAGO);
		$('#moneda').val($scope.listaCompras[$scope.indexRowCompra].MONEDA);
		$scope.tipocambio = $scope.listaCompras[$scope.indexRowCompra].TIPO_CAMBIO != null ? $scope.listaCompras[$scope.indexRowCompra].TIPO_CAMBIO : '';
		$scope.buscacompraclave();
	}

	$scope.buscacompraclave = function () {
		$http.get(pathCmpr + 'getcomprodbyid/' + $scope.idSelCompra, { responseType: 'json' }).
			then(function (res) {
				$scope.listaproductos = res.data;
        $scope.calculaValores();
			}).catch(function (err) {
				console.log(err);
			});
	}

	$scope.eliminacompra = function () {
    
    swal({
      title: "Está seguro que desea eliminar la  compra "+$scope.listaCompras[$scope.indexRowCompra].DOCUMENTO,
      text: "Una vez eliminada, no se podrá recuperar",
      icon: "warning",
      buttons: [true,true],
      dangerMode: true,
    })
    .then((willAdd) => {
      if(willAdd){
        $http.delete(pathCmpr + 'elimcompraprodid/' + $scope.listaCompras[$scope.indexRowCompra].ID_COMPRA + '/' + $scope.idsucursal).
        then(function (res) {
          if (res.status == "200") {
            if (Number(res.data[0].borra_compra) >= 1) {
              swal('Se ha eliminado la compra');
              $scope.getDataInit();
            }
          }
        }).catch(function (err) {
          console.log(err);
        });
      }
    });
	}

	$scope.limpiar = function () {
		$scope.codigo = '';
		$scope.descripcion = '';
		$scope.cantidad = 0;
		$scope.unidad = '';
		$scope.precio = 0;
		$('#imgfig').hide();
		$scope.imagePath = '';
	}

	$scope.btnCancel = function () {
		$scope.isAgregaCompra = false;
		$scope.limpiarBox1();
		$scope.listaproductos = [];
		if ($scope.msgBton == 'Cerrar') {
			$('#barranavegacion').show();
			$('#listacompras').show();
			$('#barraProducto').show();
			$scope.btnCompHide = true;
			$scope.msgBton = 'Cancelar';
			$scope.suma = '';
			$scope.ivapaga = '';
			$scope.total = 0;
			$scope.descuento = '';
			habilitarBox1(false);
		}
	}

	$scope.limpiarBox1 = function () {
		$scope.docprev = 'D';
		$scope.claveprov = '';
		$scope.numdoc = $scope.numdocTmp;
		$scope.proveedor = '';
		$('#tipopago').val('1');
		$scope.diascred = '';
		$scope.contrarecibo = '';
		$('#fechapago').val(formatDatePrint(new Date()));
		$('#moneda').val('1');
		$scope.tipocambio = '';
		$scope.descuento = '';
		$scope.iva = '';
	}

	$scope.eliminarPordSed = function () {
		$scope.limpiar();
		habilitar(true, true);
		$('#updtTable').val('F');
	}

	$scope.closeDivSearch = function () {
		$('#dispsearch').hide();
		$scope.lstaprdctbusq = [];
	}

	$scope.agregarcompra = function () {
		$scope.isAgregaCompra = true;
	}

});
