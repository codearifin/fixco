<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');

function global_update($table_name, $arr_data, $strWhere, $debug=false) {
    global $db;
    $str_column_field = "";
    foreach($arr_data AS $key => $val) {
		$str_column_field .= ($str_column_field == "" ? "":", ")." `".$key."` = '".$val."' ";
    }
	
    $str = "UPDATE `".$table_name."` SET ".$str_column_field." WHERE ".$strWhere." ";
    if($debug) { echo $str; exit; }

    $result = $db->query($str) or die($db->error);
    return $result;
}
		
if(isset($_POST['submit'])){
			
		 //select member
		 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		 $query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
		 $res = $query->fetch_assoc();
		 
		 $idabook = $_POST['idabook'];	
		 $member_id = $res['id'];
		 	 	
		 $abook['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);		
		 $abook['lastname'] = "";
		 $abook['phone'] = "";	
		 $abook['mobile_phone'] = filter_var($_POST['mobilephone'], FILTER_SANITIZE_STRING);			
		 $abook['address'] = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		 $abook['kodepos'] = filter_var($_POST['kodepos'], FILTER_SANITIZE_STRING);
		 $abook['provinsi'] = filter_var($_POST['province'], FILTER_SANITIZE_STRING);
		 $abook['kabupaten'] = filter_var($_POST['kabupaten'], FILTER_SANITIZE_STRING);
		 $abook['idcity'] = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		 $abook['modified_datetime'] = date("Y-m-d H:i:s");
		 
		 $savedata = global_update("address_book",$abook," `id`='$idabook' and `member_id`='$member_id' ",false);
		 
		 $_SESSION['error_msg']='update-oke';	
		 echo'<script type="text/javascript">window.location="'.$SITE_URL.'finishorder"</script>';	
}
?>		
	