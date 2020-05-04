function setValue(valor)
{
	var id_empresa_codigo = document.getElementById('idemprcodigo');
	document.getElementById('imgsrc').src='./uploads/'+id_empresa_codigo.value+'/'+ valor;
	document.getElementById('img_name').value = './uploads/'+id_empresa_codigo.value+'/'+valor;
}

function showImg(flag)
{
	var x = document.getElementById("imgsrc");
	var y = document.getElementById('img_name');
	if(flag=='True')
	{
		x.style.display = "block";
	}else
	{
		x.src = '';
		x.style.display = "none";
		y.value = '';
	}
}

function popupwindow(url, title, w, h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  return window.open(url, title, 'titlebar=0, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

function toUpper(nombre)
{
	nombre.value = nombre.value.toUpperCase();
}
