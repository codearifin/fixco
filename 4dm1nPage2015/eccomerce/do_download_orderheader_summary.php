<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");

	function getnamecateprod($idprod){
		global $db;
		$quep = $db->query("SELECT `idkat` FROM `product` WHERE `id`='$idprod'");
		$data = $quep->fetch_assoc();
		$idkat = $data['idkat'];
		
		$query = $db->query("SELECT `name` FROM `category` WHERE `id`='$idkat'");
		$row = $query->fetch_assoc();
		return $row['name'];
	}

	function replacenamenameexcel($text){
			$str = array(" ");
			$newtext = str_replace($str,"_",$text);
			return strtolower($newtext);
	}	
	
	//update RNT 2 dec 15
	$datenews = $_POST['tgl_y'].'-'.$_POST['tgl_m'].'-'.$_POST['tgl_d'];
	
	$lastN = mktime(0, 0, 0, $_POST['tgl_m2'], $_POST['tgl_d2'] , $_POST['tgl_y2']);
	$datesampai	= date("Y-m-d", $lastN);		
	$datekahir = $datesampai;	
			
	$status_payment = $_POST['status_payment'];
	$payment_metod = $_POST['payment_metod'];
	
	if($status_payment=="ALL"):
		
		if($payment_metod=="ALL"):
			$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
			WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' )  ORDER BY `id` ASC") or die($db->error);			
		else:
			$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
			WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' ) and `payment_metod`='$payment_metod'  ORDER BY `id` ASC") or die($db->error);
		endif;
		
	else:	
	
		if($payment_metod=="ALL"):
			$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
			WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' ) and  `status_payment`='$status_payment' ORDER BY `id` ASC") or die($db->error);	
		else:
			$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
			WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' ) and ( `payment_metod`='$payment_metod' and `status_payment`='$status_payment' ) ORDER BY `id` ASC") or die($db->error);
		endif;		
		
	endif;
	
	$output = "";
	// Get The Field Name
	$output .= 'NO SALES,';
	$output .= 'BRAND,';
	$output .= 'PRODUCT CATEGORY,';
	$output .= 'ARTICLE,';
	$output .= 'NAMA PRODUK,';
	$output .= 'DESCRIPTION,';
	$output .= 'SATUAN,';
	$output .= 'QTY SALES,';
	$output .= 'DISCOUNT PERCENTAGE,';
	$output .= 'DISCOUNT VALUE,';
	$output .= 'HARGA,';
	$output .= 'TOTAL,';
	$output .="\n";

	// Get Records from the table
	$idorder = ''; $tokenorder = '';
	$sku_prod = ''; $colorname = '';
	while ($row = $sql->fetch_assoc()){
		$idorder = 'SO'.$row['tglso'].sprintf('%06d',$row['id']);
		$tokenorder = $row['tokenpay'];
		
		//select order detail
		$nameproddetail = ''; $totalsub = 0;
		$quippp = $db->query("SELECT * FROM `order_detail` WHERE `tokenpay`='$tokenorder' ORDER BY `id` ASC");
		while ($data = $quippp->fetch_assoc()){
	
			$itemwarnalist = explode("#",$data['nama_detail']);
			$warnaprod = $itemwarnalist[0];
			$EURsize = $itemwarnalist[1];
			$totalsub = $data['price']*$data['qty'];
										
			$nameproddetail = $warnaprod.' - '.$EURsize;
			$output .= $idorder.','; //NO SALES
			$output .= 'MIZUNO,'; //BRAND
			$output .= getnamecateprod($data['idproduct']).','; //PRODUCT CATEGORY
			$output .= $data['sku'].','; //ARTICLE
			$output .= $data['name'].','; //NAME
			$output .= $nameproddetail.','; //DESCRIPTION
			$output .= 'PCS,';
			$output .= $data['qty'].',';
			$output .= '0,';
			$output .= '0,';
			$output .= $data['price'].',';
			$output .= $totalsub.',';
			$output .="\n";
	
		}
		
	}

	$namaexcel = replacenamenameexcel($status_payment);
	// Download the file
	$filename = "order_header_summarylist_".$namaexcel.".csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $output;
	exit;
?>
