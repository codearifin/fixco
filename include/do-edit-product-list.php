<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('function.php');	
		
if(isset($_POST['submit'])){
		 
		$token = $_POST['token'];
		$uidlist = $_POST['uidlist'];
		$idpord = $_POST['idpord'];
		$iddetail = $_POST['iddetail'];
		$qty = replaceamount($_POST['qty']);
		
		if($token!="" and $idpord >0 and $qty>0 and $iddetail>0):
			
			$quppprod3 = $db->query("UPDATE `draft_quotation_detail` SET `qty` = '$qty' WHERE `id` = '$uidlist' and `tokenpay` = '$token' and `idproduct` = '$idpord' and `iddetail` = '$iddetail' ");	

			if($quppprod3):
				//last update weight and ongkir
				updateongkirTotal($token);
			endif;
				
		endif;	 	
	
		$_SESSION['error_msg'] = 'updateprodoke';
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'quotation-detail/'.$token.'"</script>';
}
?>		
	