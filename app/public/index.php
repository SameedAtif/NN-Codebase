<?php 
	require("../includes/config.php");

	render("header", ["title" => "NotesNetwork",
								"desc" => "A place where you can find high quality study materials including notes, guides, past papers, mockup entry tests and way much more, for matric and intermediate students."]);
?>

    <main>
		
		<header class="jumbotron">
			<div class="left">
				<h1>The Best Notes, Past Papers and Online Tests <small>anywhere, anytime</small></h1>
			</div>
			<div class="right">
				<!-- Facebook Page Plugin -->
				<div class="fb-page" data-href="https://www.facebook.com/notesnetworkofficial/" data-tabs="" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/notesnetworkofficial/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/notesnetworkofficial/">NotesNetwork</a></blockquote></div>
			</div>
		</header>
		
		<section class="trending-widget">
			<h2>Popular These Days</h2>
			<ol class="no-bullets dark-links">
				<?php
					$data = get_trending_data();
					forEach ($data as $key => $ele) {
						if ($key >= 8) {
							break;
						}
						echo "<li><a href='" . $ele["url"] . "'>" . $ele["title"] . "</a></li>";
					}
				?>
			</ol>
		</section>
		
		<section class="cards">
			<div class="card">
				<h1>Notes</h1>
				<h4>Self-explaining, easy-to-understand, detailed notes of all subjects.</h4>
				<a href="notes/"><button class="ghost-btn btn-blue">GO</button></a>
			</div>
			<div class="card">
				<h1>Past Papers</h1>
				<h4>Archives of previous Lahore and Gujranwala board papers.</h4>
				<a href="past-papers/" class="btn"><button class="ghost-btn btn-blue">GO</button></a>
			</div>
			<div class="card">
				<h1>Online Tests</h1>
				<h4>Test your knowledge and skills or prepare for exams with our modern testing application. </h4>
				<a href="online-tests/" class="btn"><button class="ghost-btn btn-blue">GO</button></a>
			</div>
		</section>
		
		<section class="features">
			<h3>What makes us different?</h3>
			
			<div class="flex feature-flex">
				<div class="flex-child">
					<h3>Speed</h3>
					<p>Our high quality content loads at lightning fast speeds, therefore you don't have to wait.</p>
				</div>
				<div class="flex-child">
					<h3>Quality</h3>
					<p>High quality content is really the core of our services.</p>
				</div>
				<div class="flex-child">
					<h3>Tools</h3>
					<p>Our advanced tools make it easier to find and manage what you want.</p>
				</div>
			</div>
		</section>
		
		<section class="motto">
			<h3 class="center">&ldquo;Facilitating students for tomorrow...&rdquo;</h3>
		</section>
		
	</main>

<?php render("footer"); ?>