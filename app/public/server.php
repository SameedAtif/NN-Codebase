<?php
	
	require("../includes/functions.php");
	
	/**
	 ** SERVER.PHP serves content which user requests. For that, it needs 4 parameters: category(notes, past papers, etc), grade, subject, type(SQ, LQ, MCQs, Numericals)
	 **/
	
	$category = $_GET["category"];
	global $grade,
	$subject,
	$chapter,
	$type,
	$year,
	$path;
	
	if ($category == "notes") {
		
		$grade = $_GET["grade"];
		$subject = $_GET["subject"];
		$chapter = $_GET["chapter"];
		$type = $_GET["type"];
		
	} elseif ($category == "past_papers") {
		
		$grade = $_GET["grade"];
		$subject = $_GET["subject"];
		$year = $_GET["year"];
		
	} else {
		die("Unknown Category");
	}
	
	// Make Title - title => chapter, subject, grade, subtitle => type (not only for <title> but also for other elements such as headings)
	$title = "";
	$title_comps = gen_title($chapter, $subject, $grade, $type);
	
	if ( isset($_GET["year"]) ) {
		// if YEAR exists, then we are probably serving past papers
		$year = $_GET["year"];
		$path = $category . "/" . $grade . "/" . $subject . "/" . $year . ".html";
		$title = $year . " Past Papers | " . $title_comps["grade"] . " " . $title_comps["subject"];
		render("header", ["title" => $title,
									"desc" => "Past Papers of " . $title_comps["subject"] . " for the year ". $year . ", both Lahore and Gujranwala board."]);
	} else {
		// if YEAR does not exist then these are probably notes
		$path = $category . "/" . $grade . "/" . $subject . "/" . $chapter . "/" . $type . ".html";
		$title = $title_comps["chapter"] . " " . ucwords($title_comps["type"]) . " | " . $title_comps["grade"] . " " . $title_comps["subject"];
		render("header", ["title" => $title,
									"desc" => ucwords($title_comps["type"]) . " of " . $title_comps["subject"] . " " . $title_comps["chapter"] . ", " . $title_comps["grade"] . "."]);
	}
	
?>
	
	<header class="content-header">
		<div class="content-main-header" style="background-image: url('images/notes/<?= $grade ?>/<?= $subject ?>/header-bg.jpg');">
			<h2><?=  $title_comps["grade"] ?> &dash; <?= $title_comps["subject"] ?></h2>
			<h2><?php  if ( isset($chapter) ) {echo $title_comps["chapter"];} else {echo $year;} ?></h2>
		</div>
		
		<h2 class="type"><?php if ( isset($chapter) ) echo $title_comps["type"]; else echo "Past Papers"; ?></h2>
	</header>
	
    <main class="sided">
		
		<section>
			
			<?php
				if ($type == "idioms") {
					tools("f_alphabet", "f_text", "imp_highlighter");
				}
				else {
					tools(null, "f_text", "imp_highlighter");
				}
				
				// being a good citizen
				if ( file_exists(__DIR__ . "/../templates/" . $path) ) {
					if ($category == "past_papers") {
						echo "<h2>Lahore Board</h2><div class='lahore-board'></div><h2>Gujranwala Board</h2><div class='gujranwala-board'></div><h2>Unknown Board</h2><div class='unknown-board'></div>";
						render($path, ["category" => $category, "grade" => $grade, "subject" => $subject, "chapter" => $chapter, "type" => $type, "year" => $year]);
					} else {
						render($path, ["category" => $category, "grade" => $grade, "subject" => $subject, "chapter" => $chapter, "type" => $type, "year" => $year]);
					}
					update_trending_data("http://notesnetwork.org" . $_SERVER['REQUEST_URI'], $title);
				} else {
					echo "<h2>The File does not exists! The path provided probably does not exists.</h2>";
				}
				
			?>
			
		</section>
		
	</main>
	
	<aside class="sidebar">
		
		<div class="side-element" id="fb-widget">
			<!-- Facebook Like widget -->
			<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1856099134626810";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			<!-- Facebook Page Plugin -->
			<div class="fb-page" data-href="https://www.facebook.com/notesnetworkofficial/" data-tabs="" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/notesnetworkofficial/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/notesnetworkofficial/">NotesNetwork</a></blockquote></div>
		</div>
		
		<div class="side-element" id="side-ad">
			<h5>ADVERTISEMENT</h5>
		</div>
		
		<div class="side-element" id="trending">
			<h4>TRENDING NOW</h4>
			<ol class="no-bullets dark-links">
				<?php
					$data = get_trending_data();
					forEach ($data as $key => $ele) {
						if ($key >= 14) {
							break;
						}
						echo "<li><a href='" . $ele->url . "'>" . $ele->title . "</a></li>";
					}
				?>
			</ol>
		</div>
		
	</aside>
	
	<!-- COMMENTS SECTION - Powered by Disqus -->

<?php
	loadMathjax();
	render("footer");
?>