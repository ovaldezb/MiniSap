<html>
<head>
<title>Upload Form</title>
<script>
	function OnLoad() {
		window.opener.setValue('<?php echo $nombre.$upload_data['file_ext']?>');
		window.opener.showImg('True');
		setTimeout(function () { window.close();}, 1000);
	}
</script>
</head>
<body onload="OnLoad();">
<?php
		if(isset($error)){
			echo $error;
		}
		else
		{ ?>
			<h3>El Archivo <?php echo $nombre.$upload_data['file_ext'] ?> se cargó exitosamente</h3>
<?php	}?>

</body>
</html>
