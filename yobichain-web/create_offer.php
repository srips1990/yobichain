<?php 
	include_once("page_header.php");
	include_once("primechain_functions/config.php");
	include_once("primechain_functions/blockchain_engine.php");
	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();

	// Get full details of the current user
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
?>
	<script type="text/javascript" src="js/assets.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			offerAssetNameElement = document.getElementById('asset_offer');
			balanceElement = document.getElementById('qty_balance');
			getUserAssetBalance(offerAssetNameElement, balanceElement);
        	setInterval(function(){ getUserAssetBalance(offerAssetNameElement, balanceElement); },3000);
		}
	</script>

	<div class="row">
		<div class="col-md-8">
			<form role="form" action="create_offer_process.php" method="post">

				<div class="alert alert-info">  
					<div class="form-group">
						<label>1. Offer asset</label>
						<div class="row">
							<div class="col-sm-3">
								<select id="asset_offer" name="asset_offer" class="form-control">
									<?php
										$asset_name_list = $blockchain_engine->getAssetNamesForAddress($user->user_public_address);
										$assets = $blockchain_engine->getAssetsFullDetails($asset_name_list);

										foreach ($assets as $asset) 
										{
											echo "<option value='".$asset->name."'>".$asset->name."</option>";
										}
									?>
								</select>
							</div>

							<div class="col-sm-4">
								<div style="color:green;"><strong>Balance: <span id="qty_balance"></span></strong></div>
							</div>
						</div>
					</div>
				</div>

				<div class="alert alert-info">  
					<div class="form-group">
						<label>2. Offer quantity </label>
						<input id="qty_offer" name="qty_offer" type="number" class="form-control" value=1 >
					</div>
				</div>

				<div class="alert alert-info">  
					<div class="form-group">
						<label>3. Ask asset </label>
						<select id="asset_receive" name="asset_receive" class="form-control">
							<?php
								$user_asset_name_list = $crud_engine->listAssetsNamesForUser($user->user_id);
								$user_assets = $blockchain_engine->getAssetsFullDetails($user_asset_name_list);

								foreach ($user_assets as $asset) 
								{
									echo "<option value='".$asset->name."'>".$asset->name."</option>";
								}
							?>
						</select>
					</div>
				</div>

				<div class="alert alert-info">  
					<div class="form-group">
						<label>4. Ask quantity </label>
						<input id="qty_receive" name="qty_receive" type="number" class="form-control" value=1>
					</div>
				</div>
																	
				<button type="submit" class="btn btn-primary">Create Offer</button>
				<button type="reset" class="btn btn-danger">Reset</button>

			</form>
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