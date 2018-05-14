<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	require("webconfig-parameters.php");
	
	$iddata = $_POST['iddata'];
	$status_list = $_POST['status_list'];
	
	$idmember = getnamegenal(" `id`='$iddata' ", "affiliate_member", "idmember_aid");
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	
	
	$query = $db->query("UPDATE `affiliate_member` SET `status` = '$status_list' WHERE `id` = '$iddata' ");	
	
	if($status_list==1):
		require("email_orderan/affiliate_member_email.php");	
	else:
		require("email_orderan/affiliate_member_email_frezee.php");				  
	endif;
		 	
	echo'<script language="JavaScript">';
		echo'window.location="../affiliate_list.php?msg=Update successful.";';
	echo'</script>';		
?>                    