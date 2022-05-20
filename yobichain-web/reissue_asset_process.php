<?php
	session_start();
	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:logout.php?msg=29");}

	include_once("yobichain_functions/error_reporting.php");
	include_once("yobichain_functions/crud_engine.php");
	include_once("yobichain_functions/blockchain_engine.php");
	include_once("yobichain_functions/resources.php");
	include_once("yobichain_functions/helperFunctions.php");
	include_once("classes/classes.php");

	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();

	try {
		$userDetails = $crud_engine->getUserDetails($_SESSION['user_id']);
		$asset = new Asset();
		$asset->asset_ref = isset($_POST['asset_ref']) ? htmlspecialchars($_POST['asset_ref'], ENT_QUOTES) : "";
		$quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : "";

		$assetDetails = $blockchain_engine->getAssetDetails($asset->asset_ref);

		if (is_null($assetDetails))
			throw new Exception("Unable to verify asset details.", 1);

		$asset->name = $assetDetails['name'];
		$asset->quantity = $quantity;

		if ($asset->quantity <= 0)
			throw new Exception("129");

		if (!is_numeric($asset->quantity))
			throw new Exception("121", 1);

		if ($asset->quantity < $assetDetails['units'])
			throw new Exception("126");

		if (($assetDetails['issueraw'] + ($asset->quantity / $assetDetails['units'])) > Literals::MAX_ALLOWED_ASSET_RAW_QUANTITY)
			throw new Exception("The maximum number of raw units allowed is ".Literals::MAX_ALLOWED_ASSET_RAW_QUANTITY."!!");

		if (is_null($assetDetails))
			throw new Exception("130", 1);

		if ($assetDetails['open'] == false)
			throw new Exception("131", 1);

		$txID = $blockchain_engine->issueMore($asset, $userDetails['user_public_address'], $userDetails['user_public_address']);	// Use issuer address as 2nd parameter

		if (empty($txID))
			throw new Exception("132", 1);

		header("location:view_asset.php?msg=43");
	}
	catch (Exception $e) {
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "reissue_asset.php?msg=";
		if (is_numeric($ex_msg)) {
			header("location:".$error_redirect_url_prefix.$ex_msg);
		}
		else {
			error_log("internal error: ".$ex_msg);
			header("location:".$error_redirect_url_prefix."56");
		}
	}

		
?>