<?php 
    include("page_header.php");
    include_once("yobichain_functions/crud_engine.php");
    $crud_engine = new crudEngine();
	$target_user_id = $_SESSION['user_id'];
	$target_user = $crud_engine->getUserFullDetails($target_user_id, true);
?>

<div class="col-lg-8">

        <div class="row alert alert-info">
            <div class="col-md-4">
                <label>1. Name of user </label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="col-md-8">
                <label><?php echo $target_user->user_name; ?></label>
            </div>
        </div>

        <div class="row alert alert-info">
            <div class="col-md-4">
                <label>2. Email address of user *</label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="col-md-8">
                <label><?php echo $target_user->user_email; ?></label>
            </div>
        </div>

        <div class="row alert alert-info">
            <div class="col-md-4">
                <label>3. Blockchain address</label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="col-md-8">
                <label><?php echo $target_user->user_public_address; ?></label>
            </div>
        </div>

        <!-- <div class="row alert alert-info">
            <div class="col-md-4">
                <label>9. KYC document</label>&nbsp;&nbsp;&nbsp;
            </div>
            <div class="col-md-8">
                <label><?php // echo $crud_engine->getKycDocumentInfoFromId($target_user->kyc_doc_type_id)['document']; ?></label><br>
                <?php // echo "<a href='download_user_kyc_doc.php?user_id=".$target_user_id."' target='_new'>Click here to download the document</a>"; ?>
            </div>
        </div> -->
</div>

<?php include("page_footer.php");?>
