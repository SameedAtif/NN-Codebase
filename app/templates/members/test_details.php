<?php
	$rows = query("SELECT * FROM tests WHERE counter = ?", $_GET["test"]);
	$test_info = $rows[0];

	/**  WARNING: If it is not public, make a 404 redirect or display a message. Just don't show the details! **/
?>
<section>
	<h1 class="center">Results</h1>
	<div class="score">
		<h2>You scored <?= $test_info["score"] ?> in <?= $test_info["time_taken"] ?> minute(s).</h2>
		<p class="score-text"></p>
	</div>
	<h2 class="percentage center"><?= $test_info["score"]/$test_info["total"]*100 ?>%</h2>
	
</section>

<section>
	<h3>Summary</h3>
	<table>
		<thead>
			<tr>
				<th>Subject</th>
				<th>Total Questions</th>
				<th>Your Score</th>
				<th>Percentage</th>
			</tr>
		</thead>
		
		<tbody>
			
		</tbody>
	</table>
</section>

<section>
	<h3>Details</h3>
	<p>Note: <span class="userChoice correct">Green and checked</span> means it was your marked option and it was correct. <span class="correct">Only green</span> means it was correct but you didn't mark it. <span class="userChoice">Red</span> means you marked the wrong option.</p>
	<div id="details"></div>
</section>

<style>
.score h2 {
	font-weight: 400;
}
.percentage {
	font-size: 8em;
	font-weight: 100;
    color: #25af25;
}
tbody tr td {
	text-align: center;
	border-bottom: 2px solid black;
}
tbody tr td:first-child {
	text-transform: capitalize;
}
#details .question {
	background-color: aliceblue;
    border-bottom: 2px solid blue;
}
#details .statement {
	font-weight: 700;
}
.userChoice {
	color: red;
}
.correct {
	color: green;
}
.userChoice.correct:after {
	content: ' \02713';
}
</style>
<script>
	var perc = parseInt($(".percentage").html());
	if (perc <= 40) {
		$(".percentage").css("color", "red");
		$(".score-text").html("Hmmm...that doesn't looks good! You will need a lot of practise.");
	} else if (perc > 40 && perc <= 65) {
		$(".percentage").css("color", "yellow");
		$(".score-text").html("Not bad! A little more practise will take you to the top 1%.");
	} else {
		//$(".percentage").css("color", "green");
		$(".score-text").html("Great! You are doing awesome. Keep it up.");
	}
</script>
<script>
	// For rendering mathematics written in LaTeX
	MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
</script>