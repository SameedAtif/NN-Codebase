<section>
	<h2 class="center">
	<?php
		if ( sizeof($subjects) == 5) {
			($timed == "on") ? print("Full Scale NET &dash; Timed") : print("Full Scale NET &dash; Timeless");
		}
		else {
			($timed == "on") ? print("Subject Specific NET &dash; Timed") : print("Subject Specific NET &dash; Timeless");
		}
	?>
	</h2>
	<div style="display: none;" id="subjList"><?php foreach ($subjects as $subject) { echo $subject . " "; } ?></div>
	
	<div class="test-component">
		<span id="currSection">Current Section</span>
		<span id="nthQuestion">Q/A Progress</span>
	</div>
	
	<div id="question" class="test-component">
		<h4>Question:</h4>
	</div>
	
	<div id="answer" class="test-component">
		Please Select an Answer:
		<ol type="i">
			<li>
				<div class="option"> 
					<input type="radio" name="answer" />
					<span class="option-text"></span>
				</div>
			</li>
			<li>
				<div class="option">
					<input type="radio" name="answer" />
					<span class="option-text"></span>
				</div>
			</li>
			<li>
				<div class="option">
					<input type="radio" name="answer" />
					<span class="option-text"></span>
				</div>
			</li>
			<li>
				<div class="option">
					<input type="radio" name="answer" />
					<span class="option-text"></span>
				</div>
			</li>
		</ol>
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
			<button title="Save" onclick="save();">Save</button>
			<button title="Next Question" onclick="nextQ();">Next</button>
			<button title="Previous Question" onclick="prevQ();">Prev</button>
			<button title="Mark for Review" onclick="setReview();">Review</button>
			<button title="Next Section" onclick="nextSection();">Next Section</button>
			<button title="Previous Section" onclick="prevSection();">Prev Section</button>
			<button title="First Question" onclick="firstQ();">First</button>
			<button title="Last Question" onclick="lastQ();">Last</button>
			<button title="Help">Help</button>
		</div>
		
		<div id="final" class="center">
			<button onclick="displayResult();">Submit</button>
		</div>
	</div>
	
</section>

<script src="scripts/tester.js"></script>

<style>
	.test-component {
		padding: 5px;
		border: 1px solid black;
	}
	#currSection {
		color: #32E832;
		margin: 0 20px;
		text-transform: capitalize;
	}
	#nthQuestion {
		color: blue;
	}
	#question {
		height: 200px;
		overflow-y: scroll;
	}
	#question h4 {
		font-size: 18px;
		font-weight: 400;
	}
	.MathJax {
		font-size: 180%;
	}
	#answer {
		background-color: #e6f6ff;
	}
	#answer .option {
		background-color: #ffffff;
		margin: 5px;
		padding: 10px;
		border: 1px solid black;
	}
	#answer .option:hover {
		background-color: #b2b2b2;
	}
	#answer .option.active {
		background-color: #00ff00;
	}
	#answer .option.selected {
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

<?php loadMathjax(); ?>

<script>
	window.onload = function () {
		loadQuestions("https://raw.githubusercontent.com/MustagheesButt/Online-tests/master/NET/set1.json");
		if ( $("section h2").text().indexOf("Timeless") == -1) {
			timer(180);
		} else {
			// unlimited time
			timer(-1);
		}
	}
</script>