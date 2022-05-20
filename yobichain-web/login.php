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
                        <h3 class="panel-title">Login to Yobichain</h3>
                    </div>
                    <div class="panel-body">
                        <?php 
                        // for displaying notifications
                        include_once('yobichain_functions/message_display.php'); 
                        ?><br/>
                        <form role="form" action="login_process.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <p>Email address:</p><input class="form-control" placeholder="E-mail" name="user_email" type="email" autofocus><br/>
                                </div>
                                <div class="form-group">
                                    <p>Password</p><input class="form-control" placeholder="Password" name="user_password" type="password" value=""><br/>
                                </div>
                                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Login"><br/>
                                <a href='forgot_password.php'>Forgot password?</a><br>
                                <a href='create_user.php'>Create new user</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include_once('bottom.php');?>