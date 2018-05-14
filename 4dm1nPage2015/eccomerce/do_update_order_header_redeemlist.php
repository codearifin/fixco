<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	require("webconfig-parameters.php");
	
	$iddata = $_POST['iddata'];
	$kurir = $_POST['kurir'];
	$noresi = $_POST['noresi'];
	$status_list = $_POST['status_list'];
	$notemember = $_POST['notemember'];

	$quppe = $db->query("SELECT * FROM `order_header_redeemlist` WHERE `id` = '$iddata' ");	
	$row = $quppe->fetch_assoc();
	$idmember = $row['idmember'];
	$totalpointreward = $row['point'];
	
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	$phonenum = $data['phone'];
	$mobilephone = $data['mobile_phone'];
	$password = $data['password'];
	
	
	$query = $db->query("UPDATE `order_header_redeemlist` SET `kurir` = '$kurir', `resinumber`='$noresi', `status_delivery` = '$status_list' WHERE `id` = '$iddata' ");	
	
	if($status_list=="Shipped"):
		require("email_orderan/confirm_redeem_list.php");	
	else:
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date("Y-m-d H:i:s");		
		$textdata = 'Pembatalan redeem order, Redeem ID #'.sprintf('%06d',$iddata).'';
		$quipst4 = $db->query("INSERT INTO `rewads_point_member` (`idmember`,`date`,`description`,`point`,`status`) VALUES ('$idmember','$dateNow','$textdata','$totalpointreward','1') ");				
		require("email_orderan/cancel_redeem_list.php");						  
	endif;
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../redeem_history_list.php?msg=Confirm successful.";';
	echo'</script>';		
?>                    