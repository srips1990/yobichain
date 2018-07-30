<?php 
	session_start();
	ob_start();
	$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:login.php?msg=29");}

	include_once("primechain_functions/config.php");
	include_once("primechain_functions/blockchain_engine.php");
	include_once("primechain_functions/crud_engine.php");
	include_once("primechain_functions/helperFunctions.php");
	include_once("primechain_functions/resources.php");
	include_once("classes/classes.php");

	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();

	// Get full details of the current user
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
?>

	<div class="row">
		<div class="col-md-12">
			<?php

				try {

					if (!isset($_GET['hash'])) {
						throw new Exception("500", 1);
					}

					$hash = htmlspecialchars($_GET['hash'], ENT_QUOTES);
					$binData = $blockchain_engine->getFileDataFromBlockchain($hash);
					$file = bin_data_to_file($binData);
						
					if (is_array($file)) 
					{

						if (strlen($file['mimetype']))
							header('Content-Type: '.$file['mimetype']);
						
						if (strlen($file['filename'])) 
							{
								// for compatibility with HTTP headers and all browsers
								$filename=preg_replace('/[^A-Za-z0-9 \\._-]+/', '', $file['filename']);
								header('Content-Disposition: attachment; filename="'.$filename.'"');
							}
					
						echo $file['content'];
					
					} else
						echo 'File not formatted as expected';

				}
				catch (Exception $e)
				{
					$ex_msg = $e->getMessage();
					echo $ex_msg;
					error_log("internal error: ".$ex_msg);
				}
					

			?>
		</div>
	</div>

<?php include("page_footer.php");?>