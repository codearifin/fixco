<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	require("webconfig-parameters.php");
	
	
	$iddata = $_POST['iddata'];
	$status_list = $_POST['status_list'];
	$notemember = $_POST['notemember'];
		
	$querypp = $db->query("SELECT 
	
	`claim_komis_member`.`id` as cid,
	`claim_komis_member`.`idmember_claim` as midmember,
	`claim_komis_member`.`bank_transfer` as cbank_transfer,
	`claim_komis_member`.`total` as ctotal,
	`claim_komis_member`.`status_payment` as cstatus_payment,
	DATE_FORMAT(`claim_komis_member`.`date`, '%d/%m/%Y %H:%i:%s') as mdate,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `claim_komis_member` LEFT JOIN `member` ON `member`.`id` = `claim_komis_member`.`idmember_claim` 
	 
	WHERE `claim_komis_member`.`id` = '$iddata' ");
	$row = $querypp->fetch_assoc();
	
	$totalclaim = $row['ctotal'];
	$bankaccount = $row['cbank_transfer'];
		
	$idmember = $row['midmember'];
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	

	$query = $db->query("UPDATE `claim_komis_member` SET `status_payment` = '$status_list' WHERE `id` = '$iddata' ");	
	
	if($status_list=="Paid"):
		require("email_orderan/claim_komis_member_member_email.php");	
	else:
		
		//save to member back
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date("Y-m-d H:i:s");		
		$textdata = 'Cancelled Withdrawal, ID #'.sprintf('%06d',$iddata).'';
		$quipst4 = $db->query("INSERT INTO `komisilist_member` (`idmember`,`date`,`description`,`amount`,`status`) VALUES ('$idmember','$dateNow','$textdata','$totalclaim','1') ");				
		
		require("email_orderan/claim_komis_member_member_email_no.php");	
	endif;

	echo'<script language="JavaScript">';
		echo'window.location="../commission_withdrawal.php?msg=Update successful.";';
	echo'</script>';		
		
?>                    