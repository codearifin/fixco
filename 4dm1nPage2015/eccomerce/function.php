<?php

//RNT
function getimagesdetailbonus($id){
	global $db;
	$que = $db->query("SELECT `thumb_image` FROM `product_gift` WHERE `id`='$id'");
	$row = $que->fetch_assoc();
	return $row['thumb_image'];		
}	
	
function getmembername($id){

	global $db;

	$query = $db->query("SELECT `name`,`lastname` FROM `member` WHERE `id`='$id' ");

	$res = $query->fetch_assoc();	

	return $res['name'].' '.$res['lastname'].'<br /><a href="eccomerce/detail_member.php?idmember='.$id.'" class="nuke-fancied2">View Detail</a>';

}

function getnamegeneral($tableName, $fieldname, $id){

	global $db;

	$query = $db->query("SELECT `".$fieldname."` FROM `".$tableName."` WHERE `id`='$id' ");

	$res = $query->fetch_row();

	return ucwords($res[0]);

}



function getstatus_konfirmasi($id){

	global $db;

	$query = $db->query("SELECT `id` FROM `konfirmasi_bayar` WHERE `idorder`='$id' ");

	$resjumpage = $query->num_rows;

	return $resjumpage;

}



function getstatuspayment($status){

			if($status=="Pending On Payment"):

				$stdpayment='<span style="font-weight:600; font-size:12px; color:#FF3333;">Menunggu Pembayaran</span>';

						

			elseif($status=="Waiting"):

				$stdpayment='<span style="font-weight:600; font-size:12px; color:#FF9933;">Menunggu Konfirmasi</span>';

										

			elseif($status=="Confirmed"):

				$stdpayment='<span style="font-weight:600; font-size:12px; color:#339966;">Pembayaran Diterima</span>';

										

			else:

				if($status=='Cancelled By User' or $status=='Cancelled By Admin'):

					$stdpayment='<span style="font-weight:600; font-size:12px; color:#FF3333;">Dibatalkan</span>';

				else:

					$stdpayment='<span style="font-weight:600; font-size:12px; color:#FF3333;">'.ucwords($status).'</span>';

				endif;	

			endif;	

		return $stdpayment;	

}



function getstatusdelivery($status){

		if($status=="Shipped"):

			$stdpaymentdeli='<span style="font-weight:600; font-size:12px; color:#339966;">Terkirim</span>';

		elseif($status=="Pending On Delivery"):

			$stdpaymentdeli='<span style="font-weight:600; font-size:12px; color:#FF3333;">Menunggu Pengiriman</span>';

		else:

			$stdpaymentdeli='<span style="font-weight:600; font-size:12px; color:#FF3333;">'.ucwords($status).'</span>';

		endif;

	

		return $stdpaymentdeli;	

}



function getstatuspaymentButton($status,$orderid){

			if($status=="Pending On Payment"):

				$stdpayment='<br /><a href="eccomerce/confirmpayment-order.php?idorder='.$orderid.'" onclick="return confirm(\'Apakah anda yakin untuk konfirmasi pembayaran Pesanan ini?\');" class="confirm_pay">Confirm Payment</a>';

						

			elseif($status=="Waiting"):

				$stdpayment='<br /><a href="eccomerce/confirmpayment-order.php?idorder='.$orderid.'" onclick="return confirm(\'Apakah anda yakin untuk konfirmasi pembayaran Pesanan ini?\');" class="confirm_pay">Confirm Payment</a>';

										

			elseif($status=="Confirmed"):

				$stdpayment='<br /><a href="eccomerce/confirmpayment-order.php?idorder='.$orderid.'" class="confirm_pay">Resent Email</a>';

			else:

				$stdpayment = '';								

			endif;	

			

		return $stdpayment;	

}



function getstatusdeliveryButton($paystatus,$status,$orderid){

		if($status=="Shipped"):

			$stdpaymentdeli='<br /><a href="eccomerce/confirmpayment-delivery.php?idorder='.$orderid.'" class="nuke-fancied2 confirm_pay">Resent Delivery</a>';

		

		elseif($status=="Pending On Delivery" and $paystatus=="Confirmed"):

			$stdpaymentdeli='<br /><a href="eccomerce/confirmpayment-delivery.php?idorder='.$orderid.'" class="nuke-fancied2 confirm_pay">Send Delivery</a>';

		

		elseif($paystatus=="Pending On Payment" or $paystatus=="Waiting"):

			$stdpaymentdeli='<br /><a href="eccomerce/cancel-order.php?idorder='.$orderid.'" onclick="return confirm(\'Apakah anda yakin untuk membatalkan order ini?\');" class="cancel_pay">Cancel Order</a>';

			

		else:

			$stdpaymentdeli = '';

		endif;

	

		return $stdpaymentdeli;	

}



function getimagesdetail($id,$UPLOAD_FOLDER){

	global $db;

	$que = $db->query("SELECT `image` FROM `product` WHERE `id`='$id'");

	$row = $que->fetch_assoc();

	return '<img src="'.$UPLOAD_FOLDER.$row['image'].'" />';

}



function getpembayaran($uid){

	global $db;

	$querypage = $db->query("SELECT `user_badgedame` FROM `1001ti14_vi3w2014` WHERE `id`='$uid'");

	$data= $querypage->fetch_assoc();

    echo $data['user_badgedame'];	

}



function replacetgl($tgl){

	$itemtgl = explode("/",$tgl);

	$hasiltgl = $itemtgl[0].'-'.sprintf('%02d',$itemtgl[1]).'-'.sprintf('%02d',$itemtgl[2]);

	return $hasiltgl;

}



//END RNT

?>