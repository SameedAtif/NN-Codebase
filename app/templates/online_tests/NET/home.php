<section class="test-home net">
	<h1 class="center">NET Mockup Test</h1>
	<p>Following test will be a mockup NUST Entry Test aka NET, with full 200 MCQs and all same features with similar interface and options, just faster. Before you begin, make sure you have configured the settings below as you desire.</p>
	
	<h3>Settings</h3>
	<form method="POST" action="otests.php">
		<!-- Identification purposes-->
		<input type="hidden" name="name" value="NET" />
		
		<label>Time Settings:</label>
		<div class="setting">
			<input type="checkbox" name="timed" /><label>Timed</label>
		</div>
		
		<label>Select Subjects:</label>
		
		<div class="setting">
			<input type="checkbox" name="subjects[]" value="math" /><label>Math</label>
		</div>
		<div class="setting">
			<input type="checkbox" name="subjects[]" value="physics" /><label>Physics</label>
		</div>
		
		<div class="setting">
			<select name="subjects[]">
				<option value="chemistry" selected>Chemistry</option>
				<option value="computer">Computer Science</option>
			</select>
		</div>
		
		<div class="setting">
			<input type="checkbox" name="subjects[]" value="english" /><label>English</label>
		</div>
		<div class="setting">
			<input type="checkbox" name="subjects[]" value="intelligence" /><label>Intelligence</label>
		</div>
		
		<h4>You need to keep in mind the following:</h4>
		<ul>
			<li>There are 3 hours(180 min) for 200 MCQs.</li>
			<li>The test consists of 80 Math, 60 Physics, 30 Chemistry/Computer, 20 English and 10 Intelligence MCQs.</li>
			<li>Each MCQ carries 1 point.</li>
			<li>There is no negative marking on wrong answers.</li>
			<li>Once you mark an answer, save it. If you don't save, it will not be counted. You will be able to change it later.</li>
			<li>After time has finished, test session will suspend automatically and <strong>detailed</strong> result will be displayed.</li>
			<li>If you finish before time limit, you can submit your test using the submit button.</li>
		</ul>
		
		<button type="submit" class="ghost-btn">Begin</button>
	</form>
	
</section>