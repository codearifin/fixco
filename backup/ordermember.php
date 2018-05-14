<?php include("header.php");
	  include("eccomerce/function.php");	 

// config name
$name = "ordermember";

// start pagging
if(isset($_SESSION['page-'.$name.'']) AND $_SESSION['page-'.$name.'']>0){
	$batas = $_SESSION['page-'.$name.'']; 
}else{
	 $batas = 30;
}

// get search keyword
if(isset($_GET['keyword']) AND $_GET['keyword']!=""){
	$keyword=$_GET['keyword'];
	$order_idkey = sprintf('%01d',$keyword);
}elseif(isset($_POST['keyword']) AND $_POST['keyword']!=""){
	$keyword=$_POST['keyword'];
	$order_idkey = sprintf('%01d',$keyword);
}else{
	$keyword=null;
	$order_idkey = 0;
}

if( isset($_SESSION['startdate']) <>'' and isset($_SESSION['enddate']) <>''):
	$startdate = $_SESSION['startdate'];  $enddate = $_SESSION['enddate'];
	$tglcari_start = replacetgl($startdate); 
	//tgl carinya
	$itemtgl = explode("/",$enddate);
	$lastN = mktime(0, 0, 0, $itemtgl[1], $itemtgl[2], $itemtgl[0]); // m d y
	$datepost_cari2	= date("Y/m/d", $lastN);
	$tglcari_end = replacetgl($datepost_cari2);	
	$LabelCari = " and DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN '$tglcari_start' and '$tglcari_end' ";
	
else:
	$startdate = '';  $enddate = '';
	$LabelCari = '';
	
endif;

if(isset($_GET['idmember']) <>'' ):
	$idmember = $_GET['idmember'];
else:
	$idmember = 0;
endif;

//status filter
if( isset($_GET['statuspayment']) <>'' ): $statuspayment = $_GET['statuspayment']; else: $statuspayment = 0; endif;
if( isset($_GET['statusdelivery']) <>'' ): $statusdelivery = $_GET['statusdelivery']; else: $statusdelivery = 0; endif;

if($statuspayment==1):
	$paymentstatus = 'Pending On Payment';
elseif($statuspayment==2):
	$paymentstatus = 'Waiting';
elseif($statuspayment==3):
	$paymentstatus = 'Confirmed';
elseif($statuspayment==4):
	$paymentstatus = 'Cancelled';	
elseif($statuspayment==5):
	$paymentstatus = 'Transaction Fail';	
elseif($statuspayment==6):
	$paymentstatus = 'Auto Cancelled';		
endif;	

if($statusdelivery==1):
	$edelivery = 'Pending';
elseif($statusdelivery==2):
	$edelivery = 'Shipped';
endif;

if(isset($_GET['paymentvia'])):
	$paymentvia = $_GET['paymentvia'];
else:
	$paymentvia = 0;	
endif;	

if($paymentvia==1):
	$paymentvia_text = 'BANK TRANSFER';
	
elseif($paymentvia==2):
	$paymentvia_text = 'Veritrans';
	
elseif($paymentvia==3):
	$paymentvia_text = 'BCA KlikPay';

elseif($paymentvia==4):
	$paymentvia_text = 'Visa Master';	

else:
	$paymentvia_text = '';
	
endif;	


// Get Page
if(isset($_GET['page'])){
	// set limit posisi
	$i = 0; $page = $_GET['page'];		
}

if(empty($page)){
	$posisi=0;$page=1;	
}else{ 
	$posisi= (($page-1) * $batas ); 
}

//main Query
if($keyword!=""):	
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `order_header` WHERE 1=1 ".$LabelCari." 
	and ( `id` LIKE '%$order_idkey%' or `nama_penerima` LIKE '%$keyword%' or `bca_tokenid` LIKE '%$order_idkey%' or `kode_trxno_klikpay1` LIKE '%$keyword%' or `kode_trxno_klikpay2` LIKE '%$keyword%' 
	or `resinumber` LIKE '%$keyword%' or `vouchercode` LIKE '%$keyword%' or `address_penerima` LIKE '%$keyword%' ) ORDER BY `date` DESC LIMIT $posisi,$batas") or die($db->error);
elseif($idmember>0):
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `order_header` WHERE 1=1 and `idmember`='$idmember' ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");
elseif($statuspayment>0):
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `order_header` WHERE 1=1 and `status_payment`='$paymentstatus' ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");
elseif($statusdelivery>0):
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `order_header` WHERE 1=1 and `status_delivery`='$edelivery' ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");
elseif($paymentvia>0):
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `order_header` WHERE 1=1 and `payment_metod`='$paymentvia_text' ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");
else:
	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl FROM `order_header` WHERE 1=1 ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");
endif;
?>
<link href="eccomerce/eccomerce.css" rel="stylesheet" type="text/css" />
<script>
$(document).ready(function() { 
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
  
  $('.date-pick').datepicker({ 'format': 'yyyy/mm/dd', 'autoclose': true });
  
});
</script>

	
	<div id="cms-content" class="clearfix">
		<?php show_left_menu($theMenu);?>
		<div class="cms-main-content right">
			<?php check_privileges(); ?>
			<div class="cm-top">
				<h2><a href="ordermember.php">Order History</a></h2>
                <?php if(isset($_GET['msg']) <>'' ): echo'<span class="error_ordernotif">* '.$_GET['msg'].'</span>'; endif;?>
				<form class="search-form" action="ordermember.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
            	<a href="eccomerce/cari_by_member.php" title="" class="add-btn left nuke-fancied2" style="margin-right:20px;">Browse By Member</a>
                <form action="eccomerce/filter_bydate.php" method="post">
                	<span style="display:inline-block; width:90px; float:left;">Filter by date : 
					<?php if( isset($_SESSION['startdate']) <>'' and isset($_SESSION['enddate']) <>''): echo'<br /><a href="eccomerce/filter_bydate.php" class="datereset">Reset Date</a>'; endif;?></span> 
                    <input type="text" class="date-pick tngglinput" name="startdate" value="<?php echo $startdate;?>" placeholder="Start Date" />  
                    <input type="text" class="date-pick tngglinput" name="enddate" value="<?php echo $enddate;?>" placeholder="End Date" />
                    <input type="hidden" value="<?php echo $idmember;?>" name="idmember" />
					<input type="submit" value="FIND" class="cari-btntgl" />
                </form>
				<div style="clear:both; height:15px;"></div>
				
                <div class="filter-order">
                      <form action="" method="post" class="meta-form"> 
                       <select onChange="MM_jumpMenu('parent',this,0)" style="width:170px;">
                            <option value="ordermember.php">- Payment Via -</option>		
                            <option value="ordermember.php?paymentvia=1" <?php if($paymentvia==1): echo'selected="selected"'; endif;?>>Bank Transfer</option>
                            <option value="ordermember.php?paymentvia=2" <?php if($paymentvia==2): echo'selected="selected"'; endif;?>>Veritrans</option>
                            <?php /*
                            <option value="ordermember.php?paymentvia=3" <?php if($paymentvia==3): echo'selected="selected"'; endif;?>>BCA KlikPay</option>
                            <option value="ordermember.php?paymentvia=4" <?php if($paymentvia==4): echo'selected="selected"'; endif;?>>Visa Master</option>
							*/?>
                        </select>
                                                
                        &nbsp;&nbsp;&nbsp;
                       <select onChange="MM_jumpMenu('parent',this,0)" style="width:210px;">
                            <option value="ordermember.php">- Payment -</option>		
                            <option value="ordermember.php?statuspayment=1" <?php if($statuspayment==1): echo'selected="selected"'; endif;?>>Pending On Payment</option>
                            <option value="ordermember.php?statuspayment=2" <?php if($statuspayment==2): echo'selected="selected"'; endif;?>>Waiting Confirm</option>
                            <option value="ordermember.php?statuspayment=3" <?php if($statuspayment==3): echo'selected="selected"'; endif;?>>Paid</option>
                            <option value="ordermember.php?statuspayment=4" <?php if($statuspayment==4): echo'selected="selected"'; endif;?>>Cancelled</option>
                            <option value="ordermember.php?statuspayment=5" <?php if($statuspayment==5): echo'selected="selected"'; endif;?>>Fail</option>
                            <option value="ordermember.php?statuspayment=6" <?php if($statuspayment==6): echo'selected="selected"'; endif;?>>Auto Cancelled</option>
                        </select>
                       
                        &nbsp;
                        <select onChange="MM_jumpMenu('parent',this,0)" style="width:190px;">
                            <option value="ordermember.php">- Delivery -</option>		
                            <option value="ordermember.php?statusdelivery=1" <?php if($statusdelivery==1): echo'selected="selected"'; endif;?>>Pending</option>
                            <option value="ordermember.php?statusdelivery=2" <?php if($statusdelivery==2): echo'selected="selected"'; endif;?>>Shipped</option>
                        </select>
                        
                        <span style="display:inline-block; width:105px;"></span>
                        Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-ordermember" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `id` FROM `order_header` WHERE 1=1 ".$LabelCari." and ( `id` LIKE '%$order_idkey%' or `nama_penerima` LIKE '%$keyword%' or `bca_tokenid` LIKE '%$order_idkey%' 
										or `kode_trxno_klikpay1` LIKE '%$keyword%' or `kode_trxno_klikpay2` LIKE '%$keyword%' or `resinumber` LIKE '%$keyword%' or `vouchercode` LIKE '%$keyword%' or `address_penerima` LIKE '%$keyword%' ) 
										ORDER BY `date` DESC");
										
									elseif($idmember>0):
										$sql2 = $db->query("SELECT `id` FROM `order_header` WHERE 1=1 and `idmember`='$idmember' ".$LabelCari." ORDER BY `date` DESC");
									elseif($statuspayment>0):
										$sql2 = $db->query("SELECT `id` FROM `order_header` WHERE 1=1 and `status_payment`='$paymentstatus' ".$LabelCari." ORDER BY `date` DESC");
									elseif($statusdelivery>0):
										$sql2 = $db->query("SELECT `id` FROM `order_header` WHERE 1=1 and `status_delivery`='$edelivery' ".$LabelCari." ORDER BY `date` DESC");
									elseif($paymentvia>0):
										$sql2 = $db->query("SELECT `id` FROM `order_header` WHERE 1=1 and `payment_metod`='$paymentvia_text' ".$LabelCari." ORDER BY `date` DESC");
									else:
										$sql2 = $db->query("SELECT `id` FROM `order_header` WHERE 1=1 ".$LabelCari." ORDER BY `date` DESC");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="ordermember.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											elseif($idmember>0):
												echo'<option value="ordermember.php?page='.$y.'&idmember='.$idmember.'">'.$y.'</option>';
											elseif($statuspayment>0):
												echo'<option value="ordermember.php?page='.$y.'&statuspayment='.$statuspayment.'">'.$y.'</option>';
											elseif($statusdelivery>0):
												echo'<option value="ordermember.php?page='.$y.'&statusdelivery='.$statusdelivery.'">'.$y.'</option>';
											elseif($paymentvia>0):
												echo'<option value="ordermember.php?page='.$y.'&paymentvia='.$paymentvia.'">'.$y.'</option>';
											else:
												echo'<option value="ordermember.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="ordermember.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											elseif($idmember>0):
												echo'<option value="ordermember.php?page='.$y.'&idmember='.$idmember.'" selected="selected">'.$y.'</option>';
											elseif($statuspayment>0):
												echo'<option value="ordermember.php?page='.$y.'&statuspayment='.$statuspayment.'" selected="selected">'.$y.'</option>';
											elseif($statusdelivery>0):
												echo'<option value="ordermember.php?page='.$y.'&statusdelivery='.$statusdelivery.'" selected="selected">'.$y.'</option>';											
											elseif($paymentvia>0):
												echo'<option value="ordermember.php?page='.$y.'&paymentvia='.$paymentvia.'" selected="selected">'.$y.'</option>';	
											else:
												echo'<option value="ordermember.php?page='.$y.'" selected="selected">'.$y.'</option>';
											endif;	
										endif;
									endfor;
								?>	
							</select>
                            </label>
                      </form>  
                 </div>
                                        
                <div style="clear:both; height:10px;"></div>
			</div><!-- .meta-top -->

			<div style="float:left; padding-bottom:10px;">
				<a href="download_orderheader_header.php" class="add-btn left nuke-fancied2" style="margin-right:10px;" target="_blank">Download order header</a>
				<a href="download_orderheader_summary.php" class="add-btn left nuke-fancied2" target="_blank">Download Summary Order</a>
			</div>

			<div class="cm-mid">
				<form action="#" method="post">
				<input type="hidden" value="<?php echo $baselink ?>" name="urel" id="urelid_page" />
				<input type="hidden" value="order_header" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="3%">No.</td>
                            <td width="8%">Order Date</td>
                            <td width="10%">Order ID</td>
                            <td width="12%">Total Order</td>
                            <td width="13%">Payment Method</td>
                            <td width="14%">Shipping To</td>
                            <td width="16%">Payment</td>
                            <td width="17%">Delivery</td>
                            <td width="10%">Action</td>
						</tr>
					</thead>
                    
					<tbody>
						<?php
							if($query->num_rows>0):
								$i = 0; $totalorder = 0; $statusbayar = 0;
								while($row = $query->fetch_assoc()):
										$pageNo = $i+$posisi+1;
										if($row['payment_metod']=="Deposit"):
											$totalorder = ( ( $row['orderamount']+$row['shippingcost'] ) - $row['discountamount'] ) + $row['kode_unik'] + $row['handling_fee'];
										else:	
											$totalorder = ( ( ( $row['orderamount']+$row['shippingcost'] ) - $row['discountamount'] ) + $row['kode_unik'] + $row['handling_fee']) - $row['deposit_amount'];
										endif;
										
										$statusbayar = getstatus_konfirmasi($row['id']);
										
										echo'<tr id="wrap-media-'.$row['id'].'">';
											echo'<td>'.$pageNo.'. </td>';
											echo'<td>'.$row['tgl'].'<br /><a href="eccomerce/order_noteview.php?idorder='.$row['id'].'" class="nuke-fancied2 confirm_pay-2" title="">Add Note</a></td>';
											echo'<td><strong style="color:#666; font-size:14px;">'.sprintf('%06d',$row['id']).'</strong>
													<br /><a href="eccomerce/detail_order.php?orderid='.$row['id'].'" class="nuke-fancied2">View Detail</a>';
											
												  if($row['bca_tokenid']<>''):
												  		echo'<div style="clear:both; height:5px;"></div>';
														echo'ID Trx BCA:<br> <strong>'.$row['bca_tokenid'].'</strong>';
													endif;
													
											echo'</td>';
											echo'<td><strong style="color:#666; font-size:14px;">'.number_format($totalorder).'</strong>';
												if($statusbayar>0): echo'<br /><a href="eccomerce/detail_pembayaran.php?orderid='.$row['id'].'" class="nuke-fancied2">View Payment</a>'; endif;
												
																										
														if($row['kode_trxno_klikpay1']<>''):
															echo'<div style="clear:both; height:5px;"></div>'; 
															echo'Detail Payment:<br> <strong>'.$row['kode_trxno_klikpay1'].'</strong> | <strong>'.$row['kode_trxno_klikpay2'].'</strong>';
														endif;
														
											echo'</td>';											
											echo'<td><strong style="text-transform:uppercase; color:#009999;">'.$row['payment_metod'].'</strong></td>';
											echo'<td>';
												echo'<strong>'.$row['nama_penerima'].'</strong><br />';
												echo'<a href="eccomerce/detail_shiiping_address.php?orderid='.$row['id'].'" class="nuke-fancied2">View Detail</a>';
											echo'</td>';
											
											echo'<td>'.getstatuspayment($row['status_payment']).' '.getstatuspaymentButton($row['status_payment'],$row['id']).'</td>';
											echo'<td>'.getstatusdelivery($row['status_delivery']).' '.getstatusdeliveryButton($row['status_payment'],$row['status_delivery'],$row['id']).'</td>';
											
											echo'<td>';
												  echo '<a href="eccomerce/delete_order.php?orderid='.$row['id'].'" title="Delete" onclick="return confirm(\'Apakah anda yakin untuk menghapus order ini?\');">Delete</a>';	
												  
													 if($row['status_payment']=="Confirmed" or $row['status_payment']=="Pending On Payment" or $row['status_payment']=="Waiting"):?>
													<div style="padding-top:7px;">
															  <a href="" onclick="javascript:window.open('eccomerce/print_invoice.php?idorder=<?php echo $row['id'];?>', '', 
															 'toolbar=,location=no,status=no,menubar=yes,scroll bars=yes, resizable=no,width=800,height=450'); return false" title="Print Invoice.."><img src="eccomerce/printlogo.png" width="15" /></a>
		
															 &nbsp;
															 <a href="" onclick="javascript:window.open('eccomerce/print_labelorder.php?idorder=<?php echo $row['id'];?>', '', 
															 'toolbar=,location=no,status=no,menubar=yes,scroll bars=yes, resizable=no,width=800,height=450'); return false" title="Print Label.."><img src="eccomerce/printlogo.png" width="15" /></a>                                                    </div>
													<?php
														
													 endif;
												 	
											echo'</td>';
											
										echo'</tr>';
									$i++;
								endwhile;
							else:
								echo'<tr><td colspan="9"><span class="no-page">Records not found.</span></td></tr>';
							endif;
						?>	 
					</tbody>
				</table>
					<input type="submit" value="Submit" class="submit_bulk" style="display:none;" />
				</form>
			</div><!-- .cm-mid -->
		</div><!-- .cms-main-content -->
	</div><!-- #cms-content -->
	
<?php include("footer.php"); ?>