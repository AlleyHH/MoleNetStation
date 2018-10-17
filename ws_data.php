<?php
header ( 'Access-Control-Allow-Origin: *' );
$servername = "localhost";
$username = "root";
$password = "Ali@4545121";
$dbname = "testdb";

// Create connection
$conn = mysqli_connect ( $servername, $username, $password, $dbname );
// Check connection
if (! $conn) {
	die ( "Connection failed: " . mysqli_connect_error () );
}

$sql = "SELECT * FROM weatherStation ORDER BY idweatherStation DESC LIMIT 1";
$result = mysqli_query ( $conn, $sql );
$data = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$data[] = $row;
}

echo json_encode($data);
mysqli_close ( $conn );

?>
