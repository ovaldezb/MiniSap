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

function formatDateReporte(fechaRep)
{
	var mesNombre = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	var fecha;
	var dia = fechaRep.substr(0,2);
	var mes = Number(fechaRep.substr(3,2))-1;
	var year = fechaRep.substr(6,4);
	fecha = dia+' de '+mesNombre[mes]+' de '+year;
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

function formatHoraReporte(hoy)
{
	var mesNombre = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
	var dia, mes, year;
	var fecha;
	dia = hoy.getDate() <10 ? '0'+hoy.getDate() : hoy.getDate();
	mes = hoy.getMonth() < 9 ? '0'+(hoy.getMonth()+1) : (hoy.getMonth()+1);
	year = hoy.getFullYear();
	var hours = hoy.getHours();
  var minutes = hoy.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
	fecha = dia+' de '+mesNombre[mes-1]+' de '+year+' '+hours+':'+minutes+' '+ ampm ;
	return fecha
}

function formatFecPago(fecha)
{
	var dia = fecha.substr(0,2);
	var mes = fecha.substr(3,2);
	var year = fecha.substr(6,4);
	return year+'-'+mes+'-'+dia;
}

function formatFecQuery(fecha,tipo)
{
	var horas;
	var dia = fecha.substr(0,2);
	var mes = fecha.substr(3,2);
	var year = fecha.substr(6,4);
	if(tipo=='ini'){
		horas = '00:00:00'
	}else if(tipo=='fin'){
		horas = '23:59:59'
	}
	return mes+'-'+dia+'-'+year+' '+horas;
}

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
				 break;
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
