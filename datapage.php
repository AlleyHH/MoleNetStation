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
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
	<link
		href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.2/css/tabulator.min.css"
		rel="stylesheet" />
	<link rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" />
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.2/css/bootstrap/tabulator_bootstrap.min.css" />
	<link
		href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
		rel="stylesheet"
		integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
		crossorigin="anonymous" />
	<link rel="stylesheet" href="css/style.css" />
	<title>Data Log</title>
</h:head>
<h:body>
	<div id="top" class="container-fluid">
		<header class="masthead">
			<div class=row>
				<div class=col-lg-11>
					<h3 class="text-muted">MoleNet Station</h3>
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
						<li class="nav-item"><a class="nav-link" href="rt.php">Real-Time
								Data</a></li>
						<li class="nav-item active"><a class="nav-link"
							href="datapage.php">Data Log</a></li>
						<li class="nav-item"><a class="nav-link" href="wspage.php">Weather
								Station</a></li>
						<li class="nav-item"><a class="nav-link"
							href="networkpage.php">Network Topology</a></li>
						<li class="nav-item"><a class="nav-link" href="config.php">Network
								Configuration</a></li>
					</ul>
				</div>
			</nav>
		</header>
	</div>
	<div id="content-area" class="container-fluid">
		<div class="container-fluid  clearfix">
			<div class="row py-2 my-5">
				<div class="col-lg-10">
					<input id="waqt" class="flatpickr flatpickr-input active h-100"
						type="text" placeholder="Enter DateTime Range to query Database"
						readonly="readonly" />
				</div>
				<div class="col-lg-2">
					<button class="btn btn-success align-middle h-100"
						style="background-color: #f7f7f7; color: black; font-weight: bold; border-color: #eee; width: 100%;"
						onclick="myFunction()">Go</button>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row align-middle">
				<div id="example-table"></div>
				<div class="row">
					<div class="col-lg-5">
						<button type="button" class="btn btn-default"
							style="background-color: #f7f7f7; border: 1px solid #e5e5e5; color: black;"
							id="download-csv">Import CSV</button>
					</div>
					<div class="col-lg-5">
						<button type="button" class="btn btn-default"
							style="background-color: #f7f7f7; border: 1px solid #e5e5e5; color: black;"
							id="download-json">Import JSON</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<footer class="footer">
			<a href="http://www.uni-bremen.de/"> <img
				src="resources/logo_uni-bremen.png" style="float: left;" />
			</a> <a href="https://www.comnets.uni-bremen.de/start/"> <img
				src="resources/logo_comnets.png" style="float: right;" />
			</a>
		</footer>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"
		integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
		crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
		integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
		crossorigin="anonymous"></script>
	<script
		src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
	<script type="text/javascript"
		src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/3.3.2/js/tabulator.min.js"></script>
	<script
		src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script>
		window.onload = function() {

			flatpickr(".flatpickr", {
				enableTime : true,
				altInput : true,
				dateFormat : "Y-m-d H:i",
				mode : "range",
				time_24hr : true
			});

			$("#example-table").tabulator({
				height : "600px",
				width : "800px",
				layout : "fitColumns",
				placeholder : "No Data Available",
				columns : [ {
					title : "Time Stamp",
					field : "ts",
					headerSort : false,
					align : "center"
				}, {
					title : "Source ID",
					field : "soid",
					headerSort : false,
					align : "center"
				}, {
					title : "Sink ID",
					field : "siid",
					headerSort : false,
					align : "center"
				}, {
					title : "Packet Type",
					field : "pt",
					headerSort : false,
					align : "center"
				}, {
					title : "Packet Length",
					field : "pl",
					headerSort : false,
					align : "center"
				}, {
					title : "Temperature",
					field : "temp",
					headerSort : false,
					align : "center"
				}, {
					title : "Dielectric",
					field : "die",
					headerSort : false,
					align : "center"
				}, {
					title : "RTT",
					field : "rtt",
					headerSort : false,
					align : "center"
				}, {
					title : "RSSI",
					field : "rssi",
					headerSort : false,
					align : "center"
				} ],
			});

			//trigger download of data.csv file
			$("#download-csv").click(function() {
				$("#example-table").tabulator("download", "csv", "data.csv");
			});

			//trigger download of data.json file
			$("#download-json").click(function() {
				$("#example-table").tabulator("download", "json", "data.json");
			});
		}

		function myFunction() {
			var input = document.getElementById("waqt").value;
			var res = input.split(" to ");
			var sendData = "sendData=" + res[0] + "," + res[1];
			var ajax = new XMLHttpRequest();
			var method = "POST";
			var url = "http://134.102.188.200/ws.php";
			var asynchronous = true;
			ajax.open(method, url, asynchronous);
			ajax.setRequestHeader("Content-Type",
					"application/x-www-form-urlencoded");
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var data = JSON.parse(this.responseText);
					console.log(data);
					var len = data.length;
					for (var a = 0; a < len; a++) {
						$("#example-table").tabulator("addData", [ {
							id : a,
							ts : data[a].timeStamp,
							soid : data[a].sourceID,
							siid : data[a].sinkID,
							pt : data[a].packetType,
							pl : data[a].packetLength,
							temp : data[a].temp,
							die : data[a].die,
							rtt : data[a].rtt,
							rssi : data[a].rssi
						} ], true);
					}
				}
			}
			ajax.send(sendData);
		}
	</script>
</h:body>
</html>

<?php   
} else 
{	
	session_start();
	$_SESSION["page"] = "data";
	header("Location: login.php"); 
}	
?>
