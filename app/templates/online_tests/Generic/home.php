<section class="test-home">
	<h1 class="center"><?= $test_title ?></h1>
	<p>This test will contain 40 <?= $test_title ?> questions. You will have about 40 minutes to complete it. You may spend as much time on a question as you want, but remember that total time is 40 minutes. You can start anytime by pressing the <strong>Begin</strong> button at the bottom.</p>
	
	<form method="POST" action="online-tests/">
		<!-- Identification purposes-->
		<input type="hidden" name="name" value=<?= $test ?> />
		<?php //<input type="hidden" name="timed" value="true" />
		?>
		
		<h4>You need to keep in mind the following:</h4>
		<ul>
			<li>There are 40 minutes for 40 MCQs.</li>
			<li>Each MCQ carries 1 point.</li>
			<li>There is no negative marking on wrong answers.</li>
			<li>Once you mark an answer, it will be automatically saved.</li>
			<li>After time has finished, test session will suspend automatically and <strong>detailed</strong> result will be displayed.</li>
			<li>If you finish before time limit, you can submit your test using the submit button.</li>
		</ul>
		
		<button type="submit" class="ghost-btn">Begin</button>
	</form>
	
</section>