<?php
	if(isset($_POST["submit"])){
		session_start();

		require_once("databaseConnection.php");

		//if the enterd code equal the sent generated code
		//then Insert the user information in user table
		//which is in st201670960 database
		//
		//else show an error message


		//this will be change when I implement 
		//the email functionality
		//$randomCode = "$_SESSION['random']";
		//for now randomCode=111111
		$randomCode = "111111";

		$name = $_SESSION["name"];
		$email = $_SESSION["email"];
		$password = $_SESSION["password"]; 

		define("InsertUser", "INSERT INTO 
			users (Name, Email, Password) 
			VALUES 
			('$name'
			,'$email'
			,'$password')");

		define("CreateTable","CREATE TABLE $name 
			(Survey_Name VARCHAR(30) , 
			Survey_Description VARCHAR(30),
			PRIMARY KEY(Survey_Name))");

		if($randomCode == $_POST["confirmation"]){
			//insert the user data into the table users
			//and create table for the surveyor that will include
			//only the sruveys name, survey description
			//and redirect the user to DashBoard.php
			mysqli_query($connection, InsertUser);
			mysqli_query($connection, CreateTable);
			header("Location: ../DashBoard.php");
		}
		else{
			//confirmation code is wrong
			//show an error message 
			header("Location: ../ConfirmationSignUp.php?error=WrongCode");
		}
	}
?>