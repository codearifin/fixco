<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');

$cryptinstall="../js/crypt/cryptographp.fct.php";
include $cryptinstall; 	

function verifyFormToken($form) {
	if(!isset($_SESSION[$form.'_token'])) { 
		$statusform2 = 0;
		return $statusform2;
	}
	
	if(!isset($_POST['tokenId'])) {
		$statusform2 = 0;
		return $statusform2;
	}
	
	if ($_SESSION[$form.'_token']!== $_POST['tokenId']) {
		$statusform2 = 0;
		return $statusform2;
	}
	
	$statusform2 = 1;
	return $statusform2;
}
	
function getmemberidaffiliate($tokenaffilate){
		global $db;
		$query = $db->query("SELECT `idmember_aid` FROM `affiliate_member` WHERE `token_affiliate` = '$tokenaffilate' and `status` = 1 ") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$row = $query->fetch_assoc();
			return $row['idmember_aid'];
		else:
			return 0;	
		endif;		
}	
		
if(isset($_POST['submit'])){

	$kodevalidasi = $_POST['kodevalidasi'];
	if(chk_crypt($kodevalidasi)){
		$TokenIdform = verifyFormToken('RegisterPIERO2014');
		
		 if(isset($_SESSION['affiliate_tokenuid'])): $token_affiliate_tokenuid = $_SESSION['affiliate_tokenuid']; else: $token_affiliate_tokenuid = ''; endif;	
		 $uidmember_affilate = getmemberidaffiliate($token_affiliate_tokenuid);	

		 $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);		
		 $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
		 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		 $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);	
		 $mobilephone = filter_var($_POST['mobilephone'], FILTER_SANITIZE_STRING);			
		 $password = $_POST['password'];
		 
		 //setpassword
		 $password1 = sha1($password.'MemberRegisterDusdusan.com2014');
		 $password2 = sha1($password1);
		 $password3 = md5($password2);
		 $passwordNew = sha1($password3);
		 
		 date_default_timezone_set('Asia/Jakarta');
		 $dateNow = date("Y-m-d H:i:s");
		 $ipname_user = $_SERVER['REMOTE_ADDR']; 
		 $kode_token = sha1($dateNow.$ipname_user.$email.$name.$lastname);
		 
		 //faceboo uid regis
		 $fbid_fbregis = $_POST['fbid_fbregis'];
		 //google id
		 $googleid = $_POST['googleid'];
		 
		 if($fbid_fbregis<>'' or $googleid<>''):
		 	$statusmember = 'Active';
		 else:
		 	$statusmember = 'Active';
		 endif;	
		 
		 
		 if($TokenIdform==1):
		 		//register save-- 	
				$quip304 = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' ");
				$jumdata = $quip304->num_rows;				
				if($jumdata>0):
					$_SESSION['error_msg']='email-failed';
					echo'<script type="text/javascript">window.location="'.$SITE_URL.'register"</script>';			   
				else:
					//save datalist--
					$querry = $db->query("SELECT max(`id`) as lastudmember FROM `member` ");
					$datamem = $querry->fetch_assoc();
					$idmemberlist = $datamem['lastudmember']+1;
					
					$query = $db->query("INSERT INTO `member` (`id`,`date`,`name`,`lastname`,`phone`,`mobile_phone`,`email`,`password`,`tokenmember`,`status`,`status_complete`,`member_category`,`facebook_id`,`google_plusid`,`sortnumber`,
					`modified_datetime`,`modified_by`) 
					VALUES ('$idmemberlist','$dateNow','$name','$lastname','$phone','$mobilephone','$email','$passwordNew','$kode_token','$statusmember','0','REGULAR MEMBER','$fbid_fbregis','$googleid','0','$dateNow','1')") or die($db->error);
					
					if($uidmember_affilate>0):
						$quepplist = $db->query("INSERT INTO `affiliate_memberid` (`member_id`,`anggotanya_member_id`,`komisi_persen`) VALUES ('$uidmember_affilate','$idmemberlist','$commission_persenpig')");
						unset($_SESSION['affiliate_tokenuid']);
					endif;
					
					
					if($fbid_fbregis<>'' or $googleid<>''):
						unset($_SESSION['name_fb']);
						unset($_SESSION['lastname_fb']);
						unset($_SESSION['email_fb']);
						unset($_SESSION['fbid_fb']);
						unset($_SESSION['googleid']);
					
						require('register_admin_email.php');
						//login facebook--
						$_SESSION['user_token'] = $kode_token;
						$_SESSION['user_statusmember'] = "REGULAR MEMBER";
						echo'<script type="text/javascript">window.location="'.$SITE_URL.'my-account"</script>';
						
					else:
					
						require('register_admin_email.php');
						
						$_SESSION['error_msg']='registersuccess';
						echo'<script type="text/javascript">window.location="'.$SITE_URL.'register"</script>';	
					endif;

						
				endif;	   		 
		 else:
			 $_SESSION['error_msg']='token-failed';	 
			 echo'<script type="text/javascript">window.location="'.$SITE_URL.'register"</script>';	
		 endif;	
		 		
		
		
		
	}else{
		$_SESSION['error_msg'] = 'error-capctha';
		echo'<script type="text/javascript">window.location="'.$SITE_URL.'register"</script>';	
	}
	
	
}
?>		
	