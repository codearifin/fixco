<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	
	if(isset($_GET['iddata'])): $iddata = $_GET['iddata']; else: $iddata = 0; endif;
	$query = $db->query("SELECT *, DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as mdate FROM `order_header_redeemlist` WHERE `id` = '$iddata' ");	
	
	$row = $query->fetch_assoc();
?>


<div class="nuke-wysiwyg" style="width:550px; min-height:330px; padding:20px;">
     
	  <?php echo '<strong>'.getnamegenal(" `id`='".$row['idmember']."' ", "member","name").' '.getnamegenal(" `id`='".$row['idmember']."' ", "member","lastname").'</strong>'."<span style='opacity:0.5;float:right;'>Redeem Details</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Member Name</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.getnamegenal(" `id`='".$row['idmember']."' ", "member","name").' '.getnamegenal(" `id`='".$row['idmember']."' ", "member","lastname").'</strong>';?></td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Redeem Date</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo $row['mdate'];?></td>
	  </tr>	
  
  	
  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Redeem ID</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo '<strong style="font-size:14px;">#'.sprintf('%06d',$row['id']).'</strong>';?></td>
	  </tr>	   
     
     
   	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Item Product</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo '<strong>'.$row['sku_product'].'</strong><br />'.$row['name'].'';?></td>
	  </tr>	       
     
   	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Point</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		 <?php echo '<strong>'.number_format($row['point']).'</strong>';?></td>
	  </tr>	         
     
   	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Alamat Pengiriman</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php
			echo''.$row['nama_penerima'].' ('.$row['phone_penerima'].')<br />';
			echo $row['address_penerima'];
		    echo'<br />'.$row['kota_penerima'].' - '.$row['kabupaten_penerima'];
		    echo'<br />'.$row['provinsi_penerima'].' - '.$row['country_penerima'].' '.$row['kodepos'];
		?>
        </td>
	  </tr>	     
     

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Kurir</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
           <?php echo '<strong>'.$row['kurir'].'</strong>';?>
        </td>
	  </tr>	     
     
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Kurir</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
           <?php echo '<strong>'.$row['resinumber'].'</strong>';?>
        </td>
	  </tr>	    
                 	
	  </table>
       </form> 
	</div>
     