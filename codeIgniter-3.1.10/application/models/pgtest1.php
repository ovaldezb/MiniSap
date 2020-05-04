<?php 

$host = "127.0.0.1"; 
//$host = "localhost";
$db = "testdb"; 
$port = 5432; 
$user="postgres";
//$con = pg_connect("host=$host port=$port dbname=$db")
$con = pg_connect("dbname=$db user=$user")
    or die ("Could not connect to server-> ".$host."-> ".pg_last_error()."\n"); 

//$query = "SELECT VERSION()"; 
$query = "SELECT COUNT(*) FROM COMPANY";
$rs = pg_query($con, $query) or die("Cannot execute query: $query\n"); 
$row = pg_fetch_row($rs);

echo $row[0] . "\n";

pg_close($con); 

?>
