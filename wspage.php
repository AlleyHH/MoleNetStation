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
	<link rel="stylesheet" href="css/style.css" />
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
	<title>Weather Station</title>
</h:head>

<h:body>
	<div id="top" class="container-fluid">
		<header class="masthead">
			<h3 class="text-muted">MoleNet Station</h3>

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
						<li class="nav-item"><a class="nav-link" href="datapage.php">Data
								Log</a></li>
						<li class="nav-item active"><a class="nav-link"
							href="wspage.php">Weather Station</a></li>
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
		<div class="row clearfix">
			<div class col-lg-2>
				<div id="g1"></div>
			</div>
			<div class col-lg-2>
				<div id="g2"></div>
			</div>
			<div class col-lg-2>
				<div id="g3"></div>
			</div>
			<div class col-lg-2>
				<div id="g4"></div>
			</div>
			<div class col-lg-2>
				<div id="g5"></div>
			</div>
			<div class col-lg-2>
				<div id="g6"></div>
			</div>
		</div>
		<hr />
		<div class="container-fluid  clearfix">
			<div class="row py-2 my-0">
				<div class="col-lg-4" style="margin-top: 5%;">
					<input id="waqt" class="flatpickr flatpickr-input active h-25"
						type="text" placeholder="Enter DateTime Range to query Database"
						readonly="readonly" />
					<button class="btn btn-success align-middle h-25"
						style="background-color: #f7f7f7; color: black; font-weight: bold; border-color: #eee; width: 100%;"
						onclick="myFunction()">Go</button>
				</div>
				<div class="col-lg-8">
					<div id="example-table"></div>
				</div>
			</div>
		</div>
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
	<script src="js/raphael-2.1.4.min.js"></script>
	<script src="js/justgage.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<script>
		window.onload = function() {
	var g1, g2, g3, g4, g5;
	var updateInterval = 3000;
	var pID = 0;

	var g1 = new JustGage({
		id : "g1",
		min : -10,
		max : 50,
		relativeGaugeSize : true,
		symbol : ' °C',
		pointer : true,
		label : "Temperature",
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g2 = new JustGage({
		id : "g2",
		min : 0,
		max : 100,
		relativeGaugeSize : true,
		symbol : ' %',
		pointer : true,
		label : "Humidity",
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g3 = new JustGage({
		id : "g3",
		// value : getRandomInt(0, 100),
		min : 0,
		max : 20,
		relativeGaugeSize : true,
		symbol : ' km/h',
		label : "Wind Speed",
		pointer : true,
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g4 = new JustGage({
		id : "g4",
		min : 0,
		max : 30,
		relativeGaugeSize : true,
		symbol : ' km/h',
		label : "Wind Gust",
		pointer : true,
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g5 = new JustGage({
		id : "g5",
		min : 0,
		max : 50,
		relativeGaugeSize : true,
		symbol : ' %',
		label : "Rainfall",
		pointer : true,
		gaugeWidthScale : 0.6,
		customSectors : [ {
			color : '#ff0000',
			lo : 50,
			hi : 100
		}, {
			color : '#00ff00',
			lo : 0,
			hi : 50
		} ],
		counter : true
	});

	var g6 = new JustGage({
		id: 'g6',
		min: 0,
		max: 360,
		relativeGaugeSize : true,
		symbol: '°',
		label : "Wind Direction",
		valueMinFontSize : 8,
		donut: true,
		pointer: true,
		gaugeWidthScale: 0.6,
		pointerOptions: {
		  toplength: 10,
		  bottomlength: 10,
		  bottomwidth: 8,
		  color: '#000'
		},
		labelMinFontSize : 8,
		counter: true,
      });

	flatpickr(".flatpickr", {
		enableTime : true,
		altInput : true,
		dateFormat : "Y-m-d H:i",
		mode : "range",
		time_24hr : true
	});

	$("#example-table").tabulator({
		height : "400px",
		width : "500px",
		layout : "fitColumns",
		placeholder : "No Data Available",
		columns : [ {
			title : "Time Stamp",
			field : "ts",
			headerSort : false,
			align : "center"
		}, {
			title : "Temperature",
			field : "temp",
			headerSort : false,
			align : "center"
		}, {
			title : "Humidity",
			field : "hum",
			headerSort : false,
			align : "center"
		}, {
			title : "Wind Speed",
			field : "ws",
			headerSort : false,
			align : "center"
		}, {
			title : "Wind Gust",
			field : "wg",
			headerSort : false,
			align : "center"
		}, {
			title : "Rainfall",
			field : "rain",
			headerSort : false,
			align : "center"
		} ],
	});

	var updateGauges = function() {
		var ajax = new XMLHttpRequest();
		var method = "GET";
		var url = "http://134.102.188.200/ws_data.php";
		var asynchronous = true;

		ajax.open(method, url, asynchronous);
		ajax.send();
		ajax.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var data = JSON.parse(this.responseText);
				console.log(data);
				if (pID < data[0].idweatherStation) {
					pID = data[0].idweatherStation;
					g1.refresh(parseInt(data[0].ws_temp));
					g2.refresh(parseInt(data[0].ws_hum));
					g3.refresh(parseInt(data[0].ws_ws));
					g4.refresh(parseInt(data[0].ws_wg));
					g5.refresh(parseInt(data[0].ws_rain));
					g6.refresh(parseInt(data[0].ws_d));
				}
			}
		}
	}

	setInterval(function() {
		updateGauges()
	}, updateInterval);
}

function myFunction() {
	var input = document.getElementById("waqt").value;
	var res = input.split(" to ");
	var sendData = "sendData=" + res[0] + "," + res[1];
	var ajax = new XMLHttpRequest();
	var method = "POST";
	var url = "http://134.102.188.200/ws_storage.php";
	var asynchronous = true;
	ajax.open(method, url, asynchronous);
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.onreadystatechange = function() {
		if (ajax.readyState == 4 && ajax.status == 200) {
			var data = JSON.parse(this.responseText);
			console.log(data);
			var len = data.length;
			for (var a = 0; a < len; a++) {
				$("#example-table").tabulator("addData", [ {
					id : a,
					ts : data[a].timeStamp,
					temp : data[a].ws_temp,
					hum : data[a].ws_hum,
					ws : data[a].ws_ws,
					wg : data[a].ws_wg,
					rain : data[a].ws_rain
				} ], true);
			}
		}
	}
	ajax.send(sendData);
}
	</script>
</h:body>

</html>
