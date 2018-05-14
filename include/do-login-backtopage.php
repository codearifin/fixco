<?php 
	@session_start();
	require('../config/connection.php');;
	require('../config/myconfig.php');

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
	
 if(isset($_POST['submit'])){
 		
		 $TokenIdform = verifyFormToken('RegisterPIERO2015Login');
		 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		 $password = $_POST['password'];
		
		 //setpassword
		 $password1 = sha1($password.'MemberRegisterDusdusan.com2014');
		 $password2 = sha1($password1);
		 $password3 = md5($password2);
		 $passwordNew = sha1($password3);
 		
		if($TokenIdform==1):

					$queryco = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' and `member_category`='CORPORATE MEMBER' ");
					$jumdatacop = $queryco->num_rows;
					if($jumdatacop>0):
					
						$_SESSION['error_msg']='member_corporate';
								
					else:
							
							$query = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' and `member_category`='REGULAR MEMBER' ");
							$jumpage = $query->num_rows;
							if($jumpage > 0):
										$row = $query->fetch_assoc();
										$query2 = $db->query("SELECT `tokenmember`,`status` FROM `member` WHERE `id`='".$row['id']."' and `password` = '$passwordNew' and `member_category`='REGULAR MEMBER' ");
										$jumpage2 = $query2->num_rows;
										if($jumpage2 > 0):
												
												$row2 = $query2->fetch_assoc();
												if($row2['status']=="Active"):
														$_SESSION['user_token'] = $row2['tokenmember'];
														$_SESSION['user_statusmember'] = "REGULAR MEMBER";
															
												else:
														$_SESSION['error_msg']='login-failed2';
												endif;					
										
										else:
											$_SESSION['error_msg']='login-failed';			
										endif;			
							else:
								$_SESSION['error_msg']='login-failed';
	
							endif;
				  endif;				
		
		else:
			$_SESSION['error_msg']='token-failed';

		endif;


		echo'<script language="javascript">';
			echo'javascript:history.go(-1);';
		echo'</script>';
				
 }	
?>