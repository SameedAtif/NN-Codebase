<?php
	
	require("../includes/config.php");
	
	/**
	 ** Handle Login/Register/Logout + Data Update POST requests
	 **/
	if ($_SERVER["REQUEST_METHOD"] == "POST") { // if user reached via form submission, Ajax etc
		/** Data Update Request **/
		if (!empty($_POST["new_pic_url"])) { // since $_POST is always set, we can't use isset()
			query("UPDATE `users` SET `profile_pic` = ? WHERE id = ?", $_POST["new_pic_url"], $_SESSION["id"]);
			die("pic updated");
		}
		if (!empty($_POST["new_fullname"])) {
			query("UPDATE `users` SET `full_name` = ? WHERE id = ?", $_POST["new_fullname"], $_SESSION["id"]);
			die("fullname updated");
		}
		if (!empty($_POST["new_about_info"])) {
			query("UPDATE `users` SET `about_me` = ? WHERE id = ?", $_POST["new_about_info"], $_SESSION["id"]);
			die("about_info updated");
		}
		if (isset($_POST["public"])) {
			$rows = query("SELECT * FROM `tests` WHERE user_id = ?", $_SESSION["id"]);
			$_POST["public"] = array_map("intval", explode(",", $_POST["public"])); // not needed if form not submitted via JS
			
			foreach ($rows as $key => $value) {
				if (in_array($value["counter"], $_POST["public"]) && $value["public"] == 0)
					query("UPDATE `tests` SET `public` = 1 WHERE counter = ? AND user_id = ?", $value["counter"], $_SESSION["id"]);
				elseif (!in_array($value["counter"], $_POST["public"]) && $value["public"] == 1)
					query("UPDATE `tests` SET `public` = 0 WHERE counter = ? AND user_id = ?", $value["counter"], $_SESSION["id"]);
			}
			die("tests privacy updated");
		}

		/** Logout Request **/
		if (isset($_POST["logout"])) {
			logout();
			die("logged out");
		}

		/** Login/Register **/
		// validate submission
		if (empty($_POST["email"]))
			die("Email address must be provided!");
		elseif (empty($_POST["username"]))
			die("Username must be provided!");
		elseif (empty($_POST["access_token"]))
			die("Access Token must be provided!");

		// get app token
		$app_token = json_decode( file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=" . FB_CLIENT_ID . "&client_secret=" . FB_CLIENT_SECRET . "&grant_type=client_credentials"), true );
		
		// validate access token
		$access_token_response = json_decode( file_get_contents("https://graph.facebook.com/debug_token?input_token=" . $_POST["access_token"] . "&access_token=" . $app_token["access_token"]), true )["data"];
		if (!$access_token_response["is_valid"] || $access_token_response["application"] != "NotesNetwork") {
			die("f*** off!");
		}

		// query database for user
		$rows = query("SELECT * FROM users WHERE email = ?", $_POST["email"]);
		$row;
		if (count($rows) == 1) {
			// first (and only) row
			$row = $rows[0];
			echo "logged in";
		} else { // else if not found, register as new user
			$row = query("INSERT INTO `users`(`username`, `email`, `full_name`, `registered_with`, `profile_pic`, `registered_on`, `about_me`) VALUES (?,?,?,?,?,?,?)", 
				$_POST["username"],
				$_POST["email"],
				$_POST["full_name"],
				$_POST["registered_with"],
				$_POST["pic_url"],
				date("Y/m/d"),
				$_POST["about"]);
			echo "registered";
		}
		// registered or logged in, store info in session
		$_SESSION["id"] = $row["id"];
		$_SESSION["username"] = $row["username"];
		$_SESSION["access_token"] = $_POST["access_token"];
		
	} elseif ($_SERVER["REQUEST_METHOD"] == "GET") { // if user reached via clicking a link etc
		$mode = "default";
		$Profile;
		$Test;

		if ( isset($_GET["profile"]) && isset($_GET["test"]) ) {
			$mode = "test";
		} elseif ( isset($_GET["profile"]) ) {
			$mode = "profile";
		} elseif ( isset($_GET["login"]) ) {
			$mode = "login";
		}
		
		if ($mode == "test") {
			$member = query("SELECT * FROM users WHERE username = ?", $_GET["profile"]);
			$Profile = $member[0];
			
			render("header", ["title" => $Profile["full_name"] . "'s Test Details on NotesNetwork", "desc" => "View the public test details of " . $Profile["full_name"] . " on NotesNetwork.",
				"injection" => ["<meta property=\"og:image\" content=\"" . $Profile["profile_pic"] . "\" />",
				"<script type=\"text/javascript\" src=\"//platform-api.sharethis.com/js/sharethis.js#property=59202e17baf27a00129fc5cb&product=inline-share-buttons\" async></script>"]]);
		} elseif ($mode == "profile") {
			$member = query("SELECT * FROM users WHERE username = ?", $_GET["profile"]);
			$Profile = $member[0];

			render("header", ["title" => $Profile["full_name"] . " on NotesNetwork", "desc" => "View the public profile of " . $Profile["full_name"] . " on NotesNetwork.",
				"injection" => ["<meta property=\"og:image\" content=\"" . $Profile["profile_pic"] . "\" />",
				"<script type=\"text/javascript\" src=\"//platform-api.sharethis.com/js/sharethis.js#property=59202e17baf27a00129fc5cb&product=inline-share-buttons\" async></script>",
				"<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script><script type=\"text/javascript\">google.charts.load('current', {packages: ['corechart']});// callback is defined in the template</script>"]]);
		} elseif ($mode == "login") {
			render("header", ["title" => "Login/Sign-up", "desc" => "Login to NotesNetwork to save the results of your tests, see analytics and much more. Don't have an account? Create one now!"]);
		} else {
			$member = query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
			$Profile = $member[0];
			render("header", ["title" => "My Account", "desc" => "Change your account settings here. Add more information about yourself or update existing information."]);
		}

	?>
		
	    <main class="<?php if ($mode == 'login') echo 'sided'; ?> dark-links">
				
				<?php
					
					// being a good citizen
					if ($mode == "test") {
						render("/../templates/members/test_details", ["Data" => $Profile, "Model" => $TestsModel]);
						echo '<section><div class="sharethis-inline-share-buttons"></div></section>';
					} elseif ($mode == "profile") {
						if ( file_exists(__DIR__ . "/../templates/members/profile.php") ) {
							render("/../templates/members/profile", ["Data" => $Profile, "Model" => $TestsModel]);
							echo '<section><div class="sharethis-inline-share-buttons"></div></section>';
						} else {
							echo "<h2>The File does not exists! The path provided probably does not exists.</h2>"; // 404
							echo __DIR__ . "/../templates/members/profile.php";
						}
					} elseif ($mode == "login") {
						render("/../templates/members/login");
					} else {
						render("/../templates/members/account", ["Data" => $Profile, "Model" => $TestsModel]);
					}
					
				?>
			
		</main>
		
	<?php
		if ($mode == "login")
			loadSidebar();
		
		render("footer");
	}
?>