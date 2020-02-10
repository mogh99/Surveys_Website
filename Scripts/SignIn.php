<?php 
	if(isset($_POST["submit"])){
		session_start();
		require_once("databaseConnection.php");

		$nameoremail = mysqli_real_escape_string($connection, $_POST["nameoremail"]);
		$password = mysqli_real_escape_string($connection, $_POST["password"]);

		
		define("SelectNameByEmail", "
			SELECT Name 
			FROM users 
			WHERE 
			Email='$nameoremail'");

		define("SelectByName", "
			SELECT Name 
			FROM users 
			WHERE 
			Name='$nameoremail'");

		define("SelectByEmail", "
			SELECT Email 
			FROM users 
			WHERE 
			Email='$nameoremail'");

		define("SelectByPassword", "
			SELECT Password
			FROM users 
			WHERE  
			Password='$password'");


		$checkName = mysqli_fetch_row(mysqli_query($connection, SelectByName));
		$checkEmail = mysqli_fetch_row(mysqli_query($connection, SelectByEmail));
		$checkPassword = mysqli_fetch_row(mysqli_query($connection, SelectByPassword));

		if(!empty($checkName)){
			if(empty($checkPassword)){
				//the name is found but the password is wrong
				//show error message
				header("Location: ../SignIn.php?error=WrongPassword");
			}
			else{
				//the name is found and the password is correct
				//and but the name in the session to find the tables
				//redirect to the DashBoard.php
				$_SESSION["name"] = $checkName[0];
				header("Location: ../DashBoard.php");
			}
		}

		if(!empty($checkEmail)){
			if(empty($checkPassword)){
				//the email is found but the password is wrong
				//show error message
				header("Location: ../SignIn.php?error=WrongPassword");
			}
			else{
				//the email is found and the password is correct
				//and but the name in the session to find the tables
				//redirect to the DashBoard.php
				$name = mysqli_fetch_row(mysqli_query($connection, SelectNameByEmail));
				$_SESSION["name"] = $name[0];
				header("Location: ../DashBoard.php");
			}
		}

		if(empty($checkName) && empty($checkEmail)){
			//this will be executed when the enterd value
			//nameoremail is not found
			header("Location: ../SignIn.php?error=nameoremailWrong");
		}

		
	}
?>