<?php 
		 @session_start();	
		 require('../config/connection.php');
		 require('../config/myconfig.php');
		
		 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		 $qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		 $ros = $qummep->fetch_assoc();	
		 $idmember = $ros['idmember_list']; $idabook = $_GET['id'];

		 $quepp = $db->query("DELETE FROM `address_book` WHERE `id`='$idabook' and `member_id`='$idmember' ") or die($db->error);
		 $_SESSION['error_msg']='delete-oke';
		 echo'<script type="text/javascript">window.location="'.$SITE_URL.'address-book-corporate"</script>';
?>		
	