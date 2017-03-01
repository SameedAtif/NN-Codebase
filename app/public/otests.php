<?php
	
	require("../includes/functions.php");
	
	global $test;
	
	/**
	** GET REQUEST
	**/
	if ( !empty($_GET["test"]) ) {
		$test = $_GET["test"];
		if ($test == "NET") {
			render("header", ["title" => "NET | Online Tests",
										"desc" => "Online mockup examination of NET(NUST Entry Test)."]);
		} elseif ($test == "SAT") {
			render("header", ["title" => "SAT | Online Tests",
										"desc" => "Online mockup examination of SAT(Scholastic Aptitude Test) in co-operation with Khanacademy."]);
		} elseif ($test == "gk") {
			render("header", ["title" => "General Knowledge | Online Tests",
										"desc" => "Test you general knowledge with our specially crafted quiz to benchmark your knowledge and memory."]);
		}
	}
	else {
		render("header", ["title" => "Online Tests",
									"desc" => "Online mockup examination of most popular entry tests, including NET, FAST Entry Test, NAT/NTS, PUCIT."]);
	}
	
?>

    <main>
		
		<?php
			/**
			** GET REQUEST
			**/
			// Test Handler
			if ( isset($test) ) {
				if ($test == "NET") {
					render("online_tests/NET/home");
				}
				elseif ($test == "SAT") {
					render("online_tests/SAT/home");
				}
				elseif ($test == "gk") {
					render("online_tests/Generic/home", ["test_title" => "General Knowledge",
																				"description" => "This test will contain 40 general knowledge questions. You will have about 40 minutes to complete it. You may spend as much time on a question as you want, but remember that total time is 40 minutes. You can start anytime by pressing the <strong>Begin</strong> button at the bottom."]);
				}
			}
			// Result Handler - Result page also does not requires a description(meta tag)
			elseif ( isset($_GET["result"]) ) {
				render("online_tests/result", ["marks" => $_GET["marks"],
																"percentage" => $_GET["perc"],
																"time_taken" => $_GET["tt"]]);
			}
			/**
			** POST REQUEST
			**/
			elseif ( !empty($_POST) ) {
				$name = $_POST["name"];
				( !empty($_POST["timed"]) ) ? $timed = true : $timed = false;
				if ($name == "NET") {
					render("online_tests/NET/test", [ "subjects" => $_POST["subjects"], "timed" => $timed ]);
				}
				elseif ($name == "SAT") {
					render("online_tests/SAT/test", []);
				}
				elseif($name == "gk") {
					render("online_tests/Generic/test", ["test" => "gk", "test_title" => "General Knowledge"]);
				}
			}
			/**
			** DEFAULT
			**/
			else {
				render("online_tests/otests_default");
			}
			
		?>
		
	</main>

<?php render("footer"); ?>