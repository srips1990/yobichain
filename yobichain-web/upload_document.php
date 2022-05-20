<?php 
	include_once("page_header.php");
	include_once("yobichain_functions/config.php");
	include_once("yobichain_functions/resources.php");
	include_once("yobichain_functions/blockchain_engine.php");
	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();

	// Get full details of the current user
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
?>

	<div class="row">
		<div class="col-md-8">
			<form role="form" action="upload_document_process.php" enctype="multipart/form-data" method="post">

				<div class="alert alert-info">  
					<div class="form-group">
						<label>1. Document * (max. <?php echo Literals::MAX_ALLOWED_FILE_SIZE_IN_BYTES/(1024*1024); ?> MB)</label>
						<input id="doc" name="doc" type="file" class="form-control"><p class="help-block">Any format can be used e.g. pdf, docx, jpg, zip. Max <?php echo Literals::MAX_ALLOWED_FILE_SIZE_IN_BYTES/(1024*1024); ?> MB per document.</p>
					</div>
				</div>

				<div class="alert alert-info">  
					<div class="form-group">
						<label>2. Description * </label>
						<textarea id="desc" name="desc" rows="5" class="form-control" maxlength="2000" required></textarea><p class="help-block">Brief description of the document</p>
					</div>
				</div>

				<button type="submit" class="btn btn-primary">Upload</button>
				<button type="reset" class="btn btn-danger">Reset</button>
			</form>
		</div>

		<div class="col-lg-4">
			<div class="alert alert-warning">
				<img src="images/yobi.png">
				<p><strong>Yobi says: </strong>You can use this form to upload a document (in any format e.g.e.g. pdf, docx, jpg, zip) to the blockchain. Since this is a demo blockchain, please do not upload files more than <?php echo Literals::MAX_ALLOWED_FILE_SIZE_IN_BYTES/(1024*1024); ?> MB in size.</p>
			</div>
		</div>
		
	</div>

<?php include("page_footer.php");?>