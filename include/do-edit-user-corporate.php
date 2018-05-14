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
		 
			 $iduser = $_POST['iduser'];	
			 $emaillama = $_POST['emaillama'];
			 
			 $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);		
			 $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
			 $divisi = filter_var($_POST['divisi'], FILTER_SANITIZE_STRING);
			 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
			 $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);	
			 $mobilephone = filter_var($_POST['mobilephone'], FILTER_SANITIZE_STRING);			
		 
		 
			 $query = $db->query("UPDATE `corporate_user` SET `name`='$name',`lastname`='$lastname',`divisi`='$divisi',`phone`='$phone',`mobile`='$mobilephone' WHERE `id` = '$iduser' and  `idmember_list` = '$idmember' and `status` = 0 ");
			 
			 if($emaillama<>$email):
			 
					$quip304 = $db->query("SELECT `id` FROM `corporate_user` WHERE `email`='$email' ");
					$jumdata = $quip304->num_rows;				
					if($jumdata>0):
						  $_SESSION['error_msg']='email-failed';		   
					else:
			 
						  $querry2= $db->query("UPDATE `corporate_user` SET `email`='$email' WHERE `id` = '$iduser' and  `idmember_list` = '$idmember' and `status` = 0 ");
						  $_SESSION['error_msg']='update-oke';
						 
				   endif;
				   
			 else:
			 	 $_SESSION['error_msg']='update-oke';   
			 endif;
		 	
		 else:
			$_SESSION['error_msg']='toikenUrelfail'; 			
		 endif;

		 
		 echo'<script type="text/javascript">window.location="'.$SITE_URL.'user-management"</script>';
		 			
    }	
?>