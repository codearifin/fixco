<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');

function replaceamount($amount){
	$str = array(",");
	$newtext=str_replace($str,"",strtolower($amount));
	return $newtext;
}

function upload_images($img,$img_name)
	{
		$imgbaru = date("Y-m-d-His");
		$extG = get_file_extension22($img_name);
		$ext = '.'.$extG;
		
		$checkextimg = strtolower($extG);
		if($checkextimg=="php" or $checkextimg=="asp" or $checkextimg=="js" or $checkextimg=="xml" or $checkextimg=="html"):
			$nama_gambar='';
		else:
			
			$imgname = "confirm-payment-".$imgbaru.$ext;
			$folder = "../uploads/".$imgname;
			if($img_name == ''){ $nama_gambar = ''; } else { $nama_gambar = $imgname; }
			
			move_uploaded_file($img,$folder);
			return $nama_gambar;				
		endif;			
}

function get_file_extension22($file_name) {
   return substr(strrchr($file_name,'.'),1);
}

	
if(isset($_POST['submit'])){
		$pageURL = $_POST['pageURL'];	
		$nama_bank = filter_var($_POST['nama_bank'], FILTER_SANITIZE_STRING);
		$norek = filter_var($_POST['norek'], FILTER_SANITIZE_STRING);
		$atas_nama = filter_var($_POST['atas_nama'], FILTER_SANITIZE_STRING);
		$tanggal = $_POST['tanggal'];	
		$order_id = sprintf('%01d',$_POST['idorder']);
		$jumlah_transfer = replaceamount($_POST['nominal']);
		$jumlah_transfer2 = 'IDR '. number_format($jumlah_transfer);	
		$transferke = "";	
		
		//images--
		$img=$_FILES['bukti_trf']['tmp_name'];
		$img_name=$_FILES['bukti_trf']['name'];
		
		
		$quem = $db->query("SELECT * FROM `order_header` WHERE `id`='$order_id' and `status_payment`='Pending On Payment'");
		$jumdata = $quem->num_rows;
		if($jumdata > 0):
			 $data = $quem->fetch_assoc();
			 $nama_gambar=upload_images($img,$img_name);	
			 $query = $db->query("INSERT INTO `konfirmasi_bayar` (`idorder`,`nama_bank`,`atas_nama`,`norek`,`transferke`,`nominal`,`tanggal`,`image`) 
			  					  VALUES ('$order_id','$nama_bank','$atas_nama','$norek','$transferke','$jumlah_transfer2','$tanggal','$nama_gambar')") or die($db->error);
			 if($query):
			 	$quem2 = $db->query("UPDATE `order_header` SET `status_payment` = 'Waiting' WHERE `id`='".$data['id']."' ");
			 endif;
			 
			 include("confirm-order-email.php");
			 $_SESSION['error_msg']='konfirmasioke';
	
		else:
			$_SESSION['error_msg']='konfirmasigagal';
		endif;
					

	echo'<script type="text/javascript">window.location="'.$pageURL.'"</script>';
}
?>		
	