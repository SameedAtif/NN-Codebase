<?php

	/**
	 ** RENDER FUNCTION - Renders a template
	 **/
	
	function render($template, $data = []) {
		// if it is an HTML file, then DO NOT add .php at the end of it
		if ( preg_match("/.html$/", $template) ) {
			$path = __DIR__ . "/../templates/" . $template;
		} else {
			$path = __DIR__ . "/../templates/" . $template . ".php";
		}
		
		if ( file_exists($path) ) {
			extract($data);
			require($path);
		}
	}
	
	/**
	 ** TITLE GENERATOR - generates title for served content
	 **/
	function gen_title($chapter, $subject, $grade, $type) {
		if ( strpos($chapter, "-") !== false ) {
			$data = explode("-", $chapter);
			$chapter_name = str_replace("ch", ": Chapter ", $data[sizeof($data) - 1]);
			array_pop($data);
			$chapter = implode(" ", $data);
			$chapter = $chapter . $chapter_name;
		} else {
			$chapter = str_replace("ch", "Chapter ", $chapter);
		}
		$title["chapter"] = ucwords( $chapter );
		
		$subjects = ["physics" => "Physics", "math" => "Mathematics", "computer" => "Computer Science",
				"chemistry" => "Chemistry", "biology" => "Biology", "english" => "English",
				"urdu" => "Urdu", "pakstudy-urdu" => "Pak. Study (Urdu)", "pakstudy-english" => "Pak. Study (English)"];
		$title["subject"] = $subjects[$subject];
		
		switch ($grade) {
			case "first-year":
				$title["grade"] = "First Year";
				break;
			case "second-year":
				$title["grade"] = "Second Year";
				break;
			default:
				$title["grade"] = $grade;
		}
		
		if ( strpos($type, "ex") !== false ) {
			$title["type"] = str_replace("ex", "Exercise ", $type);
		} else {
			$title["type"] = str_replace("-", " ", $type);
		}
		return $title;
	}
	
	/**
	 ** TOOLS - takes in 3 parameters. t1 for Alphabet filter. t2 for Text filter. And t3 for important hightlighter
	 **/
	$tool_alphabet = "<div class='tool'><select class='filter-alphabet' type='text' placeholder='FILTER'>
			<option selected disabled style='display: none;'>Filter By Alphabet</option>
			<option>All</option>
			<option>A</option><option>B</option><option>C</option><option>D</option>
			<option>E</option><option>F</option><option>G</option><option>H</option>
			<option>I</option><option>J</option><option>K</option><option>L</option>
			<option>M</option><option>N</option><option>O</option><option>P</option>
			<option>Q</option><option>R</option><option>S</option><option>T</option>
			<option>U</option><option>V</option><option>W</option><option>X</option>
			<option>Y</option><option>Z</option>
		<select></div>";
	$tool_filter_text = "<div class='tool'><input class='filter-text' type='text' placeholder='FILTER' /></div>";
	$tool_imp_highlighter = "<div class='tool'><button class='ghost-btn imp-highlighter'>Highlight Important</button></div>";
	
	function tools($t1, $t2, $t3) {
		global $tool_alphabet, $tool_filter_text, $tool_imp_highlighter;
		
		echo "<div class='tools'>";
		
		if ($t1 == "f_alphabet") {
			echo $tool_alphabet;
		} elseif ($t1 != null) {
			echo "First parameter is supposed to be f_alphabet or, in case if Alphabet filter is not required, null.";
		}
		
		if ($t2 == "f_text") {
			echo $tool_filter_text;
		} elseif ($t2 != null) {
			echo "Second parameter is supposed to be f_text or, in case if Text filter is not required, null.";
		}
		
		if ($t3 == "imp_highlighter") {
			echo $tool_imp_highlighter;
		} elseif ($t3 != null) {
			echo "Third parameter is supposed to be imp_highlighter or, in case if Important Highlighter is not required, null.";
		}
		
		echo "</div>";
	}
	
	/**
	 ** Short/Long Questions Renderer
	 **/
	function render_questions($path) {
		if ( file_exists($path) ) {
			$data = file_get_contents($path);
			$questions = json_decode($data);
			
			foreach($questions as $key=>$value) {
				($value->imp == true) ? print("<div class='question important'>") : print("<div class='question'>");
				echo "<h3 class='statement'><span>" . $value->no . "</span> " . $value->statement . "</h3>";
				echo "<h4 class='answer'>" . $value->answer . "</h4>";
				echo "</div>";
			}
			
		} else {
			echo "$path -> Path not found!";
		}
	}

	/**
	 ** Render Images - can be used for both rendering images-based notes and past papers
	 **/
	function render_images($_category, $_grade, $_subject, $_chapter, $_type) {
		if ($_category == "past_papers") {
			$dir = "images/$_category/$_grade/$_subject/$_chapter/"; // in case of past papers chapter is the year
			$files = array_diff(scandir($dir), array('..', '.'));

			foreach ($files as $file) {
				$alt_tag = "";
				if ( strpos($file, "-lhr") != false ) {
					$alt_tag = "Lahore";
				} elseif ( strpos($file, "-gjw") != false ) {
					$alt_tag = "Gujranwala";
				} else {
					$alt_tag = "Unknown";
				}
				echo "<img src='$dir$file' class='scanned-img' alt='$alt_tag Board' />";
			}
		} else {
			$dir = "images/$_category/$_grade/$_subject/$_chapter/$_type/";
			$files = array_diff(scandir($dir), array('..', '.'));
			foreach ($files as $file) {
				echo "<img src='$dir$file' class='scanned-img' />";
			}
		}
	}
	
	/**
	 ** For rendering Mathematics
	 **/
	function loadMathjax() {
		echo '<script type="text/x-mathjax-config">
		MathJax.Hub.Config({
			tex2jax: {
				inlineMath: [ ["\\\(", "\\\)"] ]
			},
			displayAlign: "left",
			showProcessingMessages: false,
			showMathMenu: false
		});
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-MML-AM_CHTML" async></script>';
	}

	/**
	 ** For rendering Sidebar
	 **/
	function loadSidebar() {
		echo '<aside class="sidebar">
			
			<div class="side-element" id="fb-widget">
				<!-- FB JS SDK is now in header -->
				<!-- Facebook Page Plugin -->
				<div class="fb-page" data-href="https://www.facebook.com/notesnetworkofficial/" data-tabs="" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/notesnetworkofficial/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/notesnetworkofficial/">NotesNetwork</a></blockquote></div>
			</div>
			
			<div class="side-element" id="side-ad">
				<h5>ADVERTISEMENT</h5>
			</div>
			
			<div class="side-element" id="trending">
				<h4>TRENDING NOW</h4>
				<ol class="no-bullets dark-links">';
						$data = get_trending_data();
						forEach ($data as $key => $ele) {
							if ($key >= 14) {
								break;
							}
							echo "<li><a href='" . $ele->url . "'>" . $ele->title . "</a></li>";
						}
				echo '</ol>
			</div>
			
		</aside>
		<div class="clear-fix"></div>
		';
	}
	/**
	 ** For rendering Comments section
	 **/
	function loadCommentsSection() {
		echo "<!-- COMMENTS SECTION - Powered by Disqus -->\n<div id=\"disqus_thread\"></div>\n
	<script>var disqus_config = function () {";

		$uniform_URI = str_replace(["?i=1", "&i=1", "/app/public"], "", $_SERVER['REQUEST_URI']);
		echo "this.page.url = \"http://notesnetwork.org" . $uniform_URI . "\";\n";  // Replace PAGE_URL with your page's canonical URL variable
		echo "this.page.identifier = \"" . $uniform_URI . "\";"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable

		echo '};(function() { // DON\'T EDIT BELOW THIS LINE
	var d = document, s = d.createElement(\'script\');
	s.src = \'https://notesnetwork.disqus.com/embed.js\';
	s.setAttribute(\'data-timestamp\', +new Date());
	(d.head || d.body).appendChild(s);
	})();
	</script>
	<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>';
	}

	function loadConfirmationBox() {
		echo '<div class="confirmation-box" style="display: none;">
					<div>
						<div>Are You Sure You Want to Submit?</div>
						<div class="confirmation-buttons">
							<span class="true">yes</span>
							<span class="false">no</span>
						</div>
					</div>
				</div>';
	}
	/**
	 ** TRENDING NOW
	 **/
	function update_trending_data($url, $title) {
		$data = json_decode( file_get_contents("./data/trending_now.json"), true );
		
		// remove ?i=1 or &i=1 if present
		$url = preg_replace("/\?i=[0-9]$/", "", $url);
		$url = preg_replace("/&i=[0-9]$/", "", $url);
		
		// see if URL already has an entry
		$exists = false; $index = -1;
		forEach ($data as $key => $value) {
			if ($value["url"] == $url) {
				$exists = true;
				$index = $key;
			}
		}
		
		if ($exists == true) {
			if ( isset( $data[$index]["hits"][ date("Y-m-d") ] ) ) { // checking if record for current data exists
				// record exists
				$data[$index]["hits"][ date("Y-m-d") ]++;
				// greater than 15 so removing last (just in case!)
				if ( count($data[$index]["hits"]) > 15 ) {
					array_shift($data[$index]["hits"]);
				}
				// updating score
				$sum = 0;
				forEach($data[$index]["hits"] as $value) {
					$sum += $value;
				}
				$data[$index]["score"] = $sum;
			} else {
				// record exists but record for current date doesnt
				$data[$index]["hits"][date("Y-m-d")] = 1;
				// greater than 15 so removing last
				if ( count($data[$index]["hits"]) > 15 ) {
					array_shift($data[$index]["hits"]);
				}
				// updating score
				$sum = 0;
				forEach($data[$index]["hits"] as $value) {
					$sum += $value;
				}
				$data[$index]["score"] = $sum;
			}
		} else {
			// entry does not exist
			array_push($data, array(
				"title" => $title,
				"url" => $url,
				"hits" => array(date("Y-m-d") => 1),
				"score" => 1
			));
		}
		
		// SORTING in DESCENDING ORDER
		usort($data, function($a, $b) { //Sort the array using a user defined function
			return $a["score"] > $b["score"] ? -1 : 1; //Compare the scores
		});
		
		if ($data == null) {
			echo "Something went wrong. Trending data was null. Update failed!";
			$txt = "A null error was detected at " . date("Y-m-d");
			file_put_contents('./data/trending_error.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
		} else {
			file_put_contents( "./data/trending_now.json", json_encode($data) );
		}
	}
	
	// return data of top objects in trending_now.json
	function get_trending_data() {
		return json_decode( file_get_contents("./data/trending_now.json") );
	}

	/**
	 ** ARTICLE VIEWS
	 **/
	function update_article_views($uri, $title) {
		$data = json_decode( file_get_contents("./data/articles/views_data.json"), true );
		
		// see if URI already has an entry
		$exists = false; $index = -1;
		forEach ($data as $key => $value) {
			if ($value["uri"] == $uri) {
				$exists = true;
				$index = $key;
			}
		}
		
		$views_count = 1; // for returning
		if ($exists == true) {
				$views_count = ++$data[$index]["views"];
		} else {
			array_push($data, array(
				"uri" => $uri,
				"title" => $title,
				"views" => 1
			));
		}
		
		// SORTING in DESCENDING ORDER
		usort($data, function($a, $b) { //Sort the array using a user defined function
			return $a["views"] > $b["views"] ? -1 : 1; //Compare the scores
		});
		
		if ($data == null) {
			echo "Something went wrong. Article views data was null. Update failed!";
			$txt = "A null error was detected at " . date("Y-m-d");
			file_put_contents('./data/articles/views_data_error.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
			die("Article views update failed!");
		} else {
			file_put_contents( "./data/articles/views_data.json", json_encode($data) );
		}

		// return views count
		return $views_count;
	}
	
	// return data of top objects in trending_now.json
	function get_article_views($uri) {
		$data = json_decode( file_get_contents("./data/articles/views_data.json") );
		forEach ($data as $key => $value) {
			if ($value["uri"] == $uri) {
				return $value["views"];
			} else {
				echo "URI not found!";
				return -1;
			}
		}
	}

	/**
	 * Database and Login Functions
	 *
	 */

	/** Database Query function **/
	function query(/* $sql [, ... ] */) {
		// SQL statement
		$sql = func_get_arg(0);

		// parameters, if any
		$parameters = array_slice(func_get_args(), 1);
		// try to connect to database
        static $handle;
        if (!isset($handle))
        {
            try
            {
                // connect to database
                $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, DB_USERNAME, DB_PASSWORD);
 
                // ensure that PDO::prepare returns false when passed invalid SQL
                $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            }
            catch (Exception $e)
            {
                // trigger (big, orange) error
                trigger_error($e->getMessage(), E_USER_ERROR);
                exit;
            }
        }
 
        // prepare SQL statement
        $statement = $handle->prepare($sql);
        if ($statement === false)
        {
            // trigger (big, orange) error
            trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }
 
        // execute SQL statement
        $results = $statement->execute($parameters);
 
        // return result set's rows, if any
        if ($results !== false)
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        else
            return false;
	}

	/** LOGOUT **/
	function logout() {
		// unset any session variables
		$_SESSION = [];

		// expire cookie
		if (!empty($_COOKIE[session_name()]))
		{
			setcookie(session_name(), "", time() - 42000);
		}

		// destroy session
		session_destroy();
	}

	/**
	* Redirects user to destination, which can be
	* a URL or a relative path on the local host.
	*
	* Because this function outputs an HTTP header, it
	* must be called before caller outputs any HTML.
	*/
	function redirect($destination)
	{
		// handle URL
		if (preg_match("/^https?:\/\//", $destination))
		{
			header("Location: " . $destination);
		}
		// handle absolute path
		else if (preg_match("/^\//", $destination))
		{
			$protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
			$host = $_SERVER["HTTP_HOST"];
			header("Location: $protocol://$host$destination");
		}
		// handle relative path
		else
		{
			// adapted from http://www.php.net/header
			$protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
			header("Location: $protocol://$host$path/$destination");
		}
		// exit immediately since we're redirecting anyway
		exit;
	}

	function calculate_score($all_questions, $subjects, $user_answers) {
		$score = 0;
		if (count($all_questions) != count($user_answers))
		{
			echo "questions and answers arrays are not of the same size :(</br>";
			return -1;
		}
		foreach ($all_questions as $key => $value) {
			if (!in_array($value["subject"], $subjects)) {
				unset($all_questions[$key]); // keep previous indices
			}
		}
		foreach ($all_questions as $key => $value) {
			if ($value["answer"] === $user_answers[$key]) {
				$score++;
			}
		}
		return $score;
	}
?>