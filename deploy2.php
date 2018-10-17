<?php
$datastring = $_POST["params"];
echo "received data = " . $datastring;
$data = explode ( ',', $datastring );
// echo $data[0];

$servername = "localhost";
$username = "root";
$password = "Ali@4545121";
$dbname = "testdb";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$value = "'".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', '".$data[5]."', "."now()";
//echo $value;
$sql = "INSERT INTO weatherStation (ws_temp, ws_hum, ws_ws, ws_wg, ws_rain,ws_d,timeStamp) VALUES (".$value.")";
//echo $sql;
	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully\n";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
	
	$sql2 = "delete  from weatherStation where ws_temp =0 and ws_hum =0 and ws_rain = 0  and ws_d = 0 and ws_ws = 0  and ws_wg = 0";
        mysqli_query($conn, $sql2);

	mysqli_close($conn);

?>
