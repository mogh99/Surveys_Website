<?php
	if(isset($_POST["submit"])){
		//Sign out from the system
		session_destroy();
		header("Location: ../index.html");
	}
?>