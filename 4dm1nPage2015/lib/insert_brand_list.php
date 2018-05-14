<?php 
@session_start();
require('../../config/connection.php');
$idvoucher = $_POST['idvoucher'];
$brand_list = $_POST['brand_list'];

function getnamebrand($id){
	global $db;
    $query = $db->query("SELECT * FROM `brand` WHERE `id` = '$id' ");
	$res = $query->fetch_assoc();		
	return $res['name'];
}
	
$query = $db->query("SELECT * FROM `voucher_online` WHERE `id` = '$idvoucher' ");
$res = $query->fetch_assoc();
$brand_item = $res['brand_item'];
if($brand_list>0):
	if($brand_item!=""):
		$hasilVouherbrand = $brand_item.'#'.$brand_list;
	else:
		$hasilVouherbrand = $brand_list;
	endif;
	$queryupdate = $db->query("UPDATE `voucher_online` SET `brand_item` = '$hasilVouherbrand' WHERE `id` = '$idvoucher' ");
	echo'<tr class="listdata-'.$brand_list.'-'.$idvoucher.' tr-skuList">';
			echo'<td>'.getnamebrand($brand_list).'</td>';
			echo'<td><a href="" class="deletelist_ajax" id="item-'.$idvoucher.'-'.$brand_list.'-product_promo" style="color:#FF3366;">[Delete]</a></td>';
	echo'</tr>';
									
endif;
?>

<script type="text/javascript">
$(document).ready(function() { 
	$("a.deletelist_ajax").click(function(e) {
		var idadd=$(this).attr("id");
		var itemExplode = idadd.split("-");
		var idvoucher = itemExplode[1];
		var idbrand = itemExplode[2];
		
		$.post("lib/delete_general_brand_voucher.php", {"idvoucher": idvoucher, "idbrand": idbrand},
				 function(data) {				 
					if(data==1){
						$(".listdata-"+idbrand+"-"+idvoucher).fadeOut();	
					}else{
						alert("Delete unsuccessful, Please try again!");	
					} 
		}); 

		e.preventDefault();	 
	});	
	
});	
</script>


