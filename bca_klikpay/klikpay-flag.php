<?php
@session_start();
include("../config/connection.php");

// parsing request data
$data_sprint = array(
	"transactionNo" 	=> preg_match('/[a-zA-Z0-9]{3,18}/',$_REQUEST['transactionNo'],$match) ? $match[0] : NULL,
	"transactionDate" 	=> preg_match('/\d\d\/\d\d\/\d{4} \d\d:\d\d:\d\d/',$_REQUEST['transactionDate'],$match) ? $match[0] : NULL,
	"currency" 			=> preg_match('/[A-Z]{2,5}/',$_REQUEST['currency'],$match) ? $match[0] : NULL,
	"payType"			=> preg_match('/01|02|03/',$_REQUEST['payType'],$match) ? $match[0] : "01",
	"totalAmount" 		=> preg_match('/(\d{5,9})\D?/',$_REQUEST['totalAmount'],$match) ? $match[1] . ".00" : NULL,
	"approvalCode" 		=> array(
		"fullTransaction" 			=> preg_match('/\d+/',$_REQUEST['approvalCode']['fullTransaction'],$match) ? $match[0] : NULL,
		"installmentTransaction" 	=> preg_match('/\d+/',$_REQUEST['approvalCode']['installmentTransaction'],$match) ? $match[0] : NULL
	),
	"authKey" 			=> preg_match('/[A-Z0-9]{32}/',$_REQUEST['authKey'],$match) ? $match[0] : NULL
);

// Just a misc action
$additionalData = $_REQUEST['additionalData'];

// Get data from database
$bca_tokenid = $data_sprint['transactionNo'];
//new data field
$kode_trxno1 = $data_sprint['approvalCode']['fullTransaction'];
$kode_trxno2 = $data_sprint['approvalCode']['installmentTransaction'];

$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tglbel, DATE_FORMAT(`date`, '%d%m%Y') as tglbeltrx, unix_timestamp(`date`) as tanggal_trx FROM `order_header` WHERE `bca_tokenid`='$bca_tokenid' ");
$jumdatalist = $query->num_rows;

$row = $query->fetch_assoc();
$totalorder = (($row['orderamount']+$row['shippingcost']+$row['kode_unik']+$row['handling_fee'])-$row['discountamount'])-$row['deposit_amount'];	

if($row['bca_klik_pay_cicilan']>0):
	//BCA Config
	$klikPayCode	= "03FIXC0538";
	$clearKey		= "ClearKeyDevFixco";
else:
	//BCA Config
	$klikPayCode	= "03FIXC0538";
	$clearKey		= "ClearKeyDevFixco";
endif;


//kadaluarsa
$nowtime_tgltrx  = time();
$nowcheck_trx = $row['tanggal_trx'];
$hour_order_trx = $nowtime_tgltrx - $nowcheck_trx;
$sisa_waktu_trx = $hour_order_trx/60;
$jumlah_jam_trx = round($sisa_waktu_trx); //per menit

//600 menit
if($jumlah_jam_trx > 600):
	$order_is_expired = 'expiry_trx';
else:
	$order_is_expired = '';	
endif;	

// authKey initialization
include "klikpaykeys.php"; // Include module for Signature - AuthKey
$bcakey = new KlikPayKeys;



if ($jumdatalist < 1) {
	$status = "01";
	$reason_indonesian = "Transaksi Anda tidak dapat diproses.";
	$reason_english = "Your transaction cannot be processed.";
	$additionalData = "This order no in Database";

// 2. Check if transaction already paid in your database
} elseif ($row['status_payment'] == "Confirmed") {
	$status = "01";
	$reason_indonesian = "Transaksi Anda telah dibayar.";
	$reason_english = "Your transaction has been paid.";
		
// 4. Check if totalAmount mismatch
} elseif (intval($totalorder).".00" != $data_sprint['totalAmount']) {
	$status = "01";
	$reason_indonesian = "Transaksi Anda tidak dapat diproses.";
	$reason_english = "Your transaction cannot be processed.";
	$additionalData = "totalAmount mismatch (".intval($totalorder).".00"." : ".$data_sprint['totalAmount'].")";

// 5. Check if authKey mismatch. Use values from database for generating authKey
} elseif (($authkey = $bcakey->authkey($klikPayCode, $data_sprint['transactionNo'], "IDR", date('d/m/Y H:i:s',strtotime($row['date'])), $clearKey)) && $authkey != $data_sprint['authKey']) {
	$status = "01";
	$reason_indonesian = "Transaksi Anda tidak dapat diproses.";
	$reason_english = "Your transaction cannot be processed.";
	$additionalData = "authKey mismatch (". $authkey ." : ". $data_sprint['authKey'] .")";

// 6. Check if transactionDate mismatch
} elseif ($row['tglbel'] != $data_sprint['transactionDate'] ) {
	$status = "01";
	$reason_indonesian = "Transaksi Anda tidak dapat diproses.";
	$reason_english = "Your transaction cannot be processed.";
	$additionalData = "transactionDate mismatch (".$row['tglbel']." : ".$data_sprint['transactionDate'].")";

// Your other conditions like order was expired or canceled
} elseif ($order_is_expired=="expiry_trx") {
	$status = "01";
	$reason_indonesian = "Transaksi Anda telah kedaluwarsa.";
	$reason_english = "Your transaction has expired.";

} elseif ($row['status_payment'] == "Cancelled By Admin") {
	$status = "01";
	$reason_indonesian = "Transaksi Anda telah dibatalkan.";
	$reason_english = "Your transaction has been canceled.";
	
// Payment is success
} else {
	$status = "00";
	$reason_indonesian = "Sukses";
	$reason_english = "Success";

	//running query
	$quep = $db->query("UPDATE `order_header` SET `status_payment`='Confirmed', `kode_trxno_klikpay1`='$kode_trxno1', `kode_trxno_klikpay2`='$kode_trxno2' WHERE `bca_tokenid`='$bca_tokenid' ");
}


// Output content type text/xml
header("Content-type: text/xml");
// Sprint's Gateway only accept encoding "ISO-8859-1" or "UTF-8"
echo '<?xml version="1.0" encoding="utf-8"?>';	
// And here is the output XML Response
?>

<OutputPaymentIPAY>
  <klikPayCode><?php echo $klikPayCode;?></klikPayCode> 
  <transactionNo><?php echo $data_sprint['transactionNo'];?></transactionNo> 
  <transactionDate><?php echo $data_sprint['transactionDate'];?></transactionDate> 
  <currency>IDR</currency> 
  <totalAmount><?php echo $data_sprint['totalAmount'];?></totalAmount> 
  <payType><?php echo $data_sprint['payType'];?></payType> 
  <approvalCode> 
    <fullTransaction><?php echo $data_sprint['approvalCode']['fullTransaction'];?></fullTransaction> 
    <installmentTransaction><?php echo $data_sprint['approvalCode']['installmentTransaction'];?></installmentTransaction> 
  </approvalCode>
  <status><?php echo $status;?></status> 
  <reason> 
    <indonesian><?php echo $reason_indonesian;?></indonesian> 
    <english><?php echo $reason_english;?></english> 
  </reason> 
  <additionalData><?php $additionalData;?></additionalData> 
</OutputPaymentIPAY>