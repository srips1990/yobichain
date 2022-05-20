<?php
	session_start();
	ob_start();
	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:login.php?msg=29");}

	include_once("yobichain_functions/crud_engine.php");
	$crud_engine = new crudEngine();

	include_once("yobichain_functions/blockchain_engine.php");
	include_once("yobichain_functions/helperFunctions.php");
	include_once("yobichain_functions/config.php");
	include_once("yobichain_functions/resources.php");
	include_once("classes/classes.php");
	$blockchain_engine = new blockchainEngine();

	try 
	{
		$sender = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
		$recipient_email = isset($_POST['recipient']) ? htmlspecialchars($_POST['recipient'], ENT_QUOTES) : "";
		$recipient = $crud_engine->getUserFullDetails($recipient_email, true);
		$asset_name = isset($_POST['asset_name']) ? stripslashes($_POST['asset_name']) : "";
		$quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;

		if (!$blockchain_engine->isAddressValid($recipient->user_public_address))
			throw new Exception("98");

		if ($quantity<0)
			throw new Exception("100");

		if ($quantity > $blockchain_engine->getAssetBalanceForAddress($sender->user_public_address, $asset_name))
			throw new Exception("94");

		if (!$blockchain_engine->addressOwnsAsset($sender->user_public_address, $asset_name))
			throw new Exception("101");

		$txID = $blockchain_engine->sendAssetFrom($sender->user_public_address, $recipient->user_public_address, $asset_name, $quantity);

		header("location:send_asset.php?msg=110&msg_data=".$txID);
	} 
	catch (Exception $e) 
	{
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "send_asset.php?msg=";
		if (is_numeric($ex_msg)) {
			header("location:".$error_redirect_url_prefix.$ex_msg);
		}
		else {
			error_log("internal error: ".$ex_msg);
			header("location:".$error_redirect_url_prefix."56");
		}
	}

	ob_end_flush();
?>