<h1 class="center">Articles</h1>
<?php
	
	// load all articles data
	$data = json_decode( file_get_contents("./data/articles/articles.json"), true );

	foreach ($data as $key => $value) {
		echo "<section class=\"post\">
				<a href=\"http://" . $_SERVER["HTTP_HOST"] . preg_replace("/[&?]i=[0-9]/", "", $_SERVER["REQUEST_URI"]) .  $value["uri"] . "/\"><h2>" . $value["title"] . "</h2></a>
				<h4 class=\"description\">" . $value["desc"] . "</h4>
				Published on <date>" . $value["date-published"] . "</date> by " . $value["author"] . "
			</section>";
	}
?>