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
										
	$idorder = $_GET['idorder'];
	$que = $db->query("SELECT * FROM `order_header` WHERE `id`='$idorder' ");
	$row = $que->fetch_assoc();
	
	$ordidmm = $row['id'];
	$idmembernew = $row['idmember'];
	$tokenordernew = $row['tokenpay'];

	if($row['payment_metod']=="Deposit"):
		$totalamount = ( ( $row['orderamount']+$row['shippingcost'] ) - $row['discountamount'] ) + $row['kode_unik']+ $row['handling_fee'];
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
	
	$quipst3 = $db->query("UPDATE `order_header` SET `status_payment`='Cancelled By Admin', `status_delivery`='Cancelled By Admin' WHERE `id`='$ordidmm'");	
	
	if($quipst3):
		$idprod = ''; $totalqty = 0; 
		$que33 = $db->query("SELECT `iddetail`,`qty` FROM `order_detail` WHERE `tokenpay`='$tokenordernew' ORDER BY `id` ASC");
		while($data = $que33->fetch_assoc()):
			
			$idprod = $data['iddetail'];
			$totalqty = $data['qty'];
			$quipst2 = $db->query("UPDATE `product_detail_size` SET `stock` = `stock` + '$totalqty' WHERE `id`='$idprod' ");	
			
		endwhile;		
	endif;
		
	require("email_orderan/cancel-order-email-order.php");		 
			 	
	echo'<script language="JavaScript">';
		echo'window.location="../ordermember.php?msg=Cancel successful.";';
	echo'</script>';		
?>                    