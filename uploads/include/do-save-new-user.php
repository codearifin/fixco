<?php 
	@session_start();
	require('../config/connection.php');;
	require('../config/myconfig.php');	
	
 	if(isset($_POST['submit'])){

		//member data
		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		$idmember = $ros['idmember_list'];
		
		if($idmember>0):
		
		 $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);		
		 $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		 $divisi = filter_var($_POST['divisi'], FILTER_SANITIZE_STRING);
		 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		 $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);	
		 $mobilephone = filter_var($_POST['mobilephone'], FILTER_SANITIZE_STRING);			
		 $password = $_POST['password'];
		 
		 //setpassword
		 $password1 = sha1($password.'MemberRegisterDusdusan.com2014');
		 $password2 = sha1($password1);
		 $password3 = md5($password2);
		 $passwordNew = sha1($password3);

				$quip304 = $db->query("SELECT `id` FROM `corporate_user` WHERE `email`='$email' ");
				$jumdata = $quip304->num_rows;				
				if($jumdata>0):
					$_SESSION['error_msg']='email-failed';		   
				else:
		 
					 $query = $db->query("INSERT INTO `corporate_user` (`idmember_list`,`name`,`lastname`,`divisi`,`email`,`phone`,`mobile`,`password`,`status`,`status_active`) 
					 VALUES ('$idmember','$name','$lastname','$divisi','$email','$phone','$mobilephone','$passwordNew','0','1')");
					
					 $_SESSION['error_msg']='saveuseroke';
					 
			   endif;
		
		else:
			$_SESSION['error_msg']='toikenUrelfail'; 			
		endif;

		 
		 echo'<script type="text/javascript">window.location="'.$SITE_URL.'user-management"</script>';
		 			
    }	
?>