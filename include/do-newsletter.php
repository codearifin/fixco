<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');

require_once ('../mcapi/MCAPI.class.php');
require_once ('../mcapi/MCAPI.config.php');

if(isset($_POST['submit'])){

	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);	
		
	//insert to mailchimb
	$api = new MCAPI($apikey);
	$merge_vars = array('FNAME'=>"Fixcomart Subscriber");
	$api->listSubscribe( $listId, $email, $merge_vars, 'html', false );
	if($api->errorCode):
		$_SESSION['error_msg'] = 'error-emailaddress';
	 else:

		//save data member
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date("Y-m-d H:i:s");	
		
		$quipe2 = $db->query("SELECT max(`sortnumber`) as sortnumberlast FROM `newsletter` ");
		$res2 = $quipe2->fetch_assoc();
		$lastsortnumber = $res2['sortnumberlast']+1;
							
		$query = $db->query("INSERT INTO `newsletter` (`email`,`sortnumber`,`modified_datetime`,`modified_by`) VALUES ('$email','$lastsortnumber','$dateNow','1')");	
		
		$_SESSION['error_msg'] = 'success-newsletter';
 	endif;	
	
	echo'<script type="text/javascript">window.location="'.$SITE_URL.'index"</script>';
	
}
?>		
	