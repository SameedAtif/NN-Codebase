<section>
	<h1 class="center">Results</h1>
	<div class="score">
		<h2>You scored <?= $marks ?> in <?= $time_taken ?> minute(s).</h2>
		<p class="score-text"></p>
	</div>
	<h2 class="percentage center"><?= $percentage ?>%</h2>
	
	<!--div>
		<h3>Share your results</h3>
		<ul class="share-buttons">
		  <li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.notesnetwork.org&t=" target="_blank" title="Share on Facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-facebook-square fa-3x"></i></a></li>
		  <li><a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fwww.notesnetwork.org&text=:%20http%3A%2F%2Fwww.notesnetwork.org" target="_blank" title="Tweet" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-twitter-square fa-3x"></i></a></li>
		  <li><a href="https://plus.google.com/share?url=http%3A%2F%2Fwww.notesnetwork.org" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><i class="fa fa-google-plus-square fa-3x"></i></a></li>
		</ul>
		<style>
			ul.share-buttons {
			  list-style: none;
			  padding: 0;
			}

			ul.share-buttons li {
			  display: inline;
			}
			.fa.fa-facebook-square {
				color: #3A5795;
			}
			.fa.fa-twitter-square {
				color: #1da1f2;
			}
			.fa.fa-google-plus-square {
				color: #D73D32;
			}
		</style>
	</div-->
	
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