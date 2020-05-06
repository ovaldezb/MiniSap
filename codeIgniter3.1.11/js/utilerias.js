function addListener()
{
	var tabla = document.getElementById('permisos');
	var td, tr,i,j;
	for (i = 0; i <tabla.rows.length; i++)
	{
		td = tabla.rows[i].cells;
		for (j = 0; j < td.length; j++)
		{
			//if ( td[i].addEventListener ) {
				td[j].addEventListener("click", alertRowCell, false);
			//} else if ( cls[i].attachEvent ) {
			//	td[i].attachEvent("onclick", alertRowCell);
			//}
		}
	}
}

function formatDatePrint(hoy)
{
	var dia, mes, year;
	var fecha;
	dia = hoy.getDate() <10 ? '0'+hoy.getDate() : hoy.getDate();
	mes = hoy.getMonth() < 9 ? '0'+(hoy.getMonth()+1) : (hoy.getMonth()+1);
	fecha = dia+'/'+mes+'/'+hoy.getFullYear();
	return fecha
}

function formatDatePantalla(hoy)
{
	var dia, mes, year;
	var diasNombre = ['Domingo','Lunes','Martes','Miércoles', 'Jueves','Viernes','Sábado'];
	var mesNombre = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	var fecha;
	dia = hoy.getDate() <10 ? '0'+hoy.getDate() : hoy.getDate();
	mes = hoy.getMonth() < 9 ? '0'+(hoy.getMonth()+1) : (hoy.getMonth()+1);
	fecha = diasNombre[hoy.getDay()]+' '+dia+' de '+mesNombre[hoy.getMonth()]+' de '+hoy.getFullYear();
	return fecha
}

function formatDateInsert(hoy)
{
	var dia, mes, year;
	var hrs, min, seg;
	var fecha;
	dia = hoy.getDate() <10 ? '0'+hoy.getDate() : hoy.getDate();
	mes = hoy.getMonth() < 9 ? '0'+(hoy.getMonth()+1) : (hoy.getMonth()+1);
	year = hoy.getFullYear();
	hrs = hoy.getHours();
	min = hoy.getMinutes();
	seg = hoy.getSeconds();
	fecha = year+'-'+mes+'-'+dia+' '+hrs+':'+min+':'+seg ;
	return fecha
}

function formatFecPago(fecha)
{
	var dia = fecha.substr(0,2);
	var mes = fecha.substr(3,2);
	var year = fecha.substr(6,4);
	return year+'-'+mes+'-'+dia;
}

//$('#tipopago').change(function()
function cambiotp()
{
  if($('#tipopago').val() ==1)
  {
    $('#diascred').prop('disabled',true);
    $('#diascred').val('');
    $('#fechapago').val(formatDatePrint(new Date()));
  }else if (tipopago.value ==2) {
    $('#diascred').prop('disabled',false);
    $('#diascred').focus();
  }
}


function sumadias()
{
  if($('#diascred').val()!='' && isNaN($('#diascred').val()))
  {
    $('#diascred').val('');
    alert('Sólo se permiten números')
    $('#diascred').focus();
  }
  var nuevafecha = new Date();
  nuevafecha.setDate(nuevafecha.getDate() + Number($('#diascred').val()));
  $('#fechapago').val(formatDatePrint(nuevafecha));
}

function habilitar(activar,all)
{
  $('#codigo').prop('disabled',activar);
  $('#descripcion').prop('disabled',activar);
  $('#unidad').prop('disabled',activar);
  $('#desctoprod').prop('disabled',activar);
	if(all){
    $('#desctoprod').prop('disabled',activar);
    $('#precio').prop('disabled',activar);
    $('#cantidad').prop('disabled',activar);
    $('#mascant').prop('disabled',activar);
    $('#mencant').prop('disabled',activar);
	}
}

function habilitarEdicion(activar)
{
  $('#desctoprod').prop('disabled',activar);
  $('#precio').prop('disabled',activar);
  $('#cantidad').prop('disabled',activar);
  $('#mascant').prop('disabled',activar);
  $('#mencant').prop('disabled',activar);
}


function agregarcompra()
{
  $('#divdisplay').show();
}

function closeDivSearchProv()
{
	$('#buscaprov').hide();
}

function habilitarBox1(ishabilitado)
{
  $('#docprev').prop('disabled',ishabilitado);
  $('#claveprov').prop('disabled',ishabilitado);
  $('#numdoc').prop('disabled',ishabilitado);
  $('#fechacompra').prop('disabled',ishabilitado);
  $('#proveedor').prop('disabled',ishabilitado);
  $('#tipopago').prop('disabled',ishabilitado);
  $('#diascred').prop('disabled',ishabilitado);
  $('#contrarecibo').prop('disabled',ishabilitado);
  $('#fechapago').prop('disabled',ishabilitado);
  $('#moneda').prop('disabled',ishabilitado);
  $('#tipocambio').prop('disabled',ishabilitado);
  $('#descuento').prop('disabled',ishabilitado);
  $('#iva').prop('disabled',ishabilitado);
}

function doFilter(filter,nombre_tabla)
{
	var td, tr, found, i, j;
	var tabla = document.getElementById(nombre_tabla);
	 for (i = 0; i <tabla.rows.length; i++)
	 {
		 td = tabla.rows[i].cells;
		 for (j = 0; j < td.length; j++)
		 {
			 if (td[j].innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1)
			 {
				 found = true;
			 }
		 }
		 if (found) {
			tabla.rows[i].style.display = "";
			found = false;
		} else {
			tabla.rows[i].style.display = "none";
		}
	 }
}



function alertRowCell(e){
  var cell = e.target || window.event.srcElement;
  //alert( cell.cellIndex + ' : ' + cell.parentNode.rowIndex );
	if(cell.style.backgroundColor == "red")
	{
		//cell.bgColor = "green";
		cell.style.backgroundColor = "green";
	}else if(cell.style.backgroundColor == "green") {
			//cell.bgColor = "red";
			cell.style.backgroundColor = "red";
	}else {
		cell.style.backgroundColor = "green";
	}

}
