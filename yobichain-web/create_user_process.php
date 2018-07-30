<?php
include_once("primechain_functions/error_reporting.php");
include_once("primechain_functions/crud_engine.php");
include_once("primechain_functions/blockchain_engine.php");
include_once("primechain_functions/helperFunctions.php");
include_once("classes/classes.php");

$crud_engine = new crudEngine();
$blockchain_engine = new blockchainEngine();

try 
	{
		$user = new User();
		//echo json_encode($roles);

		$user_email = htmlspecialchars($_POST['user_email'], ENT_QUOTES);

		// Does a user with this email address exist in the database?
		$doesEmailExist = $crud_engine->doesEmailExist($user_email);
		if ($doesEmailExist===true) { header("location:create_user.php?role_code=create_user&msg=4"); }

		foreach ($_POST as $key => $value) {
			if (property_exists($user, $key) && !empty($value) && !is_null($value)) {
				$user->{$key} = htmlspecialchars($value, ENT_QUOTES);
			}
		}
		
		$user->random = $crud_engine->random_str(40);

		$crud_engine->beginTransaction();
		// Insert new user details into database
		if(!$crud_engine->createNewUser($user))
			throw new Exception("128", 1);
		
		$user->user_id = $crud_engine->getUserIdFromUserEmail($user->user_email);

		// Send activation email to newly created user
		include_once("primechain_functions/sendgridemail/notification_grid.php");
		$notificationEngine = new notificationEngine();
		$sendActivationEmail = $notificationEngine->sendActivationEmail($user, $orgnName);
		$crud_engine->commit();
		// printSuccessMessage("User created!");
		header("location:create_user.php?msg=3");
	
	} 
catch (Exception $e) 
	{
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "create_user.php?msg=";
		if (is_numeric($ex_msg)) {
			header("location:".$error_redirect_url_prefix.$ex_msg);
		}
		else {
			error_log("internal error: ".$ex_msg);
			header("location:".$error_redirect_url_prefix."56");
		}
	}
?>