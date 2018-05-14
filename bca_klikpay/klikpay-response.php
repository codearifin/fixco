<?php
 @session_start();
 include("../include/connection.php");

 function cancelorderlistmember($bca_tokenid){		
		$que = mysql_query("SELECT `id`,`tokenpay` FROM `order_header` WHERE `bca_tokenid` = '$bca_tokenid' ") or die(mysql_error());
		$jumpage = mysql_num_rows($que);
		if($jumpage>0):
				$row = mysql_fetch_assoc($que);
				$ordidmm = $row['id'];
					
				$quipst3 = mysql_query("UPDATE `order_header` SET `status_payment`='Transaction Fail',`status_delivery`='Transaction Fail' WHERE `id`='$ordidmm' ");
				
				$idprod = ''; $totalqty = 0; 
				$que33=mysql_query("SELECT `iddetail`,`qty` FROM `order_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
				while($data=mysql_fetch_assoc($que33)):
					$iddetail = $data['iddetail'];
					$totalqty = $data['qty'];
					$quipst2 = mysql_query("UPDATE `product_detail_size` SET `stock` = `stock`+'$totalqty' WHERE `id`='$iddetail'");	
				endwhile;	
		endif;		
 }	
		
 $checksumResponse = MD5(
    $_POST['acquirerApprovalCode'].
    $_POST['acquirerCode'].
    $_POST['acquirerMerchantAccount'].
    $_POST['acquirerResponseCode'].
    $_POST['amount'].
    $_POST['cardNo'].
    $_POST['currency'].
    $_POST['merchantTransactionID'].
    $_POST['scrubCode'].
    $_POST['scrubMessage'].
    $_POST['serviceVersion'].
    $_POST['siteID'].
    $_POST['SOML'].
    $_POST['transactionDescription'].
    $_POST['transactionID'].
    $_POST['transactionStatus'].
    $_POST['transactionType'].
    $_POST['userDefineValue'].
    "4829F366-E63E-232D-F5138412E490FA40" 
 ); 
 
if( $_POST['checksumResponse'] == strtoupper($checksumResponse) ){  
  	//checksumResponse is valid  
	 $statuspayment = $_POST['transactionStatus'];
	 $idtrx_odr = $_POST['merchantTransactionID'];
	 if($statuspayment=="APPROVED"){
		if( $_POST['acquirerApprovalCode'] != "" ){ // IF TRANSACTION APPROVED acquirerApprovalCode != ""
			$quep = mysql_query("UPDATE `order_header` SET `status_payment`='Confirmed' WHERE `bca_tokenid`='$idtrx_odr' ");
		}else{
			//trx tidak valid
			cancelorderlistmember($idtrx_odr);
		}
	 }
	 
	 echo'<script type="text/javascript">window.location="/visa-master-confirmation/'.$idtrx_odr.'/'.$statuspayment.'"</script>';
	 
 }else{
 	 //trx tidak valid
	 echo'<script type="text/javascript">window.location="/visa-master-confirmation/00/fail"</script>';
 }

?>