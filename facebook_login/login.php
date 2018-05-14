<?php
@session_start();
include("Facebook/Facebook.php");
include("Facebook/autoload.php");
require('../config/myconfig.php');

$fb = new Facebook\Facebook([
	  'app_id' => '1336531049793304',
	  'app_secret' => '7d6ed6e981732e0f99f07bcea1c82f68',
	  'default_graph_version' => 'v2.4',
	  'persistent_data_handler'=>'session'
]);
	
$helper = $fb->getRedirectLoginHelper();
	
$permissions = ['email', 'public_profile']; // permissions
$loginUrl = $helper->getLoginUrl(''.$SITE_URL.'/facebook_login/fb-callback.php', $permissions);

echo'<script type="text/javascript">window.location="'.$loginUrl.'"</script>';	
?>