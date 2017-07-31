<?php
	
	require("../includes/config.php");
	
	global $test;
	$Model = $TestsModel; // in constants.php

	/** Saving test **/
	if ( isset($_GET["result"]) && isset($_SESSION["username"]) ) {
		$query_string = "INSERT INTO `tests` (`user_id`, `test_id`, `subjects`, `score`, `total`, `time_taken`, `date`, `answers`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
		$test_id = array_search($_GET["test_name"], array_keys($Model)); // index of test (to get details like test name)

		$result = query($query_string, $_SESSION["id"], $test_id, json_encode($_GET["subject_list"]), $_GET["marks"], $_GET["total"], $_GET["time_taken"], date("Y/m/d"), json_encode($_GET["user_choices"]) );

		die(SITE_PROTOCOL . "://" . SITE_DOMAIN . SITE_BASE_LINK . "profile/" . $_SESSION["id"] . "/" . $result["counter"] . "/");
	}
	
	/**
	 ** GET REQUEST
	 **/
	if ( isset($_GET["test"]) ) {
		$test = $_GET["test"];
		render("header", ["title" => $Model[$test]["title"] . " | Online Tests",
							"desc" => $Model[$test]["desc"]]);
	}
	else {
		render("header", ["title" => "Online Tests",
							"desc" => "Online mockup examination of most popular entry tests, including NET, FAST Entry Test, NAT/NTS, PUCIT."]);
	}
	
	// Header for headings
	if ( isset($test) )
		echo'
    <header class="content-header">
		<div class="content-main-header" style="background-image: url(\'images/otests/' . $test . '/header-bg.jpg\');">
			<h1 class="heading-large">' . $Model[$test]["title"] . '</h1>
		</div>
	</header>
	';

?>

    <main <?php if ( !isset($_GET["result"]) && empty($_POST) ) echo 'class="sided"'; ?>>
		
		<?php
			/**
			 ** GET REQUEST
			 **/
			// Test Handler
			if ( isset($test) ) {
				render($Model[$test]["path_home"], $Model[$test]["test_data"]);
				update_trending_data("http://notesnetwork.org" . $_SERVER['REQUEST_URI'], $Model[$test]["title"] . " | Online Tests");
			}
			// Result Handler - Result page also does not requires a description(meta tag)
			elseif ( isset($_GET["result"]) ) {
				render("online_tests/result", ["marks" => $_GET["marks"],
											"percentage" => $_GET["perc"],
											"time_taken" => $_GET["time_taken"]]);
			}
			/**
			 ** POST REQUEST
			 **/
			elseif ( !empty($_POST) ) {
				$name = $_POST["name"];
				( isset($_POST["timed"]) ) ? $timed = true : $timed = false;
				
				render($Model[$name]["path_test"], $Model[$name]["test_data"]);

				loadMathjax();
			}
			/**
			 ** DEFAULT
			 **/
			else {
				render("online_tests/otests_default");
			}
			
		?>
		
	</main>

<?php
	
	if ( !isset($_GET["result"]) && empty($_POST) )
		loadSidebar();
	
	if ( isset($test) )
		loadCommentsSection();
	
	render("footer");

?>