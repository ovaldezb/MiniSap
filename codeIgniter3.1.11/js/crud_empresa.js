

function addEmpresa()
{
	var tabla, row;
	axios.post(path+'save', {
		nombre:document.getElementById('nombre').value,
		domicilio:document.getElementById('domicilio').value,
		rfc:document.getElementById('rfc').value,
		ejercicio_fiscal:document.getElementById('ejercicio_fiscal').value,
		regimen:document.getElementById('regimen').value,
		digxcta:document.getElementById('dig1').value+document.getElementById('dig2').value+document.getElementById('dig3').value+document.getElementById('dig4').value,
		cuenta_resultado:document.getElementById('cuenta_resultado').value,
		resultado_anterior:document.getElementById('resultado_anterior').value,
		idempresa:document.getElementById('idempresa').value
		}
	).then(function(res) {
		console.log(res);
		if(res.status==200) {
			tabla = document.getElementById("tablaempresas");
			row = tabla.insertRow(tabla.rows.length);
			row.insertCell(0).innerHTML = res.data[0].NOMBRE;
			row.insertCell(1).innerHTML = res.data[0].RFC;
			row.insertCell(2).innerHTML ='<span class="icon has-text-info"> <a href="javascript:update(\''+res.data[0].ID_EMPRESA+'\');"><i class="fas fa-info-circle"></i></a></span>';
			row.insertCell(3).innerHTML ='<span class="icon has-text-danger"><a href="javascript:eliminar(\''+res.data[0].ID_EMPRESA+'\',\'index'+(tabla.rows.length-1)+'\')"><i class="fas fa-ban"></i></a></span>';
			cleanup();
			alert('La nueva empresa ha sido almacenado');
		}
	}).catch(function(err) {
		console.log(err);
	});
}

function update(id)
{
	var tabla = document.getElementById("tablaempresas");
	axios.get(path+'loadbyid/'+id, {
	responseType: 'json'})
	.then(function(res)
	{
		if(res.status == 200)
		{
			document.getElementById("nombre").value = res.data[0].nombre;
			document.getElementById("domicilio").value = res.data[0].DOMICILIO;
			document.getElementById("rfc").value = res.data[0].rfc;
			document.getElementById("ejercicio_fiscal").value = res.data[0].EJER_FISC;
			document.getElementById("regimen").value = res.data[0].ID_REGIMEN;
			document.getElementById("cuenta_resultado").value = res.data[0].CUENTA_RESULTADO;
			document.getElementById("resultado_anterior").value = res.data[0].RESULTADO_ANTERIOR;
			document.getElementById("dig1").value = res.data[0].DIGITO_X_CUENTA.split("")[0];
			document.getElementById("dig2").value = res.data[0].DIGITO_X_CUENTA.split("")[1];
			document.getElementById("dig3").value = res.data[0].DIGITO_X_CUENTA.split("")[2];
			document.getElementById("dig4").value = res.data[0].DIGITO_X_CUENTA.split("")[3];
		}
	}).catch(function(err)
	{
		console.log(err);
	});
	var submit = document.getElementById("submit");
	submit.innerHTML = "<button class=\"button is-link\" onClick=\"submitUpdate("+id+");\">Update</button>";
}

function submitUpdate(id)
{
	axios.put(path+'update/'+id,
	{
		nombre:document.getElementById('nombre').value,
		domicilio:document.getElementById('domicilio').value,
		rfc:document.getElementById('rfc').value,
		ejercicio_fiscal:document.getElementById('ejercicio_fiscal').value,
		regimen:document.getElementById('regimen').value,
		digxcta:document.getElementById('dig1').value+document.getElementById('dig2').value+document.getElementById('dig3').value+document.getElementById('dig4').value,
		cuenta_resultado:document.getElementById('cuenta_resultado').value,
		resultado_anterior:document.getElementById('resultado_anterior').value
	}).then(function(res)
	{
		if(res.status==200)
		{
			alert(res.data.value);
			cleanup();

		}
	}).catch(function(err)
	{
		console.log(err);
	});

	var submit = document.getElementById("submit");
	submit.innerHTML = '<button class=\"button is-link\" id="add" onClick="addEmpresa();">Agregar</button>';
}

function eliminar(id,index)
{
	var idx=0;
	var tabla = document.getElementById("tablaempresas");
	for (i = 1; i <tabla.rows.length; i++)
	{
		td = tabla.rows[i].cells;
		for (j = 4; j < td.length; j++)
		{
			if (td[j].innerHTML.indexOf(index) > -1)
			{
				idx = i;
				break;
			}
		}
	}

	axios.delete(path+'delete/'+id).
	then(function(res){
		if(res.status==200)
		{
			if(res.data.value=='OK')
			{
				tabla.deleteRow(idx);
				alert('Empresa elimnada exitosamente');
			}
		}
	}).catch(function(err){
		console.log(err)
	})
}

function doFilter(filter)
{
	var td, tr, found;
	var tabla = document.getElementById("tablaempresas");
	 for (i = 1; i <tabla.rows.length; i++)
	 {
		 td = tabla.rows[i].cells;
		 for (j = 0; j < td.length-2; j++)
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

function cleanup()
{
	document.getElementById("nombre").value = '';
	document.getElementById("domicilio").value = '';
	document.getElementById("rfc").value = '';
	document.getElementById("ejercicio_fiscal").value = '';
	document.getElementById("regimen").value = '';
	document.getElementById("cuenta_resultado").value = '';
	document.getElementById("resultado_anterior").value = '';
	document.getElementById("dig1").value = '';
	document.getElementById("dig2").value = '';
	document.getElementById("dig3").value = '';
	document.getElementById("dig4").value = '';
}
