<section id="contact-main">
	<div class="container">
		<h2 class="center">We would <i class="fa fa-heart-o"></i> to hear from you!</h1>
		<p class="center">Want to request something, report a mistake or just provide some feedback? It's simple.</p>
		<form onsubmit="submitForm(); return false;">
			<input type="text" name="name" placeholder="Name" required />
			<input type="text" name="subject" placeholder="Subject" />
			<input type="email" name="email" placeholder="Your Email address" required />
			<textarea name="msg" placeholder="Your message" required></textarea>
			</br></br>
			<!-- 3 checkboxes for security -->
			<input type="checkbox" name="sec[]"/>
			<input type="checkbox" name="sec[]"/>
			<input type="checkbox" name="sec[]"/>
			
			<button type="submit" class="ghost-btn">SEND <i class="fa fa-lg fa-send-o"></i></button>
		</form>
	</div>
</section>

<section>
	<h2 class="center">Or just email us at <a href="mailto: support@notesnetwork.org" class="dark">support@notesnetwork.org</a></h2>
</section>

<script src="scripts/form.js"></script>