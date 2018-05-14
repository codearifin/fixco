<?php
@session_start();
include("../config/connection.php");			
include("klikpaykeys.php");		

$klikPayCode	= "03FIXC0538";					// klikPayCode, unique ID per merchant, provided by Sprint on development, provided by BCA on production.
$clearKey		= "ClearKeyDevFixco"; 			//dev ClearKeyDevPiero live IeOioP10p4s3Rd1n			// Clearkey or secret key, provided by Sprint on development, provided by BCA on production.
$sprint_IPs		= array("202.59.167.220", "103.28.57.108", "103.28.57.85");	// IP addresses of Sprint's Gateway. First one is development, remove on production.
$merchantIds	= array("03" => "000405993", "06" => "000405994");	// MerchantID provided by BCA, unique per tenor (03, 06, or 12).

// parsing request data (data from BCA / Sprint)
$data_sprint = array(
	"klikPayCode"		=> $_REQUEST['klikPayCode'],
	"transactionNo" 	=> preg_match('/[a-zA-Z0-9]{3,18}/',$_REQUEST['transactionNo'],$match) ? $match[0] : NULL,
	"signature" 		=> preg_match('/\d+/',$_REQUEST['signature'],$match) ? $match[0] : NULL,
	"additionalData"	=> $_REQUEST['additionalData'],
	"authKey" 			=> preg_match('/[A-Z0-9]{32}/',$_REQUEST['authKey'],$match) ? $match[0] : NULL
);

//new
$authKey_sprint = $_REQUEST['signature'];

// Get transaction data from database
$sqlodr = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tglbel, DATE_FORMAT(`date`, '%d%m%Y') as tglbeltrx, unix_timestamp(`date`) as tanggal_trx FROM `order_header` WHERE `bca_tokenid`='".$data_sprint['transactionNo']."' ");
$jumpage = $sqlodr->num_rows;
$order  = $sqlodr->fetch_assoc();

//total orderan gw
$totalorderList = (($order['orderamount']+$order['shippingcost']+$order['kode_unik']+$order['handling_fee'])-$order['discountamount'])-$order['deposit_amount'];	
$jumlahcicilan = sprintf('%02d',$order['bca_klik_pay_cicilan']);

//news bca update
$bca_tokenid_notrx = $order['bca_tokenid'];
$signature_bca_website = $order['signature_bca'];

// Validate request signature
$keys = new KlikPayKeys;
$signature = $keys->signature($data_sprint['klikPayCode'], $data_sprint['transactionNo'], "IDR", $clearKey, date('d/m/Y H:i:s', strtotime($order['date'])), $totalorderList.".00");


// Get details transaction data from database, max 5 transaction
$totqtyitem = 0;
$sql = $db->query("SELECT * FROM `order_detail` WHERE `tokenpay` = '".$order['tokenpay']."'");
$installment = "";  $totalorderItem = 0;
while ($row = $sql->fetch_assoc()) {
	$fullTransaction = array();
	
	$totalorderItem = $row['qty']*$row['price'];		  
	$totqtyitem = $totqtyitem+$row['qty'];
}


if($jumpage>0):
	
	if($totalan_cicilan>0):
		
		$new_nomer_trx = $data_sprint['transactionNo'];
		$currency_data = 'IDR';
		
		$installment .= sprintf('
			  <installmentTransaction>
				<itemName>Order in Fixcomart</itemName>
				<quantity>'.$totqtyitem.'</quantity>
				<amount>'.$totalorderList.'.00</amount>
				<tenor>'.$jumlahcicilan.'</tenor>
				<codePlan>000</codePlan>
				<merchantId>'.$merchantIds[ $jumlahcicilan ].'</merchantId>
			  </installmentTransaction>');
			  
		$datalist = '<fullTransaction>
						<amount>0.00</amount>  
						<description></description>  
					 </fullTransaction>  
					 '.$installment.'
					 <miscFee>0.00</miscFee>  
					 <additionalData></additionalData>';
					 	
	else:
		
		if( $data_sprint['transactionNo'] != $bca_tokenid_notrx or $signature_bca_website != $authKey_sprint  ):
			
			$new_nomer_trx = ""; $currency_data = '';
			$datalist = '<fullTransaction> 
						  <amount></amount> 
						  <description></description> 
						 </fullTransaction> 
						 <installmentTransaction></installmentTransaction> 
						 <miscFee></miscFee>';			 			
		else:		  
			
			$currency_data = 'IDR';
			$new_nomer_trx = $data_sprint['transactionNo'];
			$datalist = '<fullTransaction> 
						  <amount>'.$totalorderList.'.00</amount> 
						  <description></description> 
						 </fullTransaction> 
						 <installmentTransaction></installmentTransaction> 
						 <miscFee>0.00</miscFee>';
					 
		endif;
		
	
	endif;			 
				 
else:
			$new_nomer_trx = ""; $currency_data = '';
			$datalist = '<fullTransaction> 
						  <amount></amount> 
						  <description></description> 
						 </fullTransaction> 
						 <installmentTransaction></installmentTransaction> 
						 <miscFee></miscFee>';		  
endif;

// Output content type text/xml
header("Content-type: text/xml");
// Sprint's Gateway only accept encoding "ISO-8859-1" or "UTF-8"
echo '<?xml version="1.0" encoding="utf-8"?>';	
// And here is the output XML Response
?>
<OutputListTransactionIPAY>  
  <klikPayCode><?php echo $data_sprint['klikPayCode'];?></klikPayCode>  
  <transactionNo><?php echo $new_nomer_trx;?></transactionNo>  
  <currency><?php echo $currency_data;?></currency>  
  <?php echo $datalist;?>
</OutputListTransactionIPAY>