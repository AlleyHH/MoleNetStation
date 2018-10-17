<?php
session_start();
if (isset($_SESSION['user'])) {
?>

<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	xmlns:h="http://java.sun.com/jsf/html"
	xmlns:p="http://primefaces.org/ui"
	xmlns:ui="http://java.sun.com/jsf/facelets"
	xmlns:f="http://java.sun.com/jsf/core">

<h:head>
	<meta http-equiv="X-UA-Compatible" content="ie=edge; charset=UTF-8" />
	<meta name="viewport"
		content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link
		href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.2/css/tabulator.min.css"
		rel="stylesheet" />
	<link rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" />
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.2/css/bootstrap/tabulator_bootstrap.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<title>Real-Time Data</title>
</h:head>
<h:body>
	<div id="top" class="container-fluid">
		<header class="masthead">
			<div class=row>
				<div class=col-lg-4>
					<h3 class="text-muted">MoleNet Station</h3>
				</div>
				<div id="warning" class=col-lg-7 style="font-size:100%; color:#F04D4D; font-weight: bold;">

				</div>
				<div class=col-lg-1>
					<a href="logout.php">
					<h4 class="text-muted">Logout</h4>
					</a>
				</div>
			</div>
			<nav
				class="navbar navbar-expand-md navbar-light bg-light rounded mb-3">
				<button class="navbar-toggler" type="button" data-toggle="collapse"
					data-target="#navbarCollapse" aria-controls="navbarCollapse"
					aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarCollapse">
					<ul class="navbar-nav text-md-center nav-justified w-100">
						<li class="nav-item"><a class="nav-link" href="index.php">Home
								<span class="sr-only">(current)</span>
						</a></li>
						<li class="nav-item active"><a class="nav-link"
							href="rt.php">Real-Time Data</a></li>
						<li class="nav-item"><a class="nav-link" href="datapage.php">Data
								Log</a></li>
						<li class="nav-item"><a class="nav-link" href="wspage.php">Weather
								Station</a></li>
						<li class="nav-item"><a class="nav-link" href="networkpage.php">Network
								Topology</a></li>
						<li class="nav-item"><a class="nav-link" href="config.php">Network
								Configuration</a></li>
					</ul>
				</div>
			</nav>
		</header>
	</div>
	<div id="content-area" class="container-fluid">
		<div id="example-table"></div>
	</div>
	<div class="container-fluid">
		<footer class="footer">
			<a href="http://www.uni-bremen.de/"> 
				<img src="resources/logo_uni-bremen.png" style="float: left;" />
			</a> 
			<a href="https://www.comnets.uni-bremen.de/start/"> 
				<img src="resources/logo_comnets.png" style="float: right;" />
			</a>
		</footer>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.2/js/tabulator.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<script>
		window.onload = function() 
{
	var updateInterval = 4000;
	var pID = 0;

	$("#example-table").tabulator({
	    height:"700px",
	    width: "500px",
	    layout:"fitColumns",
	    placeholder:"No Data Available",
	    columns:
	    [
	    	{title:"Time Stamp", field:"ts", headerSort:false, align:"center"},
	        {title:"Source ID", field:"soid", headerSort:false, align:"center"},
	        {title:"Sink ID", field:"siid", headerSort:false, align:"center"},
	        {title:"Packet Type", field:"pt", headerSort:false, align:"center"},
	        {title:"Packet Length", field:"pl", headerSort:false, align:"center"},
	        {title:"Temperature", field:"temp", headerSort:false, align:"center"},
	        {title:"Dielectric", field:"die", headerSort:false, align:"center"},
	        {title:"RTT", field:"rtt", headerSort:false, align:"center"},
	        {title:"RSSI", field:"rssi", headerSort:false, align:"center"}
	    ],
	});
	
	var updateTable = function() 
	{
		var ajax = new XMLHttpRequest();
		var method = "GET";
		var url = "http://134.102.188.200/data.php";
		var asynchronous = true;

		ajax.open(method, url, asynchronous);
		ajax.send();
		ajax.onreadystatechange = function() 
		{
			if (this.readyState == 4 && this.status == 200) 
			{
				var data = JSON.parse(this.responseText);
				//console.log(data);
				var waqt = data[0].timeStamp.split(" ");
				var din = waqt[0].split("-");
				var corr_format = din[1] + "/" + din[2] + "/" + din[0] + " " + waqt[1];
				//console.log(corr_format);
				var last = new Date(corr_format);
				var current = new Date();
				last.setDate(last.getDate() + 1);
				//console.log(current);
				//console.log(last);
				if(current > last)
				{
					document.getElementById("warning").innerHTML = "Base Station Not Responding. Please check Battery Power or manually restart the GSM Shield";
				}
				if(pID < data[0].iddata)
				{
					pID = data[0].iddata;
					$("#example-table").tabulator("addData", [ {
						id : 1,
						ts : data[0].timeStamp,
						soid : data[0].sourceID,
						siid : data[0].sinkID,
						pt : data[0].packetType,
						pl : data[0].packetLength,
						temp : data[0].temp,
						die : data[0].die,
						rtt : data[0].rtt,
						rssi : data[0].rssi
					} ], true);
				}
			}
		}
	}	
	setInterval(function() {updateTable()}, updateInterval);
}
	</script>
</h:body>
</html>

<?php   
} else 
{	
	session_start();
	$_SESSION["page"] = "rt";
	header("Location: login.php"); 
}	
?>
