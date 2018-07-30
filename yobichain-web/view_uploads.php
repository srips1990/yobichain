<?php 
	include_once("page_header.php");
	include_once("primechain_functions/config.php");
	include_once("primechain_functions/blockchain_engine.php");
	include_once("primechain_functions/resources.php");
	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();

	// Get full details of the current user
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);

	$count = 100;

?>

	<div class="row">
		<div class="col-md-8">
			<table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
			    <thead>
			        <tr>
			            <th>File Description</th>
			            <th>Transaction ID</th>
			            <th>Download</th>
			        </tr>
			    </thead>
			    <tbody>
					<?php

						try {

							$uploads = $blockchain_engine->getRecentUploadsForUser($user->user_public_address, -1, $count);

							foreach ($uploads as $upload) {
								$txID = $upload['txid'];
								$file_hash = $upload['key'];
								$time = $upload['time'];
								$description = json_decode(hex2bin($upload['data']), true)[Literals::UPLOAD_FIELD_NAMES['DESCRIPTION']];

								echo "
								<tr>
									<td>
										<p>".$description."</p>
										<p>Upload Time: ".date('m-d-Y'.',  '.'h:i:s a'.',  T', $time)."</p>
									</td>

									<td>
										<a href='transaction_details.php?msg_data=".$txID."' target='_new'><button class='btn btn-warning'>Transaction ID</button></a>
									</td>

									<td><a href='file_download.php?hash=".$file_hash."' target='_new'><button class='btn btn-primary'>Download</button></a>
									</td>
								</tr>";
							}
						}
						catch (Exception $e)
						{
							$ex_msg = $e->getMessage();
							echo $ex_msg;
							error_log("internal error: ".$ex_msg);
						}
					?>
			    </tbody>
			</table>
		</div>

		<div class="col-lg-4">
			<div class="alert alert-warning">
				<img src="images/yobi.png">
				<p><strong>Yobi says: </strong>Click on the 'Transaction ID' button to view details of the transaction ID relating to the upload of the file to the blockchain. Click on the 'Download' button to download the relevant file from the blockchain";
				?></p>
			</div>
		</div>

	</div>

<?php include("page_footer.php");?>