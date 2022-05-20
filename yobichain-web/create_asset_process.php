<?php
session_start();
ob_start();
$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:login.php?msg=29");}

include_once("yobichain_functions/crud_engine.php");
include_once("yobichain_functions/blockchain_engine.php");
include_once("yobichain_functions/helperFunctions.php");
include_once("yobichain_functions/resources.php");
include_once("classes/classes.php");

$crud_engine = new crudEngine();

$blockchain_engine = new blockchainEngine();

try 
	{
		$crud_engine->beginTransaction();
		$user_id = $_SESSION['user_id'];
		$user_details = $crud_engine->getUserDetails($user_id);

		$asset = new Asset();
		$asset->name = isset($_POST['name']) ? $_POST['name'] : "";
		$asset->quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;
		$asset->minimum_qty = isset($_POST['minimum_qty']) ? floatval($_POST['minimum_qty']) : "";
		$asset->threshold = isset($_POST['threshold']) ? floatval($_POST['threshold']) : 0;
		$asset->issuer = $user_id;
		$asset->created_by = $_SESSION['user_id'];
		$asset->is_deleted = 'n';
		$asset->type = isset($_POST['asset_type']) ? $_POST['asset_type'] : Literals::ASSET_TYPE_CODES['OPEN'];
		//$asset->isOpen = ($assetType == Literals::ASSET_TYPE_CODES['OPEN']) ? true : false;
		$asset->unit = isset($_POST['asset_unit']) ? $_POST['asset_unit'] : "";
		$asset->description = isset($_POST['desc']) ? $_POST['desc'] : "";

		if (strlen($asset->name) > 32)
			throw new Exception("117");

		if (strlen($asset->unit) > 50)
			throw new Exception("118");

		if (strlen($asset->description) > 2000)
			throw new Exception("119");

		if (trim($asset->name) == "")
			throw new Exception("120");

		if (!is_numeric($asset->quantity))
			throw new Exception("121", 1);

		if (!is_numeric($asset->minimum_qty))
			throw new Exception("122", 1);

		if (($asset->minimum_qty < Literals::MINIMUM_DIVISIBLE_UNIT_MIN_VALUE) || ($asset->minimum_qty > 1))
			throw new Exception("123", 1);

		if ($asset->quantity < $asset->threshold)
			throw new Exception("124");

		if ($asset->minimum_qty <= 0)
			throw new Exception("125");

		if ($asset->quantity < $asset->minimum_qty)
			throw new Exception("126");

		if (($asset->quantity / $asset->minimum_qty) > Literals::MAX_ALLOWED_ASSET_RAW_QUANTITY)
			throw new Exception("The maximum number of raw units allowed is ".Literals::MAX_ALLOWED_ASSET_RAW_QUANTITY."!!");
		
		if (!is_null($blockchain_engine->getAssetDetails($asset->name)))
			throw new Exception("114", 1);		

		if(!$crud_engine->insertAssetDetailsToDB($asset))
			throw new Exception("127", 1);

		$blockchain_engine->createAsset($asset, $user_details['user_public_address']);	// Use issuer address as 2nd parameter

		if (empty($asset->issue_txid))
			throw new Exception("41", 1);

		// echo "<strong><font color='green'>"."Transaction successful.</font> <br/><font color='green'>Transaction ID :</font><br/>"."<a href='".ExplorerParams::$TX_URL_PREFIX.$asset->issue_txid."' target='_new'>$asset->issue_txid</a></strong>";
		
		// echo "<h5><b><font color='green'>Asset created successfully</font></b></h5>";
		
		$crud_engine->commit();
		header("location:view_asset.php?msg=40");
	} 
catch (Exception $e) 
	{
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "create_asset.php?msg=";
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