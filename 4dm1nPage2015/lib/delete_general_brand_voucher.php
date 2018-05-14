<?php 
@session_start();
require('../../config/connection.php');
$idvoucher = $_POST['idvoucher'];
$idbrand = $_POST['idbrand'];
	
$query = $db->query("SELECT * FROM `voucher_online` WHERE `id` = '$idvoucher' ");
$res = $query->fetch_assoc();
$brand_item = $res['brand_item'];
if($brand_item!=""){

	$outputdata = ''; $outputdata_hasil = '';
	$listbranddata = explode("#",$brand_item);
	$jumlistdata = count($listbranddata);
	for($i = 0; $i<$jumlistdata; $i++){
		$idbranddata = $listbranddata[$i];
		if($idbranddata==$idbrand):
			//not save
		else:
			$outputdata.="#".$idbranddata;	
		endif;
	}							
	
	if($outputdata!=""):
		$outputdata_hasil = ltrim($outputdata,"#");
	else:
		$outputdata_hasil = '';
	endif;
	
	
	$queryupdate = $db->query("UPDATE `voucher_online` SET `brand_item` = '$outputdata_hasil' WHERE `id` = '$idvoucher' ");
	if($queryupdate):
		echo 1;
	else:
		echo 2;
	endif;
	
}else{
	echo 1;
}
?>
