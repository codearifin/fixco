<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("function.php");
	require("webconfig-parameters.php");
		
	$idorder = $_GET['idorder'];	
	$que = $db->query("SELECT * FROM `order_header` WHERE `id`='$idorder'");
	$row = $que->fetch_assoc();
	
	$sqlconfig = $db->query("SELECT * FROM `web_config`");
	$s = $sqlconfig->fetch_assoc();	
?>
<script src="../scripts/jquery-1.6.min.js" type="text/javascript"></script>
    <div style="bottom:0; margin-top:20px; font-family:courier; font-size:13px; display:block;" id="bottom_invoice">
        <div></div>
        <div style="margin-left:10px; float:left;">
            <div style="border:solid; margin-top:10px; padding:15px; width:260px">
            	<div style="font-size:17px;"><img src="<?php echo $UPLOAD_FOLDER;?><?php echo $picconfig;?>" style="width:100px;" /></div>
            	<div style="margin-top:10px; font-size:14px;"><b><?php echo $s['company'];?></b></div>
				<div style="margin-top:4px; margin-bottom:10px;"><?php echo nl2br($s['company_address']);?></div>
            	<div>E. <?php echo $s['email'];?></div>
            	<div style="margin-top:10px;">Ph. <?php echo $s['phone'];?></div>
            </div>
			<div>&nbsp;</div>
        </div>

        <div style="margin-left:20px; float:left;">
            <div style="border:solid; margin-top:10px; padding:5px; width:260px; font-family:courier; font-size:13px;">
          		<div style="margin-left:10px; margin-top:4px; font-size:14px;"><b><?php echo ucwords($row['nama_penerima']);?></b></div>
            	<div style="margin-left:10px; margin-top:2px;">Ph. <?php echo $row['phone_penerima'];?></div>
                <div style="margin-left:10px; margin-top:15px;">Alamat Pengiriman:</div>
                <div style="margin-left:10px;">
                	<?php
                         echo $row['address_penerima'].'<br />';
                         echo getnamegeneral("ongkir","nama_kota",$row['kota_penerima']).', '. $row['kabupaten_penerima'].'<br />';
                         echo $row['provinsi_penerima'].' - '.$row['country_penerima'];				
                    ?>  
                </div>
            </div>
        </div>
    </div>

<div style="clear:both;"></div>
<div style="padding-top:60px; padding-bottom:50px;" class="submit_btn_label">
<form class="nuke-fancyform" method="post" action="" name="form1">
<input type="submit" name="submit_btn" id="submit_btn" class="submit-btn" value="Print Label" style="cursor:pointer; visibility:visible;  float:left; margin-left:10px; width:100px;">
</form>
</div>

<?php
if($_POST['submit_btn']){
?>	
	<script type="text/javascript">
		var subt=document.getElementById("submit_btn");
		subt.style.visibility='hidden';
		window.print();
	</script>
<?php } ?>	