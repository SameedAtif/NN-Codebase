<?php
	
	require("../includes/config.php");
	
	global $test;
	$Model = $TestsModel; // in constants.php

	/** Saving test **/
	if ( isset($_GET["result"]) && isset($_SESSION["username"]) ) {
		$query_string = "INSERT INTO `tests`(`user_id`, `test_id`, `source_index`, `subjects`, `score`, `total`, `time_taken`, `date`, `answers`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);";
		$test_id = $_GET["test_name"]; // so we can get info from the model

		$subjects = $_GET["subject_list"];
		$user_choices = json_decode($_GET["user_choices"]);
		$questions = json_decode(file_get_contents($Model[$test_id]["test_data"]["path_data"][$_GET["source_index"]]), TRUE);
		$filtered_questions = [];
		foreach ($questions as $key => $value) {
			if (in_array($value["subject"], $subjects))
				array_push($filtered_questions, $value);
		}
		$score = calculate_score($filtered_questions, $subjects, $user_choices);
		if ($score == -1) die("Something went wrong!");

		query($query_string, $_SESSION["id"], $test_id, $_GET["source_index"], json_encode($subjects), $score, $_GET["total"], $_GET["time_taken"], date("Y/m/d"), json_encode($user_choices));

		$result = query("SELECT `counter` FROM `tests` WHERE counter = LAST_INSERT_ID()")[0];

		// TODO: calculate and update user's rank

		die(SITE_PROTOCOL . "://" . SITE_DOMAIN . SITE_BASE_LINK . "profile/" . $_SESSION["username"] . "/" . $result["counter"] . "/");
	} elseif ( isset($_GET["result"]) ) {
		$test_id = $_GET["test_name"];
		
		$subjects = $_GET["subject_list"];
		$user_choices = json_decode($_GET["user_choices"]);
		$questions = json_decode(file_get_contents($Model[$test_id]["test_data"]["path_data"][$_GET["source_index"]]), TRUE);
		$filtered_questions = [];
		foreach ($questions as $key => $value) {
			if (in_array($value["subject"], $subjects))
				array_push($filtered_questions, $value);
		}

		$score = calculate_score($filtered_questions, $subjects, $user_choices);

		render("online_tests/result", [
			"source_index" => $_GET["source_index"],
			"subjects" => $subjects,
			"questions" => $filtered_questions,
			"user_answers" => $user_choices,
			"score" => $score,
			"total" => $_GET["total"],
			"time_taken" => $_GET["time_taken"],
			"Model" => $Model,
			"test_id" => $test_id]);
		die();
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
			/**
			 ** POST REQUEST
			 **/
			elseif ( !empty($_POST) ) {
				$name = $_POST["name"];
				( isset($_POST["timed"]) ) ? $timed = true : $timed = false;
				
				render($Model[$name]["path_test"], $Model[$name]["test_data"]);

					render("components/MathJax");
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
		render("components/sidebar");
	
	if ( isset($test) )
		render("components/comments-section");
	
	render("footer");

?>