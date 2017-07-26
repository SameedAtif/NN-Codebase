<?php

	require("../includes/config.php");
	
	global $page;
	
	if ( isset( $_GET["about"]) ) {
		render("header", ["title" => "About Us",
									"desc" => "Everything you need to know about NotesNetwork and the amazing people who contributed to this project."]);
		$page = "about";
	} elseif ( isset( $_GET["privacy_policy"]) ) {
		render("header", ["title" => "Privacy Policy",
									"desc" => "NotesNetwork's privacy policy regarding user information, data collection and other issues that matter."]);
		$page = "privacy_policy";
	} else {
		render("header", ["title" => "Contact",
									"desc" => "Contact NotesNetwork administration now. Provide feedback or report any mistakes, broken links or errors."]);
	}
	
?>

<main>

	<?php
		if ($page == "about") {
			render("contact/about");
		} elseif ($page == "privacy_policy") {
			render("contact/privacy_policy");
		} else {
			render("contact/default");
		}
	?>
	
</main>

<?php render("footer"); ?>