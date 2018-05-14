<?php
	@session_start();
	require('config/myconfig.php');
	function replace($text){
			$str = array("’", " " , "/" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "." , "rsquo;");
			$newtext=str_replace($str,"-",strtolower($text));
			return $newtext;
	}
		

	if(isset($_POST['submit'])){

		$idsubkat = $_POST['idsubkat'];
		if($idsubkat == "all" or $idsubkat ==""):
			$getsubkat = "all";
		else:
			$getsubkat = $idsubkat;
		endif;
		
		if($_POST['keyword'] <>''):
			$datakeytmp = explode(" di",$_POST['keyword']);
			$_SESSION['keyword_data'] = $datakeytmp[0];
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'search-product-result/'.$getsubkat.'"</script>';	
		else:
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'product"</script>';
		endif;
			
	}else{
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'product"</script>';	
	}
?>