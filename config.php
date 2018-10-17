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
						<li class="nav-item"><a class="nav-link" href="networkpage.php">Network
								Topology</a></li>
						<li class="nav-item active"><a class="nav-link" href="config.php">Network
								Configuration</a></li>
					</ul>
				</div>
			</nav>
		</header>
	</div>
	<div id="content-area" class="container-fluid">
		<div class="container py-5 my-5">
			<form method="post">
			<button type="button" class="btn btn-info btn-block" style="background-color:#f7f7f7; border: 1px solid #e5e5e5; color: 			black;" name="on" id="1" onClick="reply_click(this.id)">LED On</button>
			<button type="button" class="btn btn-info btn-block" style="background-color:#f7f7f7; border: 1px solid #e5e5e5; color: 			black;" name="off" id="2" onClick="reply_click(this.id)">LED Off</button>
			</form>
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
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
	<script>
		function reply_click(clicked_id)
		{
    			var sendData = "sendData=" + clicked_id;
			var ajax = new XMLHttpRequest();
			var method = "POST";
			var url = "http://134.102.188.200/set.php";
			var asynchronous = true;
			ajax.open(method, url, asynchronous);
			ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			ajax.onreadystatechange = function() 
			{
				if (ajax.readyState == 4 && ajax.status == 200) 
				{
					alert(ajax.responseText);
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
	$_SESSION["page"] = "conf";
	header("Location: login.php"); 
}	
?>
