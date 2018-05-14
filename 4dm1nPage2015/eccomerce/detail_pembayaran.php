<?php	
@session_start();
require("../../config/connection.php");
require("../../config/myconfig.php");
require("function.php");

if(isset($_GET['orderid'])): $orderid = $_GET['orderid']; else: $orderid = 0; endif;
$query = $db->query("SELECT * FROM `konfirmasi_bayar` WHERE `idorder`='$orderid'");
$row = $query->fetch_assoc();
?>
	<div class="nuke-wysiwyg" style="width:500px; height:auto; padding:20px;">
	  <?php echo"Order ID: <strong>#".sprintf('%06d',$orderid)."</strong><span style='opacity:0.5;float:right;'>Detail Konfirmasi Pembayaran</span><hr/>"; ?>
	  <table style="width:100%;margin:15px auto;">
	
     <?php if($row['id'] > 0):?>
     
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:transparent">Nama Bank</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#f6f6f6"><?php echo ucwords($row['nama_bank']);?></td>
          </tr>
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Atas Nama</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo ucwords($row['atas_nama']);?></td>
          </tr>
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">No. Rek</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo ucwords($row['norek']);?></td>
          </tr>      
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:transparent">Jumlah Transfer</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:#f6f6f6"><strong><?php echo $row['nominal'];?></strong></td>
          </tr>
    
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Tanggal Transfer</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent"><?php echo $row['tanggal'];?></td>
          </tr>
          
 		  
          <?php if($row['image']<>''):?>
           <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:right;background:#f6f6f6">Bukti Transfer</td>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;border-left:none;background:transparent">
            	<a href="<?php echo $GLOBALS['UPLOAD_FOLDER'];?><?php echo $row['image'];?>" class="nuke-fancied" title="Zoom Images"><img src="<?php echo $GLOBALS['UPLOAD_FOLDER'];?><?php echo $row['image'];?>" style="width:150px;"></a></td>
          </tr>
          <?php endif;?>         
            
      <?php else:?>
          <tr>
            <td style="border:1px solid #adadad;padding:5px 10px;text-align:left;background:#fff;">Record not found.</td>
          </tr>      
      <?php endif;?> 
      
	  </table>
	</div>