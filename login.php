<?php
	session_start();
	if (isset($_POST['submit']))
	{
		$un = $_POST['user'];
		$pw = $_POST['inputPassword'];
		$servername = "localhost";
		$username = "root";
		$password = "Ali@4545121";
		$dbname = "testdb";
		$conn = mysqli_connect ( $servername, $username, $password, $dbname );
		if (! $conn) 
       		{
			die ( "Connection failed: " . mysqli_connect_error () );
		}

		$sql = "SELECT * FROM Users;";
		$result = mysqli_query ( $conn, $sql );
		$data = array ();

		while ( $row = mysqli_fetch_assoc ( $result ) ) 
		{
			if($row["UserName"] == $un && $row["Password"] == $pw)
			{				
				$_SESSION["user"] = $un;
				if ($_SESSION["page"] == "rt")
				{
					header("Location: rt.php"); 
				}
				if ($_SESSION["page"] == "data")
				{
					header("Location: datapage.php"); 
				}
				if ($_SESSION["page"] == "network")
				{
					header("Location: networkpage.php"); 
				}
				if ($_SESSION["page"] == "conf")
				{
					header("Location: config.php"); 
				}				
			}
		}
		echo "<script language='javascript' type='text/javascript'>";
		echo "alert('User Name or Password Incorrect');";
		echo "</script>";	
	}
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
		href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/login.css" />
	<title>Login</title>
</h:head>
<h:body>

	<div class="container">

		<form method="post" class="form-signin">
			<h2 class="form-signin-heading">Please Log In</h2>
			<label for="user" class="sr-only">User Name</label> 
			<input type="text" name="user" class="form-control" placeholder="User Name"> </input>
			<label for="inputPassword" class="sr-only">Password</label>
			<input type="password" name="inputPassword" class="form-control" placeholder="Password"></input>
			<button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Log In</button>
		</form>

	</div>
</h:body>
</html>
