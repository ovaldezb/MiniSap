function printProveedores()
{	var tabla, row;	
	axios.get('/codeigniter3.1.11/index.php/provercontroller/load', {
	responseType: 'json'})
	.then(function(res)
	{
		if(res.status == '200')
		{
			tabla = document.getElementById("tablaprovedores");
			row = tabla.insertRow(0);					
			row.insertCell(0).innerHTML = 'NOMBRE';
			row.insertCell(1).innerHTML = 'CLAVE';					
			row.insertCell(2).innerHTML = 'Update';
			row.insertCell(3).innerHTML = 'Delete';
			for(var i=0; i< res.data.length;i++)
			{	
				row = tabla.insertRow(i+1);						
				row.insertCell(0).innerHTML = res.data[i].NOMBRE;
				row.insertCell(1).innerHTML = res.data[i].CLAVE;
				row.insertCell(2).innerHTML ='<button onClick=\"update('+res.data[i].ID_PROVEEDOR+')\">Update</button>';
				row.insertCell(3).innerHTML ='<button onClick=\"eliminar('+res.data[i].ID_PROVEEDOR+','+(i+1)+')\">Delete</button>';
			}
		}
	})
	.catch(function(err) {
		console.log(err);
	});
}

function addProveedor()
{			
	var tabla, row;	
	axios.post('/codeigniter3.1.11/index.php/provercontroller/save', {		
		clave:document.getElementById('clave').value,
		nombre:document.getElementById('nombre').value,
		domicilio:document.getElementById('domicilio').value,
		cp:document.getElementById('cp').value,
		telefono:document.getElementById('telefono').value,
		contacto:document.getElementById('contacto').value,
		rfc:document.getElementById('rfc').value,
		curp:document.getElementById('curp').value,
		id_tipo_prov:document.getElementById('id_tipo_prov').value,
		dias_cred:document.getElementById('dias_cred').value,
		id_tipo_alc_prov:document.getElementById('id_tipo_alc_prov').value,
		banco:document.getElementById('banco').value,
		cuenta:document.getElementById('cuenta').value,
		email:document.getElementById('email').value,	
		notas:document.getElementById('notas').value
		}
	).then(function(res) {
		console.log(res);
		if(res.status==200) {
			tabla = document.getElementById("tablaprovedores");
			row = tabla.insertRow(tabla.rows.length);
			row.insertCell(0).innerHTML = res.data[0].NOMBRE;
			row.insertCell(1).innerHTML = res.data[0].CLAVE;	
			row.insertCell(2).innerHTML ='<button onClick=\"update('+res.data[0].ID_PROVEEDOR+')\">Update</button>';
			row.insertCell(3).innerHTML ='<button onClick=\"eliminar('+res.data[0].ID_PROVEEDOR+','+(tabla.rows.length-1)+')\">Delete</button>';
			alert('El nuevo proveedor ha sido almacenado');
			cleanup();
		}
	}).catch(function(err) {
		console.log(err);
	});
}

function update(id)
{			
	var tabla = document.getElementById("tablaempresas");	
	axios.get('/codeigniter3.1.11/index.php/provercontroller/loadbyid/'+id, {
	responseType: 'json'})
	.then(function(res)
	{	
		if(res.status == 200)
		{				
			document.getElementById('clave').value = res.data[0].CLAVE;
			document.getElementById("nombre").value = res.data[0].NOMBRE;			
			document.getElementById('domicilio').value = res.data[0].DOMICILIO;
			document.getElementById('cp').value = res.data[0].CP;
			document.getElementById('telefono').value = res.data[0].TELEFONO;
			document.getElementById('contacto').value = res.data[0].CONTACTO;
			document.getElementById('rfc').value = res.data[0].RFC;
			document.getElementById('curp').value = res.data[0].CURP;
			document.getElementById('id_tipo_prov').value = res.data[0].ID_CATEGORIA_PROV;
			document.getElementById('dias_cred').value = res.data[0].DIAS_CRED;
			document.getElementById('id_tipo_alc_prov').value = res.data[0].ID_TIPO_ALC_PROV;
			document.getElementById('banco').value = res.data[0].ID_BANCO;
			document.getElementById('cuenta').value = res.data[0].CUENTA;
			document.getElementById('email').value = res.data[0].EMAIL;	
			document.getElementById('notas').value = res.data[0].NOTAS;
			
		}
	}).catch(function(err)
	{
		console.log(err);				
	});			 
	var submit = document.getElementById("submit");
	submit.innerHTML = "<button onClick=\"submitUpdate("+id+");\">Update</button>";
}

function submitUpdate(id)
{	
	axios.put('/codeigniter3.1.11/index.php/provercontroller/update/'+id, 
	{				
		clave:document.getElementById('clave').value,
		nombre:document.getElementById('nombre').value,
		domicilio:document.getElementById('domicilio').value,
		cp:document.getElementById('cp').value,
		telefono:document.getElementById('telefono').value,
		contacto:document.getElementById('contacto').value,
		rfc:document.getElementById('rfc').value,
		curp:document.getElementById('curp').value,
		id_tipo_prov:document.getElementById('id_tipo_prov').value,
		dias_cred:document.getElementById('dias_cred').value,
		id_tipo_alc_prov:document.getElementById('id_tipo_alc_prov').value,
		banco:document.getElementById('banco').value,
		cuenta:document.getElementById('cuenta').value,
		email:document.getElementById('email').value,	
		notas:document.getElementById('notas').value
	}).then(function(res)
	{							
		if(res.status==200 && res.data.value=='OK')
		{
			alert('El proveedor se actualizó correctamente');			
		}else
		{
			alert('Error,  no se puedo actualizar el proveedor');			
		}
		cleanup();
	}).catch(function(err)
	{
		console.log(err);				
	});			
	var submit = document.getElementById("submit");
	submit.innerHTML = '<button id="add" onClick="addProveedor();">Agregar</button>';				
}
		
function eliminar(id,index)
{
	axios.delete('/codeigniter3.1.11/index.php/provercontroller/delete/'+id).
	then(function(res){
		if(res.status==200)
		{
			if(res.data.value=='OK')
			{
				alert('Cliente elimnado exitosamente');
				var tabla = document.getElementById("tablaprovedores");
				tabla.deleteRow(index);							
			}
		}
	}).catch(function(err){
		console.log(err)
	})
}

function doFilter(filter)
{
	var td, tr, found;
	var tabla = document.getElementById("tablaprovedores");
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
	document.getElementById('clave').value = '';
	document.getElementById('nombre').value = '';
	document.getElementById('domicilio').value = '';
	document.getElementById('cp').value = '';
	document.getElementById('telefono').value = '';
	document.getElementById('contacto').value = '';
	document.getElementById('rfc').value = '';
	document.getElementById('curp').value = '';
	document.getElementById('id_tipo_prov').value = '';
	document.getElementById('dias_cred').value = '';
	document.getElementById('id_tipo_alc_prov').value = '';
	document.getElementById('banco').value = '';
	document.getElementById('cuenta').value = '';
	document.getElementById('email').value = '';
	document.getElementById('notas').value = '';
}