<?php 
	@session_start();
	require('config/connection.php');;
	require('config/myconfig.php');
	unset($_SESSION['user_token']);	
	unset($_SESSION['user_statusmember']);	
	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';	
?>