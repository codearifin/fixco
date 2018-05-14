<?php 	
	 @session_start();
	 include('config/connection.php');
	 include "include/function.php";
	 include "config/myconfig.php";

	  function cancelorderlistmember($orderid){		
			global $db;
			$que = $db->query("SELECT `id`,`tokenpay` FROM `order_header` WHERE `id`='$orderid' and `status_payment`='Pending On Payment' ");
			$jumpage = $que->num_rows;
			if($jumpage>0):
				$row = $que->fetch_assoc();
				$ordidmm = $row['id'];
				
				$quipst3 = $db->query("UPDATE `order_header` SET `status_payment`='Transaction Fail', `status_delivery`='Transaction Fail' WHERE `id`='$orderid' ");
			
				$idprod = ''; $totalqty = 0; 
				$que33 = $db->query("SELECT `iddetail`,`qty` FROM `order_detail` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
				while($data = $que33->fetch_assoc()):
					$iddetail = $data['iddetail'];
					$totalqty = $data['qty'];
					$quipst2 = $db->query("UPDATE `product_detail` SET `stock` = `stock`+'$totalqty' WHERE `id`='$iddetail'");	
				endwhile;	
			endif;			
		}

		function tambahkanaffiliate($idmembernew,$idorder,$orderdate,$grandtot_member,$member_name){
			global $db;
			$query = $db->query("SELECT * FROM `affiliate_memberid` WHERE `anggotanya_member_id` = '$idmembernew' ");
			$jumpage = $query->num_rows;
			if($jumpage>0):
				$idmemberdapet = 0; $komisipersen = 0; 
				while($row = $query->fetch_assoc()):
						$idmemberdapet = $row['member_id'];
						$komisipersen = $row['komisi_persen'];
						
						//insert komisi
						$totalkomisi_1 = $grandtot_member*$komisipersen;
						$totalkomisi_2 = $totalkomisi_1/100;
						$totalkomisi_3 = round($totalkomisi_2);
						if($totalkomisi_3>0):
							
							$textdata5 = 'Pembelanjaan Order ID #'.sprintf('%06d',$idorder).'';
							$quipst5 = $db->query("INSERT INTO `komisilist_member` (`idmember`,`member_name`,`date`,`description`,`amount`,`status`) 
							VALUES ('$idmemberdapet','$member_name','$orderdate','$textdata5','$totalkomisi_3','1') ");	
										
						endif;	
						//insert komisi
						
										
						
				endwhile;
			endif;	
		}
			
		function insertkomisimember($orderid){
			global $db;
			
			$que = $db->query("SELECT * FROM `order_header` WHERE `id`='$orderid' and `status_payment`='Pending On Payment' ");
			$res = $que->fetch_assoc();
			
			$grandtot_member = $res['orderamount'];
			$idmembernew = $res['idmember'];
			$affiliate_memberid = $res['affiliate_memberid'];

			$queppp = $db->query("SELECT `name`,`lastname` FROM `member` WHERE `id`='$idmembernew'");
			$data = $queppp->fetch_assoc();
			$member_name = $data['name'].' '.$data['lastname'];
				
			tambahkanaffiliate($idmembernew,$res['id'],$res['date'],$grandtot_member,$member_name);		
		}	
	 
		//VERITRANS NOTIFICATION.
		include_once('veritrans/veritrans-php-master/Veritrans.php');
		Veritrans_Config::$serverKey = "VT-server-ep12_4yLkHITcQp5MebDz2-R";
						
		$notif = new Veritrans_Notification();
		$transaction = $notif->transaction_status;
		$fraud = $notif->fraud_status;
		
		$order_id = (int) $notif->order_id; 
		$order_idLIST = sprintf('%01d',$order_id);
		
		echo 'Order ID. '.$order_idLIST;
		echo '<br />Status. '.$transaction;
		echo '<br />Status Detail. '.$fraud;
		
		if ( strtolower($transaction) == 'capture') {
				if (strtolower($fraud) == 'challenge') {
					 // TODO Set payment status in merchant's database to 'challenge'
					 $arr_update = array("status_payment" => "challenge");
					 $que = $db->query("UPDATE `order_header` SET `status_payment`='Waiting' WHERE `id`='$order_idLIST' ");
				}
				else if (strtolower($fraud) == 'accept') {
					 // TODO Set payment status in merchant's database to 'success'
					$arr_update = array("status_payment" => "approve");
					
					 //komisi
					 insertkomisimember($order_idLIST);  
					 
					 $que = $db->query("UPDATE `order_header` SET `status_payment`='Confirmed' WHERE `id`='$order_idLIST' ");
					 
				}else{
					 $que = $db->query("UPDATE `order_header` SET `status_payment`='Waiting' WHERE `id`='$order_idLIST' ");
				}

		}else if (strtolower($transaction) == 'settlement') {
				
				$que = $db->query("UPDATE `order_header` SET `status_payment`='Waiting' WHERE `id`='$order_idLIST' ");
								
		}else if (strtolower($transaction) == 'cancel') {
				if (strtolower($fraud) == 'challenge') {
				  // TODO Set payment status in merchant's database to 'failure'
				  $arr_update = array("status_payment" => "failed");
				  cancelorderlistmember($order_idLIST);          
				}
				else if (strtolower($fraud) == 'accept') {
				  // TODO Set payment status in merchant's database to 'failure'
				   $arr_update = array("status_payment" => "failed");
				   cancelorderlistmember($order_idLIST);   
				}
		
		
		}else if (strtolower($transaction) == 'deny') {
		  // TODO Set payment status in merchant's database to 'failure'
			 $arr_update = array("status_payment" => "deny");
			 cancelorderlistmember($order_idLIST);
		}
?>