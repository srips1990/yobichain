<?php
include_once("yobichain_functions/config.php");
include_once("yobichain_functions/error_reporting.php");
include_once("yobichain_functions/crud_engine.php");
include_once("yobichain_functions/blockchain_engine.php");
include_once("yobichain_functions/utilities_engine.php");
include_once("yobichain_functions/helperFunctions.php");
include_once("classes/classes.php");

$crud_engine = new crudEngine();
$blockchain_engine = new blockchainEngine();
$utilities_engine = new utilitiesEngine();

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

		if (NotificationParams::USE_MAILER) {
			$user->random = $crud_engine->random_str(40);
		} else {
			$user->checked = 'y';
			$user_password = $utilities_engine->random_str(12); // generate password
			$user->user_password=password_hash($user_password, PASSWORD_DEFAULT); // hash the password for storing in db

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
		}

		$crud_engine->beginTransaction();
		// Insert new user details into database
		if(!$crud_engine->createNewUser($user))
			throw new Exception("128", 1);
		
		$user->user_id = $crud_engine->getUserIdFromUserEmail($user->user_email);

		if (NotificationParams::USE_MAILER) {
			// Send activation email to newly created user
			include_once("yobichain_functions/sendgridemail/notification_grid.php");
			$notificationEngine = new notificationEngine();
			$sendActivationEmail = $notificationEngine->sendActivationEmail($user, $orgnName);
			$crud_engine->commit();
			// printSuccessMessage("User created!");
			header("location:login.php?msg=3");
		} else {
			$crud_engine->commit();
			$pass_encoded = urlencode(base64_encode($user_password));
			header("location:login.php?msg=133&msg_data=".$pass_encoded);
		}
	
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
	finally {
		// $crud_engine->endTransaction();
	}
?>