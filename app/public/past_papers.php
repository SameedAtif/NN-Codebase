<?php
	
	require("../includes/config.php");

	$Data = [
		"9th" => [
			"title" => "9th | Past Papers",
			"desc" => "Latest Past Papers for Matric 9th class. Old papers Lahore Board and Gujranwala Board.",
			"template_path" => "past_papers/pp_9th",
			"onpage_title" => "9<sup>th</sup> Past Papers"
		],
		"10th" => [
			"title" => "10th | Past Papers",
			"desc" => "Latest Past Papers for Matric 10th class. Old papers from Lahore Board and Gujranwala Board.",
			"template_path" => "past_papers/pp_10th",
			"onpage_title" => "10<sup>th</sup> Past Papers"
		],
		"first-year" => [
			"title" => "First Year | Past Papers",
			"desc" => "Latest Past Papers for Intermediate/FSC Part 1. Old papers from Lahore Board and Gujranwala Board.",
			"template_path" => "past_papers/pp_firstyear",
			"onpage_title" => "First Year Past Papers"
		],
		"second-year" => [
			"title" => "Second Year | Past Papers",
			"desc" => "Latest Past Papers for Intermediate/FSC Part 2. Old papers from Lahore Board and Gujranwala Board.",
			"template_path" => "past_papers/pp_secondyear",
			"onpage_title" => "Second Year Past Papers"
		],
		"default" => [
			"title" => "Past Papers",
			"desc" => "Past Papers of all subjects, for all classes from Matric to Intermediate/FSC are available.",
			"template_path" => "past_papers/pp_default",
			"onpage_title" => "All Past Papers"
		]
	];
	
	$grade = "default";
	if ( isset($_GET["grade"]) ) {
		$grade = $_GET["grade"];
	}
	
	if ( isset($Data[$grade]) ) {
		render("header", ["title" => $Data[$grade]["title"], "desc" => $Data[$grade]["desc"] ]);
	} else {
		/**  Should be a 404 **/
		render("header", ["title" => $Data[$grade]["title"], "desc" => $Data[$grade]["desc"] ]);
	}
	
?>

<h1 class="center"><?= $Data[$grade]["onpage_title"] ?></h1>
<main class="sided">
	<?php
		render($Data[$grade]["template_path"]);
	?>
</main>

<?php
	render("components/sidebar");
	render("footer");
?>