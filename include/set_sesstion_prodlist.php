<?php 
	@session_start();
	$idpages = $_POST['namasession'];
	$actsort = $_POST['idstyle'];
	
	if($actsort==""): 
		unset($_SESSION[$idpages]); 
	else:
		$_SESSION[$idpages] = $actsort;
		echo $_SESSION[$idpages];
	endif;	
?>