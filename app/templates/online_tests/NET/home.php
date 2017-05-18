<section class="test-home net">
	<h2>Ready?</h2>
	<p>Once ready, click the below button to begin!</p>
	<form method="POST" action="online-tests/">
		<!-- Identification purposes-->
		<input type="hidden" name="name" value="NET" />

		<div class="center">
			<button type="submit" class="ghost-btn btn-green btn-lg">Begin</button>
		</div>
		
		<p>It is recommended that, before starting, you read the rules stated below.</p>
		<p>To change between Chemistry and Computer Science, modify the settings below. You can also include/exclude other subjects.</p>
		<p>To save your results and to get in-depth analytics, sign-in before starting.</p>
		
		<div class="accordion-closed">
			<h3><i class="fa fa-angle-right"></i> Settings</h3>
			<div>
				<label>Time Settings:</label>
				<div class="setting">
					<input type="checkbox" name="timed" value="on" checked="checked" /><label>Timed</label>
				</div>
				
				<label>Select Subjects:</label>
				
				<div class="setting">
					<input type="checkbox" name="subjects[]" value="math" checked="checked" /><label>Math</label>
				</div>
				<div class="setting">
					<input type="checkbox" name="subjects[]" value="physics" checked="checked" /><label>Physics</label>
				</div>
				
				<div class="setting">
					<select name="subjects[]">
						<option value="chemistry" selected>Chemistry</option>
						<option value="computer">Computer Science</option>
					</select>
				</div>
				
				<div class="setting">
					<input type="checkbox" name="subjects[]" value="english" checked="checked" /><label>English</label>
				</div>
				<div class="setting">
					<input type="checkbox" name="subjects[]" value="intelligence" checked="checked" /><label>Intelligence</label>
				</div>
			</div>

			<h3><i class="fa fa-angle-right"></i> Rules</h3>
			<div>
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
			</div>
		</div>
		
	</form>
	
</section>