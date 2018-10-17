<?php
header ( 'Access-Control-Allow-Origin: *' );
$datastring = $_POST["sendData"];

$servername = "localhost";
$username = "root";
$password = "Ali@4545121";
$dbname = "testdb";

$conn = mysqli_connect ( $servername, $username, $password, $dbname );
if (! $conn) {
	die ( "Connection failed: " . mysqli_connect_error () );
}

$dates = explode ( ',', $datastring );
$sql = "SELECT * FROM data WHERE timestamp >= '" . $dates [0] . "' AND timestamp <= '" . $dates [1] . "'";
//echo $sql;
$result = mysqli_query ( $conn, $sql );
$db = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {	
	$db [] = $row;
}

echo json_encode($db);
mysqli_close ( $conn );
?>
