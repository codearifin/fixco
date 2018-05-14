<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("../function_rnt.php");
	
	if(isset($_GET['iddata'])): $iddata = $_GET['iddata']; else: $iddata = 0; endif;
	$query = $db->query("SELECT *, DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as mdate FROM `order_header_redeemlist` WHERE `id` = '$iddata' ");	
	
	$row = $query->fetch_assoc();
?>

<style>
	select.geninput {padding:6px 5px 6px 5px; width:220px; font-size:14px; font-family:calibri; font-weight:600;}
	textarea.geninput2 {padding:10px 8px 10px 8px; width:90%; height:35px; font-size:14px; font-family:calibri;}
	input.submit {border:none; text-align:center; padding:10px 15px 10px 15px; cursor:pointer;}
	input.submit:hover { background:#003399; color:#fff;}
	input.input-text { border:1px solid #ccc; padding:10px 7px 10px 7px; width:90%; font-family:calibri; font-size:14px; }
</style>

<div class="nuke-wysiwyg" style="width:550px; min-height:330px; padding:20px;">
     <form action="eccomerce/do_update_order_header_redeemlist.php" method="post"> 
     	<input type="hidden" name="iddata" value="<?php echo $row['id'];?>" />
        
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
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Status</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
            <select name="status_list" class="geninput">
            	<option value="Shipped" selected="selected">Confirmed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </td>
	  </tr>	    

	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Kurir</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
            <input type="text" name="kurir" placeholder="e.g JNE" class="input-text" />
        </td>
	  </tr>	     
     
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">No. Resi</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
            <input type="text" name="noresi" placeholder="e.g 10930100001001" class="input-text" />
        </td>
	  </tr>	 
            
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff; vertical-align:top;">Note to Member</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
           <textarea name="notemember" class="geninput2"></textarea>
        </td>
	  </tr>	 
      
  	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">&nbsp;</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
          <input type="submit" name="submit" value="SUBMIT" class="submit" onclick="return confirm('Anda yakin untuk confirm redeem member ini?');" />
        </td>
	  </tr>	    
                 	
	  </table>
       </form> 
	</div>
     