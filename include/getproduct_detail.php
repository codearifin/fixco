<?php 
@session_start();	
require('../config/connection.php');
function getnamegeneral($id,$tablename,$fieldname){
	global $db;
	$query = $db->query("SELECT `".$fieldname."` FROM `".$tablename."` WHERE `id` = '$id' ") or die($db->error);
	$row = $query->fetch_assoc();
	return $row[$fieldname];
}

$idpord = $_POST['idpord'];
$quepp = $db->query("SELECT `discount_value`,`product_detail_label` FROM `product` WHERE `id` = '$idpord' ") or die($db->error);
$data = $quepp->fetch_assoc();
$diskonprice = $data['discount_value'];

$query = $db->query("SELECT * FROM `product_detail` WHERE `idproduct_header`='$idpord' and `publish` = 1 ORDER BY `id` ASC") or die($db->error);
$jumpage = $query->num_rows;
if($jumpage>0):
	echo'<option  value="" selected="selected">- Pilih '.$data['product_detail_label'].' -</option>';
	while($row = $query->fetch_assoc()):	
		
		$price1 = $row["price"];
		if($diskonprice>0):
			$diskonval1 = ($price1*$diskonprice)/100;
			$diskonval2 = round($diskonval1);
			$priceprod = $price1-$diskonval1;
		else:
			$priceprod = $price1;
		endif;	
			
		echo'<option value="'.$row['id'].'">'.$row['sku_product'].' - '.$row['title'].' - Rp. '.number_format($priceprod).'</option>';
	endwhile;	
else:
	echo'<option  value="" selected="selected">Maaf produk ini tidak tersedia.</option>';
endif;		
?>		
	