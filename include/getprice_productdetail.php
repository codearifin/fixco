<?php
@session_start();
require('../config/connection.php');

$idprod = $_POST['idprod'];
$iddetail = $_POST['iddetail'];

//select prd
$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ");
$row = $query->fetch_assoc();

//select prd detail
$quepp = $db->query("SELECT * FROM `product_detail` WHERE `id`='$iddetail' and `idproduct_header`='$idprod' ");
$ros = $quepp->fetch_assoc();

$diskonval1 = 0; $diskonval2 = 0;
if($row['discount_value']>0):
	$diskonval1 = ($ros['price']*$row['discount_value'])/100;
	$diskonval2 = round($diskonval1);
	$price = $ros['price']-$diskonval1;
	
	echo'<span class="old-price">Rp '.number_format($ros['price']).',-</span>';
    echo'<span class="prod-price f-pb">Rp '.number_format($price).',-</span>';		
else:	
  echo'<span class="prod-price f-pb">Rp '.number_format($ros['price']).',-</span>';
endif;
?>