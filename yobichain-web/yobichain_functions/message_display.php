<?php
if (isset($_GET['msg']))
    {
		$message_id = htmlspecialchars($_GET['msg'], ENT_QUOTES);

		include_once("yobichain_functions/crud_engine.php");
		$crudEngine = new crudEngine();
		$getMessage = $crudEngine->getMessage($message_id);
		$alert = $getMessage['alert']; $message = $getMessage['message'];
		if ($message_id == 133) {
			$message_data = isset($_GET['msg_data']) ? "<br>".htmlspecialchars(base64_decode(urldecode($_GET['msg_data'])), ENT_QUOTES) : "";
		} else {
			$message_data = isset($_GET['msg_data']) ? "<br>".htmlspecialchars($_GET['msg_data'], ENT_QUOTES) : "";
		}

		echo "<div class='panel panel-$alert'><div class='panel-heading'>".$message."<b>".$message_data."</b></div></div>";
    }
?>