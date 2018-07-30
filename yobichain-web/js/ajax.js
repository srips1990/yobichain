

function getTransactionDetails(txID, output) {

	var xmlhttp = new XMLHttpRequest();
	try {
		if (output == null)
			throw "Invalid target element!";

		xmlhttp.onreadystatechange = function () {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				output.innerHTML = xmlhttp.responseText;
			}
		};

		xmlhttp.open("POST", "transaction_details_processor.php", true);
		var formData = new FormData();
		formData.append("txid", txID);
		xmlhttp.send(formData);
		
	}
	catch (e) {
		alert("Error: " + xmlhttp.statusText + e.description);
	}
}