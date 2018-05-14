<?php 
@session_start();
require('../../config/connection.php');
function replaceamount($amount){
	$str = array(",");
	$newtext=str_replace($str,"",strtolower($amount));
	return $newtext;
}	

function replaceamounttgl($amount){
	$str = array("/");
	$newtext=str_replace($str,"-",$amount);
	return $newtext;
}	

if(isset($_POST['submit'])){
			
			$datenow = date("Y-m-d H:i:s");	
			
			$jumlah_voucher = $_POST['jumlah_voucher'];

			$datenews = replaceamounttgl($_POST['datepost']);
			$datenews2 = replaceamounttgl($_POST['datepost2']);

			$diskon_typeid 	  = $_POST['diskon_typeid'];
			$diskonval 		  = $_POST['price'];

			$minbelanja = replaceamount($_POST['minbelanja']);
			$makbelanja = replaceamount($_POST['makbelanja']);
			
			$min_qty_beli = $_POST['min_qty_beli'];
			$status_item = $_POST['status_item'];
			
			$product_item = $_POST['product_item'];
			$category_item = $_POST['category_item'];
			$brand_item	 = "";
			$stock = $_POST['stock'];
			$publish = $_POST['publish'];
			
			$discount_status = $_POST['discount_status'];
			
			
				
	for($x = 0; $x<$jumlah_voucher;$x++){

			$quip = $db->query("SELECT max(`id`) as `makid` FROM `voucher_online`");
			$re = $quip->fetch_assoc();
			$makid = $re['makid']+1;
			//cari code
			$daytoken = date("YmdHis");	
			$codeToken = sha1($daytoken.$makid);
			$get_codev = substr($codeToken,3,10);
			$get_codev_2 = 'FX'.$get_codev;		
			$codeVoucher  = strtoupper($get_codev_2);
		
			if($codeVoucher<>''):
				$query = $db->query("INSERT INTO `voucher_online` (`id`, `voucher_code`, `start_date`, `end_date`, `diskon_type`, `diskon_value`, `min_transaksi_price`, `mak_transaksi_price`, `mak_item_buy`, `status_item`, 
				`product_item`, `category_item`,`brand_item`, `stock`, `discount_status`, `publish`, `sortnumber`, `modified_datetime`, `modified_by`) 
				
				VALUES ('$makid','$codeVoucher','$datenews','$datenews2','$diskon_typeid','$diskonval','$minbelanja','$makbelanja','$min_qty_beli','$status_item','$product_item','$category_item',
				'$brand_item','$stock','$discount_status','$publish','$makid','$datenow','1') ");		
			endif;
		
	}
	
		
	echo'<script language="JavaScript">';
		echo'window.location="../view.php?menu=member&submenu=voucher_online";';
	echo'</script>';		
}
?>
