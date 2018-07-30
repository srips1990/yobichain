
function getUserAssetBalance(assetNameElement, output) {

	var xmlhttp = new XMLHttpRequest();

	try {

		xmlhttp.onreadystatechange = function () {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				output.innerHTML = xmlhttp.responseText;
			}
		};

		xmlhttp.open("POST", "user_asset_balance_processor.php", true);
//    	xmlhttp.setRequestHeader("Content-type", "multipart/form-data");
		var formData = new FormData();
		formData.append("asset_name", assetNameElement.value);
		xmlhttp.send(formData);
		
	}
	catch (e) {
		alert("Error: " + xmlhttp.statusText + e.description);
	}

}