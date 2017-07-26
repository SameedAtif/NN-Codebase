<section>
	<h2 class="center"><?= $test_title ?></h2>
	
	<?php
		$test_name = $_POST["name"];
		$source = $path_data; // path to JSON file
		
		echo "<div id='meta' style='display: none;'><span id='test_name'>" . $test_name . "</span><span id='source'>" . $source . "</span></div>";
		
		$data = file_get_contents($source);
		$questions = json_decode($data);
		foreach ($questions as $index => $question) {
			echo '<div class="card">';
			
			echo '<div class="question"><h4>' . ($index+1) . ". " . $question->statement . '</h4></div>'; // QUESTION STATEMENT
			echo '<div class="answer">
							<ol type="i">
								<li class="option">
									<span class="option-text">' . $question->options[0] . '</span>
								</li>
								<li class="option">
									<span class="option-text">' . $question->options[1] . '</span>
								</li>
								<li class="option">
									<span class="option-text">' . $question->options[2] . '</span>
								</li>
								<li class="option">
									<span class="option-text">' . $question->options[3] . '</span>
								</li>
							</ol>
						</div>';
	
			echo '</div>'; // CARD END
		}
	?>
	
	<div id="hud">
		
		<div id="controls">
			<button class="ghost-btn first-btn" title="First Question"><i class="fa fa-lg fa-step-backward"></i> First</button>
			<button class="ghost-btn prev-btn" title="Previous Question"><i class="fa fa-lg fa-arrow-left"></i> Prev</button>
			<button class="ghost-btn next-btn" title="Next Question">Next <i class="fa fa-lg fa-arrow-right"></i></button>
			<button class="ghost-btn last-btn" title="Last Question">Last <i class="fa fa-lg fa-step-forward"></i></button>
		</div>
		
			
		<div id="progress">
			Done <span>n</span> of <?= sizeof($questions) ?>
		</div>
		
		<div id="timer">
			<span id="time"></span> minutes remaining
		</div>
		
		<div id="final" class="center">
			<button class="ghost-btn">Submit</button>
		</div>
			
	</div>
	
</section>

<script src="scripts/generic_tester.js"></script>

<style>
	#meta {
		display: none;
	}
	.question h4 {
		font-size: 18px;
		font-weight: 400;
	}
	.MathJax {
		font-size: 180%;
	}
	.answer {
		background-color: #e6f6ff;
	}
	.answer ol {
		padding: 0;
		list-style-position: inside;
	}
	.answer .option {
		padding: 10px;
		border-bottom: 2px solid black;
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
	#hud {
		padding: 2%;
		text-align: center;
	}
	#hud > div {
		padding: 10px;
	}
	#hud #progress, #hud #timer {
		display: inline-block;
	}
	#hud #timer #time {
		font-size: 32px;
	}
	
</style>

<script>
	window.onload = function () {
		MainController.init();
	}
</script>