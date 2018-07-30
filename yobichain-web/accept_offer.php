<?php 
	include_once("page_header.php");
	include_once("primechain_functions/config.php");
	include_once("primechain_functions/blockchain_engine.php");
	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
?>

<div class="row">
	<div class="col-lg-8">
		<table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
			<thead>
				<tr>
					<th>Offer asset</th>
					<th>Offer quantity</th>
					<th>Ask asset</th>
					<th>Ask quantity</th>
					<th>Creator</th>
					<th>Accept</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$activeOffers = $blockchain_engine->getActiveOffers();
					$count = 0;

					foreach ($activeOffers as $txVoutKey=>$offer) {
						$row_output = "
						<tr class='odd gradeX'>";

						$row_output .= "
							<td>".$offer->exchangeAssets->offerAssets[0]->name."</td>
							<td>".$offer->exchangeAssets->offerAssets[0]->qty."</td>
							<td>".$offer->exchangeAssets->receiveAssets[0]->name."</td>
							<td>".$offer->exchangeAssets->receiveAssets[0]->qty."</td>";

						$creator_name = $crud_engine->getUserNameFromPublicAddress($offer->creator);

						$row_output .= "<td>".$creator_name."</td>";

						$row_output .= "<td>";
						if ($offer->creator !== $user->user_public_address)  
							$row_output .= "	
									<form action='accept_offer_process.php' method='post'>
										<input name='key' type='hidden' value='".$txVoutKey."'></input>
										<button type='submit' class='btn btn-primary'>Accept</button>
									</form>";
						$row_output .= "</td>";

						$row_output .= "</tr>";

						echo $row_output;
					}
				?>
			 </tbody>
		</table>
	</div>

		<div class="col-lg-4">
			<div class="alert alert-warning">
				<img src="images/yobi.png">
				<p><strong>Yobi says: </strong>This is the list of all the blockchain assets. To view detailed information about the transactions involving each asset, click the button below: </p><p><?php 
					echo "<a href='".ExplorerParams::$ASSETS_URL_PREFIX."' target='_new'><button type='button' class='btn btn-warning'>View Asset Masterlist</button></a>";
				?></p>
			</div>
		</div>
	
</div>

<?php include("page_footer.php");?>
