<?php
session_start();
session_destroy();
include_once("primechain_functions/error_reporting.php");

include_once("primechain_functions/crud_engine.php");
$crudEngine = new crudEngine();

if(!isset($_GET['user_email'])) {
    header("location:forgot_password.php");
}

// Forgot Password - is email and random correct
$user_email=$_GET['user_email']; $user_email = htmlspecialchars($user_email, ENT_QUOTES);
$random=$_GET['random']; $random = htmlspecialchars($random, ENT_QUOTES);
$isEmailRandomCorrect = $crudEngine->isEmailRandomCorrect($user_email,$random);
if ($isEmailRandomCorrect===false) 
    { 
        header("location:login.php?msg=20"); 
    }

elseif ($isEmailRandomCorrect===true) 
    {
        include_once('top.php');
        echo "
        </head>
        <body>
            <div class='container'>
                <div class='row'>
                    <div class='col-md-4 col-md-offset-4'>
                        <div class='login-panel panel panel-red'>
                            <div class='panel-heading'>
                                <h3 class='panel-title'>Reset your password</h3>
                            </div>
                            <div class='panel-body'>";
                               
                                // for displaying notifications
                                include_once('primechain_functions/message_display.php');
                                echo "
                                <form role='form' action='reset_password_process.php' method='post'>
                                    <input type='hidden' name='user_email' value='$user_email'>
                                    <input type='hidden' name='random' value='$random'>
                                    <fieldset>
                                        <div class='form-group'>
                                            <p>Enter new password:</p><input class='form-control' name='user_password' type='password' autofocus><br/>
                                        </div>
                                        <input type='submit' class='btn btn-lg btn-danger btn-block' value='Reset password'><br/>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";

        include_once('bottom.php');
    }
?>