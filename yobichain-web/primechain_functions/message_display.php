<?php
if (isset($_GET['msg']))
    {
		$message_id = htmlspecialchars($_GET['msg'], ENT_QUOTES);
		$message_data = isset($_GET['msg_data']) ? "<br>".htmlspecialchars($_GET['msg_data'], ENT_QUOTES) : "";

		include_once("primechain_functions/crud_engine.php");
		$crudEngine = new crudEngine();
		$getMessage = $crudEngine->getMessage($message_id);
		$alert = $getMessage['alert']; $message = $getMessage['message'];
		echo "<div class='panel panel-$alert'><div class='panel-heading'>".$message."<b>".$message_data."</b></div></div>";
    }
?>