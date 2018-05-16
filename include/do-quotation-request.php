<?php 

@session_start();	

require('../config/connection.php');

require('webconfig-parameters.php');

require('../config/myconfig.php');

if(isset($_POST['submit'])){
	global $db;
	$user_token = $_SESSION['user_token'];
	$query_user = $db->query("SELECT `id`,`email`,`name`,`lastname` FROM `member` WHERE `tokenmember` = '$user_token'") or die($db->error);
	$user = $query_user->fetch_assoc();

	$user_name = $user['name'].' '.$user['lastname'];
	$user_email = $user['email'];

	date_default_timezone_set('Asia/Jakarta');
	$dateNow = date("Y-m-d H:i:s");

	$query_header = $db->query("INSERT INTO `quotation_header` (`creator_name`,`creator_email`,`publish`, `modified_datetime`) VALUES('$user_name', '$user_email', 1, '$dateNow')") or die($db->error);
	$get_header_id = $db->query("SELECT `id` FROM `quotation_header` WHERE `modified_datetime` = '$dateNow' AND `creator_email` = '$user_email'");
	$header_id_arr = $get_header_id->fetch_assoc();
	$header_id = $header_id_arr['id'];

	for ($i=0; $i < sizeof($_POST['nama_produk']); $i++) { 
		$nama_produk = filter_var($_POST['nama_produk'][$i], FILTER_SANITIZE_STRING);
		$jumlah 	 = filter_var($_POST['jumlah'][$i], FILTER_SANITIZE_STRING);
		$id_satuan   = filter_var($_POST['satuan'][$i], FILTER_SANITIZE_STRING);
		$keterangan  = filter_var($_POST['keterangan'][$i], FILTER_SANITIZE_STRING);
		$query_header = $db->query("INSERT INTO `quotation_detail` (`id_quotation_header`,`nama_produk`,`jumlah`,`id_satuan`,`keterangan`,`publish`,`modified_datetime`) VALUES('$header_id','$nama_produk','$jumlah',$id_satuan,'$keterangan',1,'$dateNow')") or die($db->error);
	}
	$_SESSION['error_msg'] = 'addquotationoke';
	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
}

?>