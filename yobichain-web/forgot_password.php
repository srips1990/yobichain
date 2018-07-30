<?php
session_start();
session_destroy();
include_once("primechain_functions/error_reporting.php");
include_once("top.php");
?>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Reset password to Yobichain</h3>
                    </div>
                    <div class="panel-body"><?php include_once('primechain_functions/message_display.php'); ?>
                        <form role="form" action="forgot_password_process.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <p>Email address:</p><input class="form-control" placeholder="E-mail" name="user_email" type="email" autofocus>
                                </div>
                                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Reset password"><br/>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once("bottom.php");?>
