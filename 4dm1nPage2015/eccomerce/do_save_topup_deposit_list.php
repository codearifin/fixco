<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");

	date_default_timezone_set('Asia/Jakarta');
	$dateNow = date("Y-m-d H:i:s");
			
	$memberid = $_POST['memberid'];
	$nominal_trf = replaceamount($_POST['nominal_trf']);
	$namabank = $_POST['namabank'];
	$namapemilik = $_POST['namapemilik'];
	$tgltrf = replacetanggal($_POST['tgltrf']);
	
	$query = $db->query("INSERT INTO `deposit_member_corporatelist_konfirmasi` (`idmember`,`date_posting`,`bank`,`account_holder`,`amount`,`date`,`bukti_trf`,`status`) 
						VALUES ('$memberid','$dateNow','$namabank','$namapemilik','$nominal_trf','$tgltrf','','0') ") or die($db->error);
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../topup_deposit_list.php?msg=Topup deposit successful.";';
	echo'</script>';		
?>                    