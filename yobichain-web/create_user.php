<?php
session_start();
session_destroy();
include_once('top.php');
?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Create new user</h3>
                    </div>
                    <div class="panel-body">
                        <?php 
                        // for displaying notifications
                        include_once('yobichain_functions/message_display.php'); 
                        ?><br/>
                        <form role="form" action="create_user_process.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <p>Name of user:</p><input class="form-control" placeholder="User name" name="user_name" type="text" autofocus><br/>
                                </div>
                                <div class="form-group">
                                    <p>Email address:</p><input class="form-control" placeholder="E-mail" name="user_email" type="email" autofocus><br/>
                                </div>
                                <div class="form-group">
                                    <p>Mobile number of user:</p><input class="form-control" placeholder="Mobile number" name="user_cell" type="text" value=""><br/>
                                </div>
                                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Register"><br/>
                                <a href='login.php'>Already registered?</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once('bottom.php');?>