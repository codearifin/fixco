<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	require("webconfig-parameters.php");
	
	
	$iddata = $_POST['iddata'];
	$totaldepo = replaceamount($_POST['totaldepo']);
	$status_list = $_POST['status_list'];
	$notemember = $_POST['notemember'];
		
	$querypp = $db->query("SELECT 
	`deposit_member_corporatelist_konfirmasi`.`id` as cid,
	`deposit_member_corporatelist_konfirmasi`.`idmember` as cidmember,
	`deposit_member_corporatelist_konfirmasi`.`bank` as cbank,
	`deposit_member_corporatelist_konfirmasi`.`account_holder` as caccount_holder,
	`deposit_member_corporatelist_konfirmasi`.`amount` as camount,
	`deposit_member_corporatelist_konfirmasi`.`bukti_trf` as cbukti_trf,
	`deposit_member_corporatelist_konfirmasi`.`status` as cstatus,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date_posting`, '%d/%m/%Y %H:%i:%s') as mdate,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date`, '%d/%m/%Y') as mdate2,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `deposit_member_corporatelist_konfirmasi` LEFT JOIN `member` ON `member`.`id` = `deposit_member_corporatelist_konfirmasi`.`idmember` 
	 
	WHERE `deposit_member_corporatelist_konfirmasi`.`id` = '$iddata' ");
	$row = $querypp->fetch_assoc();
	
		
	$idmember = $row['cidmember'];
	$quiopp = $db->query("SELECT * FROM `member` WHERE `id` = '$idmember' ");	
	$data = $quiopp->fetch_assoc();
	$namemember = $data['name'];
	$lastname = $data['lastname'];
	$emailmember = $data['email'];
	
	$query = $db->query("UPDATE `deposit_member_corporatelist_konfirmasi` SET `status` = '$status_list' WHERE `id` = '$iddata' ");	
	if($query):
		if($status_list==1):
			//save to member back
			date_default_timezone_set('Asia/Jakarta');
			$dateNow = date("Y-m-d H:i:s");		
			$textdata = 'Topup Deposit by '.$row['cbank'].'';
			$quipst4 = $db->query("INSERT INTO `deposit_member_corporatelist` (`idmember`,`date`,`description`,`amount`,`status`) VALUES ('$idmember','$dateNow','$textdata','$totaldepo','1') ");		
			
			require("email_orderan/saldo_deposit_confirm_email.php");	
			
		endif;
	endif;	

	echo'<script language="JavaScript">';
		echo'window.location="../topup_deposit_list.php?msg=Confirm successful.";';
	echo'</script>';			
?>                    