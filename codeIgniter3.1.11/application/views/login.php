<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
<head>
  <title>MiniSAP</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
</head>
<body>
	<div class="container">
		<div class="notification">
			<h1 class="title is-2 has-text-centered">Bienvenido al Sistema de Gestion Empresarial</h1>
		</div>
  <?php echo $error; ?>
	  <form action="<?php echo base_url();?>access/login" method="post" accept-charset="utf-8">
			<table style="width:100%" >
				<tr>
					<td align="center">
						<table class="table" >
				      <tr>
				        <td style="vertical-align:middle" align="right">Usuario:</td>
				        <td><input type="text" class="input" name="username"></td>
				      </tr>
				      <tr>
				        <td style="vertical-align:middle" align="right">Password:</td>
				        <td><input type="password" class="input" name="password"></td>
				      </tr>
				      <tr>
				        <td colspan="2" align="center"><button class="button is-success" type="submit">OK</button></td>
				      </tr>
				    </table>
					</td>
				</tr>
			</table>
	  </form>
	</div>
</html>
