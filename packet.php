<?php
while(true)
{
	$sinkID = 100;
	$packetType = mt_rand ( 1, 2 );
	$packetLength = mt_rand ( 5, 10 );
	$sourceID = 4;
	$temprature = mt_rand ( 2, 15 );
	$dielectric = mt_rand ( 21, 30 );
	$rtt = mt_rand ( 7, 12 );
	$rssi = mt_rand ( - 80, - 72 );
	$timestamp = date ( "Y-m-d-H:i:s" );
	$wsT = mt_rand ( 5, 20 );
	$wsSp = mt_rand ( 4, 8 );
	$wsH = mt_rand ( 10, 30 );
	$wsWg = mt_rand ( 10, 18  );
	$wsR = mt_rand ( 1, 15 );
	$wsD = mt_rand ( 0, 360 );
	$nextHopID = 100;
	$datastring = $sinkID . "," . $packetType . "," . $packetLength . "," . $sourceID . "," . $temprature . "," . $dielectric . "," . $rtt . "," . $rssi . "," . $timestamp . "," . $wsT . "," . $wsSp . "," . $wsH . "," . $wsWg . "," . $wsR . "," . $nextHopID . "," . $wsD;
	//echo $datastring;
	//$post = $_POST [$datastring];
	//$url = "deploy.php?params=" . $datastring;
	//header ( 'Location: ' . $url );

	$data = explode ( ',', $datastring );

$servername = "localhost";
$username = "root";
$password = "Ali@4545121";
$dbname = "testdb";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
}
$value = "'".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."', '".$data[7]."', "."now()".", '" . $data[9]."', '".$data[10]."', '".$data[11]."', '".$data[12]."', '".$data[13]."', '".$data[14]."', '".$data[15]."'";
//echo $value;
$sql = "INSERT INTO data (sinkID, packetType, packetLength, sourceID, temp, die, rtt, rssi, timeStamp, ws_temp, ws_ws, ws_hum, ws_wg, ws_rain,nextHopID,ws_d) VALUES (".$value.")";
//echo $sql;
	if (mysqli_query($conn, $sql)) {
		echo "New record created successfully\n";
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
		echo $data[0] . " exists\n";
	}
	else
	{
		echo $data[0] . " created\n";
		$sql3 = "INSERT INTO Nodes (NodeID,sinkNode) VALUES ('".$data[0]."','1')";
		mysqli_query ( $conn, $sql3 );
		$sql2 = "SELECT * FROM Nodes";
		$result = mysqli_query ( $conn, $sql2 );
		$Nodes = array ();
		$i = 0;

		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$Nodes[$i] = $row["NodeID"];
//			echo $Nodes[$i]."\n";
			$i = $i+1;
		}
	}
	if (in_array($data[3], $Nodes))
	{
		echo $data[3] . " exists\n";
	}
	else
	{
		echo $data[3] . " created\n";
		$sql4 = "INSERT INTO Nodes (NodeID,sinkNode) VALUES ('".$data[3]."','0')";
		mysqli_query ( $conn, $sql4 );
		$sql2 = "SELECT * FROM Nodes";
		$result = mysqli_query ( $conn, $sql2 );
		$Nodes = array ();
		$i = 0;

		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$Nodes[$i] = $row["NodeID"];
//			echo $Nodes[$i]."\n";
			$i = $i+1;
		}
	}
	if (in_array($data[14], $Nodes))
	{
		echo $data[14]. " exists\n";
	}
	else
	{
		echo $data[14]. " created\n";
		$sql5 = "INSERT INTO Nodes (NodeID,sinkNode) VALUES ('".$data[14]."','0')";
		mysqli_query ( $conn, $sql5 );
		$sql2 = "SELECT * FROM Nodes";
		$result = mysqli_query ( $conn, $sql2 );
		$Nodes = array ();
		$i = 0;

		while ( $row = mysqli_fetch_assoc ( $result ) ) {
			$Nodes[$i] = $row["NodeID"];
//			echo $Nodes[$i]."\n";
			$i = $i+1;
		}
	}
	mysqli_close($conn);
	sleep(1);
}

?>
