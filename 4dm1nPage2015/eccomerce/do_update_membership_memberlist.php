<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	require("webconfig-parameters.php");
	
	$idmember = $_GET['idmember'];
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	$phonenum = $data['phone'];
	$mobilephone = $data['mobile_phone'];
	$password = $data['password'];
	
	$quipo = $db->query("UPDATE `member` SET `status` = 'Active' ,`member_category` = 'CORPORATE MEMBER' WHERE `id` = '$idmember' ");
	$quipo2 = $db->query("UPDATE `corporate_user` SET `status_active` = 1 WHERE `idmember_list` = '$idmember' ");

	require("email_orderan/member_konfirmasi_status.php");		 
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../member_list.php?msg=Confirm member successful.";';
	echo'</script>';		
?>                    