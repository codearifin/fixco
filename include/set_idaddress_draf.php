<?php 
	@session_start();
	require('../config/connection.php');
	unset($_SESSION['notememberlist']);
	
	if(isset($_SESSION['TokenDrafQuoteList'])): $TokenDrafQuoteList = $_SESSION['TokenDrafQuoteList']; else: $TokenDrafQuoteList = ''; endif;
	$que = $db->query("SELECT `idcity` FROM `draft_quotation_header` WHERE `tokenpay`='$TokenDrafQuoteList' ");
	$row = $que->fetch_assoc();
	$_SESSION['TokenDrafQuoteListidcity'] = $row['idcity'];

	//kurir lain
	$kurir_lainnya = $_POST['kurir_lainnya'];
	$_SESSION['kuririd_pilih_lainnya'] = $kurir_lainnya;		
?>