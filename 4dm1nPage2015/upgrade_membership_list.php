<?php include("header.php"); 
	
	  include("function_rnt.php");

// config name
$name = "upgrade_membership_list";

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
	
	`member_membership_data_payment`.`id` as mid,
	 DATE_FORMAT(`member_membership_data_payment`.`date`, '%d/%m/%Y') as mdate,
	`member_membership_data_payment`.`idmember` as midmember,
	`member_membership_data_payment`.`iddata_membership` as middata_membership,
	`member_membership_data_payment`.`idbank` as midbank,
	`member_membership_data_payment`.`idtype_membership` as midtype_membership,
	`member_membership_data_payment`.`metode_payment` as mmetode_payment,
	`member_membership_data_payment`.`amount` as mamount,
	`member_membership_data_payment`.`status` as mstatus,
	
	`member_membership_data`.`id` as pid,
	`member_membership_data`.`company_name` as pcompany_name,
	`member_membership_data`.`npwp` as pnpwp,
	`member_membership_data`.`file_member` as pfile_member
	
			
	
	FROM `member_membership_data_payment` LEFT JOIN `member_membership_data` ON `member_membership_data_payment`.`iddata_membership` = `member_membership_data`.`id` 
	
	WHERE `member_membership_data`.`company_name` LIKE '%$keyword%' or `member_membership_data`.`npwp` LIKE '%$keyword%' 
	
	ORDER BY `member_membership_data_payment`.`id` DESC LIMIT $posisi,$batas");
		
else:
	$query = $db->query("SELECT 
	
	`member_membership_data_payment`.`id` as mid,
	 DATE_FORMAT(`member_membership_data_payment`.`date`, '%d/%m/%Y') as mdate,
	`member_membership_data_payment`.`idmember` as midmember,
	`member_membership_data_payment`.`iddata_membership` as middata_membership,
	`member_membership_data_payment`.`idbank` as midbank,
	`member_membership_data_payment`.`idtype_membership` as midtype_membership,
	`member_membership_data_payment`.`metode_payment` as mmetode_payment,
	`member_membership_data_payment`.`amount` as mamount,
	`member_membership_data_payment`.`status` as mstatus,
	
	`member_membership_data`.`id` as pid,
	`member_membership_data`.`company_name` as pcompany_name,
	`member_membership_data`.`npwp` as pnpwp,
	`member_membership_data`.`file_member` as pfile_member
	
			
	
	FROM `member_membership_data_payment` LEFT JOIN `member_membership_data` ON `member_membership_data_payment`.`iddata_membership` = `member_membership_data`.`id` 
	ORDER BY `member_membership_data_payment`.`id` DESC LIMIT $posisi,$batas");
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
				<h2>Upgrade Membership</h2>
				<form class="search-form" action="upgrade_membership_list.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
            	<span style="color:#FF3300;"><?php echo $error_message;?></span>
				<div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-upgrade_membership_list" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `member_membership_data_payment`.`id` FROM 
										`member_membership_data_payment` LEFT JOIN `member_membership_data` ON `member_membership_data_payment`.`iddata_membership` = `member_membership_data`.`id` 
										 WHERE `member_membership_data`.`company_name` LIKE '%$keyword%' or `member_membership_data`.`npwp` LIKE '%$keyword%' 
										 ORDER BY `member_membership_data_payment`.`id` DESC ");
									else:
										$sql2 = $db->query("SELECT `member_membership_data_payment`.`id` FROM 
										`member_membership_data_payment` LEFT JOIN `member_membership_data` ON `member_membership_data_payment`.`iddata_membership` = `member_membership_data`.`id` 
										 ORDER BY `member_membership_data_payment`.`id` DESC ");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="upgrade_membership_list.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="upgrade_membership_list.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="upgrade_membership_list.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="upgrade_membership_list.php?page='.$y.'" selected="selected">'.$y.'</option>';
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
				<input type="hidden" value="upgrade_membership_list" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="4%">No.</td>
                            <td width="12%">Member Name</td>
                            <td width="20%">Company Name</td>
                            <td>Top Up Date</td>
                            <td>Jumlah Deposit</td>
                            <td>Type Deposit</td>
                            <td>Payment</td>
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
											echo'<td><strong>'.getnamegenal(" `id`='".$row['midmember']."' ", "member","name").' '.getnamegenal(" `id`='".$row['midmember']."' ", "member","lastname").'</strong></td>';
											echo'<td><strong>'.$row['pcompany_name'].'</strong>';
													if($row['pnpwp']<>''): echo'<br />NPWP: '.$row['pnpwp'].''; endif;
													echo'<br />File Upload: <a href="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['pfile_member'].'" target="_blank" style="color:#00CC66;">View File</a>';
											echo'</td>';
											echo'<td>'.$row['mdate'].'</td>';
											echo'<td>Rp. '.number_format($row['mamount']).'</td>';
											echo'<td>'.getnamegenal(" `id`='".$row['midtype_membership']."' ", "membership_deposit_adm","title").'</td>';
											echo'<td><strong>'.$row['mmetode_payment'].'</strong>';
												echo'<br />'.getnamegenal(" `id`='".$row['midbank']."' ", "bank_account","bank_name").' - '.getnamegenal(" `id`='".$row['midbank']."' ", "bank_account","account_number").'';
											echo'</td>';
											echo'<td>';
												if($row['mstatus']=="Waiting"):
													echo'<strong style="color:#FF6633;">Waiting Approval</strong>';
												elseif($row['mstatus']=="Confirmed"):
													echo'<strong style="color:#00CC33;">Confirmed</strong>';
												else:
													echo'<strong style="color:#FF6633;">'.$row['mstatus'].'</strong>';
												endif;
												
												if($row['mstatus']=="Waiting"):
													echo'<br /><a href="eccomerce/confirm_upgarde_membership.php?iddata='.$row['mid'].'" style="color:#0066CC;" class="nuke-fancied2">Approve Member!</a>';	
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