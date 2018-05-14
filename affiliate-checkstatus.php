<?php
	@session_start();
	require('config/connection.php');
	require('config/myconfig.php');

	function replaceUrel($katatext){
		if($katatext<>''):
			$str = array("or", "union", "=", "==", "|", "concat", "&", "*", "where", "<", ">", "/", "'", "select", "from");
			$kata_text = strtolower($katatext);
			$katatext_1	= filter_var($kata_text, FILTER_SANITIZE_STRING);	
			$katatext_2 = strip_tags($katatext_1);
			$newtext = str_replace($str,"",$katatext_2);
			return $newtext;	
		endif;		
	}
		
	if(isset($_GET['token'])): $token = replaceUrel($_GET['token']); else: $token = ''; endif;
	$query = $db->query("SELECT `id` FROM `affiliate_member` WHERE `token_affiliate` = '$token' and `status` = 1 ");
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$_SESSION['affiliate_tokenuid'] = $token;
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'register"</script>';
	else:
		$_SESSION['error_msg']='toikenUrelfail';
		if(isset($_SESSION['affiliate_tokenuid'])): unset($_SESSION['affiliate_tokenuid']); endif;
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'register"</script>';
	endif;
?>