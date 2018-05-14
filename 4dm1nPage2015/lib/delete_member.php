<?php
require('../../config/connection.php');
$idmember = $_GET['idmember'];
$status = $_GET['status'];

	//regular member
	$query1 = $db->query("DELETE FROM `komisilist_member` WHERE `idmember`='$idmember' ");
	$query2 = $db->query("DELETE FROM `order_header_redeemlist` WHERE `idmember`='$idmember' ");
	$query3 = $db->query("DELETE FROM `rewads_point_member` WHERE `idmember`='$idmember' ");
	$query4 = $db->query("DELETE FROM `address_book` WHERE `member_id`='$idmember' ");
	$query5 = $db->query("DELETE FROM `affiliate_member` WHERE `idmember_aid`='$idmember' ");
	$query6 = $db->query("DELETE FROM `billing_address` WHERE `idmember`='$idmember' ");
	$query7 = $db->query("DELETE FROM `claim_komis_member` WHERE `idmember_claim`='$idmember' ");
	$query8 = $db->query("DELETE FROM `corporate_user` WHERE `idmember_list`='$idmember' ");
	$query9 = $db->query("DELETE FROM `deposit_member_corporatelist` WHERE `idmember`='$idmember' ");
	$query10 = $db->query("DELETE FROM `deposit_member_corporatelist_konfirmasi` WHERE `idmember`='$idmember' ");
	$query11 = $db->query("DELETE FROM `member_membership_data` WHERE `idmember`='$idmember' ");
	$query12 = $db->query("DELETE FROM `member_membership_data_payment` WHERE `idmember`='$idmember' ");
	$queppp = $db->query("DELETE FROM `affiliate_memberid` WHERE `idmember` = '$idmember' ");
	
	
	
	//draf quote
	$quep = $db->query("SELECT `tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' ORDER BY `id` ASC ");
	while($data = $quep->fetch_assoc()):
		$tokendata = $data['tokenpay'];
		$quergg = $db->query("DELETE FROM `draft_quotation_detail` WHERE `tokenpay`='$tokendata' ");	
	endwhile;
	$query13 = $db->query("DELETE FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' ");
	
	//order header
	$quep2 = $db->query("SELECT `id`,`tokenpay` FROM `order_header` WHERE `idmember`='$idmember' ORDER BY `id` ASC ");
	while($data2 = $quep2->fetch_assoc()):
		$tokendata2 = $data2['tokenpay'];
		$quergg2 = $db->query("DELETE FROM `order_detail` WHERE `tokenpay`='$tokendata2' ");	
		$quergg3 = $db->query("DELETE FROM `konfirmasi_bayar` WHERE `idorder`='".$data2['id']."' ");	
	endwhile;
	$query14 = $db->query("DELETE FROM `order_header` WHERE `idmember`='$idmember' ");
		
	
	
	$query = $db->query("DELETE FROM `member` WHERE `id`='$idmember' ");		


echo'<script language="JavaScript">';
	echo'window.location="../member_list.php?msg=Delete successful.";';
echo'</script>';
?>