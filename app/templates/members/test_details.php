<?php
	$rows = query("SELECT * FROM tests WHERE counter = ? AND user_id = ?", $_GET["test"], $Data["id"]);

	/** TODO: If $rows is empty then it's a 404 **/
	$test_info = $rows[0];
	/**  TODO: If it is not public, make a 404 redirect or display a message. Just don't show the details! **/
	$questions = json_decode(file_get_contents($Model[$test_info["test_id"]]["test_data"]["path_data"][$test_info["source_index"]]), TRUE);
	$subjects = json_decode($test_info["subjects"]);
	$user_answers = json_decode($test_info["answers"]);
?>
<section>
	<h1 class="center">Results - <?= $Model[$test_info["test_id"]]["title"] ?></h1>
	<div class="score">
		<h2>You scored <?= $test_info["score"] ?> in <?= $test_info["time_taken"] ?> minute(s).</h2>
		<p class="score-text"></p>
	</div>
	<h2 class="percentage center"><?= $test_info["score"]/$test_info["total"]*100 ?>%</h2>
	
</section>

<?php
	$filtered_questions = [];
	foreach ($questions as $key => $value) {
		if (in_array($value["subject"], $subjects))
			array_push($filtered_questions, $value);
	}

	if (count($subjects) > 1) {

		echo "<section>
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
				
				<tbody>";

		foreach ($subjects as $key => $subject) {
			$total_questions = 0;
			$scored = calculate_score($filtered_questions, [$subject], $user_answers);

			foreach ($filtered_questions as $key2 => $question) {
				if ($question["subject"] == $subject) {
					$total_questions++;
				}
			}
			
			echo "<tr><td>" . $subject . "</td>" . "<td>" . $total_questions . "</td><td>" . $scored . "</td><td>" . ( ($total_questions > 0) ? ($scored/$total_questions)*100 : 0 ) . "%</td></tr>";
		}

		echo "</tbody>
			</table>
		</section>";
	}
?>


<section>
	<h3>Details</h3>
	<p>Note: <span class="userChoice correct">Green and checked</span> means it was your marked option and it was correct. <span class="correct">Only green</span> means it was correct but you didn't mark it. <span class="userChoice">Red</span> means you marked the wrong option.</p>
	<div id="details">
		<?php
			foreach ($filtered_questions as $key => $value) {
				$string = "<div class='question'><div class='statement'>Q." . ($key+1) . ": " . $value["statement"] . "</div><div class='options'><ol type='i'>";
				foreach ($value["options"] as $optionIndex => $option) {
					if ($optionIndex === $user_answers[$key] && $user_answers[$key] === $value["answer"]) {
						$string .= "<li class='user-choice correct'>" . $option . "</li>";
					} else if ($optionIndex === $user_answers[$key] && $user_answers[$key] !== $value["answer"]) {
						$string .= "<li class='user-choice'>" . $option . "</li>";
					} else if ($optionIndex === $value["answer"]) {
						$string .= "<li class='correct'>" . $option . "</li>";
					} else {
						$string .= "<li>" . $option . "</li>";
					}
					
				}
				$string .= "</ol></div><div class='explanation'><h4>Explanation:</h4>" . $value["explanation"] . "</div></div>";
				
				echo $string;
			}
		?>
	</div>
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
.user-choice {
	color: red;
}
.correct {
	color: green;
}
.user-choice.correct:after {
	content: ' \02713';
}
</style>
<script>
	window.onload = function () {
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
	}
</script>