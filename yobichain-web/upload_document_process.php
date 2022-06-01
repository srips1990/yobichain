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
		$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
		$file_description = isset($_POST['desc']) ? htmlspecialchars($_POST['desc'], ENT_QUOTES) : "";

		////////

		if (!isset($_FILES['doc']))
			throw new Exception("Please upload a file!", 1);

		$target_file = $_FILES['doc'];
		$target_file_path = $_FILES['doc']['tmp_name'];
		
		if($_FILES['doc']['size'] > Literals::MAX_ALLOWED_FILE_SIZE_IN_BYTES) {
			throw new Exception("115");
		}

		// Reading file contents
		$file_bin_data = file_get_contents($target_file_path);
		$file_bin_data = file_to_txout_bin($target_file['name'], $target_file['type'], $file_bin_data);

		$target_file_hash = hash_file('sha256', $target_file_path);
		unlink($target_file_path);

		$txID = $blockchain_engine->uploadDocument($user->user_public_address, $target_file_hash, array('description'=> $file_description) , $file_bin_data);

		$upload = new Upload();
		$upload->file_hash = $target_file_hash;
		$upload->transaction_id = $txID;
		$upload->user_id = intval($user->user_id);

		// print_r($upload);
		$crud_engine->insertUploadToDB($upload);

		header("location:transaction_details.php?msg=110&msg_data=".$txID);
	} 
	catch (Exception $e) 
	{
		$crud_engine->rollBack();
		$ex_msg = $e->getMessage();
		$error_redirect_url_prefix = "upload_document.php?msg=";
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