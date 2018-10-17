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

$sql = "Select * from Config where idConfig=3";
$result = mysqli_query ( $conn, $sql );
$data = array ();

while ( $row = mysqli_fetch_assoc ( $result ) ) {
	$s = $row["status"];
}

echo($s);
mysqli_close ( $conn );

?>
