<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('function.php');	

$idlist = replaceUrel($_GET['idlist']);
$token = replaceUrel($_GET['token']);

if($idlist>0):
			//member data
			if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
			$qummep = $db->query("SELECT `id`,`idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
			$ros = $qummep->fetch_assoc();	
			$idmember = $ros['idmember_list']; $idusr = $ros['id'];
			
			if($ros['status']==1):
				$que = $db->query("SELECT `tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `tokenpay`='$token'");
			else:
				$que = $db->query("SELECT `tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `id_user` = '$idusr' and `tokenpay`='$token'");
			endif;
			$row = $que->fetch_assoc();	
			$tokendatabse = $row['tokenpay'];
			
			$quppprod3 = $db->query("DELETE FROM `draft_quotation_detail` WHERE `id` = '$idlist' and `tokenpay` = '$tokendatabse' ");	

			if($quppprod3):
				//last update weight and ongkir
				updateongkirTotal($tokendatabse);
			endif;
						
endif;

$_SESSION['error_msg'] = 'deleteprodoke';
echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'quotation-detail/'.$tokendatabse.'"</script>';
?>		
	