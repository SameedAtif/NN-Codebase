<section>
	<h2 id="test-title" class="center">
	<?php
		if ( sizeof($_POST["subjects"]) == 5) {
			($_POST["timed"] == "on") ? print("Full Scale NET &dash; Timed") : print("Full Scale NET &dash; Timeless");
		}
		else {
			($_POST["timed"] == "on") ? print("Subject Specific NET &dash; Timed") : print("Subject Specific NET &dash; Timeless");
		}
	?>
	</h2>
	<div id="meta" style="display: none;">
		<span id="test-name"><?= $_POST["name"]; ?></span>
		<span id="source"><?= $_POST["source_index"] ?></span>
		<span id="subj-list"><?= implode(",", $_POST["subjects"]) ?></span>
	</div>
	<noscript>This application requires JavaScript to be enabled! Please enable JavaScript and then reload this page.</noscript>
	
	<div class="indirect">
	<?php
		$data = file_get_contents($path_data[$_POST["source_index"]]);
		$questions = json_decode($data);
		$filtered_questions = [];
		// Filter questions array so it contains requested subjects only
		foreach ($questions as $question) {
			if (array_search($question->subject, $_POST["subjects"]) !== FALSE)
				array_push($filtered_questions, $question);
		}

		foreach ($filtered_questions as $index => $question) {
			echo '<div class="card">';
			
			echo '<div class="test-component">
					<span class="curr-section">' . $question->subject . '</span>
					<span class="nth-question">' . ($index + 1) . ' of ' . count($filtered_questions) . '</span>
				</div>';

			echo '<div class="statement test-component"><h4> ' . ($index+1) . ". " . $question->statement . '</h4></div>'; // QUESTION STATEMENT
			echo '<div class="answer test-component">
					Please Select an Answer:
					<ol type="i">
						<li>
							<div class="option"> 
								<input type="radio" name="answer' . ($index + 1) . '" />
								<span class="option-text">' . $question->options[0] . '</span>
							</div>
						</li>
						<li>
							<div class="option">
								<input type="radio" name="answer' . ($index + 1) . '" />
								<span class="option-text">' . $question->options[1] . '</span>
							</div>
						</li>
						<li>
							<div class="option">
								<input type="radio" name="answer' . ($index + 1) . '" />
								<span class="option-text">' . $question->options[2] . '</span>
							</div>
						</li>
						<li>
							<div class="option">
								<input type="radio" name="answer' . ($index + 1) . '" />
								<span class="option-text">' . $question->options[3] . '</span>
							</div>
						</li>
					</ol>
				</div>';
	
			echo '</div>'; // CARD END
		}
	?>
	</div>
	
	<div id="hud" class="test-component">
		<div id="timer">
			<span id="time"></span> minutes Remaining
		</div>
		
		<div id="categories">
			<select>
				<option value="all">All</option>
				<option value="ans">Answered</option>
				<option value="unans">Unanswered</option>
				<option value="rev">Reviewable</option>
			</select>
		</div>
		
		<div id="controls">
			<button class="save-btn" title="Save">Save</button>
			<button class="next-btn" title="Next Question">Next</button>
			<button class="prev-btn" title="Previous Question">Prev</button>
			<button class="review-btn" title="Mark for Review">Review</button>
			<button class="next-section-btn" title="Next Section">Next Section</button>
			<button class="prev-section-btn" title="Previous Section">Prev Section</button>
			<button class="first-btn" title="First Question">First</button>
			<button class="last-btn" title="Last Question">Last</button>
			<button class="help-btn" title="Help">Help</button>
		</div>
		
		<div id="final" class="center">
			<button>Submit</button>
		</div>

		<?php loadConfirmationBox(); ?>
	</div>
	
</section>

<script src="scripts/generic_tester.js"></script>

<style>
	.test-component {
		padding: 5px;
		border: 1px solid black;
	}
	.curr-section {
		color: #32E832;
		margin: 0 20px;
		text-transform: capitalize;
	}
	.nth-question {
		color: blue;
	}
	.statement {
		height: 200px;
		overflow-y: scroll;
	}
	.statement h4 {
		font-size: 18px;
		font-weight: 400;
	}
	.MathJax {
		font-size: 180%;
	}
	.answer {
		background-color: #e6f6ff;
	}
	.answer .option {
		background-color: #ffffff;
		margin: 5px;
		padding: 10px;
		border: 1px solid black;
	}
	.answer .option:hover {
		background-color: #b2b2b2;
	}
	.answer .option.active {
		background-color: #00ff00;
	}
	.answer .option.selected {
		background-color: #73CBFF;
	}
	#hud #timer {
		background-color: #009933;
		color: #ffffff;
		width: 100px;
		float: left;
		padding: 5px;
		border: 1px solid black;
	}
	#hud #categories {
		float: left;
		margin: 5px 35px;
	}
	#hud #controls {
		float: right;
	}
	#hud #controls button {
		background-color: #517beb;
		height: 50px;
		border: 2px solid #ffffff;
		border-radius: 5px;
		color: #ffffff;
	}
	#hud #final {
		clear: both;
	}
	#hud:after {
		display: block;
		content: " ";
		clear: both;
	}
</style>

<script>
	window.onload = function () {
		if ($("section h2").text().indexOf("Timeless") == -1) {
			MainController.init(180); // passing time (in minutes)
		} else {
			MainController.init(-1); // unlimited time
		}
	}
</script>