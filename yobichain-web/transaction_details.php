<?php 
	include_once("page_header.php");
	include_once("primechain_functions/config.php");
	include_once("primechain_functions/blockchain_engine.php");
	include_once("primechain_functions/helperFunctions.php");
	$crud_engine = new crudEngine();
	$blockchain_engine = new blockchainEngine();

	// Get full details of the current user
	$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);

	if (!isset($_GET['msg_data']))
		throw new Exception("500", 1);

	$txID = htmlspecialchars($_GET['msg_data'], ENT_QUOTES);

?>
	<script type="text/javascript" src="js/ajax.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			<?php echo "var txID = '" . $txID . "';" ?>
			var outputElement = document.getElementById('output');
			getTransactionDetails(txID, outputElement);
        	setInterval(function(){ getTransactionDetails(txID, outputElement); },3000);
		}
	</script>

	<div class="row">
		<div class="col-md-8">
			<div id="output">
			</div>
		</div>

		<div class="col-lg-4">
			<div class="alert alert-warning">
				<img src="images/yobi.png">
				<p><strong><br/>
					Yobi says: </strong>

					The 'Transaction Details' table shows the following information
					<ol>
						<li>Transaction ID: This is the transaction in which the particular action took place e.g. a file was uploaded, an asset was created etc.</li>
						<li>Block Hash: This is the hash of the block in which this transaction took place.</li>
						<li>Confirmations: This is the number of blocks added to the blockchain after the block of this transaction.</li>
						<li>Time: This is the time when the block of this transaction was added to the blockchain.</li>
					</ol>

					The 'Block Details' table shows the following information
					<ol>
						<li>Block Hash: This is the hash of the block in which this transaction took place.</li>
						<li>Block Height</li>
						<li>Size</li>
						<li>Merkle root</li>
						<li>Transactions</li>
						<li>Confirmations: This is the number of blocks added to the blockchain after the block of this transaction.</li>
						<li>Mined at</li>
						<li>Nonce</li>
						<li>Chainwork</li>
						<li>Previous Block Hash</li>
						<li>Next Block Hash	</li>						
					</ol>

				</p>
			</div>
		</div>
	</div>

<?php include("page_footer.php");?>