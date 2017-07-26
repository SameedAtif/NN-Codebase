<section>
	<h3>Profile Settings</h3>
	
	<div class="row-item">
		<img src="<?= $Data["profile_pic"] ?>" style="width: 150px;" />
	</div>
	
	<form method="POST" action="members.php/" class="personal_info">
		<div class="row-item">
			<h4>Profile Picture</h4>
			<input type="text" name="new_pic_url" placeholder="Enter new Picture's URL" />
		</div>

		<div class="row-item">
			<h4>Display Name</h4>
			<input type="text" name="new_fullname" placeholder="Enter new Full Name" />
		</div>

		<div class="row-item">
			<h4>About Me</h4>
			<textarea name="new_about_info" placeholder="Say what you want to here..."></textarea>
		</div>

		<div>
			<button class="ghost-btn btn-blue" type="submit">Save</button>
		</div>
	</form>
</section>

<section>
	<h3>Tests History</h3>
	<p>All of your given tests are by default publicly visible. You can change that here, and choose which ones you want to be publicly visible.</p>
	
	<form method="POST" action="members.php/" class="tests_history">
		<table class="bordered">
			<thead>
				<tr>
					<td>#</td>
					<td>Test</td>
					<td>Total</td>
					<td>Obtained</td>
					<td>Percentage</td>
					<td>Date</td>
					<td>Public</td>
				</tr>
			</thead>
			<tbody>
			<?php
				$rows = query("SELECT * FROM tests WHERE user_id = ?", $Data["id"]);
				foreach ($rows as $key => $value) {
					$percentage = round( ($value["score"]/$value["total"]) * 100, 2 );
					$keys = array_keys($Model);
					echo "<tr><td>" . ($key + 1) . "</td>
					<td><a href=\"" . SITE_PROTOCOL . "://" . SITE_DOMAIN . SITE_BASE_LINK . "profile/" . $Data["username"] . "/" . $value["counter"] . "/\">" . $Model[ $keys[$value["test_id"]] ]["title"]  . "</a></td>
					<td>" . $value["total"] . "</td>
					<td>" . $value["score"] . "</td>
					<td>" . $percentage . "%</td>
					<td>" . $value["date"] . "</td>
					<td><input type=\"checkbox\" name=\"public[]\" value=\"" . $value["counter"] . "\" " . (($value["public"] == 1) ? "checked" : "") . "/></td>
					</tr>";
				}
			?>
			</tbody>
		</table>

		<div>
			<button class="ghost-btn btn-blue" type="submit">Save</button>
		</div>
	</form>
</section>

<script type="text/javascript">
	var xhr = new XMLHttpRequest();

	xhr.onreadystatechange = function () {
		if (xhr.readyState == 4 && xhr.status == 200)
			console.log(xhr.responseText);
		else if (xhr.readyState == 4)
			console.error("Something went wrong");
	}

	// for personal info
	document.querySelector(".personal_info").onsubmit = function (e) {
		e.preventDefault();

		xhr.open("POST", "members.php/");
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		var profile_pic = document.querySelector("input[name=new_pic_url]").value,
			fullname = document.querySelector("input[name=new_fullname]").value,
			about_info = document.querySelector("textarea[name=new_about_info]").value;

		xhr.send("new_pic_url=" + encodeURIComponent(profile_pic) + "&new_fullname=" + encodeURIComponent(fullname) + "&new_about_info=" + encodeURIComponent(about_info));
	}

	// for tests history
	document.querySelector(".tests_history").onsubmit = function (e) {
		e.preventDefault();

		xhr.open("POST", "members.php/");
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		var public_values = [];
		document.querySelectorAll("input[name='public[]']").forEach(function (item, index) {
			if (item.checked) public_values[index] = item.value;
		});

		xhr.send("public=" + encodeURIComponent(public_values));
	}
</script>