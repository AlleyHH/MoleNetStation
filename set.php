<?php
header ( 'Access-Control-Allow-Origin: *' );
$datastring = $_POST["sendData"];

$servername = "localhost";
$username = "root";
$password = "Ali@4545121";
$dbname = "testdb";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}

if($datastring == 1)
{
	$sql1 = "UPDATE Config SET status = '1' WHERE idConfig = 4";
	$result = mysqli_query ( $conn, $sql1 );
	echo("LED ON");
}
if($datastring == 2)
{
	$sql2 = "UPDATE Config SET status = '2' WHERE idConfig = 4";
	$result = mysqli_query ( $conn, $sql2 );
	echo("LED OFF");
}

mysqli_close ( $conn );

?>
