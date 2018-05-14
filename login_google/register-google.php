<?php
require_once('googlesdk/src/Google_Client.php');		
require_once('googlesdk/src/contrib/Google_Oauth2Service.php');
$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
$oauth2 = new Google_Oauth2Service($client);

@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $token = $client->getAccessToken();
  if($token){
		$user_profile = $oauth2->userinfo->get();	 

		//FETCH USER DATA 
		$itemnamegoogle = explode(" ",$user_profile['name']);
		$first_name		=	ucwords($itemnamegoogle[1]);
		$last_name		=	ucwords($itemnamegoogle[0]);
		$email			=	$user_profile['email'];
		$googleid		=	$user_profile['id'];
		
		$queryco = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' and `member_category`='CORPORATE MEMBER' ");
		$jumdatacop = $queryco->num_rows;
		if($jumdatacop>0):
					
			$_SESSION['error_msg']='member_corporate';
			echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';		
									
		else:
			
				$query = $db->query("SELECT `tokenmember`,`status` FROM `member` WHERE ( `email`='$email' or `google_plusid`='$googleid' ) and `member_category`='REGULAR MEMBER' ");
				$jumdata = $query->num_rows;
				if($jumdata>0):
						
						//login success--
						$data = $query->fetch_assoc();
						if($data['status']=='Active'):
							
							$_SESSION['user_token'] = $data['tokenmember'];
							$_SESSION['user_statusmember'] = "REGULAR MEMBER";
							echo'<script type="text/javascript">window.location="'.$SITE_URL.'my-account"</script>';	
																			
						else:
							$_SESSION['error_msg']='login-failed2';
							echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';	
						endif;
															
				else:
							$_SESSION['name_fb'] = $first_name;
							$_SESSION['lastname_fb'] = $last_name;
							$_SESSION['email_fb'] = $email;
							$_SESSION['googleid'] = $googleid;		
							echo'<script type="text/javascript">window.location="'.$SITE_URL.'register"</script>';	
				endif;
		
	   endif;
	   
	   
		
  }else{
  	$_SESSION['error_msg']='logintwiitter_failed';
	echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';	
  }		

}else{
	$_SESSION['error_msg']='logintwiitter_failed';
	echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';	
}
?>