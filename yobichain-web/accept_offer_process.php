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

		if (!isset($_POST['key']))
			throw new Exception("116");

		$txVoutKey = $_POST['key'];

		$txID = $blockchain_engine->acceptOffer($user->user_public_address, $txVoutKey);

		header("location:accept_offer.php?msg=105");
	} 
	catch (Exception $e) 
	{
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "accept_offer.php?msg=";
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