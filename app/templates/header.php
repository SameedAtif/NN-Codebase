<!DOCTYPE html>
<html>
    <head>
        <title><?= $title ?></title>
        <base href="/app/public/">
		<meta name="description" content="<?php if ( isset($desc) ) { echo $desc; } else { echo "A place where you can find high quality study materials like notes, guides, past papers, mockup entry tests and way much more, for matric and intermediate students."; }  ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="keywords" content="notes,past papers,online tests,nust,second year,matric,fsc,intermediate" />
		<link rel="icon" 
				type="image/png" 
				href="./images/favicon.png">
		
    </head>
    <body>
		<nav>
			<div class="nav-top">
				<a href="./"><img src="./images/logo.png" class="logo" /></a>
			</div>
			
			<div class="nav-bottom">
				<div class="menu-btn"><i class="fa fa-bars"></i></div>
				<ul class="nav-menu no-padding">
					<li><a href="./notes.php">Notes</a>
						<ul class="dropdown">
							<li><a href="./notes.php?grade=9th">9th</a></li>
							<li><a href="./notes.php?grade=10th">10th</a></li>
							<li><a href="./notes.php?grade=first-year">First Year</a></li>
							<li><a href="./notes.php?grade=second-year">Second Year</a></li>
						</ul>
					</li>
					<li><a href="./past_papers.php">Past Papers</a>
						<ul class="dropdown">
							<li><a href="./past_papers.php?grade=9th">9th</a></li>
							<li><a href="./past_papers.php?grade=10th">10th</a></li>
							<li><a href="./past_papers.php?grade=first-year">First Year</a></li>
							<li><a href="./past_papers.php?grade=second-year">Second Year</a></li>
						</ul>
					</li>
					<li><a href="#">Articles</a></li>
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
				</ul>
			</div>
		</nav>