<?php 
	@session_start();
	require('../config/connection.php');;
	require('../config/myconfig.php');

	if(isset($_GET['membertoken'])){
		$member_token = $_GET['membertoken'];
	}
	
	$query = $db->query("SELECT `id`,`activation_status` FROM `member` WHERE `tokenmember` = '$member_token'") or die($db->error);
	$member = $query->fetch_assoc();

	if($member['activation_status'] == 0){
		$update = $db->query("UPDATE `member` SET `activation_status` = 1 WHERE `tokenmember` = '$member_token'");
		$_SESSION['error_msg']='activationoke';
		echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';
	}else{
		$_SESSION['error_msg']='activationdone';
		echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';
	}	
?>