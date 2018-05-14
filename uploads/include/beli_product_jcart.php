<?php
include_once('../scripts/jcart/jcart.php');
@session_start();
require('../config/connection.php');

function replaceamount($text){
	$str = array(",", " ");
	$newtext=str_replace($str,"",$text);
	return $newtext;
}

function getnamegeneral($iddata,$act,$field){
	global $db;
	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE `id`='$iddata' ");
	$row = $query->fetch_assoc();
	return $row[$field];	
}

function replacelist($text){
	$str = array("’", " " , "/" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "." , "rsquo;");
	$newtext=str_replace($str,"-",strtolower($text));
	return $newtext;
}

$idprod = $_POST['idprod'];
$iddetail = $_POST['iddetail'];
$qtyList = replaceamount($_POST['qtyitem']);
if($qtyList>0): $qtyitem = $qtyList; else: $qtyitem = 1; endif;

//select prd
$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ");
$row = $query->fetch_assoc();
$name = $row['name'];
$statusprod = 'Ready Stock';

//select prd detail
$quepp = $db->query("SELECT * FROM `product_detail` WHERE `id`='$iddetail' and `idproduct_header`='$idprod' ");
$ros = $quepp->fetch_assoc();
$namapaket = $ros['title'].'#';


$diskonval1 = 0; $diskonval2 = 0;
if($row['discount_value']>0):
	$diskonval1 = ($ros['price']*$row['discount_value'])/100;
	$diskonval2 = round($diskonval1);
	$price = $ros['price']-$diskonval1;
else:	
  $price = $ros['price'];
endif;

$description = $row['image'];
$url = 'product-detail/'.replacelist($row['name']).'/'.$row['id'].'';
$skuprod = $ros['sku_product'];

$jumstock = $ros['stock'];
if($qtyitem > $jumstock):
	echo 900;
else:

	if($iddetail > 0 and $name<>"" and $namapaket<>"" and $price > 0 and $qtyitem > 0):
		$jcart->additem_manual($iddetail, $name, $price, $qtyitem, $qtyitem, $description, $url, $idprod, $namapaket, $price, $skuprod, $statusprod);	
		echo 200;
	else:
		echo 300;
	endif;
	
endif;	
?>