<?php
	
	$hostname = "";
	$username = "";
	$password = "";
	$database = "";


	$connection = mysqli_connect($hostname, $username, $password, $database);

	if(!$connection){
		die("Error: can't connect to the database");
	}

?>