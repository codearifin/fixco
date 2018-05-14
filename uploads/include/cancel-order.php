<?php
	@session_start();	
	require('../config/connection.php');
	require('../config/myconfig.php');
	require('function.php');
	require('webconfig-parameters.php');;		
					
	if(isset($_GET['code'])): $code = $_GET['code']; else: $code = ''; endif;
	$itemid = explode("-",$code);
	$idorder = $itemid[0];
	$tokenpay = $itemid[1];
	
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; endif;
	$query = $db->query("SELECT `id`,`email` FROM `member` WHERE `tokenmember`='$Usertokenid'");
	$res = $query->fetch_assoc();
	$idmember = $res['id'];
	$emailmember = $res['email'];
	
	
	$que = $db->query("SELECT * FROM `order_header` WHERE `id`='$idorder' and `idmember`='$idmember' and `tokenpay`='$tokenpay'");
	$row = $que->fetch_assoc();
	$totalamount = (($row['orderamount']+$row['shippingcost']+$row['kode_unik']+$row['handling_fee'])-$row['discountamount']);	
	$ordidmm = $row['id'];
	
	$quipst3 = $db->query("UPDATE `order_header` SET `status_payment`='Cancelled By User',`status_delivery`='Cancelled By User' WHERE `id`='$idorder' and `idmember`='$idmember' and `tokenpay`='$tokenpay'");
   
			   
	$idprod = ''; $totalqty = 0; 
	$que33 = $db->query("SELECT `iddetail`,`qty` FROM `order_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
	while($data = $que33->fetch_assoc()):
		$iddetail = $data['iddetail'];
		$totalqty = $data['qty'];
		$quipst2 = $db->query("UPDATE `product_detail` SET `stock` = `stock` + '$totalqty' WHERE `id`='$iddetail'");	
	endwhile;	
	
	require("cancelorder-memberemail.php");
				
	$_SESSION['error_msg']='ordercancel';     
	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'order-history"</script>';	
	
?>                    