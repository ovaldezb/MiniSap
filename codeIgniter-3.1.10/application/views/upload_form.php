<html>
<head>
<link rel="stylesheet" href="https://bulma.io/css/bulma-docs.min.css?v=202003220948">
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
<title>Cargar Archivo</title>
<script>
function show()
{
	console.log('show');
	document.getElementById('getFile').click();
}

function setName()
{
	var name = document.getElementById('getFile');
	document.getElementById('file_name').innerHTML = name.files.item(0).name;
}


</script>
</head>
<body>
<div class="container">
<?php echo form_open_multipart('upload/do_upload');?>
<?php
		if(isset($error)){
			if(count($error)>1){
				foreach($error as $err )
				{
					echo $err.'<br>';
				}
			}else
			{
				echo $error[0];
			}
			echo $upload_path;
		}
?>
<input type="hidden" class="input" name="nfname" value="<?php echo $nombre?>">
<input type="hidden" class="input" name="idempresa" value="<?php echo $idempresa?>">
<input style="display:none" id="getFile" type="file"  name="userfile" onchange="setName();" value="">
<div class="file has-name is-primary">
  <label class="file-label">
    <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-upload"></i>
      </span>
      <span class="file-label" onclick="show();">
        Seleccione un archivo…
      </span>
    </span>
    <span class="file-name" id="file_name">
    </span>
  </label>
</div>
<br>
<button class="button is-link is-small">Enviar</button>
</form>
</div>
</body>
</html>
