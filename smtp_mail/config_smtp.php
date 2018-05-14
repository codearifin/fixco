<?php
require 'PHPMailer-master/class.phpmailer.php';
require 'PHPMailer-master/PHPMailerAutoload.php';

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                                 			 // Enable verbose debug output
$mail->isSMTP();                                      			 // Set mailer to use SMTP
$mail->Mailer = "smtp";
$mail->Host = 'mail.smtp2go.com';  					  			 // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               			 // Enable SMTP authentication
$mail->Username = 'cs@fixcomart.com';         			 // SMTP username
$mail->Password = 'F1xc0#234';                			 		// SMTP password
$mail->SMTPSecure = 'tls';                            			 // Enable TLS encryption, `ssl` also accepted ssl = 465 tls = 587
$mail->Port = 587; 											// 8025, 587 and 25 can also be used. Use Port 465 for SSL.
//$mail->Port = 465;                                     			 // TCP port to connect to
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->setFrom('no-reply@fixcomart.com', 'Fixcomart');
$mail->addAddress($emailmember, $orderemailmember);
if($emailmaster): 
	$mail->addCC($emailmaster,$orderemailmember);	
endif;
?>