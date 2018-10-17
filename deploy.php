<?php
$datastring = $_POST["params"];
//echo "received data = " . $datastring;
$data = explode ( ',', $datastring );
//echo $data[0];
$servername = "localhost";
$username = "root";
$password = "Ali@4545121";
$dbname = "testdb";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$value = "'".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."', '".$data[7]."', "."now()".", '" . $data[8]."', '".$data[9]."', '".$data[10]."', '".$data[11]."', '".$data[12]."', '".$data[13]."', '".$data[14]."'";
//echo $value;
$sql = "INSERT INTO data (sinkID, packetType, packetLength, sourceID, temp, die, rtt, rssi, timeStamp, ws_temp, ws_ws, ws_hum, ws_wg, ws_rain,nextHopID,ws_d) VALUES (".$value.")";
//echo $sql;
	if (mysqli_query($conn, $sql)) {
//		echo "New record created successfully\n";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	}

	$sql2 = "SELECT * FROM Nodes";
	$result = mysqli_query ( $conn, $sql2 );
	$Nodes = array ();
	$i = 0;

	while ( $row = mysqli_fetch_assoc ( $result ) ) {
		$Nodes[$i] = $row["NodeID"];
		$i = $i+1;
	}

	if (in_array($data[0], $Nodes))
	{
//		echo $data[0] . " exists\n";
	}
	else
	{
//		echo $data[0] . " created\n";
		$sql3 = "INSERT INTO Nodes (NodeID,sinkNode) VALUES ('".$data[0]."','1')";
		mysqli_query ( $conn, $sql3 );
		$sql2 = "SELECT * FROM Nodes";
		$result = mysqli_query ( $conn, $sql2 );
		$Nodes = array ();
		$i = 0;

		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$Nodes[$i] = $row["NodeID"];
			$i = $i+1;
		}
	}
	if (in_array($data[3], $Nodes))
	{
//		echo $data[3] . " exists\n";
	}
	else
	{
//		echo $data[3] . " created\n";
		$sql4 = "INSERT INTO Nodes (NodeID,sinkNode) VALUES ('".$data[3]."','0')";
		mysqli_query ( $conn, $sql4 );
		$sql2 = "SELECT * FROM Nodes";
		$result = mysqli_query ( $conn, $sql2 );
		$Nodes = array ();
		$i = 0;

		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$Nodes[$i] = $row["NodeID"];
			$i = $i+1;
		}
	}
	if (in_array($data[13], $Nodes))
	{
//		echo $data[13]. " exists\n";
	}
	else
	{
//		echo $data[13]. " created\n";
		$sql5 = "INSERT INTO Nodes (NodeID,sinkNode) VALUES ('".$data[13]."','0')";
		mysqli_query ( $conn, $sql5 );
		$sql2 = "SELECT * FROM Nodes";
		$result = mysqli_query ( $conn, $sql2 );
		$Nodes = array ();
		$i = 0;

		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$Nodes[$i] = $row["NodeID"];
			$i = $i+1;
		}
	}
	$sql10 = "DELETE FROM data WHERE packetLength=0";
	mysqli_query($conn, $sql10);
	$sql11 = "DELETE FROM Nodes WHERE NodeID=0";
	mysqli_query($conn, $sql11);

	$sql12 = "Select status from Config WHERE idConfig = '4'";
	$result2 = mysqli_query($conn, $sql12);
	$row = mysqli_fetch_assoc($result2);
	if($row["status"] == "1")
	{
		echo("1");
	}
	if($row["status"] == "2")
	{
		echo("2");
	}
	mysqli_close($conn);
?>
