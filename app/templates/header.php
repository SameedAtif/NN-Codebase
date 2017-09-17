<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>
        <base href="<?= SITE_BASE_LINK ?>">
		<meta name="description" content="<?php if ( isset($desc) ) { echo $desc; } else { echo "A place where you can find high quality study materials like notes, guides, past papers, mockup entry tests and way much more, for matric and intermediate students."; }  ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="keywords" content="notes,past papers,online tests,nust,second year,matric,fsc,intermediate" />
		<?php
			if (isset($injection))
				foreach ($injection as $element)
					echo $element;
		?>
		<link rel="icon" 
				type="image/png" 
				href="./images/favicon.png">
		<!-- FB JS SDK -->
		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '1856099134626810',
		      cookie     : true,
		      xfbml      : true,
		      version    : 'v2.9'
		    });
		    FB.AppEvents.logPageView();
		  };

		  (function(d, s, id){
		     var js, fjs = d.getElementsByTagName(s)[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement(s); js.id = id;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     fjs.parentNode.insertBefore(js, fjs);
		   }(document, 'script', 'facebook-jssdk'));
		</script>
    </head>
    <body>
		<nav>
			<div class="nav-top">
				<a href="./"><img src="./images/logo.png" class="logo" /></a>
			</div>
			
			<div class="nav-bottom">
				<div class="menu-btn"><i class="fa fa-bars"></i></div>
				<ul class="nav-menu no-padding">
					<li><a href="notes/">Notes</a>
						<ul class="dropdown">
							<li><a href="notes/9th/">9th</a></li>
							<li><a href="notes/10th/">10th</a></li>
							<li><a href="notes/first-year/">First Year</a></li>
							<li><a href="notes/second-year/">Second Year</a></li>
						</ul>
					</li>
					<li><a href="past-papers/">Past Papers</a>
						<ul class="dropdown">
							<li><a href="past-papers/9th/">9th</a></li>
							<li><a href="past-papers/10th/">10th</a></li>
							<li><a href="past-papers/first-year/">First Year</a></li>
							<li><a href="past-papers/second-year/">Second Year</a></li>
						</ul>
					</li>
					<li><a href="articles/">Articles</a></li>
					<li><a href="online-tests/">Online Tests</a>
						<ul class="dropdown">
							<li><a href="online-tests/NET/">NET</a></li>
							<li><a href="online-tests/SAT/">SAT</a></li>
						</ul>
					</li>
					<li><a href="./contact.php">Contact</a>
						<ul class="dropdown">
							<li><a href="./contact.php?about">About Us</a></li>
						</ul>
					</li>
					<li><a href="./contact.php?privacy_policy">Privacy Policy</a></li>
					<li class="login-register"><?php
						if ( isset($_SESSION["username"]) ) {
							$row = query("SELECT * FROM users WHERE username = ?", $_SESSION["username"])[0];
							echo "<a>" . $row["full_name"] . " &nbsp;<i class=\"fa fa-angle-down\"></i></a>
						<ul class=\"dropdown\">
							<li><a href=\"account/\">My Account</a></li>
							<li><a href=\"profile/" . $_SESSION["username"] . "/\">Public Profile</a></li>
							<li><a id=\"logout\">Logout</a></li>
						</ul>";
						}
						else
							echo "<button class=\"login-btn btn--facebook\">Login with Facebook</button>";
					?>
					
					</li>
				</ul>
			</div>
		</nav>