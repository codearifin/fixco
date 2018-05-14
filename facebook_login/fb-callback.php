<?php
@session_start();
require('../config/connection.php');
require('../config/myconfig.php');

require("Facebook/Facebook.php");
require("Facebook/autoload.php");

$fb = new Facebook\Facebook([
  'app_id' => '1336531049793304', // Replace {app-id} with your app id
  'app_secret' => '7d6ed6e981732e0f99f07bcea1c82f68',
  'default_graph_version' => 'v2.4',
  'persistent_data_handler'=>'session'
  ]);

$helper = $fb->getRedirectLoginHelper();

try {
  $accessToken = $helper->getAccessToken();
  $response = $fb->get('/me?fields=id,name,first_name,last_name,email', $accessToken);
  $user = $response->getGraphUser();
  
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if($user):
	
	$name			=	$user['name'];
	$first_name		=	$user['first_name'];
	$last_name		=	$user['last_name'];
	$gender			=	$user['gender'];
	$email			=	$user['email'];
	$link			=	$user['link'];
	$fbid			=	$user['id'];
		
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
	
		
endif;	
?>
