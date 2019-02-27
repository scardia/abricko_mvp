<?php
/**
 * This example shows making an SMTP connection with authentication.
 */
//Import the PHPMailer class into the global namespace
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Set the hostname of the mail server
$mail->Host = "smtp.abricko.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "AKIAI3ZOJ5VTR4C6Y45Q";
//Password to use for SMTP authentication
$mail->Password = "BJey8kTGnXPUAMCfF75ee+zRhGqIK2Rq3Q+t/jBh36AH";
//Set who the message is to be sent from
$mail->setFrom("contact@abricko.com");
//Set an alternative reply-to address
$mail->addReplyTo("contact@abricko.com", 'Abricko');
//Set who the message is to be sent to
$mail->addAddress('test@gtechinfo.com', 'John Doe');
//Set the subject line
$mail->Subject = 'PHPMailer SMTP test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$fName='SAhil ';
$email='dsrp001@gmail.com';
$password='sahil@96';
$message = "<html><body>Welcome to Abricko   <b>" . $fName . " </b> ! <br >Thank you for joining our growing community of over 2000 smart real
estate professionals who are using data every day to make better decisions.<br></br>";
$message = $message . '' . "<br></br> This is  your account details:<br> Username: " . $email . "<br> Password: " . $password . "<br>";
$message = $message . '<p style="color:#586069!important;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Helvetica,Arial,sans-serif;font-size:12px!important;font-weight:normal;line-height:1.5;margin:0 0 15px;padding:0;text-align:left">
You can always log in to your account on www.abricko.com and change your password when you like.</p></></body></html>';

$mail->Body= $message;
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
$mail->addAttachment('images/phpmailer_mini.png');
//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
