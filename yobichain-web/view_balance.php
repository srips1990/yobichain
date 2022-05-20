<?php 
    include_once("page_header.php");
    include_once("yobichain_functions/config.php");
    include_once("yobichain_functions/blockchain_engine.php");
    $crud_engine = new crudEngine();
    $blockchain_engine = new blockchainEngine();
?>
    <div class="row">
        <div class="col-md-10">
			<?php
				try
				{
					$user = $crud_engine->getUserFullDetails($_SESSION['user_id'], true);
					$assets = $blockchain_engine->getAssetBalancesForAddress($user->user_public_address);

					$assetNameArray = array();

                    foreach ($assets as $key => $row)
                    {
                        $assetNameArray[$key] = $row['name'];
                    }

                    array_multisort($assetNameArray, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $assets);

                    echo "<p><table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>";
                    echo "
                        <thead>
                            <tr>
                                <th>Asset name</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>";
					foreach ($assets as $asset) {
						echo "<tr class='odd gradeX'><td style='color:blue;width:60%'>".$asset['name']."</td><td style='color:green;width:20%'>".sprintf("%0.4f",$asset['qty'])."</td></tr>";
					}

                    echo "</tbody></table></p><br/>";

				}
				catch (Exception $e)
				{
					echo "<h5 style='color:red'><strong>Error: ".$e->getMessage()."</strong></h5>";
				}
			?>
        </div>
    </div>

<?php include("page_footer.php");?>