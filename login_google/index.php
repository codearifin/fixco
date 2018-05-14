<?php 
require_once('googlesdk/src/Google_Client.php');		
require_once('googlesdk/src/contrib/Google_Oauth2Service.php');
$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
$oauth2 = new Google_Oauth2Service($client);

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $token = $client->getAccessToken();
  if($token){
		$user_profile = $oauth2->userinfo->get();	 
		echo "<pre>";
		print_r($user_profile);
		echo "</pre>";
  }
}else {
  $authUrl = $client->createAuthUrl();
  echo "<a href='{$authUrl}'>LOGIN</a>";
}


?>