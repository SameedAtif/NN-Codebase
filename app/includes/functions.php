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
		
		switch ($subject) {
			case "physics":
				$title["subject"] = "Physics";
				break;
			case "math":
				$title["subject"] = "Mathematics";
				break;
			case "computer":
				$title["subject"] = "Computer Science";
				break;
			case "chemistry":
				$title["subject"] = "Chemistry";
				break;
			case "biology":
				$title["subject"] = "Biology";
				break;
			case "english":
				$title["subject"] = "English";
				break;
			case "urdu":
				$title["subject"] = "Urdu";
				break;
			case "pakstudy-urdu" || "pakstudy-english":
				$title["subject"] = "Pak. Study";
				break;
		}
		
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
			$title["type"] = "Exercise " . (float) filter_var( $type, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
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
	 ** For rendering Mathematics
	 **/
	function loadMathjax() {
		echo '<script type="text/javascript" async src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_CHTML"></script>
	<script>
		window.onload = function () {
			MathJax.Hub.Config({
				tex2jax: {
					inlineMath: [ ["\\\(", "\\\)"] ]
				},
				displayAlign: "left",
				showProcessingMessages: false,
				showMathMenu: false
			});
		}
	</script>';
	}
	
	/**
	 ** TRENDING NOW
	 **/
	function update_trending_data($url, $title) {
		$data = json_decode( file_get_contents("./data/trending_now.json"), true );
		
		// remove ?i=1 or &i=1 if present
		$url = str_replace("?i=1", "", $url);
		$url = str_replace("&i=1", "", $url);
		
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
		
		file_put_contents( "./data/trending_now.json", json_encode($data) );
	}
	
	// return data of top objects in trending_now.json
	function get_trending_data() {
		return json_decode( file_get_contents("./data/trending_now.json") );
	}
	
?>