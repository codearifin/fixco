<?php 
@session_start();	
require('../config/connection.php');
require('webconfig-parameters.php');
require('../config/myconfig.php');

function global_insert($table_name, $arr_data, $debug=false) {
    global $db;
    $str_column = "";
    $str_values = "";
    foreach($arr_data AS $key => $val) {
        $str_column .= ($str_column == "" ? "":", ")."`".$key."`";
        $str_values .= ($str_values == "" ? "":", ")."'".$val."'";
    }

    $str = "INSERT INTO `".$table_name."` (".$str_column.") VALUES (".$str_values.")";
    if($debug) { echo $str; exit; }

    $result = $db->query($str) or die($db->error);
    if($result):
        return $db->insert_id;
	else:
    	return false;
	endif;
}
		
if(isset($_POST['submit'])){
		
		 $pageurl = $_POST['pageurl'];
		 		
		 //select member
		 if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		 $query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
		 $res = $query->fetch_assoc();
		
		 $abook['member_id'] = $res['id'];	 	
		 $abook['name'] = filter_var($_POST['name'], FILTER_SANITIZE_STRING);		
		 $abook['lastname'] = "";
		 $abook['phone'] = "";	
		 $abook['mobile_phone'] = filter_var($_POST['mobilephone'], FILTER_SANITIZE_STRING);			
		 $abook['address'] = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
		 $abook['kodepos'] = filter_var($_POST['kodepos'], FILTER_SANITIZE_STRING);
		 $abook['provinsi'] = filter_var($_POST['province'], FILTER_SANITIZE_STRING);
		 $abook['kabupaten'] = filter_var($_POST['kabupaten'], FILTER_SANITIZE_STRING);
		 $abook['idcity'] = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
		 $abook['sortnumber'] = 0;
		 $abook['modified_datetime'] = date("Y-m-d H:i:s");
		 $abook['modified_by'] = 1;
		 
		 $savedata = global_insert("address_book",$abook,false);

		 $_SESSION['error_msg']='update-oke';	
		 if($pageurl==""):
		 	echo'<script type="text/javascript">window.location="'.$SITE_URL.'address-book"</script>';	
		 else:
		 	echo'<script type="text/javascript">window.location="'.$pageurl.'"</script>';	
		 endif;	
}
?>		
	