<?php
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('facebook.php');

$facebook = new Facebook(array(
	 'appId' => '1336531049793304',
	 'secret' => '7d6ed6e981732e0f99f07bcea1c82f68',
	 'default_graph_version' => 'v2.4',
	 'persistent_data_handler'=>'session'	 
));	

$site_urlweb = 'http://fixcomart.com/register/facebook';
$login_urlweb = $facebook->getLoginUrl(array(
		'scope'         => 'email','public_profile',
		'redirect_uri'  => $site_urlweb,	
));	
	
	
$user = $facebook->getUser();
if($user) {
			 try {
					// Proceed knowing you have a logged in user who's authenticated.
					$user_profile = $facebook->api('/me');
					
				 } catch (FacebookApiException $e) {
					error_log($e);
					$user = null;
				}
	    }

echo'LOAD...';
$member_getfacebook = $facebook->api('/me');	
var_dump($member_getfacebook);



/*
if($user):
	$member_getfacebook = $facebook->api('/me');	
	//FETCH USER DATA 
	$name			=	$member_getfacebook['name'];
	$first_name		=	$member_getfacebook['first_name'];
	$last_name		=	$member_getfacebook['last_name'];
	$gender			=	$member_getfacebook['gender'];
	$email			=	$member_getfacebook['email'];
	$link			=	$member_getfacebook['link'];
	$fbid			=	$member_getfacebook['id'];

	
	$queryco = $db->query("SELECT `id` FROM `member` WHERE `email`='$email' and `member_category`='CORPORATE MEMBER' ");
	$jumdatacop = $queryco->num_rows;
	if($jumdatacop>0):
				
		$_SESSION['error_msg']='member_corporate';
		echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';		
								
	else:
					
								
			$query = $db->query("SELECT `tokenmember`,`status` FROM `member` WHERE ( `email`='$email' or `facebook_id`='$fbid' ) and `member_category`='REGULAR MEMBER' ");
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
						$_SESSION['fbid_fb'] = $fbid;		
						echo'<script type="text/javascript">window.location="'.$SITE_URL.'register"</script>';	
			endif;
			
	
	endif;
	
	
		
else:

	echo'<script type="text/javascript">window.location="'.$login_urlweb.'"</script>';	 

endif;
*/		
?>