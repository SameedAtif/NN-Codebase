<?php
	// display errors, warnings, and notices
	ini_set("display_errors", true);
	error_reporting(E_ALL);

	// requirements
	require("constants.php");
	require("functions.php");

	// enable sessions
	session_start();

	// require authentication for some pages
	if (in_array($_SERVER["PHP_SELF"], ["/app/public/members.php"]) && preg_replace("/i=\d+/", "", $_SERVER["QUERY_STRING"]) == "")
	{
		if (empty($_SESSION["username"]))
		{
			echo "redirect";
			redirect("login/");
		}
	}
?>