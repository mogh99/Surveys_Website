<?php
	
	
	if(isset($_POST["surveyName"]) && isset($_POST["surveyDescription"])){
		$surveyName = $_POST["surveyName"];
		$surveyDescription = $_POST["surveyDescription"];
	}

	//this page have to handle three buttons for the survey
	//edit survey, statistics of a survey, and delete a survey
	if(isset($_POST["edit"])){
		//edit will take the information of the survey
		//from the table and recreate all the questions
		header("Location: ../CreateSurvey.php?edit=true&surveyName=$surveyName&surveyDescription=$surveyDescription");
	}
	elseif (isset($_POST["delete"])) {
		session_start();
		require_once("databaseConnection.php");

		$name = $_SESSION["name"];

		define("DeleteFromUser", "DELETE FROM `$name` WHERE `Survey_Name` = '$surveyName'");
		define("DropTheTable", "DROP TABLE `$surveyName`");

		mysqli_query($connection, DeleteFromUser);
		mysqli_query($connection, DropTheTable);
		
		header("Location: ../DashBoard.php");
	}
	elseif(isset($_POST["statistics"])){
		$surveyName = $_POST["surveyName"];
		header("Location: ../Statistics.php?surveyName=$surveyName");
	}
?>