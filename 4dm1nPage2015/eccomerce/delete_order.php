<?php
	@session_start();
	require("../../config/connection.php");
											
	$orderid = $_GET['orderid'];
	$que = $db->query("SELECT `id`,`tokenpay` FROM `order_header` WHERE `id`='$orderid' ");
	$row = $que->fetch_assoc();
	$ordidmm = $row['id'];
	$tokenordernew = $row['tokenpay'];
	
	$query = $db->query("DELETE FROM `order_detail` WHERE `tokenpay`='$tokenordernew'");
	$query2 = $db->query("DELETE FROM `konfirmasi_bayar` WHERE `idorder`='$ordidmm'");
	$query3 = $db->query("DELETE FROM `status_detailorder` WHERE `idorder`='$ordidmm'");
	$queryodr = $db->query("DELETE FROM `order_header` WHERE `id`='$ordidmm'");	
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../ordermember.php?msg=Delete successful.";';
	echo'</script>';		
?>                    