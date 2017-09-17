<!-- COMMENTS SECTION - Powered by Disqus -->
<div id="disqus_thread"></div>
<script>
	var disqus_config = function () {
	<?php
		$uniform_URI = str_replace(["?i=1", "&i=1", "/app/public"], "", $_SERVER['REQUEST_URI']);
		echo "this.page.url = \"http://notesnetwork.org" . $uniform_URI . "\";\n";  // Replace PAGE_URL with your page's canonical URL variable
		echo "this.page.identifier = \"" . $uniform_URI . "\";"; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
	?>
	};(function() { // DON\'T EDIT BELOW THIS LINE
	var d = document, s = d.createElement('script');
	s.src = 'https://notesnetwork.disqus.com/embed.js';
	s.setAttribute('data-timestamp', +new Date());
	(d.head || d.body).appendChild(s);
	})();
</script>
<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>