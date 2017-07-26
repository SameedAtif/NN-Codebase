<?php
	
	require("../includes/config.php");
	
	$Data = [
		"9th" => [
			"title" => "9th | Notes",
			"desc" => "MCQs, Short Questions, Long Questions and all other notes for 9th class.",
			"template_path" => "notes/notes_list_9th",
			"onpage_title" => "9<sup>th</sup> Notes"
		],
		"10th" => [
			"title" => "10th | Notes",
			"desc" => "MCQs, Short Questions, Long Questions and all other notes for 10th class.",
			"template_path" => "notes/notes_list_10th",
			"onpage_title" => "10<sup>th</sup> Notes"
		],
		"first-year" => [
			"title" => "First Year | Notes",
			"desc" => "MCQs, Short Questions, Long Questions and all other notes for Intermediate/FSc Part 1.",
			"template_path" => "notes/notes_list_firstyear",
			"onpage_title" => "First Year Notes"
		],
		"second-year" => [
			"title" => "Second Year | Notes",
			"desc" => "MCQs, Short Questions, Long Questions and all other notes for Intermediate/FSc Part 2.",
			"template_path" => "notes/notes_list_secondyear",
			"onpage_title" => "Second Year Notes"
		],
		"default" => [
			"title" => "Notes",
			"desc" => "MCQs, Short Questions, Long Questions and all other notes for Matric and Intermediate/FSc.",
			"template_path" => "notes/notes_default",
			"onpage_title" => "Notes"
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
	loadSidebar();
	render("footer");
?>