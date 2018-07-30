<?php
	session_start();
	ob_start();
	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:login.php?msg=29");}

	include_once("primechain_functions/crud_engine.php");
	$crud_engine = new crudEngine();

	include_once("primechain_functions/blockchain_engine.php");
	include_once("primechain_functions/helperFunctions.php");
	include_once("primechain_functions/config.php");
	include_once("primechain_functions/resources.php");
	include_once("classes/classes.php");
	$blockchain_engine = new blockchainEngine();

	try 
	{
		$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);

		$offerAsset = new ExchangeAsset();
		$receiveAsset = new ExchangeAsset();

		$offerAsset->name = isset($_POST['asset_offer']) ? stripslashes($_POST['asset_offer']) : "";
		$offerAsset->qty = isset($_POST['qty_offer']) ? floatval($_POST['qty_offer']) : "";
		$receiveAsset->name = isset($_POST['asset_receive']) ? stripslashes($_POST['asset_receive']) : "";
		$receiveAsset->qty = isset($_POST['qty_receive']) ? floatval($_POST['qty_receive']) : "";

		$exchangeAssets = new ExchangeAssets();
		$exchangeAssets->offerAssets[] = $offerAsset;
		$exchangeAssets->receiveAssets[] = $receiveAsset;

		if (!$blockchain_engine->validateExchangeForCreator($user->user_public_address, $exchangeAssets))
				throw new Exception("94");

		$offer = $blockchain_engine->createOffer($user->user_public_address, $exchangeAssets, $user->user_public_address);

		header("location:accept_offer.php?msg=96");
	} 
	catch (Exception $e) 
	{
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "create_offer.php?msg=";
		if (is_numeric($ex_msg)) {
			header("location:".$error_redirect_url_prefix.$ex_msg);
		}
		else {
			error_log("internal error: ".$ex_msg);
			header("location:".$error_redirect_url_prefix."56"."&msg_data=".json_encode($offer));
		}
	}

	ob_end_flush();
?>