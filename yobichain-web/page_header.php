<?php
session_start();
ob_start();
$status=(isset($_SESSION['status']) ? $_SESSION['status'] : null); if (is_null($status) || $status!="loggedin") { header("location:login.php?msg=29");}

include_once("primechain_functions/error_reporting.php");
$user_id = $_SESSION['user_id'];

include_once("primechain_functions/crud_engine.php");
$crudEngine = new crudEngine();

include_once("primechain_functions/blockchain_engine.php");
$blockchainEngine = new blockchainEngine();

// Insert user activity into logs
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
$ip = $_SERVER['REMOTE_ADDR']; 
$browser = $_SERVER['HTTP_USER_AGENT']; 
$ref = (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "");
$insertIntoLogs = $crudEngine->insertIntoLogs($user_id,$url,$ip,$browser,$ref);

// Check if the loggedin user is authorised to view this page. If not, log the user out.
$path = basename($_SERVER['PHP_SELF']); 
$role_code = pathinfo($path, PATHINFO_FILENAME);

// Get user's name, email and cell number from the database
$getUserDetails = $crudEngine->getUserDetails($user_id);
$user_name = $getUserDetails['user_name']; $user_cell = $getUserDetails['user_cell']; $user_email = $getUserDetails['user_email'];

// Get the name of this role
$getRoleTitle = $crudEngine->getRoleTitle($role_code);

?>
<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Yobichain">
	<meta name="author" content="Primechain Technologies Pvt. Ltd.">

	<title><?php echo $getRoleTitle; ?></title>

	<!-- Bootstrap Core CSS -->
	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- <link href="vendor/bootstrap/css/bootstrap1.css" rel="stylesheet"> -->

	<!-- Drag and Drop CSS -->
	<link href="vendor/drag-and-drop/css/main.css" rel="stylesheet">
    <!-- <link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" /> -->

	<!-- MetisMenu CSS -->
	<link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

	<!-- DataTables CSS -->
	<link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

	<!-- DataTables Responsive CSS -->
	<link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="dist/css/sb-admin-2.css" rel="stylesheet">

	<!-- Custom Fonts -->
	<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>

	<div id="wrapper">

		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Yobichain</a>
			</div>
			<!-- /.navbar-header -->

			<ul class="nav navbar-top-links navbar-right">
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
					</a>
					 <ul class="dropdown-menu dropdown-alerts">
						<li><a href="#"><i class="fa fa-user fa-fw"></i> Name: <?php echo $user_name; ?> </a></li>
						<li><a href="#"><i class="fa fa-gear fa-fw"></i> User ID: <?php echo $user_id; ?></a>
						</li><li><a href="#"><i class="fa fa-envelope fa-fw"></i> Email: <?php echo $user_email; ?></a></li>
						<li><a href="#"><i class="fa fa-phone fa-fw"></i> Cell: <?php echo $user_cell; ?></a></li>
						<li class="divider"></li>
						<li><a href="password_change.php"><i class="fa fa-sign-out fa-fw"></i> Change password</a></li>
						<li class="divider"></li>
						<li><a href="logout.php?msg=11"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
					</ul>
					<!-- /.dropdown-user -->
				</li>
				<!-- /.dropdown -->
			</ul>
			<!-- /.navbar-top-links -->

			<div class="navbar-default sidebar" role="navigation">
				<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
						<?php include("navigation.php");?>
					</ul>
				</div>
				<!-- /.sidebar-collapse -->
			</div>
			<!-- /.navbar-static-side -->
		</nav>


		<div id="page-wrapper">

			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header"><?php echo $getRoleTitle; ?></h1>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<?php include_once('primechain_functions/message_display.php'); ?>   
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h4><?php echo $getRoleTitle; ?></h4>
						</div>
						<div class="panel-body">