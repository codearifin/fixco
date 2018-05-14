<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
		
if(isset($_POST['submit'])){

		 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		 $query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
		 $res = $query->fetch_assoc();
		 $idmember = $res['id'];
		 
		 $password = $_POST['password'];
		 //setpassword
		 $password1 = sha1($password.'MemberRegisterDusdusan.com2014');
		 $password2 = sha1($password1);
		 $password3 = md5($password2);
		 $passwordNew = sha1($password3);
		 
		 $quepp = $db->query("UPDATE `member` SET `password`='$passwordNew' WHERE `id`='$idmember' ");
		 $_SESSION['error_msg']='update-oke';
		 echo'<script type="text/javascript">window.location="'.$SITE_URL.'change-password"</script>';	
}
?>		
	