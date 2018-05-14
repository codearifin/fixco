<?php
	require("../../config/connection.php");
	require("function.php");
	
	if(isset($_GET['orderid'])): $orderid = $_GET['orderid']; else: $orderid = 0; endif;
	$query = $db->query("SELECT * FROM `order_header` WHERE `id`='$orderid' ") or die($db->error);
	$row = $query->fetch_assoc();	
	if($row['phone_penerima']<>''): $Phonelabel = '('.$row['phone_penerima'].')'; else: $Phonelabel = ''; endif;
?>

	<div class="nuke-wysiwyg" style="width:550px; min-height:120px; padding:20px;">
	  <?php echo "<strong style='font-size:14px;'>Order ID #".sprintf('%06d',$row['id'])."</strong><span style='opacity:0.5;float:right;'>Shipping Details</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">

 	 <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;" width="140">Shipping To</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
		<?php echo'<strong>'.$row['nama_penerima'].'</strong> '.$Phonelabel.'';?></td>
	  </tr>
      
	  <tr>
	    <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Shipping Address</td>
		<td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#fff">
			<?php
					echo $row['address_penerima'].'<br />';
					echo $row['kota_penerima'].', '. $row['kabupaten_penerima'].'<br />';
					echo $row['provinsi_penerima'].' - '.$row['country_penerima'].' '.$row['kodepos'];				
			?> 
        </td>
	  </tr>
      
	  </table>
	</div>