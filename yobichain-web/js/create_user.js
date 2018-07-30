
window.onload = function() {
	var preloadToggleElement = document.getElementById('pre_load_toggle');
	var orgnElement = document.getElementById('orgn_id');
	var preLoadAssetElement = document.getElementById('pre_load_asset');
	preloadToggleElement.checked = true;
	togglePreload(preloadToggleElement.checked);

	if (orgnElement)
		getOrganizationAssets(orgnElement, preLoadAssetElement);
}


function selectAll(selectAllElement, targetName) {

	var targetElements = document.getElementsByName(targetName);

	for (var i = 0; i < targetElements.length; i++) {
		targetElements[i].checked = selectAllElement.checked;
	}

}


function togglePreload(enablePreload) {

	var targetElements = document.getElementsByName('pre_load');

	for (var i = 0; i < targetElements.length; i++) {
		targetElements[i].hidden = !(enablePreload);
	}

}


function getOrganizationAssets(orgnIdElement, ddl) {

	var xmlhttp = new XMLHttpRequest();

	try {
		if (ddl != null)
			ddl.options.length = 0;
		else
			throw "Invalid target element!";
		xmlhttp.onreadystatechange = function () {

			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				try {
					var assets = JSON.parse(xmlhttp.responseText);
					for (var i = 0; i < assets.length; i++) {
						let option = document.createElement("option");
						option.text = option.value = assets[i].name;
						ddl.add(option);
					}
				}
				catch (e) {

				}
			}
		};

		xmlhttp.open("GET", "organization_assets_processor.php?orgn_id=" + orgnIdElement.value, true);
		xmlhttp.send();
		
	}
	catch (e) {
		alert("Error: " + xmlhttp.statusText + e.description);
	}

}