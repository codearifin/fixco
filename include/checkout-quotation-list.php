<?php 
include_once('../scripts/jcart/jcart.php');
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');
require('function.php');	

$jcart->empty_cart();
unset($_SESSION['kuririd_pilih']);
unset($_SESSION['voucher_redeeemID']);
unset($_SESSION['notememberlist']);
unset($_SESSION['member_shiipinid']);


//member data
if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
$qummep = $db->query("SELECT `idmember_list` FROM `corporate_user` WHERE `id`='$Usertokenid'");
$ros = $qummep->fetch_assoc();	
$uidmembermain = $ros['idmember_list'];

//get main user--
$query = $db->query("SELECT * FROM `member` WHERE `id`='$uidmembermain'");
$res = $query->fetch_assoc();
$idmember = $res['id'];		 
$token = $_GET['code'];
	
$query = $db->query("SELECT * FROM `draft_quotation_header` WHERE `idmember_header` = '$idmember' and `tokenpay` = '$token' ");
$re = $query->fetch_assoc();	

//select detail
$quelpp = $db->query("SELECT * FROM `draft_quotation_detail` WHERE `tokenpay` = '".$re['tokenpay']."' ORDER BY `id` ASC ");
while($data = $quelpp->fetch_assoc()):
	
	$idprod = $data['idproduct'];
	$iddetail = $data['iddetail'];
	$name = $data['name'];	
	$namapaket = $data['nama_detail'];
	$price = $data['price'];
	$qtyitem = $data['qty'];
	$skuprod = $data['sku'];
	$statusprod = 'Ready Stock';
	$description = getnamegeneral($data['idproduct'],"product","image");
	$url = 'product-detail/'.replace($data['name']).'/'.$data['idproduct'].'';
	
	if($iddetail > 0 and $name<>"" and $namapaket<>"" and $price > 0 and $qtyitem > 0):
		$jcart->additem_manual($iddetail, $name, $price, $qtyitem, $qtyitem, $description, $url, $idprod, $namapaket, $price, $skuprod, $statusprod);	
	endif;
	
endwhile;	

$_SESSION['kuririd_pilih'] = $re['kurir'];
$_SESSION['notememberlist'] = $re['note'];
$_SESSION['TokenDrafQuoteList'] = $re['tokenpay'];
$_SESSION['TokenDrafQuoteListidcity'] = $re['idcity'];

echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'finishorder-corporate"</script>';
?>		
	