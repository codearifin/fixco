<?php include("header.php"); 
	
	  include("function_rnt.php");

// config name
$name = "commission_withdrawal";

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
	$query = $db->query("SELECT 
	
	`claim_komis_member`.`id` as cid,
	`claim_komis_member`.`bank_transfer` as cbank_transfer,
	`claim_komis_member`.`total` as ctotal,
	`claim_komis_member`.`status_payment` as cstatus_payment,
	DATE_FORMAT(`claim_komis_member`.`date`, '%d/%m/%Y %H:%i:%s') as mdate,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `claim_komis_member` LEFT JOIN `member` ON `member`.`id` = `claim_komis_member`.`idmember_claim` 
	
	WHERE `member`.`name` LIKE '%$keyword%' or `member`.`lastname` LIKE '%$keyword%' or `claim_komis_member`.`bank_transfer` LIKE '%$keyword%' 
	 
	 
	ORDER BY `claim_komis_member`.`id` DESC LIMIT $posisi,$batas");
		
		
else:
	$query = $db->query("SELECT 
	
	`claim_komis_member`.`id` as cid,
	`claim_komis_member`.`bank_transfer` as cbank_transfer,
	`claim_komis_member`.`total` as ctotal,
	`claim_komis_member`.`status_payment` as cstatus_payment,
	DATE_FORMAT(`claim_komis_member`.`date`, '%d/%m/%Y %H:%i:%s') as mdate,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `claim_komis_member` LEFT JOIN `member` ON `member`.`id` = `claim_komis_member`.`idmember_claim` 
	 
	ORDER BY `claim_komis_member`.`id` DESC LIMIT $posisi,$batas");
	
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
				<h2>Commission Withdrawal</h2>
				<form class="search-form" action="commission_withdrawal.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
            	<a href="new_mutasi_list_data.php" title="" class="add-btn left">Add Mutasi Commission</a>
            	<span style="color:#FF3300; padding-left:10px;"><?php echo $error_message;?></span>
                <div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-commission_withdrawal" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `claim_komis_member`.`id` as cid FROM `claim_komis_member` LEFT JOIN `member` ON `member`.`id` = `claim_komis_member`.`idmember_claim` 
	
															WHERE `member`.`name` LIKE '%$keyword%' or `member`.`lastname` LIKE '%$keyword%' or `claim_komis_member`.`bank_transfer` LIKE '%$keyword%' 

															ORDER BY `claim_komis_member`.`id` DESC");
											
									else:
										$sql2 = $db->query("SELECT `claim_komis_member`.`id` as cid FROM `claim_komis_member` LEFT JOIN `member` ON `member`.`id` = `claim_komis_member`.`idmember_claim` 
														    ORDER BY `claim_komis_member`.`id` DESC ");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="commission_withdrawal.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="commission_withdrawal.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="commission_withdrawal.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="commission_withdrawal.php?page='.$y.'" selected="selected">'.$y.'</option>';
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
				<input type="hidden" value="commission_withdrawal" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="2%">No.</td>
                            <td width="12%">Member Name</td>
                            <td width="8%">Date</td>
                            <td width="8%">Claim ID</td>
                            <td width="10%">Total Claim</td>
                            <td width="20%">Description</td>
                            <td width="12%">Status</td>
                            <td width="10%">Action</td>
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
											echo'<td><strong>'.$row['mname'].' '.$row['mlastname'].'</strong></td>';
											echo'<td>'.$row['mdate'].'</td>';
											echo'<td><strong style="font-size:14px;">#'.sprintf('%06d',$row['cid']).'</strong></td>';
											echo'<td>Rp. '.number_format($row['ctotal']).'</td>';
											echo'<td>'.$row['cbank_transfer'].'</td>';
											echo'<td>';
												if($row['cstatus_payment']=='Pending On Payment'):
													echo'<strong style="color:#FF6633;">Pending On Payment</strong>';
												
												elseif($row['cstatus_payment']=="Paid"):
													echo'<strong style="color:#00CC33;">Paid</strong>';
												
												else:
													echo'<strong style="color:#FF6633;">'.$row['cstatus_payment'].'</strong>';
												endif;
											echo'</td>';
											
											echo'<td>';
											if($row['cstatus_payment']=='Pending On Payment'):
												echo'<a href="eccomerce/update_claim_komis_member.php?iddata='.$row['cid'].'" class="nuke-fancied2" style="color:#0066CC;">Update Status</a>';
											else:
												echo'#';
											endif;	
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