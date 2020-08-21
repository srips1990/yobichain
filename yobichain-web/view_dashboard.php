<?php include("page_header.php");?>
                        
<?php
//session_start();
$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:logout.php?msg=29");}
?>
            <!-- /.row -->

            <div class="row">

<?php

// Get user's public key and public address from the database
$getUserAddressAndPublicKey = $crudEngine->getUserAddressAndPublicKey($user_id);
$user_public_key = $getUserAddressAndPublicKey['user_public_key'];
$user_public_address = $getUserAddressAndPublicKey['user_public_address']; 
$user_private_key = $blockchainEngine->getUserPrivateKeyFromUserAddress($user_public_address); 
$user_public_key_display=wordwrap($user_public_key, 8, "\n", true);

// Get details of user's previous login
$usersPreviousLoginDetails = $crudEngine->usersPreviousLoginDetails($user_id);
$ip = $usersPreviousLoginDetails['ip']; $browser = $usersPreviousLoginDetails['browser']; $timestamp = $usersPreviousLoginDetails['timestamp']; 
$timestamp=date("r", strtotime($timestamp));
?>
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $blockchainEngine->getBlockCount(); ?></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Blocks in the blockchain</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-edit fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $blockchainEngine->getAddressesCount(); ?></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?role_code=sign_contract">
                            <div class="panel-footer">
                                <span class="pull-left">Addresses on the blockchain</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-files-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $blockchainEngine->getAssetsCount(); ?></div>
                                    <div></div>
                                </div>
                            </div>
                        </div>
                        <a href="index.php?role_code=view_your_contract">
                            <div class="panel-footer">
                                <span class="pull-left">Assets on the blockchain</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-lg-7">
                    <table class='table'>
                        <thead>
                            <tr class='success'>
                                <th>#</th>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class='info'>
                                <td>1</td>
                                <td>User ID:</td>
                                <td><?php echo $user_id;?></td>
                            </tr>
                            <tr class='info'>
                                <td>2</td>
                                <td>Name:</td>
                                <td><?php echo $user_name;?></td>
                            </tr>   
                            <tr class='info'>
                                <td>3</td>
                                <td>Email:</td>
                                <td><?php echo $user_email;?></td>
                            </tr>                              
                            <tr class='success'>
                                <td>4</td>
                                <td>Public key:</td>
                                <td><?php echo $user_public_key_display;?></td>
                            </tr> 
                            <tr class='success'>
                                <td>5</td>
                                <td>Public address:</td>
                                <td><?php echo $user_public_address;?></td>
                            </tr> 
                            <tr class='danger'>
                                <td>6</td>
                                <td>Private key:</td>
                                <td><?php echo $user_private_key;?></td>
                            </tr> 
                        </tbody>
                    </table>
                </div>

                <div class='col-lg-5'>
                    <div class="alert alert-warning">
                        <img src="images/yobi.png">
                            <p><strong><br/>
                            Yobi says: </strong>Welcome to Yobichain. Use S.A.M. (Smart Asset Manager to  create, view, re-issue and send blockchain assets. You can also create and accept offers. Use D.A.V.E. (Data Authentication & Verification Engine) to upload data to blockchain and to view uploaded data.
                    </div>
                </div>

            </div>

 
        </div>
    </div>
 

<?php include("page_footer.php");?>
