<?php
	@session_start();
	require("config/connection.php");
	require("config/myconfig.php");
	require("include/function.php");
	
	if(isset($_SESSION['user_token'])==''):
		echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
	endif;

	//member data
	if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
	$qummep = $db->query("SELECT `id`,`idmember_list`,`status` FROM `corporate_user` WHERE `id`='$Usertokenid'");
	$ros = $qummep->fetch_assoc();	
	$idmember = $ros['idmember_list']; $idusr = $ros['id'];
	
	if(isset($_GET['token'])): $token = replaceUrel($_GET['token']); else: $token = ''; endif;
	
	if($ros['status']==1):
		$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgl, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `tokenpay`='$token'");
	else:
		$que = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y, %H:%i:%s') as tgl, DATE_FORMAT(`expiry_date`,'%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE `idmember_header`='$idmember' and `id_user` = '$idusr' and `tokenpay`='$token'");
	endif;
	
	$row = $que->fetch_assoc();
	$totalamount = $row['total_order']+$row['shippingcost']+$row['handling_fee'];
	if($row['phone_penerima']<>''): $Phonelabel = '('.$row['phone_penerima'].')'; else: $Phonelabel = ''; endif;		
?>
<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="<?php echo $GLOBALS['SITE_URL'];?>fonts/font.css" rel="stylesheet">

<style>
.top-wrapper { padding-top:20px; padding-left:20px; width:95%;}
.clear-2 { clear:both; height:0px;}
.left-wrapper { width:30%; float:left;} 
.left-wrapper2 { width:67%; float:left; text-align:right; padding-top:10px;}

.text-2 { color:#000; font-size:13px; line-height:1.5em; font-weight:500; float:left; width:30%;}
span.h2 { font-size:22px; font-weight:600; line-height:1.8em; font-family: 'ProximaNovaBold';}
.title { font-size:14px; text-transform:uppercase; font-weight:600;}
.text-3 {  margin-top:10px; color:#000; font-size:13px; line-height:1.5em; font-weight:500;}
span.h1 { font-size:12px; font-weight:600; line-height:1.8em;}
table.table-general {font-size:12px; color:#000;}
table.table-general td { vertical-align:top; line-height:1.5em;}

.title-wrap { width:95%; margin-left:10px; color:#000; margin-bottom:10px;}
.left-title-wrap { float:left; width:50%; font-size:14px; line-height:1.6em; font-family: 'ProximaNovaRegular';}
.left-title-wrap2 { float:left; width:48%; padding-right:2px; font-size:14px; line-height:1.6em; text-align:right; padding-top:9px; font-family: 'ProximaNovaRegular';}
.left-title-wrap-all { float:left; width:100%; font-size:12px; line-height:1.6em; padding-top:5px; margin-top:10px;  border-top:1px solid #bebdbd; padding-bottom:4px;}

.garis-wrap { height:1px; width:94%; padding-top:8px; padding-bottom:8px; border-top:1px solid #ccc; margin-top:20px; margin-left:10px;}

.content-detail { padding-top:10px; padding-left:10px;}
.table-order { font-family: 'ProximaNovaRegular';}
.table-order thead tr td { background:#0066CC; padding:7px 10px 7px 15px; color:#fff; vertical-align:top; font-size:14px; font-family: 'ProximaNovaBold';}
.table-order tbody tr td { padding:7px 10px 7px 15px; color:#000; line-height:1.6em; vertical-align:top; border-bottom:1px solid #ccc; font-size:14px; color:#666;}

td.td-ptext > p { line-height:1.4em;}
</style>

<div class="top-wrapper">
	<div class="left-wrapper">
        <div class="text-2">
            <img src="<?php echo $UPLOAD_FOLDER;?><?php echo $picconfig;?>" style="width:180px;" />
       </div>
    </div>
	<div class="left-wrapper2">
      	<span class="h2" style="text-transform:uppercase;">Draft Quotation</span>
    </div> 
    <div class="clear-2"></div>   
</div><!-- .top-wrapper -->

<div class="garis-wrap"></div>

<div class="title-wrap">
    <div class="left-title-wrap">
        <strong>Quotation Number: #DQ-<?php echo sprintf("%1$06d",$row['id']);?></strong><br />
        Order Date: <?php echo $row['tgl'];?>
    </div>
    <div class="left-title-wrap2">
    	Order By: &nbsp;&nbsp;<strong><?php echo getnamegeneral($row['id_user'],"corporate_user","name").' '.getnamegeneral($row['id_user'],"corporate_user","lastname");?></strong>
    </div> 
    <div class="clear-2"></div> 
</div>

<div class="content-detail">
			<table width="95%" border="0" cellpadding="0" cellspacing="0" class="table-order">
				<thead>
                <tr>
				  <td width="1%"><strong>No.</strong></td>
                  <td width="55%" colspan="2"><strong>Item Product</strong></td>
				  <td width="8%" align="center"><strong>Qty</strong></td>
                  <td width="12%" ><strong>Price</strong></td>
                  <td width="20%" align="right"><strong>Total</strong></td>				  
				</tr>
                </thead>
                
                <tbody>	
			<?php
					$xpage = 1; $totalS = 0; $totaloo = 0; $satuanproduct = '';
					$quippp = $db->query("SELECT * FROM `draft_quotation_detail` WHERE `tokenpay`='$token'");
					while($data = $quippp->fetch_assoc()):	
					
						if($data['nama_detail']<>''):
							$listdetail = explode("#",$data['nama_detail']);
							$namadetail = $listdetail[0];
						else:
							$namadetail = $data['nama_detail'];
						endif;													
			?>

					<tr>
						<td><?php echo $xpage;?>.</td>
					    <td colspan="2">
							<?php echo $data['name'];?><br />
                            <strong><?php echo $data['sku'];?></strong> - <?php echo $namadetail;?>	
                        </td>                    	                        					
						<td align="center"><?php echo $data['qty']?></td>	
                        <td style="text-align:left"><?php echo number_format($data['price']);?></td>
                        <td style="text-align:right"><?php echo number_format($data['price']*$data['qty']);?></td>						
					</tr>
                    </tbody>
                    
                    

			 <?php $xpage++; 
				  endwhile;
			  ?>	
            
			   <tr>
                   <td align="left" colspan="3" style="padding-top:15px;">&nbsp;</td>
                   <td colspan="2" width="75%" align="right" style="padding-top:15px;"><strong>Sub. Total</strong></td>
                   <td align="right" style="padding-top:15px;"><strong>Rp <?php echo number_format($row['total_order']);?></strong></td>
               </tr>             

			   <tr>
                   <td align="left" colspan="3" style="padding-top:15px;">&nbsp;</td>
                   <td colspan="2" width="75%" align="right" style="padding-top:15px;">Handling Fee</td>
                   <td align="right" style="padding-top:15px;">Rp <?php echo number_format($row['handling_fee']);?></td>
               </tr> 
                         	
				<tr>				
					<td colspan="5" align="right" style="padding-top:5px;">Biaya Pengiriman</td>
					<td align="right" style="padding-top:5px;">Rp <?php echo number_format($row['shippingcost']);?></td>
				</tr> 

				<tr>	
                	<td colspan="3" style="font-size:12px;">&nbsp;</td>			
					<td colspan="2" align="right" style="padding-top:5px;"><strong>Grand Total</strong></td>
                    <td align="right" style="padding-top:5px;"><strong>Rp <?php echo number_format($totalamount);?></strong>			
					</td>
				</tr>
                
		</table>

</div>


<div style="padding-top:60px; padding-bottom:50px;" class="submit_btn_label">
<form class="nuke-fancyform" method="post" action="" name="form1">
<input type="submit" name="submit_btn" id="submit_btn" class="submit-btn" value="Print Quotation" style="cursor:pointer; visibility:visible;  float:left; margin-left:10px; width:100px;">
</form>
</div>


<?php
if(isset($_POST['submit_btn'])){
?>	
	<script type="text/javascript">
		var subt=document.getElementById("submit_btn");
		subt.style.visibility='hidden';
		window.print();
	</script>
<?php } ?>	