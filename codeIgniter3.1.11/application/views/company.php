<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Codeigniter MongoDB Create Read Update Delete Example</title>	
</head>

<body>

<div>
	<h1>Codeigniter Postgress Create Read Update Delete Example</h1>

	<div>
		<?php echo anchor('http://35.231.110.9/codeigniter3.1.11/index.php/usercontroller/create', 'Create User');?>
	</div>

	<div id="body">
		<?php
			if ($company) {
		?>
        <table class="datatable">
            <thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Age</th>
					<th>Address</th>
					<th>Salary</th>
					<th>Date</th>
                </tr>
            </thead>
			<tbody>
				<?php
					$i = 0;
					foreach ($company as $comp) {
						$col_class = ($i % 2 == 0 ? 'odd_col' : 'even_col');
						$i++;
					?>
					<tr class="<?php echo $col_class; ?>">
						<td>
							<?php echo $comp['id']; ?>
						</td>
						<td>
							<?php echo $comp['name']; ?>
						</td>
						<td>
							<?php echo $comp['age']; ?>
						</td>
						<td>
							<?php echo $comp['address']; ?>
						</td>
						<td>
							<?php echo $comp['salary']; ?>
						</td>
						<td>
							<?php echo $comp['join_date']; ?>
						</td>
					</tr>
					<?php
				}
				?>
			</tbody>
        </table>
    <?php
        } else {
            echo '<div style="color:red;"><p>No Record Found!</p></div>';
        }
    ?>
	</div>
</div>

</body>
</html>
