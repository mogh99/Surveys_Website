<?php
	//new survey creation
	if(isset($_POST["submitNew"])){
		session_start();
		require_once("databaseConnection.php");

		$notUsed = true;
		

		//name is the survyor name
		$name = $_SESSION["name"];

		$surveyName = mysqli_real_escape_string($connection, $_POST["surveyName"]);
		$description = mysqli_real_escape_string($connection, $_POST["description"]);

		define("ShowTables", "SHOW TABLES");
		define("GetSurveyName", "SELECT Survey_Name FROM ");

		define("InsertSurveyInformation", "INSERT INTO $name
			(Survey_Name, 
			Survey_Description) 
			VALUES 
			('$surveyName',
			'$description')");

		define("CreateSurveyTable", "CREATE TABLE `$surveyName` (
			ID INT NOT NULL AUTO_INCREMENT,
			Question VARCHAR(100) NULL,
			Answer1 VARCHAR(100) NULL,
			Answer2 VARCHAR(100) NULL,
			Answer3 VARCHAR(100) NULL,
			Answer4 VARCHAR(100) NULL,
			Answer5 VARCHAR(100) NULL,
			Answer6 VARCHAR(100) NULL,
			PRIMARY KEY(ID))");
		
		if($tables = mysqli_query($connection, ShowTables)){
			if(mysqli_num_rows($tables) > 0){
				//retrive all the tables names and 
				while($table = mysqli_fetch_array($tables)){
					
					if($retrivedSurveysNames = mysqli_query($connection, (GetSurveyName.$table[0]))){
						if(mysqli_num_rows($retrivedSurveysNames) > 0){

							//retrive all the surveys names in the table
							//and check if it is has been used or not
							while($retrivedSurveyName = mysqli_fetch_array($retrivedSurveysNames)){
								if($surveyName == $retrivedSurveyName[0]){
									header("Location: ../CreateSurvey.php?error=UsedSurveyName");
									$notUsed = false;
									die();
								}
							}
						}
					}
					//check if the survey name does not the name of one of the users
					if($table[0] == $surveyName){
						header("Location: ../CreateSurvey.php?error=UsedSurveyName");
						$notUsed = false;
						die();
					}
				}
			}
		}

		//using the variable $notUsed is only to make sure everything is ok
		//
		//1.we add the survey name and survey description to the user table
		//usertable = $_SESSION["name"];
		//2.we create new table that have the same survey name which will have
		//all the questions and the answers for the survey
		//3.insert all the questions and answers for the survey
		if($notUsed){			
			//MCQNumber start from 1
			//convention about the value of MCQ
			//MCQ'i'id'id'  where i = 0,..,6 and id is the MCQNumber
			$MCQNumber = $_POST["MCQNumber"];
			$WrittenNumber = $_POST["WrittenNumber"];

			if($MCQNumber != 0 || $WrittenNumber != 0){
				mysqli_query($connection, InsertSurveyInformation);
				mysqli_query($connection, CreateSurveyTable);	

				insertQuestions($connection, $MCQNumber, $WrittenNumber);
			}
			else{
				//thrwo an error because there is no questions
				header("Location: ../CreateSurvey.php?error=NoQuestions");
			}
		}
		
	}

	//Insert the questions when edit the survey
	elseif(isset($_POST["submitEdit"])){
		require_once("databaseConnection.php");

		$MCQNumber = $_POST["MCQNumber"];
		$WrittenNumber = $_POST["WrittenNumber"];
		
		$surveyName = $_POST["surveyName"];
		$description = $_POST["description"];

		define("TruncateTable", "TRUNCATE TABLE `$surveyName`");
		
		if($MCQNumber != 0 || $WrittenNumber != 0){
			mysqli_query($connection, TruncateTable);
			insertQuestions($connection, $MCQNumber, $WrittenNumber);
		}
		else{
			//thrwo an error because there is no questions
			header("Location: ../CreateSurvey.php?error=NoQuestions&edit=true&surveyName=$surveyName&surveyDescription=$description");
		}
	}

	function insertQuestions($connection, $MCQNumber, $WrittenNumber){
		$surveyName = $_POST["surveyName"];

		define("InsertMCQ", "INSERT INTO `$surveyName`
			(`Question`,
			`Answer1`,
			`Answer2`,
			`Answer3`,
			`Answer4`,
			`Answer5`,
			`Answer6`)
			Values (");

		define("InsertWritten", "INSERT INTO `$surveyName`
			(`Question`)
			Values (");

		for($i = 1; $i <= $MCQNumber; $i++){
			$MCQQuestion = $_POST["MCQ0id$i"];
			$MCQAnswer1  = $_POST["MCQ1id$i"];
			$MCQAnswer2  = $_POST["MCQ2id$i"];
			$MCQAnswer3  = $_POST["MCQ3id$i"];
			$MCQAnswer4  = $_POST["MCQ4id$i"];
			$MCQAnswer5  = $_POST["MCQ5id$i"];
			$MCQAnswer6  = $_POST["MCQ6id$i"];

			//insert the data into the table of name surveyName = $_POST['surveyName']
			mysqli_query($connection, 
				(InsertMCQ."
					'$MCQQuestion', 
					'$MCQAnswer1', 
					'$MCQAnswer2', 
					'$MCQAnswer3', 
					'$MCQAnswer4', 
					'$MCQAnswer5', 
					'$MCQAnswer6')"));
		}

		//WrittenNumber start form 1
		//convention about the value of Written
		//Writtenid'id' where id is the WrittenNumber
		for($i = 1; $i <= $WrittenNumber; $i++){
			$WrittenQuestion = $_POST["Writtenid$i"];

			mysqli_query($connection, InsertWritten."'$WrittenQuestion')");
		}
		header("Location: ../DashBoard.php");
	} 
	
?>