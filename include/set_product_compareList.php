<?php 
	@session_start();
	$idproduct = $_POST['idproduct'];
	
	if(isset($_SESSION['useridprod_compare1'])): $idcompare1 = $_SESSION['useridprod_compare1']; else: $idcompare1 = ''; endif;
	if(isset($_SESSION['useridprod_compare2'])): $idcompare2 = $_SESSION['useridprod_compare2']; else: $idcompare2 = ''; endif;
	if(isset($_SESSION['useridprod_compare3'])): $idcompare3 = $_SESSION['useridprod_compare3']; else: $idcompare3 = ''; endif;
	if(isset($_SESSION['useridprod_compare4'])): $idcompare4 = $_SESSION['useridprod_compare4']; else: $idcompare4 = ''; endif;
	
	if($idproduct<>$idcompare1 and $idproduct<>$idcompare2 and $idproduct<>$idcompare3 and $idproduct<>$idcompare4):
	
			if($idcompare1==""): 
				$_SESSION['useridprod_compare1'] = $idproduct; 
			
			elseif($idcompare2==""):
				$_SESSION['useridprod_compare2'] = $idproduct; 
				
			elseif($idcompare3==""):
				$_SESSION['useridprod_compare3'] = $idproduct; 
				
			elseif($idcompare4==""):
				$_SESSION['useridprod_compare4'] = $idproduct; 
			
			else:
				echo 'DATAFULL';	
				
			endif;
			
	
	else:
			echo'DATAEXIST';
	endif;		
?>