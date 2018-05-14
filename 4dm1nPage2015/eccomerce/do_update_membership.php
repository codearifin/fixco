<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	require("webconfig-parameters.php");
	
	$iddata = $_POST['iddata'];
	$iddataadm = $_POST['iddataadm'];
	$status_list = $_POST['status_list'];
	$notemember = $_POST['notemember'];
	
	$idmember = getnamegenal(" `id`='$iddata' ", "member_membership_data_payment", "idmember");
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	$phonenum = $data['phone'];
	$mobilephone = $data['mobile_phone'];
	$password = $data['password'];

	//get deposit jum	
	$quioooopp = $db->query("SELECT `saldo_price_for_member` FROM `membership_deposit_adm` WHERE `id` = '$iddataadm' ");
	$data = $quioooopp->fetch_assoc();
	$jumlahdeposit = $data['saldo_price_for_member'];
	
	
	$query = $db->query("UPDATE `member_membership_data_payment` SET `status` = '$status_list' WHERE `id` = '$iddata' ");	
	
	if($status_list=="Confirmed"):
		$quipo = $db->query("UPDATE `member` SET `member_category` = 'CORPORATE MEMBER' WHERE `id` = '$idmember' ");
		
		$quip304 = $db->query("SELECT `id` FROM `corporate_user` WHERE `email` = '$email' ");
		$jumdata = $quip304->num_rows;				
		if($jumdata>0):
			 //no insert data
		else:		
				$quipo2 = $db->query("INSERT INTO `corporate_user` (`idmember_list`,`name`,`lastname`,`email`,`phone`,`mobile`,`password`,`status`,`status_active`) 
							  VALUES ('$idmember','$namemember','$lastname','$emailmember','$phonenum','$mobilephone','$password','1','1')");
		endif;
		
		//masukan deposit
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date("Y-m-d H:i:s");		
		$textdata = 'Upgrade Membership Topup';
		$quipst4 = $db->query("INSERT INTO `deposit_member_corporatelist` (`idmember`,`date`,`description`,`amount`,`status`) VALUES ('$idmember','$dateNow','$textdata','$jumlahdeposit','1') ");	
			
										  
	endif;
	
	require("email_orderan/member_membership_data_payment_email.php");		 
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../upgrade_membership_list.php?msg=Update successful.";';
	echo'</script>';		
?>                    