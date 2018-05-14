<?php  
	@session_start();
	require('../config/connection.php');
	$order_id = sprintf('%01d',$_POST['orderid']);

		$quem = $db->query("SELECT `id`,`status_payment`,`status_delivery`,`kurir`,`resinumber` FROM `order_header` WHERE `id`='$order_id'");
		$jumdata = $quem->num_rows;
		if($jumdata > 0):
			$data = $quem->fetch_assoc();
			if($data['status_delivery']=="Shipped"):
				$classtext = 'success';
				if($data['resinumber']<>''):	
					$no_resi = 'Resi : '.$data['resinumber'].' ('.$data['kurir'].')';
				else:
					$no_resi = '';
				endif;	
			else:
				$classtext = 'error';
				$no_resi = '';
			endif;	
			
			if($data['status_payment']=="Confirmed"):
				echo'<div class="'.$classtext.'">
					Order ID : #'.sprintf('%06d',$data['id']).'<br />
					Delivery Status : '.ucwords($data['status_delivery']).'<br />
					'.$no_resi.'
				</div>';
			else:
				echo'<div class="'.$classtext.'">
					Order ID : #'.sprintf('%06d',$data['id']).'<br />
					Delivery Status : '.ucwords($data['status_payment']).'<br />
					'.$no_resi.'
				</div>';			
			endif;	
			
		else:
			echo'<div class="error">Invalid Order ID, Please check your Order ID</div>';
		endif;	
	
?>