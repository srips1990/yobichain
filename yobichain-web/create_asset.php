<?php 
	include("page_header.php");
	include_once("yobichain_functions/crud_engine.php");
	$crud_engine = new crudEngine();
?>

<div class="col-lg-8">

	<form role="form" enctype="multipart/form-data" action="create_asset_process.php" method="post">

		<div class="alert alert-info">
			<div class="form-group">
				<label>1. Name of the asset *</label>
				<input name="name" class="form-control" required><p class="help-block">Enter the name of your asset e.g. Tintin comics</p>
			</div>
		</div>

		<div class="alert alert-info">
			<div class="form-group">
				<label>2. Quantity *</label>
				<input name="quantity" class="form-control"><p class="help-block">Enter the quantity of the asset e.g. 1250</p>
			</div>
		</div>

		<div class="alert alert-info">
			<div class="form-group">
				<label>3. Minimum divisible unit *</label>
				<input name="minimum_qty" class="form-control" required><p class="help-block">Enter a value between 0.00000001 and 1</p>
			</div>
		</div>

		<div class="alert alert-info">
			<div class="form-group">
				<label>4. Asset type *</label>
				<select id="asset_type" name="asset_type" class="form-control">
					<?php
						foreach (Literals::ASSET_TYPE_CODES as $key => $value) {
							echo "<option value='".$value."'>".Literals::ASSET_TYPE_DESC[$value]."</option>";
						}
					?>
				</select><p class="help-block">Open implies that additional units of the asset CAN be issued in future. Closed implies that additional units of the asset CANNOT be issued in future</p>
			</div>
		</div>

		<div class="alert alert-info">
			<div class="form-group">
				<label>5. Asset unit *</label>
				<input name="asset_unit" class="form-control"><p class="help-block">e.g. kg, sq meter</p>
			</div>
		</div>

		<div class="alert alert-info">
			<div class="form-group">
				<label>6. Description *</label><br/>
				<textarea id="desc" name="desc" rows="5" class="form-control" maxlength="2000" required></textarea><p class="help-block">Enter a brief description of the asset.</p>

			</div>
		</div>
															
		<button type="submit" class="btn btn-primary">Create a new asset </button>
		<button type="reset" class="btn btn-danger">Reset</button>
	</form>
</div>

<div class="col-lg-4">
	<div class="alert alert-warning">
		<img src="images/yobi.png">
		<p><strong>Yobi says: </strong>A blockchain asset can be a crypto-token, a currency or a digital representation of any real-world asset e.g. dollars, mangoes, TinTin comics. Every blockchain node tracks and verifies the quantity of assets in transactions. Use the form on this page to create your very own blockchain asset.</p>
	</div>
</div>

<?php include("page_footer.php");?>
