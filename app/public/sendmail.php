<?php
	require("../includes/PHPMailerAutoload.php");
	
	/* Get POST data */
	$fromName = isset($_POST["name"]) ? $_POST["name"] : "Unknown";
	$fromAddress = isset($_POST["address"]) ? $_POST["address"] : "Unknown Address";
	$subject = isset($_POST["subject"]) ? $_POST["subject"] : "No Subject";
	$bodyMsg = isset($_POST["message"]) ? $_POST["message"] : "Error: no message or variable error.";
	
	/* Verification */
	if ( isset($_POST["security"]) ) {
		$security_array = json_decode($_POST["security"]);
		foreach($security_array as $security) {
			if ($security !== false) {
				die("error: visitor is probably a robot.");
			}
		}
	} else {
		die("something went wrong while checking for security checkboxes in sendmail.php");
	}
	// verify email address
	if (filter_var($fromAddress, FILTER_VALIDATE_EMAIL) === false) {
		die("incorrect email address!");
	}
	
	$mail = new PHPMailer;

	$mail->SMTPDebug = 3;                               // Enable verbose debug output

	// $mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = '';  // Specify main and backup SMTP servers
	// $mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = '';                 // SMTP username
	$mail->Password = '';                           // SMTP password
	// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	// $mail->Port = 587;                                    // TCP port to connect to

	$mail->setFrom($fromAddress, $fromName);
	$mail->addAddress('');    // Add a recipient
	// $mail->addAddress('ellen@example.com', 'Ellen');               // Name is optional
	$mail->addReplyTo($fromAddress);
	// $mail->addCC('cc@example.com');
	// $mail->addBCC('bcc@example.com');

	// $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $bodyMsg;
	// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'success';
	}
?>