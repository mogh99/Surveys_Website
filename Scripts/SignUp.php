<?php
	if(isset($_POST["submit"])){
		session_start();
		require_once("databaseConnection.php");

		$name = mysqli_real_escape_string($connection, $_POST["username"]);
		$email = mysqli_real_escape_string($connection, $_POST["email"]);
		$password = mysqli_real_escape_string($connection, $_POST["password"]);

		define("SelectByName", "
			SELECT Name 
			FROM users 
			WHERE 
			Name='$name'");

		define("SelectByEmail", "
			SELECT Email
			FROM users 
			WHERE  
			Email='$email'");

		//check if the name or email has been used by another user
		$checkName = mysqli_fetch_row(mysqli_query($connection, SelectByName));
		//$checkName[0] = name
		//mysqli_error($connection); 
		
		$checkEmail = mysqli_fetch_row(mysqli_query($connection, SelectByEmail));
		//$checkEmail[0] = email
		//mysqli_error($connection);

		if(empty($checkName)){
			if(empty($checkEmail)){
				//name and email are not used
				//redirect to the ConfirmationSignUp.php page
				//and put all (name, email, password, and generated random code)
				//in the $_SESSION
				//Also send a message to the email that include the random generated code

				$_SESSION["name"] = $name;
				$_SESSION["email"] = $email;
				$_SESSION["password"] = $password;
				$_SESSION["random"] = generateRandomCode();
				header("Location: ../ConfirmationSignUp.php");
			}
			else{
				//name is not used, email is used
				//show an error message
				
				header("Location: ../SignUp.php?error=UsedEmail");
			}
		}
		else{
			if(empty($checkEmail)){
				//name is used, email is not used
				//show an error message
				
				header("Location: ../SignUp.php?error=UsedName");
			}
			else{
				//both name and email are used
				//show an error message
				header("Location: ../SignUp.php?error=UsedBoth");
			}
		}
		mysqli_close($connection);
	}

	//this code is used for email verification
	function generateRandomCode(){
		return random_int(100000, 999999);
	}
?>