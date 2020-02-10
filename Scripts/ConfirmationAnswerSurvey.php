<?php
	if(isset($_POST["submit"])){
		session_start();

		require_once("databaseConnection.php");

		//if the enterd code equal the sent generated code
		//then Insert the respondent information in respondents table
		//which is in st201670960 database
		//
		//else show an error message


		//this will be change when I implement 
		//the email functionality
		//$randomCode = "$_SESSION['random']";
		//for now randomCode=111111
		$randomCode = "111111";

		$name = $_SESSION["respondentName"];
		$email = $_SESSION["email"];
		$surveyName = $_SESSION["surveyName"]; 
		define("InsertUser", "INSERT INTO 
			respondents (Name, Email) 
			VALUES 
			('$name'
			,'$email')");


		if($randomCode == $_POST["confirmation"]){
			//insert the user data into the table respondents
			//and create table for the respondent that will include
			//only the survey name 
			//and redirect the user to Survey.php
			if(isset($_POST["answerd"]) == "true"){
				header("Location: ../Survey.php?answerd=true");
				die();
			}
			else{	
				mysqli_query($connection, InsertUser);
				header("Location: ../Survey.php");
				die();
			}
		}
		else{
			//confirmation code is wrong
			//show an error message 
			header("Location: ../ConfirmationAnswerSurvey.php?error=WrongCode");
		}
	}
?>