<?php include("header.php"); 
	
	  include("function_rnt.php");

// config name
$name = "redeem_history_list";

// start pagging
if(isset($_SESSION['page-'.$name.'']) AND $_SESSION['page-'.$name.'']>0){
	$batas = $_SESSION['page-'.$name.'']; 
}else{
	 $batas = 30;
}

// get search keyword
if(isset($_GET['keyword']) AND $_GET['keyword']!=""){
	$keyword=$_GET['keyword'];
}elseif(isset($_POST['keyword']) AND $_POST['keyword']!=""){
	$keyword=$_POST['keyword'];
}else{
	$keyword=null;
}

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

// Get Error Message
if(isset($_GET['msg']) AND $_GET['msg'] != ''){
	$error_message = $_GET['msg'];
}else{
	$error_message = '';
}


//main Query
if($keyword!=""):		
	$query = $db->query("SELECT *, DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s'') as mdate FROM `order_header_redeemlist` WHERE `resinumber` LIKE '%$keyword%' or `nama_penerima` LIKE '%$keyword%' or `address_penerima` LIKE '%$keyword%' 
	ORDER BY `id` DESC LIMIT $posisi,$batas");
		
else:
	$query = $db->query("SELECT *, DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as mdate FROM `order_header_redeemlist` ORDER BY `id` DESC LIMIT $posisi,$batas");
endif;

?>
<script>
$(document).ready(function() { 
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
});
</script>
	
	<div id="cms-content" class="clearfix">
		<?php show_left_menu($theMenu);?>
		<div class="cms-main-content right">
			<?php check_privileges(); ?>
			<div class="cm-top">
				<h2>Redeem History</h2>
				<form class="search-form" action="redeem_history_list.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
				<div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-redeem_history_list" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `id` FROM `order_header_redeemlist` WHERE `resinumber` LIKE '%$keyword%' or `nama_penerima` LIKE '%$keyword%' or `address_penerima` LIKE '%$keyword%' 
										ORDER BY `id` DESC");
									else:
										$sql2 = $db->query("SELECT `id` FROM `order_header_redeemlist` ORDER BY `id` DESC ");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="redeem_history_list.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="redeem_history_list.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="redeem_history_list.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="redeem_history_list.php?page='.$y.'" selected="selected">'.$y.'</option>';
											endif;	
										endif;
									endfor;
								?>	
							</select>
						</label>
					</form>
				</div><!-- .paging-meta -->
			</div><!-- .meta-top -->
			<div class="cm-mid">
				<form action="#" method="post">
				<input type="hidden" value="<?php echo $baselink ?>" name="urel" id="urelid_page" />
				<input type="hidden" value="redeem_history_list" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="4%">No.</td>
                            <td width="12%">Redeem Date</td>
                            <td width="10%">Redeem ID</td>
                            <td width="12%">Member Name</td>
                            <td width="20%">Item Product</td>
                            <td>Point</td>
                            <td>Alamat Pengiriman</td>
                            <td>Status</td>
						</tr>
					</thead>
					<tbody>
						<?php
							if($query->num_rows>0):
								$i = 0;
								while($row = $query->fetch_assoc()):
										$pageNo = $i+$posisi+1;
										echo'<tr>';
											echo'<td>'.$pageNo.'. </td>';
											echo'<td>'.$row['mdate'].'</td>';
											echo'<td><strong style="font-size:14px;">#'.sprintf('%06d',$row['id']).'</strong>';
											echo'<br /><a href="eccomerce/detail_redeem_membership.php?iddata='.$row['id'].'" class="nuke-fancied2">View Detail</a></td>';
											echo'<td><strong>'.getnamegenal(" `id`='".$row['idmember']."' ", "member","name").' '.getnamegenal(" `id`='".$row['idmember']."' ", "member","lastname").'</strong></td>';
											echo'<td><strong>'.$row['sku_product'].'</strong><br />'.$row['name'].'</td>';
											echo'<td>'.number_format($row['point']).'</td>';
											echo'<td>'.$row['nama_penerima'].' ('.$row['phone_penerima'].')<br />';
													 echo nl2br($row['address_penerima']);
													 echo'<br />'.$row['kota_penerima'].' - '.$row['kabupaten_penerima'];
													 echo'<br />'.$row['provinsi_penerima'].' - '.$row['country_penerima'];
											echo'</td>';
											echo'<td>';
												if($row['status_delivery']=="Pending On Delivery"):
													echo'<strong style="color:#FF6633;">Menunggu Approval</strong>';
												elseif($row['status_delivery']=="Shipped"):
													echo'<strong style="color:#00CC33;">Sudah Dikirim</strong>';
												else:
													echo'<strong style="color:#FF6633;">'.$row['status_delivery'].'</strong>';
												endif;
												
												if($row['status_delivery']=="Pending On Delivery"):
													echo'<br /><a href="eccomerce/confirm_redeem_membership.php?iddata='.$row['id'].'" style="color:#0066CC;" class="nuke-fancied2">Approve Redeem!</a>';			
												endif;
												
												echo'<br /><a href="eccomerce/order_noteview_redeem.php?idorder='.$row['id'].'" class="nuke-fancied2" style="color:#FF6633;" title="">Add Note!</a>';
											echo'</td>';
										echo'</tr>';
									$i++;
								endwhile;
							else:
								echo'<tr><td colspan="8"><span class="no-page">Records not found.</span></td></tr>';
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