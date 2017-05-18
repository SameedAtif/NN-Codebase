<?php
	
	require("../includes/functions.php");
	
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
			if ($value["uri"] == $uri) {
				$Article = $value;
			} else {
				echo "non found"; // 404
			}
		}

		render("header", ["title" => $Article["title"] . " | Articles", "desc" => $Article["desc"]]);
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
			<h1 class="heading-bottom">' . $Article["title"] . '</h1>
			<h2 class="heading-top"><i class="fa fa-eye"></i>&nbsp;&nbsp; ' . $view_count . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-comments"></i>&nbsp;&nbsp; <span class="disqus-comment-count" data-disqus-identifier="/articles/' . $Article["uri"] . '/"></span></h2>
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
						
						echo '<section style="border-bottom: 1px solid lightgray;">
							By ' . $Article["author"] . ' on <date>' . date( "l jS F\, Y", strtotime($Article["date-published"]) ) . '</date>
							</section>';
						
						render("/../templates/articles/" . $Article["template"]);
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
	echo '<script id="dsq-count-scr" src="//notesnetwork.disqus.com/count.js" async></script>';
	render("footer");
?>