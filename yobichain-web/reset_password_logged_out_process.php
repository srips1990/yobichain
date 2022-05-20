<?php
session_start();
include_once("yobichain_functions/error_reporting.php");

$user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES);
$random = htmlspecialchars($_POST['random'], ENT_QUOTES);
$user_password = $_POST['user_password'];

include_once("yobichain_functions/crud_engine.php");
$crudEngine = new crudEngine();

// Forgot Password - is email and random correct
$isEmailRandomCorrect = $crudEngine->isEmailRandomCorrect($user_email,$random);
if ($isEmailRandomCorrect===false) 
	{ 
		header("location:login.php?msg=20"); 
	}
elseif ($isEmailRandomCorrect===true)
	{
				$random = $crudEngine->random_str(40); // generate random string
				$new_password=password_hash($user_password, PASSWORD_DEFAULT);
				$updateNewPassword = $crudEngine->updateNewPassword($new_password,$random,$user_email);
				header("location:login.php?msg=12");
	}
	
?>