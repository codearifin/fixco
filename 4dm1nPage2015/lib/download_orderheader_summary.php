<?php
	require('../../config/connection.php');

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
	
	//update ANDRE 16 FEB 17
	$datepost = $_POST['datepost'];
	$datepost2 = $_POST['datepost2'];
	$output = "";

	$sql = "SELECT CONCAT('#',LPAD(oh.id,6,'0')) as `Order Id`, od.sku as `Product SKU`, od.name as `Product Name`, REPLACE(od.nama_detail,'#',' ') as `Product Detail Name`, od.qty as `Quantity`, od.price as `Product Price`
			FROM `order_detail` as od
			JOIN `order_header` as oh ON (oh.tokenpay = od.tokenpay)
	 		WHERE 1 = 1 and ( DATE_FORMAT(oh.date,'%Y-%m-%d') BETWEEN  '$datepost' and '$datepost2' )  ORDER BY oh.id ASC";

	$arr_structure 	= $db->query($sql);

  	$arr_data 		= $db->query($sql);

	$arr_structure  = $arr_structure->fetch_assoc();


	if($arr_structure){ foreach($arr_structure AS $key => $field){ $output .= strtoupper($key).','; }}

	$output .="\n";

	while ($row = $arr_data->fetch_assoc()){

		foreach ($arr_structure as $key => $field) {

			$output .= '"'.str_replace(array("\n","\r","\r\n", "\t"), " ", $row[$key]).'",';

		}

		$output .="\n";

	}

	// $date=explode('/',$datepost);
	// $datenews = $date[2].'-'.$date[1].'-'.$date[0];

	// $date2 = explode('/',$datepost2);
	// $lastN = mktime(0, 0, 0, $date2[1], $date2[0] , $date2[2]);
	// $datesampai	= date("Y-m-d", $lastN);		
	// $datekahir = $datesampai;	
			
	// $status_payment = $_POST['status_payment'];
	// $payment_metod = $_POST['payment_metod'];
	
	// if($status_payment=="ALL"):
		
	// 	if($payment_metod=="ALL"):
	// 		$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
	// 		WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' )  ORDER BY `id` ASC");			
	// 	else:
	// 		$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
	// 		WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' ) and `payment_metod`='$payment_metod'  ORDER BY `id` ASC");	
	// 	endif;
		
	// else:	
	
	// 	if($payment_metod=="ALL"):
	// 		$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
	// 		WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' ) and  `status_payment`='$status_payment' ORDER BY `id` ASC");		
	// 	else:
	// 		$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d%m%y') as tglso, DATE_FORMAT(`date`,'%d%/%m/%Y') as tglso2 FROM `order_header` 
	// 		WHERE 1 = 1 and ( DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN  '$datenews' and '$datekahir' ) and ( `payment_metod`='$payment_metod' and `status_payment`='$status_payment' ) ORDER BY `id` ASC");
	// 	endif;		
		
	// endif;
	
	// $output = "";
	// // Get The Field Name
	// $output .= 'NO SALES,';
	// $output .= 'BRAND,';
	// $output .= 'PRODUCT CATEGORY,';
	// $output .= 'ARTICLE,';
	// $output .= 'NAMA PRODUK,';
	// $output .= 'DESCRIPTION,';
	// $output .= 'SATUAN,';
	// $output .= 'QTY SALES,';
	// $output .= 'DISCOUNT PERCENTAGE,';
	// $output .= 'DISCOUNT VALUE,';
	// $output .= 'HARGA,';
	// $output .= 'TOTAL,';
	// $output .="\n";

	// // Get Records from the table
	// $idorder = ''; $tokenorder = '';
	// $sku_prod = ''; $colorname = '';
	// while ($row = $sql->fetch_assoc()){
	// 	$idorder = 'SO'.$row['tglso'].sprintf('%06d',$row['id']);
	// 	$tokenorder = $row['tokenpay'];
		
	// 	//select order detail
	// 	$nameproddetail = ''; $totalsub = 0;
	// 	$quippp = $db->query("SELECT * FROM `order_detail` WHERE `tokenpay`='$tokenorder' ORDER BY `id` ASC");
	// 	while ($data = $quippp->fetch_assoc()){
	
	// 		$itemwarnalist = explode("#",$data['nama_paket']);
	// 		$warnaprod = $itemwarnalist[0];
	// 		$EURsize = $itemwarnalist[1];
	// 		$totalsub = $data['price']*$data['qty'];
										
	// 		$nameproddetail = $warnaprod.' - '.$EURsize;
	// 		$output .= $idorder.','; //NO SALES
	// 		$output .= 'PIERO,'; //BRAND
	// 		$output .= getnamecateprod($data['idproduct']).','; //PRODUCT CATEGORY
	// 		$output .= $data['sku'].','; //ARTICLE
	// 		$output .= $data['name'].','; //NAME
	// 		$output .= $nameproddetail.','; //DESCRIPTION
	// 		$output .= 'PCS,';
	// 		$output .= $data['qty'].',';
	// 		$output .= '0,';
	// 		$output .= '0,';
	// 		$output .= $data['price'].',';
	// 		$output .= $totalsub.',';
	// 		$output .="\n";
	
	// 	}
		
	// }

	$namaexcel = date("Y.m.d-H.i.s-");
	// Download the file
	$filename = "order_header_summarylist_".$namaexcel.".csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $output;
	exit;
?>
