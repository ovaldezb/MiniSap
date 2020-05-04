<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Codeigniter MongoDB Create Read Update Delete Example</title>
    </head>
    <body>
        <div id="container">
            <h1>Codeigniter MongoDB Create Read Update Delete Example</h1>
			
			<div>
				<?php echo anchor('/usercontroller', 'Back to Users');?>
			</div>
			
            <div id="body">
                <?php
					if (isset($error)) {
						echo '<p style="color:red;">' . $error . '</p>';
					} else {
						echo validation_errors();
					}
                ?>
				<?php echo $this->uri->uri_string() ;
						if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
							$ip = $_SERVER['HTTP_CLIENT_IP'];
						} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
							$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
						} else {
							$ip = $_SERVER['REMOTE_ADDR'];
						}
						echo $ip;
						$current_ip_address = $this->input->ip_address();
						echo $current_ip_address;
				?>
                <form action="http://35.231.110.9/codeigniter3.1.11/index.php/usercontroller/create" name="form" id="form" method="post" accept-charset="utf-8">

                <h5>Full Name</h5>
                <input type="text" name="name" value="<?php echo set_value('name');?>" size="50" />

                <h5>Email Address</h5>
                <input type="text" name="email" value="<?php echo set_value('email');?>" size="50" />

                <p><input type="submit" name="submit" value="Submit"/></p>
                
                </form>
            </div>
        </div>
    </body>
</html>