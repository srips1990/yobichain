<?php

session_start();
include_once("primechain_functions/error_reporting.php");
include_once("primechain_functions/helperFunctions.php");

try {
	include_once("primechain_functions/blockchain_engine.php");
	$blockchain_engine = new blockchainEngine(); 

	include_once("primechain_functions/crud_engine.php");
	$crud_engine = new crudEngine();
	$crud_engine->beginTransaction();
	// check if a user with this email and random string is pending activation
	$user_email = htmlspecialchars($_GET['user_email'], ENT_QUOTES);
	$random = htmlspecialchars($_GET['random'], ENT_QUOTES);

	// Does a user with this email address exist in the database?
	$doesPendingUserExist = $crud_engine->doesPendingUserExist($user_email,$random);
	if ($doesPendingUserExist===false) { header("location:logout.php?msg=20"); }
	elseif ($doesPendingUserExist===true) 
	{ 
		// generate password, insert password hash in db and update db to set user as verified
		$user_password = $crud_engine->random_str(12); // generate password
		$user_password_hash=password_hash($user_password, PASSWORD_DEFAULT); // hash the passwordfor storing in db 

		// get user's name and ID for sending credentials email
		$user = $crud_engine->getUserInfoForWelcomeEmail($user_email,$random, true);
		if ($user === false)
			throw new Exception("Unable to activate user", 1);
		// print_r($user);

		if ($user->checked == 'y')
			throw new Exception("User already activated!", 1);

		$verifyUser = $crud_engine->verifyUser($user_email,$user_password_hash,$random);

		if ($verifyUser === false)
			throw new Exception("Unable to activate user", 1);
		else
			$user->checked = 'y';

		// Generate key pair and public address, on the blockchain, for a user and then enters it into the db
		$address_pub_key_pair = $blockchain_engine->createPublicAddress();

		if (!is_array($address_pub_key_pair))
			throw new Exception("Error generating user address!", 1);

		$user->user_public_address = $address_pub_key_pair['address'];
		$user->user_public_key = $address_pub_key_pair['pubkey'];

		// Granting send and receive permissions to user
		$permissions = "send".",";
		$permissions .= "receive".",";
		$permissions .= "issue";
		$blockchain_engine->grantPermissions($user->user_public_address,$permissions);

		$crud_engine->updateUser($user);

		// Granting write permission for Assets related stream to user

		$permissions = MultichainParams::ASSET_STREAMS['ASSET_DETAILS']."."."write";
		$blockchain_engine->grantPermissions($user->user_public_address,$permissions);

		$permissions = MultichainParams::ASSET_STREAMS['OFFER_HEX']."."."write";
		$blockchain_engine->grantPermissions($user->user_public_address,$permissions);

		$permissions = MultichainParams::ASSET_STREAMS['OFFER_DETAILS']."."."write";
		$blockchain_engine->grantPermissions($user->user_public_address,$permissions);

		$permissions = MultichainParams::DATA_STREAMS['FILE_DETAILS']."."."write";
		$blockchain_engine->grantPermissions($user->user_public_address,$permissions);

		$permissions = MultichainParams::DATA_STREAMS['FILE_DATA']."."."write";
		$blockchain_engine->grantPermissions($user->user_public_address,$permissions);
	

		// Email credentials to user
		include_once("primechain_functions/sendgridemail/notification_grid.php");

		$notificationEngine = new notificationEngine();
		$sendLoginCredentials = $notificationEngine->sendLoginCredentials($user_email,$user->user_name,$user->user_id,$user_password);
		
		$crud_engine->commit();
		header("location:login.php?msg=8");
	}
	
}
catch (Exception $e) {
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "login.php?msg=";
		if (is_numeric($ex_msg)) {
			header("location:".$error_redirect_url_prefix.$ex_msg);
		}
		else {
			error_log("internal error: ".$ex_msg);
			header("location:".$error_redirect_url_prefix."56");
		}
}

?>