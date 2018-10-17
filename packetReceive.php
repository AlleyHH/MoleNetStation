<?php
$datastring = $_GET['params'];
echo "received data = ".$datastring;
$servername = "localhost";
$username = "root";
$password = "Ali@4545121";

try {
	$conn = new PDO("mysql:host=$servername;dbname=testdb", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "Connected successfully";
}
catch(PDOException $e)
{
	echo "Connection failed: " . $e->getMessage();
}
?>
