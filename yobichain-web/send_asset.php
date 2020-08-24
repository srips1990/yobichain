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
			assetNameElement = document.getElementById('asset_name');
			balanceElement = document.getElementById('qty_balance');
			getUserAssetBalance(assetNameElement, balanceElement);
        	setInterval(function(){ getUserAssetBalance(assetNameElement, balanceElement); },3000);
		}
	</script>

	<div class="row">
		<div class="col-md-8">
			<form role="form" action="send_asset_process.php" method="post">
				<div class="alert alert-info">  
					<div class="form-group">
						<label>1. Asset name</label>
						<div class="row">
							<div class="col-sm-3">
								<select id="asset_name" name="asset_name" class="form-control" onchange="getUserAssetBalance(this, qty_balance);">
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
						<label>2. Quantity </label>
						<input id="quantity" name="quantity" type="number" class="form-control" placeholder="1.0" step="0.0000001" min="0.0000001" max="10000000000" value=1>
					</div>
				</div>

				<div class="alert alert-info">  
					<div class="form-group">
						<label>3. Recipient Email </label>
						<input name="recipient" id="recipient" type="text" class="form-control" />
					</div>
				</div>
																	
				<button type="submit" class="btn btn-primary">Send</button>
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