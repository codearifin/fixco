<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");

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
		$query = $db->query("SELECT `norek` FROM `konfirmasi_bayar` WHERE `idorder`='$idorder'");
		$jumpage = $query->num_rows;
		if($jumpage>0):		
			$row = $query->fetch_assoc();
			return '\''.$row['norek'];
		else:
			return '-';
		endif;	
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
	$output .= 'TANGGAL SALES,';
	$output .= 'NAMA MEMBER,';
	$output .= 'ALAMAT,';
	$output .= 'KELURAHAN,';
	$output .= 'KECAMATAN,';
	$output .= 'KOTA,';
	$output .= 'NO HANDPHONE,';
	
	$output .= 'NO REKENING,';
	$output .= 'ATAS NAMA REKENING,';
	
	$output .= 'NO RESI,';
	$output .= 'TOTAL QTY,';
	$output .= 'AMOUNT,';
	$output .= 'SHIPPING COST,';
	$output .= 'DISCOUNT,';
	$output .= 'TOTAL AMOUNT,';
	$output .= 'PAYMENT,';
	
	$output .= 'KETERANGAN,';
	$output .= 'METODE PEMBAYARAN,';
	$output .="\n";

	// Get Records from the table
	$idorder = ''; $tokenorder = ''; $membername  = ''; $hanphonemember = ''; $totalqtyorder = 0; $totalorder = 0;
	$totalorder2 = 0;
	while ($row = $sql->fetch_assoc()){
		
		$idorder = 'SO'.$row['tglso'].sprintf('%06d',$row['id']);
		$tokenorder = $row['tokenpay'];
		
		$totalqtyorder = getorderlistmember($tokenorder);
		$totalorder = ($row['orderamount']+$row['shippingcost']+$row['kode_unik'])-$row['discountamount'];
		if($row['note']==""):  $datanote = '-'; else: $datanote = replacename($row['note']); endif;
	
		$output .= $idorder.',';
		$output .= $row['tglso2'].','; 
		$output .= replacename($row['nama_penerima']).',';
		$output .= replacename($row['address_penerima']).',';
		$output .= replacename($row['kabupaten_penerima']).',';
		$output .= replacename($row['provinsi_penerima']).',';
		$output .= replacename($row['kota_penerima']).',';
		$output .= '\''.$row['phone_penerima'].',';
		$output .= getstatusbayar2($row['id']).',';
		$output .= getstatusbayar1($row['id']).',';
		
		if($row['resinumber']==""):
			$output .= '-,'; //NO RESI
		else:
			$output .= '\''.$row['resinumber'].','; //NO RESI
		endif;
		
		$output .= $totalqtyorder.','; //QTY
		$output .= $row['orderamount'].','; //AMOUNT
		$output .= $row['shippingcost'].','; //SHIPPING
		$output .= $row['discountamount'].','; //DISCOUNT
		$output .= $totalorder.','; //TOTAL AMOUNT
		$output .= $totalorder.','; //PAYMENT
		$output .= $datanote.',';
		$output .= $row['payment_metod'].','; //PAYMENT
		$output .="\n";		

		
	}
	
	$namaexcel = replacenamenameexcel($status_payment);
	// Download the file
	$filename = "order_headerlist_".$namaexcel.".csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $output;
	exit;
?>
