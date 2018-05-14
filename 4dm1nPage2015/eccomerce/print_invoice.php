<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("function.php");
	require("webconfig-parameters.php");
	
	$uidadmin = $_SESSION[$SITE_TOKEN.'userID'];
	$querypage = $db->query("SELECT `user_badgedame` FROM `1001ti14_vi3w2014` WHERE `id`='$uidadmin'");
	$datam2 = $querypage->fetch_assoc();
	$membername = $datam2['user_badgedame'];	
	
	date_default_timezone_set('Asia/Jakarta');
	$dateNow = date("Y-m-d H:i:s");
						
	$idorder = $_GET['idorder'];	
	$que = $db->query("SELECT *,date_format(`date`,'%d %M %Y %H:%i:%s') as tgl FROM `order_header` WHERE `id`='$idorder'");
	$row = $que->fetch_assoc();
	$totalamount = ( ( ( $row['orderamount']+$row['shippingcost'] ) - $row['discountamount'] ) + $row['kode_unik'] + $row['handling_fee']);

											
	$tokenpayment = $row['tokenpay'];	
	
	$sqlconfig = $db->query("SELECT * FROM `web_config` WHERE `id`=1");
	$res = $sqlconfig->fetch_assoc();
	
	$quemmm = $db->query("SELECT `name`,`lastname`,`mobile_phone` FROM `member` WHERE `id`=".$row['idmember']."");
	$datam = $quemmm->fetch_assoc();		
?>
<script src="../scripts/jquery-1.6.min.js" type="text/javascript"></script>
<style>
.top-wrapper { padding-top:5px; padding-left:20px; padding-right:20px; width:90%;}
.clear-2 { clear:both; height:0px;}
.left-wrapper { width:45%; float:left;} .left-wrapper2 { width:55%; float:left;}
.text-2 { color:#000;  font-family:courier; font-size:13px; line-height:1.5em; font-weight:500;}
span.h2 { font-size:22px; font-weight:600; line-height:1.8em;}
.title { font-size:14px; text-transform:uppercase; font-weight:600;}
.text-3 {  margin-top:10px; color:#000;  font-family:courier; font-size:13px; line-height:1.5em; font-weight:500;}
span.h1 { font-size:12px; font-weight:600; line-height:1.8em;}
table.table-general {font-size:12px; color:#000;  font-family:courier;}
table.table-general td { vertical-align:top; line-height:1.5em;}
.title-wrap { width:90%; padding-top:4px; padding-bottom:4px; margin-left:10px; border-top:1px solid #bebdbd; border-bottom:1px solid #bebdbd; margin-top:12px; font-family:courier; color:#000;}
.left-title-wrap { float:left; width:45%; font-size:13px; line-height:1.6em;}
.left-title-wrap2 { float:left; width:50%; padding-left:10px; font-size:12px; line-height:1.6em;}
.left-title-wrap-all { float:left; width:100%; font-size:12px; line-height:1.6em; padding-top:5px; margin-top:10px;  border-top:1px solid #bebdbd; padding-bottom:4px;}
.content-detail { padding-top:10px; padding-left:20px; padding-right:10px;}
.table-order {font-size:12px; color:#000;  font-family:courier;}
table.table-order td { vertical-align:top; line-height:1.5em;}
td.td-ptext > p { line-height:1.4em;}
</style>

<div class="top-wrapper">
	<div class="left-wrapper">
        <div class="text-2">
            <span class="h2">INVOICE</span><br />
            <img src="<?php echo $UPLOAD_FOLDER;?><?php echo $picconfig;?>" style="width:100px;" />
            <div style="clear:both; height:8px;"></div>
            <span class="title"><?php echo ucwords($res['company']);?></span><br />
            www.fixcomart.com<br /><span style="font-size:11px;">Phone.<?php echo $res['phone'];?></span>
       </div>
    </div>
	<div class="left-wrapper2">
        <div class="text-3">
            <span class="h1">Shipping Address : </span><br />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table-general" style="padding-top:5px;">
                      <tr>
                        <td align="left" colspan="3" class="td-ptext">
							<?php echo'<strong>'.$row['nama_penerima'].'</strong><br />';?>
                            <?php
                                    echo $row['address_penerima'].'<br />';
                                    echo $row['kota_penerima'].', '. $row['kabupaten_penerima'].'<br />';
                                    echo $row['provinsi_penerima'].' - '.$row['country_penerima'].' '.$row['kodepos'];			
                            ?>  
                        </td>
                      </tr>
                      
                      <tr>
                        <td style="padding-top:3px; font-size:13px;" width="90">Ship via</td>
                        <td width="10" style="padding-top:6px;">:</td>
                        <td style="padding-top:3px; font-size:16px; color:#111;"><strong><?php echo $row['kurir'];?></strong></td>
                      </tr>
                      <tr>
                        <td style="padding-top:5px;"><strong>No. Resi</strong></td>
                        <td width="10" style="padding-top:5px;">:</td>
                        <td style="color:#ccc; padding-top:5px;">
                        	___________________________
                        </td>
                      </tr>
            </table>

       </div>
    </div> 
    <div class="clear-2"></div>   
</div><!-- .top-wrapper -->

<div class="title-wrap">
    <div class="left-title-wrap">
        <strong>INVOICE  NO. #<?php echo sprintf("%1$06d",$row['id']);?></strong><br />
        Date: <?php echo $row['tgl'];?>
    </div>
    <div class="left-title-wrap2">
    Name: &nbsp;&nbsp;<strong><?php echo ucwords($datam['name']);?> <?php echo ucwords($datam['lastname']);?></strong><br />
    Phone: &nbsp;<strong><?php echo $datam['mobile_phone'];?></strong>
    </div> 
    <div class="clear-2"></div> 
      
    <div class="left-title-wrap-all">
        Notes from customer: <strong><?php if($row['note']==""){echo'-';}else{echo $row['note'];}?></strong>
    </div>
    <div class="clear-2"></div> 
</div>

<div class="content-detail">
			<table width="90%" border="0" cellpadding="0" cellspacing="0" class="table-order">
				<tr>
				  <td width="4%" style="color:#0066CC;"><strong>No.</strong></td>
                  <td width="35%" colspan="2" style="color:#0066CC;"><strong>Item Product</strong></td>
				  <td width="7%" align="center" style="color:#0066CC;"><strong>Qty</strong></td>
                  <td width="11%" style="color:#0066CC;"><strong>Price</strong></td>
                  <td width="15%" align="right" style="color:#0066CC;"><strong>Total</strong></td>				  
				</tr>	
			<?php
					$xpage = 1; $totalS = 0; $totaloo = 0; $satuanproduct = '';
					$quippp = $db->query("SELECT * FROM `order_detail` WHERE `tokenpay`='$tokenpayment'");
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
                    <tr><td colspan="5" style="height:2px;"></td></tr>	

			 <?php $xpage++; 
				  endwhile;
			  
								//bonus
								$nextpage = $xpage+1;
                                $quebns = $db->query("SELECT * FROM `order_detail_bonus_product` WHERE `tokenpay`='".$row['tokenpay']."' ORDER BY `id` ASC");
                                $jumpagebb = $quebns->num_rows;
								while($resb = $quebns->fetch_assoc()):
                                                
										$imgnameList = getimagesdetailbonus($resb['idproduct']);
                                          
										echo'<tr>
											<td>'.$nextpage.'.</td>
											<td colspan="2"><strong>'.$resb['sku'].'</strong> - '.$resb['name'].'</td>                    	                        					
											<td align="center">1</td>	
											<td style="text-align:left" colspan="2">free item product</td>				
										</tr>
										<tr><td colspan="5" style="height:2px;"></td></tr>';      
														
                					$nextpage++;
                                endwhile;			  	  
			  
			  ?>	
            
			   <tr><td style="height:3px;" colspan="6">&nbsp;</td></tr>
			   <tr>
                   <td align="left" colspan="3" style="padding-top:15px;border-top:1px solid #bebdbd;">&nbsp;</td>
                   <td colspan="2" width="75%" align="right" style="padding-top:15px;border-top:1px solid #bebdbd;"><strong>Sub. Total</strong></td>
                   <td align="right" style="padding-top:15px; border-top:1px solid #bebdbd;"><strong>IDR <?php echo number_format($row['orderamount']);?></strong></td>
               </tr>             

				<tr>				
					<td colspan="5" align="right" style="padding-top:5px;">Handling Fee</td>
					<td align="right" style="padding-top:5px;">IDR <?php echo number_format($row['handling_fee']);?></td>
				</tr> 
                
               <?php if($row['discountamount']>0):?> 
				<tr>				
					<td colspan="5" align="right" style="padding-top:5px;">Voucher (<?php echo $row['vouchercode'];?>)</td>
					<td align="right" style="padding-top:5px;">- IDR <?php echo number_format($row['discountamount']);?></td>
				</tr> 	
                <?php endif;?>	
                               	
				<tr>				
					<td colspan="5" align="right" style="padding-top:5px;">Shipping Cost</td>
					<td align="right" style="padding-top:5px;">IDR <?php echo number_format($row['shippingcost']);?></td>
				</tr> 
				
                <?php if($row['payment_metod']=="Deposit"):?>
                    <tr>	
                        <td colspan="3" style="font-size:12px;">&nbsp;</td>			
                        <td colspan="2" align="right" style="padding-top:5px;"><strong>Grand Total</strong></td>
                        <td align="right" style="padding-top:5px;"><strong>IDR <?php echo number_format($totalamount);?></strong>			
                        </td>
                    </tr>
                <?php else:?>
                    <tr>	
                        <td colspan="3" style="font-size:12px;">&nbsp;</td>			
                        <td colspan="2" align="right" style="padding-top:5px;"><strong>Grand Total</strong></td>
                        <td align="right" style="padding-top:5px;"><strong>IDR <?php echo number_format($totalamount);?></strong>			
                        </td>
                    </tr>   
                    
                   <?php if($row['deposit_amount']>0):?>
                        <tr>	
                            <td colspan="3" style="font-size:12px;">&nbsp;</td>			
                            <td colspan="2" align="right" style="padding-top:5px;"><strong>Bayar Deposit</strong></td>
                            <td align="right" style="padding-top:5px;"><strong>- IDR <?php echo number_format($row['deposit_amount']);?></strong>			
                            </td>
                        </tr>   
                        
                        <tr>	
                            <td colspan="3" style="font-size:12px;">&nbsp;</td>			
                            <td colspan="2" align="right" style="padding-top:5px;"><strong>Sisa Pembayaran</strong></td>
                            <td align="right" style="padding-top:5px;"><strong>IDR <?php echo number_format($totalamount-$row['deposit_amount']);?></strong>			
                            </td>
                        </tr>   
                     <?php endif;?>                                         
                
                <?php endif;?>
                
                <tr>
                	<td></td>
                </tr>
                
		</table>

    <div style="padding-top:100px; font-size:10px; font-family:courier;">
      * Printed by: <?php if($uidadmin==1): echo'Superadmin'; else: echo $membername; endif;?> <?php echo $dateNow;?>
    </div>

</div>


<div style="padding-top:60px; padding-bottom:50px;" class="submit_btn_label">
<form class="nuke-fancyform" method="post" action="" name="form1">
<input type="submit" name="submit_btn" id="submit_btn" class="submit-btn" value="Print Invoice" style="cursor:pointer; visibility:visible;  float:left; margin-left:10px; width:100px;">
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