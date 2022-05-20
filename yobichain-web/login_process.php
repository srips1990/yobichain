<?php
	session_start();
	include_once("yobichain_functions/error_reporting.php");
	include_once("yobichain_functions/crud_engine.php");
	include_once("yobichain_functions/sendgridemail/notification_grid.php");

	$crud_engine = new crudEngine();
	$user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES);
	$password_plain = $_POST['user_password'];

	if ($crud_engine->verifyCredential($user_email, $password_plain))
		{
			$user_details = $crud_engine->getUserDetailsByEmail($user_email);
			if(!empty($user_details) && !is_null($user_details))	
				{
					$_SESSION['status']="loggedin";
					$_SESSION['user_id']=$user_details['user_id'];
					$ip = $_SERVER['REMOTE_ADDR'];
					$browser=$_SERVER['HTTP_USER_AGENT'];

					$recordLoginInDB = $crud_engine->recordLoginInDB($user_details['user_id'],$ip,$browser);

					// Email login notification to user
					// $notificationEngine = new notificationEngine();
					// $action = $notificationEngine->sendLoginNotification($ip,$browser,$user_email,$user_details['user_name']);

					header( "location:view_dashboard.php");
				}
			else 
				{
					header("location:logout.php?msg=20");
				}
		}
	else
		{
			header("location:logout.php?msg=20");
		}
?>