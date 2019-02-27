<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
$mail->SMTPDebug = 3;

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
//Set the hostname of the mail server
$mail->Host = "smtp.abricko.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
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
$mail->addAddress('sahil@gtechinfo.com', 'John Doe');
$mail->AddAddress("dsrp001@gmail.com");  
$mail->AddAddress("marco.montanari@gmail.com"); 
//Set the subject line
$mail->Subject = 'PHPMailer SMTP test';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$fName='SAhil';
$email='dsrp001@gmail.com';
$password='sahil@96';
$message = "hello ,this is test mail";

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
