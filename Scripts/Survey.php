<?php 
	//Note MCQ name is (MCQID"questionNumber")
	//WrittenQuestion name is (WrittenID"questionNumber")


	//this script is required to create new table for 
	//for the respondent which be named as (username_surveyName)
	//this table will have one answer for every question
	
	//to know the question of an answer you can take 
	//it from the survey table 
	if(isset($_POST["submit"])){
		session_start();

		require_once("databaseConnection.php");

		$name = $_SESSION["respondentName"];
		$surveyName = $_SESSION["surveyName"];
		$tableName = $name."_".$surveyName;

		$query = "";

		$MCQNumber = $_POST["MCQNumber"];
		$WrittenNumber = $_POST["WrittenNumber"];

		$surveyTableName = $_SESSION["surveyTableName"];

		define("CreateTable", "CREATE TABLE `$tableName` (
			ID INT NOT NULL AUTO_INCREMENT,
			Answer VARCHAR(100) NULL,
			Type VARCHAR(10) NULL,
			PRIMARY KEY(ID))");

		define("InsertAnswers", "INSERT INTO `$tableName`
			(Answer, 
			Type)
			VALUES ");

		define("TruncateTable", "TRUNCATE TABLE `$surveyTableName`");
		echo TruncateTable;

		if(isset($_POST['edit'])){
			mysqli_query($connection, TruncateTable);
			//the values will be taken form the user answers and added to the query
			//Insert all the MCQ answers
			for($i = 0; $i < $MCQNumber; $i++){
				if(isset($_POST["MCQID$i"])){	
					$Answer = $_POST["MCQID$i"];
					$query = InsertAnswers."('$Answer', 'MCQ')";
					mysqli_query($connection, $query);
				}
			}

			for($i = $MCQNumber; $i < ($WrittenNumber+$MCQNumber); $i++){
				//Note allways the Written questions 
				$Answer = $_POST["WrittenID$i"];
				if($Answer != ""){	
					$query = InsertAnswers."('$Answer', 'Written')";
					mysqli_query($connection, $query);
				}
			}
			
		}
		else{
			//the values will be taken form the user answers and added to the query
			if($table = mysqli_query($connection, CreateTable)){
				//Insert all the MCQ answers
				for($i = 0; $i < $MCQNumber; $i++){
					if(isset($_POST["MCQID$i"])){	
						$Answer = $_POST["MCQID$i"];
						$query = InsertAnswers."('$Answer', 'MCQ')";
						mysqli_query($connection, $query);
					}
				}

				for($i = $MCQNumber; $i < ($WrittenNumber+$MCQNumber); $i++){
					//Note allways the Written questions 
					$Answer = $_POST["WrittenID$i"];
					if($Answer != ""){	
						$query = InsertAnswers."('$Answer', 'Written')";
						mysqli_query($connection, $query);
					}
				}
			}
		}
		session_destroy();
		mysqli_close($connection);
		header("Location: ../index.html");
	}
?>