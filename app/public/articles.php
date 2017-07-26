<?php
	
	require("../includes/config.php");
	
	/**
	 ** ARTICLES.PHP serves our published articles
	 **/
	
	$mode = "default";
	$Article;

	if ( isset($_GET["uri"]) ) {
		$mode = "article";
	}
	
	if ($mode == "article") {
		$uri = $_GET["uri"];
		$articles = json_decode( file_get_contents("./data/articles/articles.json"), true );
		foreach ($articles as $key => $value) {
			if ($value["uri"] == $uri)
				$Article = $value;
		}

		render("header", ["title" => $Article["title"] . " | Articles", "desc" => $Article["desc"],
			"injection" => ["<meta property=\"og:image\" content=\"http://notesnetwork.org/images/articles/" . $Article["uri"] . "/header-bg.jpg\" />", "<script type=\"text/javascript\" src=\"//platform-api.sharethis.com/js/sharethis.js#property=59202e17baf27a00129fc5cb&product=inline-share-buttons\" async></script>"]]);
	} else {
		// Render head
		render("header", ["title" => "Articles", "desc" => "Find all articles published by NotesNetwork here. How-to tutorials, guides, programming solutions and more."]);
	}
	
	// header
	if ( $mode == "article" ) {
		$view_count = update_article_views($Article["uri"], $Article["title"]);
		echo'
    <header class="content-header">
		<div class="content-main-header" style="background-image: url(\'images/articles/' . $Article["uri"] . '/header-bg.jpg\');">
			<h1 class="heading-large">' . $Article["title"] . '</h1>
			<h2 class="heading-subheading">' . $Article["desc"] . '</h2>
		</div>
	</header>
	';
	}

?>
	
    <main class="sided dark-links blog">
		
		<section>
			
			<?php
				
				// being a good citizen
				if ($mode == "article") {
					if ( file_exists(__DIR__ . "/../templates/articles/" . $Article["template"]) ) {
						update_trending_data("http://notesnetwork.org" . $_SERVER['REQUEST_URI'], $Article["title"]);
						
						echo '<article>
							<section style="border-bottom: 1px solid lightgray; color: rgba(151, 151, 151, 1);">
							<p>By '
								. $Article["author"] . ' on <date>' . date( "l jS F\, Y", strtotime($Article["date-published"]) ) . '</date></br>
								<i class="fa fa-eye"></i>&nbsp;&nbsp; ' . $view_count . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-comments"></i>&nbsp;&nbsp; <span class="disqus-comment-count" data-disqus-identifier="/articles/' . $Article["uri"] . '/"></span>
							</p>
							</section>';
						
						render("/../templates/articles/" . $Article["template"]);

						echo '</article>
						<div class="sharethis-inline-share-buttons"></div>';
					} else {
						echo "<h2>The File does not exists! The path provided probably does not exists.</h2>"; // 404
						echo __DIR__ . "/../templates/articles/" . $Article["template"];
					}
				} else {
					render("/../templates/articles/default");
				}
				
			?>
			
		</section>
		
	</main>
	
<?php
	loadSidebar();
	if ($mode == "article")
		loadCommentsSection();
	
	loadMathjax();
	
	render("footer", ["injection" => ["<script id=\"dsq-count-scr\" src=\"//notesnetwork.disqus.com/count.js\" async></script>"]]);
?>