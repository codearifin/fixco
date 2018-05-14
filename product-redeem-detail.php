<?php
@session_start();
include('config/connection.php');
include "include/function.php";
include "config/myconfig.php";

//select prod
if(isset($_GET['idprod'])): $idprodList = replaceUrel($_GET['idprod']); else: $idprodList = 0; endif;
$query = $db->query("SELECT * FROM `redeem_product` WHERE `id`='$idprodList' and `publish`=1 ") or die($db->error);
$row = $query->fetch_assoc();
?>
<style>
	.price-table { min-width: 350px!important;}
</style>
<div class="popup-wrap popup-medium" style="width:410px;">
	<div class="popup-header">
    	<h2 class="f-pb">Redeem Detail</h2>
    </div><!-- .popup-header -->
    <div class="popup-content">
		
        <div style="text-align:center; border:1px solid #f3f3f3;">
        	<?php echo'<img src="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['image'].'" alt="'.$row['name'].'" style="width:300px;" />';?>
        </div>
        <div>
                                    	<table cellspacing="0" cellpadding="0" class="price-table">
                                    		<tr>
                                            	<th>SKU</th>
                                                <td><?php echo $row['sku_product'];?></td>
                                            </tr>
                                            <tr>
                                            	<th>Name</th>
                                                <td><?php echo $row['name'];?></td>
                                            </tr>
                                            <tr>
                                            	<th>Point</th>
                                                <td><?php echo number_format($row['point_redeem']);?> Point</td>
                                            </tr>
                                            <tr>
                                            	<th>Stock</th>
                                                <td><?php echo number_format($row['stock']);?> Item</td>
                                            </tr>
                                    	</table>

        </div>
        <div style="clear:both;"></div>
        
    </div><!-- .popup-content -->
</div><!-- .popup-wrap -->