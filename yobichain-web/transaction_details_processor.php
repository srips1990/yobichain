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

		$txID = htmlspecialchars($_POST['txid'], ENT_QUOTES);
		$txItem = $blockchain_engine->getDocumentUploadTransactionItem($txID);
		
		echo "<strong><h3>"."Transaction Details"."</h3></strong>".printStreamItemDetailsVertically($txItem);
		
		if (isset($txItem['blockhash']) && !empty($txItem['blockhash']))
		{
			$blockInfo = $blockchain_engine->getBlockDetails($txItem['blockhash']);

			// Printing the block details for the hash. printBlockDetailsVertically method from HelperFunctions.php
			echo "<strong><h3>"."Block Details"."</h3></strong>".printBlockDetailsVertically($blockInfo);
		}
		else {
			echo "<strong><h3>"."Block Details"."</h3></strong>". "<h4 style='color:red'>Loading...</h4>";
		}
	} 
	catch (Exception $e) 
	{
		echo "<h4 style='color:red'><strong>Error: </strong>".$e->getMessage()."</h4>";
	}

	ob_end_flush();
?>