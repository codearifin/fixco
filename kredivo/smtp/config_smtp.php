<?php
require 'PHPMailer-master/class.phpmailer.php';
require 'PHPMailer-master/PHPMailerAutoload.php';

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                                 			 // Enable verbose debug output
$mail->isSMTP();                                      			 // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';  					  			 // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               			 // Enable SMTP authentication
$mail->Username = 'no-reply@fixcomart.com';         			 // SMTP username
$mail->Password = 'abvsulkwwqsoqnqz';                			 // SMTP password
$mail->SMTPSecure = 'tls';                            			 // Enable TLS encryption, `ssl` also accepted ssl = 465 tls = 587
$mail->Port = 587;                                     			 // TCP port to connect to
$mail->setFrom('no-reply@fixcomart.com', 'Fixcomart');
?>