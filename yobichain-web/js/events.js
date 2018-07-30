
function checkEventCodeAvailability(targetElement) {

	var xmlhttp = new XMLHttpRequest();
	try {

		xmlhttp.onreadystatechange = function () {

			if (xmlhttp.readyState == 4) {
				if(xmlhttp.status == 200) {
					targetElement.setCustomValidity('');
					console.log(xmlhttp);
				}
				else if (xmlhttp.status == 500) {
					targetElement.setCustomValidity(xmlhttp.responseText);
				}
			}
		};

		xmlhttp.open("POST", "check_event_code_availability.php", true);
		var formData = new FormData();
		formData.append("event_code", targetElement.value);
		xmlhttp.send(formData);

	}
	catch (e) {
		console.log("Error: " + xmlhttp.statusText + e.description);
	}

}

function deleteEvent(eventId) {
	try {
		if (!confirm("Confirm deletion?"))
			return false;

		var form = document.createElement("form");
	    form.setAttribute("method", "POST");
	    form.setAttribute("action", "delete_event_process.php");

        var eventIdField = document.createElement("input");
        eventIdField.setAttribute("type", "hidden");
        eventIdField.setAttribute("name", "event_id");
        eventIdField.setAttribute("value", eventId);

        form.appendChild(eventIdField);

	    document.body.appendChild(form);
	    form.submit();
		// window.location.replace("delete_event_process.php?event_id=" + eventId);
	}
	catch (e) {
		console.log("Error: " + e.description);
	}

}