<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("function.php");
	require("webconfig-parameters.php");
	
	function replacehape($nomerhape){
		$jumlahkata = strlen($nomerhape);
		$kodearea = "62";
		$nohapebarulist = substr($nomerhape,1,$jumlahkata);
		return $kodearea.$nohapebarulist;
	}
	
	function tambahkanaffiliate($idmembernew,$idorder,$orderdate,$grandtot_member,$member_name){
		global $db;
		$query = $db->query("SELECT * FROM `affiliate_memberid` WHERE `anggotanya_member_id` = '$idmembernew' ");
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$idmemberdapet = 0; $komisipersen = 0; 
			while($row = $query->fetch_assoc()):
					$idmemberdapet = $row['member_id'];
					$komisipersen = $row['komisi_persen'];
					
					//insert komisi
					$totalkomisi_1 = $grandtot_member*$komisipersen;
					$totalkomisi_2 = $totalkomisi_1/100;
					$totalkomisi_3 = round($totalkomisi_2);
					if($totalkomisi_3>0):
						
						$textdata5 = 'Pembelanjaan Order ID #'.sprintf('%06d',$idorder).'';
						$quipst5 = $db->query("INSERT INTO `komisilist_member` (`idmember`,`member_name`,`date`,`description`,`amount`,`status`) 
						VALUES ('$idmemberdapet','$member_name','$orderdate','$textdata5','$totalkomisi_3','1') ");	
									
					endif;	
					//insert komisi
					
									
					
			endwhile;
		endif;	
	}

	//user admin
	$useradminid = $_SESSION[$SITE_TOKEN.'userID'];
	//save order
	date_default_timezone_set('Asia/Jakarta');
	$dateNow = date("d-m-Y H:i:s");
												
	$idorder = $_GET['idorder'];
	$que = $db->query("SELECT * FROM `order_header` WHERE `id`='$idorder' ");
	$row = $que->fetch_assoc();
	
	$ordidmm = $row['id'];
	$idmembernew = $row['idmember'];
	$tokenordernew = $row['tokenpay'];
	$grandtot_member = $row['orderamount'];

	if($row['payment_metod']=="Deposit"):
		$totalamount = ( ( $row['orderamount']+$row['shippingcost'] ) - $row['discountamount'] ) + $row['kode_unik'] + $row['handling_fee'];
	else:	
		$totalamount = ( ( ( $row['orderamount']+$row['shippingcost'] ) - $row['discountamount'] ) + $row['kode_unik'] + $row['handling_fee']) - $row['deposit_amount'];
	endif;

	if($row['phone_penerima']<>''): $Phonelabel = '('.$row['phone_penerima'].')'; else: $Phonelabel = ''; endif;
	
	//select member
	$query = $db->query("SELECT * FROM `member` WHERE `id`='$idmembernew'");
	$res = $query->fetch_assoc();
	$member_name = $res['name'].' '.$res['lastname'];
	$emailmembername = $res['email'];
	$nomerhape_member = replacehape($res['mobile_phone']);	
	
	
	if($row['status_payment'] <> "Confirmed"):
		$quipst3 = $db->query("UPDATE `order_header` SET `status_payment`='Confirmed', `konfirmasi_bayar_byadmin`='$useradminid', `tanggal_konfirmasi`='$dateNow' WHERE `id`='$ordidmm'");	
		
		$totalpointmember1 = $row['orderamount']/$point_reward_syarat;
		$totalpointmember2 = round($totalpointmember1);
		if($totalpointmember2>0):
			$dateorder = $row['date'];
			$textdata = 'Pembelanjaan Order ID #'.sprintf('%06d',$row['id']).'';
			$quipst4 = $db->query("INSERT INTO `rewads_point_member` (`idmember`,`date`,`description`,`point`,`status`) VALUES ('$idmembernew','$dateorder','$textdata','$totalpointmember2','1') ");	
		endif;
		
		tambahkanaffiliate($idmembernew,$row['id'],$row['date'],$grandtot_member,$member_name);
		
	endif;
		
	
	require("email_orderan/confirm-payment-email-order.php");		 
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../ordermember.php?msg=Confrim successful.";';
	echo'</script>';		
?>                    