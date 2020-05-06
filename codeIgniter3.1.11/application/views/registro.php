<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html>
<head>
  <title>Alta de Usuarios</title>
</head>
<body>
  <?php echo validation_errors(); ?>
  <form action="<?php echo base_url();?>access/signin_validation" method="post" accept-charset="utf-8">
    <table>
      <tr>
        <td>Usuario:</td>
        <td><input type="text" name="username"></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="password"></td>
      </tr>
      <tr>
        <td>Confirmar Password:</td>
        <td><input type="password" name="cpassword"></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><input type="submit" value="Send"></td>
      </tr>
    </table>
  </form>
</html>
