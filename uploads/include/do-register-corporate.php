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
	
function upload_images($img,$img_name)
	{
		$imgbaru = date("Y-m-d-His");
		$extG = get_file_extension22($img_name);
		$ext = '.'.$extG;
		
		$checkextimg = strtolower($extG);
		if($checkextimg=="php" or $checkextimg=="asp" or $checkextimg=="js" or $checkextimg=="xml" or $checkextimg=="html"):
			$nama_gambar='';
		else:
			
			$imgname = "corporate-npwp-".$imgbaru.$ext;
			$folder = "../uploads/".$imgname;
			if($img_name == ''){ $nama_gambar = ''; } else { $nama_gambar = $imgname; }
			
			move_uploaded_file($img,$folder);
			return $nama_gambar;				
		endif;			
}

function get_file_extension22($file_name) {
   return substr(strrchr($file_name,'.'),1);
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

		 $statusmember = 'InActive';
		 $statmemberuser = 0;

		$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
		$npwp = filter_var($_POST['npwp'], FILTER_SANITIZE_STRING);
		
		//images--
		$img=$_FILES['filecompany']['tmp_name'];
		$img_name=$_FILES['filecompany']['name'];
		
				 
		 
		 if($TokenIdform==1):
		 		//register save-- 	
				$quip304 = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' ");
				$jumdata = $quip304->num_rows;				
				if($jumdata>0):
					$_SESSION['error_msg']='email-failed';
					echo'<script type="text/javascript">window.location="'.$SITE_URL.'register-corporate"</script>';			   
				else:
					//save datalist--
					$quip44 = $db->query("SELECT max(`id`) as maxuid FROM `member` ");
					$datalist = $quip44->fetch_assoc();
					$lastidmember = $datalist['maxuid']+1;
					
					$query = $db->query("INSERT INTO `member` (`id`,`date`,`name`,`lastname`,`phone`,`mobile_phone`,`email`,`password`,`tokenmember`,`status`,`status_complete`,`member_category`,`sortnumber`,`modified_datetime`,`modified_by`) 
					VALUES ('$lastidmember','$dateNow','$name','$lastname','$phone','$mobilephone','$email','$passwordNew','$kode_token','$statusmember','0','CORPORATE MEMBER','0','$dateNow','1')") or die($db->error);
					
					//save data detail
			 		$nama_gambar=upload_images($img,$img_name);	
			 		$querydata = $db->query("INSERT INTO `member_membership_data` (`idmember`,`company_name`,`npwp`,`file_member`,`status`,`date`) VALUES ('$lastidmember','$company','$npwp','$nama_gambar','0','$dateNow')");
					
					//save user
					$quepp2 = $db->query("INSERT INTO `corporate_user` (`idmember_list`,`name`,`lastname`,`email`,`phone`,`mobile`,`password`,`status`,`status_active`) 
					VALUES ('$lastidmember','$name','$lastname','$email','$phone','$mobilephone','$passwordNew','1','$statmemberuser')");


					if($uidmember_affilate>0):
						$quepplist = $db->query("INSERT INTO `affiliate_memberid` (`member_id`,`anggotanya_member_id`,`komisi_persen`) VALUES ('$uidmember_affilate','$lastidmember','$commission_persenpig')");
						unset($_SESSION['affiliate_tokenuid']);
					endif;
								 					
					require('register_admin_email_corporate.php');
						
					$_SESSION['error_msg']='registersuccess_corporate';
					echo'<script type="text/javascript">window.location="'.$SITE_URL.'register-corporate"</script>';	

						
				endif;	   		 
		 else:
			 $_SESSION['error_msg']='token-failed';	 
			 echo'<script type="text/javascript">window.location="'.$SITE_URL.'register-corporate"</script>';	
		 endif;	
	
		
	}else{
		$_SESSION['error_msg'] = 'error-capctha';
		echo'<script type="text/javascript">window.location="'.$SITE_URL.'register-corporate"</script>';	
	}
	
	
}
?>		
	