<?php
	session_start();
	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:logout.php?msg=29");}
	include_once("primechain_functions/error_reporting.php");

	include_once("primechain_functions/crud_engine.php");
	$crud_engine = new crudEngine();

	$old_password = $_POST['old_password'];
	$new_password_1 = $_POST['new_password_1'];
	$new_password_2 = $_POST['new_password_2'];

	if ($new_password_1!=$new_password_2)
	{
		header("location:user_password_change.php?msg=27");
	}
	else
	{
		$user_details = $crud_engine->getUserDetails($_SESSION['user_id']);
		if($crud_engine->verifyCredential($user_details['user_email'], $old_password))	
		{
			if ($crud_engine->updateCredential($_SESSION['user_id'], $new_password_1))
				header("location:password_change.php?msg=30");
			else
				header("location:password_change.php?msg=92");
		}
		else 
		{
			header("location:password_change.php?msg=31");
		}	
	}
?>