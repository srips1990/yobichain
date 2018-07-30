<?php
	session_start();
	ob_start();
	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { exit();}

	include_once("primechain_functions/crud_engine.php");
	include_once("primechain_functions/blockchain_engine.php");
	$crud_engine = new crudEngine();

	$blockchain_engine = new blockchainEngine();
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);

	try
	{
		$assetName = isset($_POST['asset_name']) ? $_POST['asset_name'] : "";
	    print_r(sprintf("%0.8f", $blockchain_engine->getAssetBalanceForAddress($user->user_public_address, $assetName)));
	} 
	catch (Exception $e) {
		echo "<h4 style='color:red'><strong>Error: </strong>".$e->getMessage()."</h4>";
	}

	ob_end_flush();

?>