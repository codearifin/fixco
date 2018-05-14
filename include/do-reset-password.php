<?php 
	@session_start();	
	require('../config/connection.php');
	require('webconfig-parameters.php');
	require('../config/myconfig.php');
	
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$query = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' and `status`='Active' and `member_category` = 'REGULAR MEMBER' ");
	$jumpage = $query->num_rows;
	if($jumpage > 0):

					$password = "";
					$length=15;
					// define possible characters
					$possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
					$i = 0; 

	  				// add random characters to $password until $length is reached
	  				while ($i < $length) { 
						$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
						if (!strstr($password, $char)) { 
		  				$password .= $char;
		 				 $i++;
						}
					}
				
					 $newpass = substr($password,0,10);
					 //setpassword
					 $password1 = sha1($newpass.'MemberRegisterDusdusan.com2014');
					 $password2 = sha1($password1);
					 $password3 = md5($password2);
					 $passwordNew = sha1($password3);
	
					$query2 = $db->query("UPDATE `member` SET `password` = '$passwordNew' WHERE `email`='$email' and `status`='Active' and `member_category` = 'REGULAR MEMBER' ");
					if($query2):
						require('resetpass-email.php');	
					endif;
					
					$_SESSION['error_msg']='password-reseted';
					echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';
						
	else:
		$_SESSION['error_msg']='email-invalid';
		echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';			
	endif;	

?>