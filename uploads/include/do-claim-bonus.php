<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');
require('../include/function.php');	
		
if(isset($_POST['submit'])){
		 
		 //select member
		 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		 $query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
		 $res = $query->fetch_assoc();
		 $idmember = $res['id'];
		 $totalkomisi = getkomisimemberlistTotal($idmember);
		 $bank_account = $res['bank_account'];
		
		 $totalamount_1 = filter_var($_POST['totalamount'], FILTER_SANITIZE_STRING);	
		 $totalamount = replaceamount($totalamount_1);	
		 $password = $_POST['password'];

		 //setpassword
		 $password1 = sha1($password.'MemberRegisterDusdusan.com2014');
		 $password2 = sha1($password1);
		 $password3 = md5($password2);
		 $passwordNew = sha1($password3);
		 
		 if($passwordNew == $res['password']):
			  
			  if($totalamount > $totalkomisi):
			  	 $_SESSION['error_msg'] = 'save_WITHDRAWAL_fail'; 	
				 echo'<script type="text/javascript">window.location="'.$SITE_URL.'commission-withdrawal"</script>';		
			  else:
			  		if($totalamount>0):
					
						$quer = $db->query("SELECT max(`id`) as cid FROM `claim_komis_member` ");
						$res = $quer->fetch_assoc();
						$idclimbonus = $res['cid']+1;		
			 	
						//valid data claim--
						date_default_timezone_set('Asia/Jakarta');
						$dateNow = date("Y-m-d H:i:s");
						$desctext = 'Claim Commission Withdrawal, ID. '.sprintf('%06d',$idclimbonus).'';
						
						$quepp = $db->query("INSERT INTO `komisilist_member` (`idmember`,`date`,`description`,`amount`,`status`) VALUES ('$idmember','$dateNow','$desctext','$totalamount','0')");
						if($quepp):
							$quepp2 = $db->query("INSERT INTO `claim_komis_member` (`idmember_claim`,`bank_transfer`,`total`,`status_payment`,`date`) VALUES ('$idmember','$bank_account','$totalamount','Pending On Payment','$dateNow')");
						endif;
						
					    $_SESSION['error_msg'] = 'save_WITHDRAWAL'; 	
				 		echo'<script type="text/javascript">window.location="'.$SITE_URL.'affiliate-commission"</script>';					
					else:
					    $_SESSION['error_msg'] = 'save_WITHDRAWAL_fail'; 	
				 		echo'<script type="text/javascript">window.location="'.$SITE_URL.'commission-withdrawal"</script>';			
					endif;
			  
			  endif;	

					 
		 else:
			 
			 $_SESSION['error_msg'] = 'save_WITHDRAWAL_failpass';		
			 echo'<script type="text/javascript">window.location="'.$SITE_URL.'commission-withdrawal"</script>';		
		 	 
		 endif;
		 

}
?>		
	