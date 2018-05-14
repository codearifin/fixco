<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');
		
if(isset($_POST['submit'])){
		  $actUrl = filter_var($_POST['actUrl'], FILTER_SANITIZE_STRING);
		  
		 //member data
		 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		 $qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		 $ros = $qummep->fetch_assoc();	
		 $uidmembermain = $ros['idmember_list'];
				 
		 //select member
		 $query = $db->query("SELECT * FROM `member` WHERE `id`='$uidmembermain' ");
		 $res = $query->fetch_assoc();
		 $idmember = $res['id'];
		 $status_address = $res['status_complete'];
			
		 $company_name = filter_var($_POST['company_name'], FILTER_SANITIZE_STRING);		
		 $npwp = filter_var($_POST['npwp'], FILTER_SANITIZE_STRING);			
			 	
		 $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);		
		 $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		 $email_lama = filter_var($_POST['email_lama'], FILTER_SANITIZE_EMAIL);
		 $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);	
		 $mobilephone = filter_var($_POST['mobilephone'], FILTER_SANITIZE_STRING);			
		 $gender = $_POST['gender'];
		 
		 $bank_account = filter_var($_POST['bank_account'], FILTER_SANITIZE_STRING);	
		 
		 //billing
		 $province = filter_var($_POST['province'], FILTER_SANITIZE_STRING);
		 $kabupaten = filter_var($_POST['kabupaten'], FILTER_SANITIZE_STRING);
		 $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		 $kodepos = filter_var($_POST['kodepos'], FILTER_SANITIZE_STRING);
		 $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		 
		 $query5 = $db->query("UPDATE `member` SET `name`='$name',`lastname`='$lastname',`gender`='$gender',`phone`='$phone',`mobile_phone`='$mobilephone',`status_complete`='1',`bank_account`='$bank_account' WHERE `id`='$idmember' ");
		 $query6 = $db->query("UPDATE `corporate_user` SET `name`='$name',`lastname`='$lastname',`phone`='$phone',`mobile_phone`='$mobilephone' WHERE `idmember_list`='$idmember' and `status` = 1 ");
		 $query9 = $db->query("UPDATE `member_membership_data` SET `company_name`='$company_name',`npwp`='$npwp' WHERE `idmember`='$idmember' ");
		 

		 //register save-- 	
		 if($email_lama<>$email):
				$quip304 = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' ");
				$jumdata = $quip304->num_rows;		
	
				$quip44 = $db->query("SELECT `id` FROM `corporate_user` WHERE `email`='$email' ");
				$jumdata4 = $quip44->num_rows;	
									
				if($jumdata>0 or $jumdata4>0):
					$_SESSION['error_msg']='email-failed';	   
				else:	
					 $_SESSION['error_msg']='update-oke';	   	 
					 $query2 = $db->query("UPDATE `member` SET `email`='$email' WHERE `id`='$idmember' ");
					 $query3 = $db->query("UPDATE `corporate_user` SET `email`='$email' WHERE `idmember_list`='$idmember' and `status` = 1 ");
				endif;
		 else:
			$_SESSION['error_msg']='update-oke';	
		 endif;	
		
		 
		if($status_address==0):
		 	$query3 = $db->query("INSERT INTO `billing_address` (`idmember`,`provinsi`,`kabupaten`,`idcity`,`address`,`kodepos`) VALUES ('$idmember','$province','$kabupaten','$city','$address','$kodepos') ");
		else:
		 	$query3 = $db->query("UPDATE `billing_address` SET `provinsi`='$province',`kabupaten`='$kabupaten',`idcity`='$city',`address`='$address',`kodepos`='$kodepos' WHERE `idmember`='$idmember' ");
		endif;
		
		if($actUrl==""):
			echo'<script type="text/javascript">window.location="'.$SITE_URL.'my-account-corporate"</script>';	
		else:
			echo'<script type="text/javascript">window.location="'.$SITE_URL.'finishorder-corporate"</script>';	
		endif;	
}
?>		
	