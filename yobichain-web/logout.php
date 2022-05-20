<?php
session_start();
session_destroy();
include_once("yobichain_functions/error_reporting.php");
$msg=$_GET['msg']; $msg = htmlspecialchars($msg, ENT_QUOTES);
header("location:login.php?msg=$msg");
?>