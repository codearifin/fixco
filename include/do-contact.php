<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');

$cryptinstall="../js/crypt/cryptographp.fct.php";
include $cryptinstall; 	
	
if(isset($_POST['submit'])){

	$fisrtname	= filter_var($_POST['name'], FILTER_SANITIZE_STRING);
	$name = $fisrtname;
	$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
	$pesanmember = filter_var($_POST['pesanmember'], FILTER_SANITIZE_STRING);
	$kodevalidasi = $_POST['kodevalidasi'];
	
	if(chk_crypt($kodevalidasi)){
		require("contact_email_form.php");
		$_SESSION['error_msg'] = 'success-sendcontact';
	}else{
		$_SESSION['error_msg'] = 'error-capctha';
	}
	
	echo'<script type="text/javascript">window.location="'.$SITE_URL.'contact"</script>';	
}
?>		
	