<?php
session_start();
include_once("yobichain_functions/error_reporting.php");
include_once("yobichain_functions/config.php");

include_once("yobichain_functions/crud_engine.php");
$crudEngine = new crudEngine();

$dbh = new PDO("mysql:host=".DBParams::DB_HOST_NAME.";dbname=".DBParams::DATABASES['YOBICHAIN'], DBParams::DB_USER_NAME, DBParams::DB_PASSWORD);

// Does a user with this email address exist in the database?
$user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES);

$doesEmailExist = $crudEngine->doesEmailExist($user_email);
if ($doesEmailExist===false) 
	{ 
		header("location:login.php?msg=38"); 
	}

else
	{
		include_once("yobichain_functions/utilities_engine.php");
		$utilitiesEngine = new utilitiesEngine();
		
		// Generate a random string
		$random = $utilitiesEngine->random_str(40);

		// Update newly generated random string in database
		$updateRandom = $crudEngine->updateRandom($user_email,$random);

		// Get data from db for emailing password reset email
		$getDataForPasswordResetEmail = $crudEngine->getDataForPasswordResetEmail($user_email);
		if ($getDataForPasswordResetEmail===false) 
			{ 
				header("location:login.php?msg=38"); 
			}
		else
			{
				$user_name = $getDataForPasswordResetEmail['user_name']; $random = $getDataForPasswordResetEmail['random'];
				// send password reset email
				include_once("yobichain_functions/sendgridemail/notification_grid.php");
				$notificationEngine = new notificationEngine();
				$action = $notificationEngine->sendPasswordResetEmail($user_email,$user_name,$random);

				header( "location:login.php?msg=14");
			}

		    $dbh = null;	
	}
?>