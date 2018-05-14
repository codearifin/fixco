<?php 
@session_start();
require('../../config/connection.php');
require('../../config/myconfig.php');
require("../../include/webconfig-parameters.php");
include("../../smtp_mail/config_smtp.php");


function getnamememberemail($id){
	global $db;
	$query = $db->query("SELECT `email` FROM `member` WHERE `id`='$id'");
	$row = $query->fetch_assoc();
	return $row['email'];
}

function replace($texdata){
	$str = array(",#AKHIREMAIL");
	$newtext=str_replace($str,"",$texdata);
	return $newtext;
}	

if(isset($_POST['submit'])){
			
	$idvoucher = $_POST['product_item'];
	$quip = $db->query("SELECT *,DATE_FORMAT(`end_date`, '%d %M %Y') as tgl FROM `voucher_online` WHERE `id` = '$idvoucher' ");
	$res = $quip->fetch_assoc();
	$codevouher = $res['voucher_code'];
	$typevoucher = $res['diskon_type'];
	$diskonv = $res['diskon_value'];
	$minbelanja = $res['min_transaksi_price'];
	$makbelanja = $res['mak_transaksi_price'];
	$min_qty_beli = $res['mak_item_buy'];
	$tglv = $res['tgl'];
	
	$memberemail = getnamememberemail($_POST['memberid']); 
	$mail->addAddress($memberemail, "Online Voucher On Mizuno Indonesia"); 

	if($codevouher<>''):
		//email voucher
		include("email_voucher_to_member.php");
	endif;	
	
	
	$message = ob_get_clean();
	$mail->isHTML(true);
	$mail->Subject = "Online Voucher On Fixcomart";
	$mail->msgHTML($message);

	if(!$mail->send()) {
		//echo 'Message could not be sent.';
		//echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		//echo 'Message has been sent';
	}
			
	echo'<script language="JavaScript">';
		echo'window.location="../view.php?menu=member&submenu=voucher_online";';
	echo'</script>';	

}
?>
