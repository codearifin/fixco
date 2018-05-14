<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('function.php');	
		
if(isset($_POST['submit'])){
		 
		$token = $_POST['token'];
		$idpord = $_POST['idpord'];
		$iddetail = $_POST['iddetail'];
		$qty = replaceamount($_POST['qty']);
		
		$skupord = getnamegeneral($iddetail,"product_detail","sku_product");
		$nameprod = getnamegeneral($idpord,"product","name");
		$namedetail = getnamegeneral($iddetail,"product_detail","title");
		
		$diskonprice = getnamegeneral($idpord,"product","discount_value");
		$price1 = getnamegeneral($iddetail,"product_detail","price");
		if($diskonprice>0):
			$diskonval1 = ($price1*$diskonprice)/100;
			$diskonval2 = round($diskonval1);
			$priceprod = $price1-$diskonval1;
		
		else:
			$priceprod = $price1;
		endif;	
		
		
		if($token!="" and $idpord >0 and $qty>0 and $iddetail>0):
			
			//member data
			if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
			$qummep = $db->query("SELECT `id`,`idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
			$ros = $qummep->fetch_assoc();	
			$idmember = $ros['idmember_list']; $idusr = $ros['id'];
			
			if($ros['status']==1):
				$que = $db->query("SELECT `tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `tokenpay`='$token'");
			else:
				$que = $db->query("SELECT `tokenpay` FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `id_user` = '$idusr' and `tokenpay`='$token'");
			endif;
			$row = $que->fetch_assoc();	
			$tokendatabse = $row['tokenpay'];
			
			//first update or cek id product
			$quppprod = $db->query("SELECT `id` FROM `draft_quotation_detail` WHERE `tokenpay` = '$tokendatabse' and `idproduct` = '$idpord' and `iddetail` = '$iddetail' ");
			$jumpage = $quppprod->num_rows;
			if($jumpage>0):
				$quppprod3 = $db->query("UPDATE `draft_quotation_detail` SET `qty` = `qty` + '$qty' WHERE `tokenpay` = '$tokendatabse' and `idproduct` = '$idpord' and `iddetail` = '$iddetail' ");	
			else:
				$quppprod3 = $db->query("INSERT INTO `draft_quotation_detail` (`tokenpay`,`idproduct`,`sku`,`iddetail`,`name`,`nama_detail`,`qty`,`price`) 
				VALUES ('$tokendatabse','$idpord','$skupord','$iddetail','$nameprod','$namedetail','$qty','$priceprod') ");		
			endif;
			
			if($quppprod3):
				//last update weight and ongkir
				updateongkirTotal($tokendatabse);
			endif;
				
		endif;	 	
	
		$_SESSION['error_msg'] = 'saveprodoke';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'quotation-detail/'.$tokendatabse.'"</script>';
}
?>		
	