<?php 
	@session_start();
	$idproduct = $_POST['idproduct'];
	
	if(isset($_SESSION['useridprod_compare1'])): $idcompare1 = $_SESSION['useridprod_compare1']; else: $idcompare1 = ''; endif;
	if(isset($_SESSION['useridprod_compare2'])): $idcompare2 = $_SESSION['useridprod_compare2']; else: $idcompare2 = ''; endif;
	if(isset($_SESSION['useridprod_compare3'])): $idcompare3 = $_SESSION['useridprod_compare3']; else: $idcompare3 = ''; endif;
	if(isset($_SESSION['useridprod_compare4'])): $idcompare4 = $_SESSION['useridprod_compare4']; else: $idcompare4 = ''; endif;
	
	if($idproduct == $idcompare1): unset($_SESSION['useridprod_compare1']); endif;	
	if($idproduct == $idcompare2): unset($_SESSION['useridprod_compare2']); endif;	
	if($idproduct == $idcompare3): unset($_SESSION['useridprod_compare3']); endif;	
	if($idproduct == $idcompare4): unset($_SESSION['useridprod_compare4']); endif;		
?>