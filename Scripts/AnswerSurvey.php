<?php
	if(isset($_POST["submit"])){
		session_start();
		require_once("databaseConnection.php");

		$name = mysqli_real_escape_string($connection, $_POST["username"]);
		$email = mysqli_real_escape_string($connection, $_POST["email"]);
		$surveyName = mysqli_real_escape_string($connection, $_POST["surveyName"]);


		$surveyIsFound = false;

		define("SelectByName", "
			SELECT `Name` 
			FROM `respondents`
			WHERE 
			Name='$name'");

		define("SelectByEmail", "
			SELECT `Email`
			FROM `respondents` 
			WHERE  
			Email='$email'");

		define("SelectSurveyor", "
			SELECT `Name`
			FROM `users`");

		define("ShowTables", "SHOW TABLES");

		//check if the username of the respondents has not been
		//used either by the survyors or respondents

		//this part of code from 35 to 66 is really really complex
		//I need to fix it later 
		if($tables = mysqli_query($connection, ShowTables)){
			if(mysqli_num_rows($tables) > 0){
				while($table = mysqli_fetch_array($tables)){
					if($table[0] == $name){
						//the respondent name match a surveyor name, a respondent name
						//or a surveyor name 
						//throw an error
						header("Location: ../AnswerSurvey.php?error=UsedName");
						die();
					}
					//check if the survey name is available 
					else{
						if($table[0] == $surveyName){
							//the survey name is available
							$surveyIsFound = true;
						}
					}
				}	
			}
		}

		//check if the survey name is not a survyor name
		if($surveyorsNames = mysqli_query($connection, SelectSurveyor)){
			if(mysqli_num_rows($surveyorsNames) > 0){
				while($surveyorName = mysqli_fetch_array($surveyorsNames)){
					if($surveyorName[0] == $surveyName){
						header("Location: ../AnswerSurvey.php?error=NoSurvey");
						die();
					}
				}
			}
		}

		if(!$surveyIsFound){
			//throw an error because the survey is not available
			//in the database
			header("Location: ../AnswerSurvey.php?error=NoSurvey");
			die();
		}

		//check if the name or email has been used
		$checkName = mysqli_fetch_row(mysqli_query($connection, SelectByName));
		//$checkName[0] = name
		//mysqli_error($connection); 
		
		$checkEmail = mysqli_fetch_row(mysqli_query($connection, SelectByEmail));
		//$checkEmail[0] = email
		//mysqli_error($connection);

		if(empty($checkName)){
			if(empty($checkEmail)){
				//name and email are not used
				//redirect to the ConfirmationAnswerSurvey.php page
				//and put all (name, email, surveyName, and generated random code)
				//in the $_SESSION
				//Also send a message to the email that include the random generated code
				$_SESSION["respondentName"] = $name;
				$_SESSION["email"] = $email;
				$_SESSION["surveyName"] = $surveyName;
				$_SESSION["random"] = generateRandomCode();
				header("Location: ../ConfirmationAnswerSurvey.php");
			}
			else{
				//name is not used, email is used
				//show an error message
				header("Location: ../AnswerSurvey.php?error=WrongName");
			}
		}
		else{
			if(empty($checkEmail)){
				//name is used, email is not used
				//show an error message
				header("Location: ../AnswerSurvey.php?error=WrongEmail");
			}
			else{
				//both name and email are used
				//redirect the user to the ConfirmationAnswerSurvey.php page
				//and update the page with all his previous answers

				//also don't forget that the respondent my enter agian to answer new survey

				define("ShowSurveyTables", "SHOW TABLES LIKE '".$name."_%'");

				if($surveysNames = mysqli_query($connection, ShowSurveyTables)){
					//The respondent have had answerd this survey 
					//redirect the respondent to confirmation page with 
					//generated code and update the survey.php page
					//with all his previous answers
					while($surveyTableName = mysqli_fetch_array($surveysNames)){
						$modifedSurveyName = str_replace($name."_", "", "$surveyTableName[0]");

						if($modifedSurveyName == $surveyName){
							$_SESSION["surveyTableName"] = $surveyTableName[0];
							$_SESSION["respondentName"] = $name;
							$_SESSION["email"] = $email;
							$_SESSION["surveyName"] = $surveyName;
							$_SESSION["random"] = generateRandomCode();
							header("Location: ../ConfirmationAnswerSurvey.php?answerd=true");
							die();
						}
					}
				}
				
				//the respondent have previous surveys that he have had answerd
				//but now he come to answer new survey this work the same as 
				//answering the first survey for a user			
				$_SESSION["respondentName"] = $name;
				$_SESSION["email"] = $email;
				$_SESSION["surveyName"] = $surveyName;
				$_SESSION["random"] = generateRandomCode();
				header("Location: ../ConfirmationAnswerSurvey.php");
			}
		}
		
		mysqli_close($connection);
	}

	//this code is used for email verification
	function generateRandomCode(){
		return random_int(100000, 999999);
	}
?>