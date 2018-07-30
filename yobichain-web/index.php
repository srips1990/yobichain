<?php
	session_start();

	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null);
	
	if (!is_null($status) && $status=="loggedin") { 
		header("location:view_dashboard.php");
	}
	else {
		header("location:login.php");
	}

// header("Location: login.php"); /* Redirect browser */
// exit();
?>