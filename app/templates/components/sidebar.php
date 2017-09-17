<aside class="sidebar">	
	<div class="side-element" id="random-quote">
		<blockquote></blockquote>
		<cite></cite>
	</div>

	<div class="side-element" id="fb-widget">
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
					echo "<li><a href='" . $ele["url"] . "'>" . $ele["title"] . "</a></li>";
				}
			?>
		</ol>
	</div>
	
</aside>
<div class="clear-fix"></div>