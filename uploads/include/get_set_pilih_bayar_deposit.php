<?php 
@session_start();	

$notememberlist = $_POST['notememberlist'];
$status = $_POST['status'];
$_SESSION['notememberlist'] = $notememberlist;
if($status=="OKE"):
	$_SESSION['gunakan_deposit'] = "OKE";
else:
	unset($_SESSION['gunakan_deposit']);
endif;

//kurir lain
$kurir_lainnya = $_POST['kurir_lainnya'];
$_SESSION['kuririd_pilih_lainnya'] = $kurir_lainnya;
?>		
	