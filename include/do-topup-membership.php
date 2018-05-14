<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');

function getnamegenal($id,$act,$field){
	global $db;
	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE ".$id." ");
	$row = $query->fetch_assoc();		
	return $row[$field];
}
	
if(isset($_POST['submit'])){
			 
		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$member_id = getnamegenal(" `tokenmember`='$Usertokenid' ", "member", "id"); 	
		$namamember = getnamegenal(" `tokenmember`='$Usertokenid' ", "member", "name").' '.getnamegenal(" `tokenmember`='$Usertokenid' ", "member", "lastname");
		$iddatapay = getnamegenal(" `idmember`='$member_id' ", "member_membership_data", "id");
		$companyname = getnamegenal(" `idmember`='$member_id' ", "member_membership_data", "company_name");
		$npwpcomany = getnamegenal(" `idmember`='$member_id' ", "member_membership_data", "npwp");
				
		$membership_priceid = $_POST['membership_price'];
		$price = getnamegenal(" `id`='$membership_priceid' ", "membership_deposit_adm", "price");
		$pricestatus = getnamegenal(" `id`='$membership_priceid' ", "membership_deposit_adm", "title");
		
		$totaldeposit = number_format($price).' ('.$pricestatus.')';
				
		$banktransfer = $_POST['banktransfer'];
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date("Y-m-d H:i:s");		
		
		$metodepay = "BANK TRANSFER";
		
		$namabankacount = getnamegenal(" `id`='$banktransfer' ", "bank_account", "bank_name").' '.getnamegenal(" `id`='$banktransfer' ", "bank_account", "account_number").' an: '.getnamegenal(" `id`='$banktransfer' ", "bank_account", "account_holder");

		$query2 = $db->query("INSERT INTO `member_membership_data_payment` (`date`,`idmember`,`iddata_membership`,`idbank`,`idtype_membership`,`metode_payment`,`amount`,`status`) 
		VALUES ('$dateNow','$member_id','$iddatapay','$banktransfer','$membership_priceid','$metodepay','$price','Waiting')") or die($db->error);
		
		require("email_upgrade_membership.php");
		
		if($query2):	
			$queppp = $db->query("UPDATE `member_membership_data` SET `status` = 1  WHERE `id` = '$iddatapay' ");
		endif;
		
		$_SESSION['error_msg']='savebayaroke';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'upgrade-membership"</script>';				
}
?>		
	