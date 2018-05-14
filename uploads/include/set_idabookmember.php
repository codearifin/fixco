<?php 
	@session_start();
	unset($_SESSION['TokenDrafQuoteListidcity']);
	
	$idabook = $_POST['idabook'];
	$_SESSION['member_shiipinid'] = $idabook;

	$notememberlist = $_POST['notememberlist'];
	$_SESSION['notememberlist'] = $notememberlist;	

	//kurir lain
	$kurir_lainnya = $_POST['kurir_lainnya'];
	$_SESSION['kuririd_pilih_lainnya'] = $kurir_lainnya;	
?>