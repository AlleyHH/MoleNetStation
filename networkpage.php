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
	<link rel="stylesheet" href="css/style.css" />
	<link rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css" />
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
	<title>Network Configuration</title>
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
						<li class="nav-item"><a class="nav-link" href="datapage.php">Data
								Log</a></li>
						<li class="nav-item"><a class="nav-link" href="wspage.php">Weather
								Station</a></li>
						<li class="nav-item active"><a class="nav-link"
							href="networkpage.php">Network Topology</a></li>
						<li class="nav-item"><a class="nav-link" href="config.php">Network
								Configuration</a></li>
					</ul>
				</div>
			</nav>
		</header>
	</div>
	<div id="content-area" class="container-fluid">
		<div class="row py-5 my-5">
			<div class="col-lg-2">
				<div id="na" class="font-weight-bold text-left"></div>
				<div id="tn" class="font-weight-light text-left"></div>
				<div id="aNodes" class="font-weight-light text-left"></div>
				<div id="artt" class="font-weight-light text-left"></div>
				<div id="arssi" class="font-weight-light text-left"></div>
			</div>
			<div class="col-lg-10">
				<div id="mynetwork" style="height: 500px;"></div>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js" />
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<script>
		window.onload = function() {

	var totalNodes = 0;
	var activeNodes = 0;
	var averageRTT = 0;
	var averageRSSI = 0;
	var active1 = [];
	var active2 = [];

	var ajax = new XMLHttpRequest();
	var method = "GET";
	var url1 = "http://134.102.188.200/nodes.php";
	var url2 = "http://134.102.188.200/network.php";
	var asynchronous = true;
	ajax.open(method, url1, asynchronous);
	ajax.send();
	ajax.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			var nodes = JSON.parse(this.responseText);
			// console.log(nodes.length);
			totalNodes = nodes.length;
			document.getElementById("na").innerHTML = "Network Analysis";
			document.getElementById("tn").innerHTML = "Total Nodes: "
					+ totalNodes;
			var n = new vis.DataSet();
			for (var a = 0; a < nodes.length; a++) {
				if (nodes[a].sinkNode == 1) {
					n.add({
						id : nodes[a].NodeID,
						label : "Base Station",
						color : {
							border : '#333333',
							background : '#737373',
							highlight : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							},
							hover : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							}
						},
						shape : 'elipse'
					});
				} else {
					n.add({
						id : nodes[a].NodeID,
						label : "Node " + nodes[a].NodeID,
						color : {
							border : '#333333',
							background : '#b3b3b3',
							highlight : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							},
							hover : {
								border : '#2B7CE9',
								background : '#D2E5FF'
							}
						},
						shape : 'circle'
					});
				}
			}

			ajax.open(method, url2, asynchronous);
			ajax.send();
			ajax.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					var edges = JSON.parse(this.responseText);
					// console.log(edges);
					var e = new vis.DataSet();
					var route = [];
					var routeNew = [];
					for (var b = 0; b < edges.length; b++) {
						var path = edges[b].sourceID + "," + edges[b].nextHopID + "," + edges[b].rssi;
						route[b] = path;
						averageRTT = averageRTT + parseInt(edges[b].rtt);
						averageRSSI = averageRSSI + parseInt(edges[b].rssi);
					}
					routeNew = route;
					var s1 = 0;
					for (var c = 0; c < routeNew.length; c++)
					{
						var unique = routeNew[c].split(",");
						s1 = 0;
						for (var g = 0; g < route.length; g++)
						{
							var check = routeNew[g].split(",");
							if((unique[0] == check[0]) && (unique[1] == check[1]))
							{
								s1 = s1+1;
								if (s1 >= 1)
								{
									routeNew.splice(g,1);
								}
							}
						}
					}
					averageRTT = averageRTT / edges.length;
					averageRSSI = averageRSSI / edges.length;
					averageRTT = Number((averageRTT).toFixed(2));
					averageRSSI = Number((averageRSSI).toFixed(2));
					document.getElementById("artt").innerHTML = "Average RTT: "
							+ averageRTT;
					document.getElementById("arssi").innerHTML = "Average RSSI: "
							+ averageRSSI;
					//routeNew = uniqueArray(route);
					//console.log(routeNew);
					for (var d = 0; d < routeNew.length; d++) {
						var line = routeNew[d].split(",");
   						//console.log(line[2]);
						if (line[0] !== line[1]) {
							e.add({
								from : line[0],
								to : line[1],
								arrows : 'to',
								label : "RSSI: " + line[2],
								labelHighlightBold: true,
							});
							active1[d] = line[0];
							active2[d] = line[1];
						}
					}
					// console.log(moles.length);
					for (var f = 0; f < nodes.length; f++) {
						if (active1.contains(nodes[f].NodeID)
								|| active2.contains(nodes[f].NodeID)) {
							activeNodes = activeNodes + 1;
						}
					}
					document.getElementById("aNodes").innerHTML = "Active Nodes: "
							+ activeNodes;
					var container = document.getElementById('mynetwork');
					var data = {
						nodes : n,
						edges : e
					};
					var options = {
						height : '100%',
						width : '100%',
						layout : {
							hierarchical : {
								enabled : true,
								levelSeparation : 250,
								nodeSpacing : 200,
								treeSpacing : 500,
								direction : "LR",
								sortMethod : "directed"
							}
						},
						interaction : {
							dragNodes : false
						},
						physics : {
							enabled : false
						},
						nodes : {
							borderWidth : 1,
							borderWidthSelected : 2,
							chosen : true,
							font : {
								color : '#343434',
								size : 25
							}
						},
						edges : {
							color : {
								color : '#848484',
								highlight : '#848484',
								hover : '#848484',
								inherit : 'from',
								opacity : 1.0
							}
						}
					};
					var network = new vis.Network(container, data, options);
				}
			}
		}
	}
}

var uniqueArray = function(arrArg) {
	return arrArg.filter(function(elem, pos, arr) {
		return arr.indexOf(elem) == pos;
	});
};

Array.prototype.contains = function(elem) {
	for ( var i in this) {
		if (this[i] == elem)
			return true;
	}
	return false;
}
	</script>
</h:body>
</html>

<?php   
} else 
{	
	session_start();
	$_SESSION["page"] = "network";
	header("Location: login.php"); 
}	
?>
