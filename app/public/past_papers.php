<?php
	
	require("../includes/config.php");
	
	$grade = "";
	if (!empty($_GET)) {
		$grade = $_GET["grade"];
	}
	
	if ( isset($grade) ) {
		if ($grade == "9th") {
			render("header", ["title" => "9th | Past Papers",
										"desc" => "Latest Past Papers for Matric 9th class. Old papers Lahore Board and Gujranwala Board."]);
		}
		elseif ($grade == "10th") {
			render("header", ["title" => "10th | Past Papers",
										"desc" => "Latest Past Papers for Matric 10th class. Old papers from Lahore Board and Gujranwala Board."]);
		}
		elseif ($grade == "first-year") {
			render("header", ["title" => "First Year | Past Papers",
										"desc" => "Latest Past Papers for Intermediate/FSC Part 1. Old papers from Lahore Board and Gujranwala Board."]);
		}
		elseif ($grade == "second-year") {
			render("header", ["title" => "Second Year | Past Papers",
										"desc" => "Latest Past Papers for Intermediate/FSC Part 2. Old papers from Lahore Board and Gujranwala Board."]);
		}
		else {
			/** should be a 404 **/
			render("header", ["title" => "Past Papers",
										"desc" => "Past Papers of all subjects, for all classes from Matric to Intermediate/FSC are available."]);
		}
	} else {
		render("header", ["title" => "Past Papers",
									"desc" => "Past Papers of all subjects, for all classes from Matric to Intermediate/FSC are available."]);
	}
	
?>

    <main>
		
		<?php
			
			if ( $grade == "9th" ) {
				render("past_papers/pp_9th");
			}
			elseif ( $grade == "10th" ) {
				render("past_papers/pp_10th");
			}
			elseif ( $grade == "first-year" ) {
				render("past_papers/pp_firstyear");
			}
			elseif ( $grade == "second-year" ) {
				render("past_papers/pp_secondyear");
			}
			else {
				render("past_papers/pp_default");
			}
			
		?>
		
	</main>

<?php render("footer"); ?>