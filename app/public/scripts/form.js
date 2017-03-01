function _(id) { return document.querySelector(id); }

function submitForm() {
	_("button[type=submit]").disable = true;
	
	var formData = new FormData();
	var security = [];
	document.querySelectorAll("input[name='sec[]']").forEach(function (item, index) {
		security[index] = item.checked;
	});
	formData.append("name", _("input[name=name]").value);
	formData.append("subject", _("input[name=subject]").value);
	formData.append("address", _("input[name=email]").value);
	formData.append("message", _("textarea[name=msg]").value);
	formData.append("security", JSON.stringify(security));
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "sendmail.php");
	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4 && xhr.status == 200) {
			console.log(xhr.responseText)
			if (xhr.responseText == "success") {
				// display thankyou
				$("#contact-main .container").html("<img src='./images/thanks.gif' class='img-block-center' /><h2 class='center'>We will respond as soon as possible!</h2>");
			} else {
				$("#contact-main .container").html("<h2 class='center'>Something went wrong...</h2>");
			}
		}
	}
	
	xhr.send( formData );
}