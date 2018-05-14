<?php
@session_start();
require('../config/connection.php');

function getnamegeneral($iddata,$act,$field){
	global $db;
	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE `id`='$iddata' ");
	$row = $query->fetch_assoc();
	if($row[$field]==""): return "-"; else:	return $row[$field]; endif;	
}

$idprod = $_POST['idprod'];
$iddetail = $_POST['iddetail'];

//select prd
$query = $db->query("SELECT * FROM `product` WHERE `id`='$idprod' ");
$row = $query->fetch_assoc();

//select prd detail
$quepp = $db->query("SELECT * FROM `product_detail` WHERE `id`='$iddetail' and `idproduct_header`='$idprod' ");
$ros = $quepp->fetch_assoc();

echo'
<table cellspacing="0" cellpadding="0" class="pdtr-table">
<tr>
    <td>SKU Number</td>
    <td>: '.$ros['sku_product'].'</td>
</tr>

<tr>
<td>Manufacture</td>
<td>: '.getnamegeneral($row['brand'],'brand','name').'</td>
</tr>
</table>
                                   
<table cellspacing="0" cellpadding="0" class="pdtr-table last">
<tr>
    <td>Model</td>
    <td>: '.$ros['title'].'</td>
</tr>

<tr>
   <td>Berat Pengiriman</td>
   <td>: '.number_format($ros['weight']).' gram</td>
</tr>
</table>
';
?>