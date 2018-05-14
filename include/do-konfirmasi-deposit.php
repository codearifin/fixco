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
			
			$imgname = "topup-deposit-".$imgbaru.$ext;
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
		 
		date_default_timezone_set('Asia/Jakarta');
		$dateNow = date("Y-m-d H:i:s");
		 	
		//member data
		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
		$ros = $qummep->fetch_assoc();	
		$idmember = $ros['idmember_list'];
		
		$jumlahdeposit = replaceamount($_POST['jumlahdeposit']);
		$nama_bank = filter_var($_POST['bankname'], FILTER_SANITIZE_STRING);
		$atas_nama = filter_var($_POST['namapemilik'], FILTER_SANITIZE_STRING);
		$tanggal = $_POST['tanggal'];	
		$itemtgl = explode("/",$tanggal);
		$tanggal2 = $itemtgl[2].'-'.$itemtgl[1].'-'.$itemtgl[0];
		
		//images--
		$img=$_FILES['bukti_trf']['tmp_name'];
		$img_name=$_FILES['bukti_trf']['name'];
		
		if($jumlahdeposit>0):
			$nama_gambar=upload_images($img,$img_name);	
			$query = $db->query("INSERT INTO `deposit_member_corporatelist_konfirmasi` (`idmember`,`date_posting`,`bank`,`account_holder`,`amount`,`date`,`bukti_trf`,`status`) 
			VALUES ('$idmember','$dateNow','$nama_bank','$atas_nama','$jumlahdeposit','$tanggal2','$nama_gambar','0') ") or die($db->error);
			 
			include("confirm-deposit-email.php");
			$_SESSION['error_msg']='savedeposit';			
		endif;
		
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'konfirmasi-deposit"</script>';
}
?>		
	