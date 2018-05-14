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
			
			 $password = $_POST['password'];
			 //setpassword
			 $password1 = sha1($password.'MemberRegisterDusdusan.com2014');
			 $password2 = sha1($password1);
			 $password3 = md5($password2);
			 $passwordNew = sha1($password3);

			 $query = $db->query("UPDATE `corporate_user` SET `password` = '$passwordNew' WHERE `id` = '$iduser' and `idmember_list` = '$idmember' and `status` = 0 ");	
			
			 $_SESSION['error_msg']='savepasswpord'; 		 			 
		 	
		 else:
			$_SESSION['error_msg']='toikenUrelfail'; 			
		 endif;

		 
		 echo'<script type="text/javascript">window.location="'.$SITE_URL.'user-management"</script>';
		 			
    }	
?>