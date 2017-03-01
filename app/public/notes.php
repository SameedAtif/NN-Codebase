<?php
	
	require("../includes/functions.php");
	
	$grade = "";
	if (!empty($_GET)) {
		$grade = $_GET["grade"];
	}
	
	if ( isset($grade) ) {
		if ($grade == "9th") {
			render("header", ["title" => "9th | Notes",
										"desc" => "MCQs, Short Questions, Long Questions and all other notes for 9th class."]);
		}
		elseif ($grade == "10th") {
			render("header", ["title" => "10th | Notes",
										"desc" => "MCQs, Short Questions, Long Questions and all other notes for 10th class."]);
		}
		elseif ($grade == "first-year") {
			render("header", ["title" => "First Year | Notes",
										"desc" => "MCQs, Short Questions, Long Questions and all other notes for Intermediate/FSc Part 1."]);
		}
		elseif ($grade == "second-year") {
			render("header", ["title" => "Second Year | Notes",
										"desc" => "MCQs, Short Questions, Long Questions and all other notes for Intermediate/FSc Part 2."]);
		}
		else {
			/** should be a 404 **/
			render("header", ["title" => "Notes",
								"desc" => "MCQs, Short Questions, Long Questions and all other notes for Matric and Intermediate/FSc."]);
		}
	} else {
		render("header", ["title" => "Notes",
									"desc" => "MCQs, Short Questions, Long Questions and all other notes for Matric and Intermediate/FSc."]);
	}
	
?>

    <main>
		
		<?php
			
			if ( $grade == "9th" ) {
				render("notes/notes_list_9th");
			}
			elseif ( $grade == "10th" ) {
				render("notes/notes_list_10th");
			}
			elseif ( $grade == "first-year" ) {
				render("notes/notes_list_firstyear");
			}
			elseif ( $grade == "second-year" ) {
				render("notes/notes_list_secondyear");
			}
			else {
				render("notes/notes_default");
			}
			
		?>
		
	</main>

<?php render("footer"); ?>