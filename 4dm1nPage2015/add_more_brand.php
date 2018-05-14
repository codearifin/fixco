<?php
	@session_start();
	include('../config/connection.php');
	include "../config/myconfig.php";
    if(isset($_GET['id_voucher'])): $id_voucher = $_GET['id_voucher']; else: $id_voucher = 0; endif;
    $query = $db->query("SELECT * FROM `voucher_online` WHERE `id` = '$id_voucher' ");
	$res = $query->fetch_assoc();
	$brand_item = $res['brand_item'];
	
	function getnamebrand($id){
		global $db;
  	    $query = $db->query("SELECT * FROM `brand` WHERE `id` = '$id' ");
		$res = $query->fetch_assoc();		
		return $res['name'];
	}
?>

<style>
	table.promo-table { text-transform:capitalize;}
	
	table.promo-table thead tr td { background:#ccc; font-weight:600; border-right:1px solid #fff; padding:3px 8px 3px 8px; vertical-align:top;}
	
	table.promo-table tbody tr td { background:#f3f3f3; border:1px solid #fff; padding:5px 8px 5px 8px; vertical-align:top;}
	
	table.promo-table tbody tr.idlastprod td { background:none; border:none; vertical-align:top;}
	
	.add-promo { margin-top:10px; padding-top:5px; border-top:1px solid #ccc;}
	
	input.submit0data { cursor:pointer; background:#111; color:#fff; border:none; padding:5px 8px 5px 8px; width:auto;}
	
	input.skulist_cek { text-transform:uppercase;}
	
	table.nota-brg thead tr td, table.nota-brg tbody tr td { padding-left:10px; padding-right:10px;}
</style>
<div style="width:500px; padding:20px;">
	<div class="title-prod" style="font-size:16px; font-weight:600; padding-bottom:10px;">Voucher Code: <?php echo $res['voucher_code'];?></div>
	<div class="table-isi-prod">
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="promo-table">
			<thead>
				<tr>
					<td width="80%">Brand</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>
            	<?php
					if($brand_item =="" ):
						//not brand
					else:
						$listbranddata = explode("#",$brand_item);
						$jumlistdata = count($listbranddata);
						for($i = 0; $i<$jumlistdata; $i++){
								$idbranddata = $listbranddata[$i];
								echo'<tr class="listdata-'.$idbranddata.'-'.$res['id'].' tr-skuList">';
									echo'<td>'.getnamebrand($idbranddata).'</td>';
									echo'<td><a href="" class="deletelist" id="item-'.$res['id'].'-'.$idbranddata.'-product_promo" style="color:#FF3366;">[Delete]</a></td>';
								echo'</tr>';
						}
					endif;
				?>
				<tr class="idlastprod"><td colspan="2"></td></tr>
			</tbody>
		</table>
	</div>
	
	<div class="add-promo">
		<div class="title-prod" style="padding-bottom:10px;">Tambahkan Brand Untuk Voucher Ini!</div>
		<input type="hidden" name="idprod" class="idprod001" value="<?php echo $res['id'];?>" />
        
		<div class="label-input">
			<span style="display:inline-block; width:90px;">Category</span> 
			<select name="category" class="category_list" style="padding:6px 5px 6px 5px; width:300px;">
            	<option value="">Select Category</option>
            	<?php
				  	    $quepp = $db->query("SELECT * FROM `category` ORDER BY `id` ASC");
						while($rescate = $quepp->fetch_assoc()):
							echo'<option value="'.$rescate['id'].'">'.$rescate['name'].'</option>';
						endwhile;	
				?>
            </select>
			<div style="clear:both; height:10px;"></div>
			
			<span style="display:inline-block; width:90px;">Brand List</span> 
			<select name="brand" class="brand_list" style="padding:6px 5px 6px 5px; width:200px;">
            	<option value="">Select Brand</option>
            </select>
			
			<div style="clear:both; height:20px;"></div>
			<input type="submit" name="submit" value="SAVE DATA" class="submit0data" />
		</div>	
	</div>
	
	
	
</div>

<script type="text/javascript">
$(document).ready(function() { 
		//select brand
		$(".category_list").change(function(){
			var idcate = $(this).val();
			if(idcate!=""){
				$.post("lib/get_brandList.php", {"idcate": idcate},
				function(data) {
					$(".brand_list").html(data);
				}); 
			}
		});

	
	$(".submit0data").click(function() {
		var idvoucher = $(".idprod001").val();
		var brand_list = $(".brand_list").val();
		if(brand_list=="" || brand_list < 1 || brand_list == undefined){
			alert("Please select brand list!");
		}else{
				$.post("lib/insert_brand_list.php", {"idvoucher": idvoucher, "brand_list": brand_list },
				function(data) {
					$(".idlastprod").before(data);
				}); 		
		}	
	});
	

	$("a.deletelist").click(function(e) {
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