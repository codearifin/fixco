<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('../include/function.php');	

//select member
if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
$query = $db->query("SELECT * FROM `member` WHERE `tokenmember`='$Usertokenid'");
$res = $query->fetch_assoc();
$idmember = $res['id'];
$totalkomisi = getkomisimemberlistTotal($idmember);

$totalamount_1 = filter_var($_POST['total_claim'], FILTER_SANITIZE_STRING);	
$totalamount = replaceamount($totalamount_1);	

if($totalamount>$totalkomisi):
	echo 99;
endif;	
?>		
	