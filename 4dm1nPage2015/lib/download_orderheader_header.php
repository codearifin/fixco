<?php
	require('../../config/connection.php');

	function replacename($text){
			$str = array("/", "?","%", ",", "!" , "#", "$", "@", "^" ,"&","\"","\\","\r\n", "\n", "\r");
			$newtext=str_replace($str," ",$text);
			return $newtext;
	}	

	function replacenamenameexcel($text){
			$str = array(" ");
			$newtext = str_replace($str,"_",$text);
			return strtolower($newtext);
	}	
		
	function replacenamephone($text){
			$str = array("(", " ",")");
			$newtext=str_replace($str,"",$text);
			return $newtext;
	}
		
	function getnamecateprod($idprod){
		global $db;
		$quep = $db->query("SELECT `idkat` FROM `product` WHERE `id`='$idprod'");
		$data = $quep->fetch_assoc();
		$idkat = $data['idkat'];
		
		$query = $db->query("SELECT `name` FROM `category` WHERE `id`='$idkat'");
		$row = $query->fetch_assoc();
		return $row['name'];
	}
	
	function getmembername($idmember){
		global $db;
		$query = $db->query("SELECT `name`,`lastname` FROM `member` WHERE `id`='$idmember'");
		$row = $query->fetch_assoc();
		return $row['name'].' '.$row['lastname'];	
	}
	function getmembernamephone($idmember){
		global $db;
		$query = $db->query("SELECT `mobile_phone` FROM `member` WHERE `id`='$idmember'");
		$row = $query->fetch_assoc();
		return $row['mobile_phone'];
	}	
	
	function getorderlistmember($token){
		global $db;
		$total = 0;
		$query = $db->query("SELECT `qty` FROM `order_detail` WHERE `tokenpay`='$token'");
		while($row = $query->fetch_assoc()):
			$total = $total+$row['qty'];
		endwhile;	
		
		return $total;	
	}

	function getstatusbayar1($idorder){
		global $db;
		$query = $db->query("SELECT `atas_nama` FROM `konfirmasi_bayar` WHERE `idorder`='$idorder'");
		$jumpage = $query->num_rows;
		if($jumpage>0):
			$row = $query->fetch_assoc();
			return $row['atas_nama'];
		else:
			return '-';
		endif;	
	}

	function getstatusbayar2($idorder){
		global $db;
		$query = $db->query("SELECT `nama_bank` FROM `konfirmasi_bayar` WHERE `idorder`='$idorder'");
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			$row = $query->fetch_assoc();
			return $row['nama_bank'];
		else:
			return '-';
		endif;	
	}
	
	//update ANDRE 16 FEB 17
	$datepost = $_POST['datepost'];
	$datepost2 = $_POST['datepost2'];
	$output = "";

	// $date=explode('/',$datepost);
	// $datenews = $date[2].'-'.$date[1].'-'.$date[0];

	// $date2 = explode('/',$datepost2);
	// $lastN = mktime(0, 0, 0, $date2[1], $date2[0] , $date2[2]);
	// $datesampai	= date("Y-m-d", $lastN);		
	// $datekahir = $datesampai;	
			
	//$status_payment = $_POST['status_payment'];
	//$payment_metod = $_POST['payment_metod'];
	
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

	$sql = "SELECT CONCAT('#',LPAD(oh.id,6,'0')) as `Order Id`, oh.date, oh.nama_penerima as `Nama Penerima`,oh.phone_penerima as `Phone`,oh.kurir, oh.resinumber, oh.note, oh.address_penerima as `Address`, oh.kabupaten_penerima as `Kabupaten`, oh.kota_penerima as `Kota`, oh.provinsi_penerima as `Provinsi`, oh.country_penerima as `Negara`, oh.kodepos, oh.payment_metod `Metode Pembayaran`, oh.vouchercode as `Kode Voucher`, oh.orderamount as `Total Order`, oh.deposit_amount as `Deposit Amount`, oh.shippingcost as `Shipping Cost`, oh.handling_fee as `Handling Fee`, oh.weight as `weight`, oh.discountamount as `Discount Amount`, oh.status_payment as `Status Payment`, oh.status_delivery as `Status Delivery`
			FROM `order_header` as oh
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
	
	// $output = "";
	// // Get The Field Name
	// $output .= 'NO SALES,';
	// $output .= 'ORDER,';
	// $output .= 'TANGGAL SALES,';
	// $output .= 'NAMA MEMBER,';
	// $output .= 'ALAMAT,';
	// $output .= 'KELURAHAN,';
	// $output .= 'KECAMATAN,';
	// $output .= 'KOTA,';
	// $output .= 'NO HANDPHONE,';
	// $output .= 'NO REKENING,';
	// $output .= 'ATAS NAMA REKENING,';
	// $output .= 'NO RESI,';
	// $output .= 'TOTAL QTY,';
	// $output .= 'AMOUNT,';
	// $output .= 'SHIPPING COST,';
	// $output .= 'DISCOUNT,';
	// $output .= 'VOUCHER,';
	// $output .= 'TOTAL AMOUNT,';
	// $output .= 'PAYMENT,';
	// $output .= 'KETERANGAN,';
	// $output .= 'METODE PEMBAYARAN,';
	// $output .="\n";

	// // Get Records from the table
	// $idorder = ''; $tokenorder = ''; $membername  = ''; $hanphonemember = ''; $totalqtyorder = 0; $totalorder = 0;
	// $totalorder2 = 0;
	// while ($row = $sql->fetch_assoc()){
		
	// 	$idorder = 'SO'.$row['tglso'].sprintf('%06d',$row['id']);
	// 	$tokenorder = $row['tokenpay'];
		
	// 	$totalqtyorder = getorderlistmember($tokenorder);
	// 	$totalorder = ($row['orderamount']+$row['shippingcost']+$row['kode_unik'])-$row['discountamount'];
	// 	if($row['note']==""):  $datanote = '-'; else: $datanote = replacename($row['note']); endif;
		
	// 	//pecah ship address
	// 	$itemlistaddress = explode('<br />',$row['shippingaddess']);
	// 	$itempecah1 = explode('</strong>',$itemlistaddress[0]);
	// 	$namepecah1 = strip_tags($itempecah1[0]);
		
	// 	$namepecah2 = strip_tags($itempecah1[1]);
	// 	$phonepecah2 = replacenamephone($namepecah2);
		
	// 	$alamatmember = $itemlistaddress[1].' '.$itemlistaddress[2];
	// 	$namakotamember = $itemlistaddress[3];
	// 	$namakotamember1 = explode(',',$itemlistaddress[3]);

	// 		//select order detail
	// 		$totalorder_dtl = 0;
	// 		$quipoo = $db->query("SELECT * FROM `order_detail` WHERE `tokenpay` = '".$row['tokenpay']."' ORDER BY `id` ASC ");
	// 		while ($res = $quipoo->fetch_assoc()){		
					
	// 				$itemwarnalist = explode("#",$res['nama_paket']);
	// 				$warnaprod = $itemwarnalist[0];
	// 				$EURsize1 = explode('-',$itemwarnalist[1]);
	// 				$EURsize = $EURsize1[0];
	// 				$totalorder_dtl = $res['qty']*$res['price'];
					
	// 				$output .= $idorder.',';
	// 				$output .= $res['sku'].'-'.$EURsize.',';
	// 				$output .= $row['tglso2'].',';
	// 				$output .= replacename($namepecah1).',';
	// 				$output .= replacename($alamatmember).',';
	// 				$output .= replacename($namakotamember1[0]).',';
	// 				$output .= replacename($namakotamember1[1]).',';
	// 				$output .= replacename($namakotamember1[1]).',';
	// 				$output .= '\''.$phonepecah2.',';
	// 				$output .= getstatusbayar2($row['id']).',';
	// 				$output .= getstatusbayar1($row['id']).',';
	// 			    if($row['resinumber']==""): $output .= '-,'; else: $output .= '\''.$row['resinumber'].','; endif;
	// 				$output .= $res['qty'].',';
	// 				$output .= $res['price'].',';
	// 				$output .= $row['shippingcost'].',';
	// 				$output .= '0,';
	// 				$output .= $row['discountamount'].',';
	// 				$output .= $totalorder_dtl.',';
	// 				$output .= $totalorder.',';
	// 				$output .= $datanote.',';
	// 				$output .= $row['payment_metod'].',';
	// 				$output .="\n";
					
	// 		}
	// 		// end select order detail
		
	// }	
	// // end Get Records from the table
	
		
	$namaexcel = date("Y.m.d-H.i.s-");
	// Download the file
	$filename = "order_headerlist_".$namaexcel.".csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
		echo $output;
	exit;
?>
