<?php include("header.php"); 
	
	  include("function_rnt.php");

// config name
$name = "topup_deposit_list";

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
	
	`deposit_member_corporatelist_konfirmasi`.`id` as cid,
	`deposit_member_corporatelist_konfirmasi`.`bank` as cbank,
	`deposit_member_corporatelist_konfirmasi`.`account_holder` as caccount_holder,
	`deposit_member_corporatelist_konfirmasi`.`amount` as camount,
	`deposit_member_corporatelist_konfirmasi`.`bukti_trf` as cbukti_trf,
	`deposit_member_corporatelist_konfirmasi`.`status` as cstatus,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date_posting`, '%d/%m/%Y %H:%i:%s') as mdate,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date`, '%d/%m/%Y') as mdate2,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `deposit_member_corporatelist_konfirmasi` LEFT JOIN `member` ON `member`.`id` = `deposit_member_corporatelist_konfirmasi`.`idmember` 
	
	WHERE `member`.`name` LIKE '%$keyword%' or `member`.`lastname` LIKE '%$keyword%' or `deposit_member_corporatelist_konfirmasi`.`account_holder` LIKE '%$keyword%' 
	 
	 
	ORDER BY `claim_komis_member`.`id` DESC LIMIT $posisi,$batas");
		
		
else:
	$query = $db->query("SELECT 
	
	`deposit_member_corporatelist_konfirmasi`.`id` as cid,
	`deposit_member_corporatelist_konfirmasi`.`bank` as cbank,
	`deposit_member_corporatelist_konfirmasi`.`account_holder` as caccount_holder,
	`deposit_member_corporatelist_konfirmasi`.`amount` as camount,
	`deposit_member_corporatelist_konfirmasi`.`bukti_trf` as cbukti_trf,
	`deposit_member_corporatelist_konfirmasi`.`status` as cstatus,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date_posting`, '%d/%m/%Y %H:%i:%s') as mdate,
	DATE_FORMAT(`deposit_member_corporatelist_konfirmasi`.`date`, '%d/%m/%Y') as mdate2,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `deposit_member_corporatelist_konfirmasi` LEFT JOIN `member` ON `member`.`id` = `deposit_member_corporatelist_konfirmasi`.`idmember` 
	 
	ORDER BY `deposit_member_corporatelist_konfirmasi`.`id` DESC LIMIT $posisi,$batas");
	
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
				<h2>Top Up Deposit</h2>
				<form class="search-form" action="topup_deposit_list.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
            	<a href="topup_deposit_list_add.php" title="" class="add-btn left">Add Topup Deposit</a>
                <span class="msg-err"><?php echo $error_message;?></span>
                <div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-topup_deposit_list" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `deposit_member_corporatelist_konfirmasi`.`id` as cid FROM `deposit_member_corporatelist_konfirmasi` LEFT JOIN `member` ON `member`.`id` = `deposit_member_corporatelist_konfirmasi`.`idmember` 
	
															WHERE `member`.`name` LIKE '%$keyword%' or `member`.`lastname` LIKE '%$keyword%' or `deposit_member_corporatelist_konfirmasi`.`account_holder` LIKE '%$keyword%' 
	 
															ORDER BY `claim_komis_member`.`id` DESC");
											
									else:
										$sql2 = $db->query("SELECT `deposit_member_corporatelist_konfirmasi`.`id` as cid 
										
															FROM `deposit_member_corporatelist_konfirmasi` LEFT JOIN `member` ON `member`.`id` = `deposit_member_corporatelist_konfirmasi`.`idmember` 
	 
															ORDER BY `deposit_member_corporatelist_konfirmasi`.`id` DESC ");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="topup_deposit_list.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="topup_deposit_list.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="topup_deposit_list.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="topup_deposit_list.php?page='.$y.'" selected="selected">'.$y.'</option>';
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
				<input type="hidden" value="topup_deposit_list" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="1%">No.</td>
                            <td width="12%">Posting Date</td>
                            <td width="12%">Member Name</td>
                            <td width="8%">Trf Date</td>
                            <td width="8%">Bank</td>
                            <td width="12%">Account Holder</td>
                            <td width="10%">Total Top Up</td>
                            <td width="8%">Bukti Trf</td>
                            <td width="15%">Status</td>
                            <td width="5%">Action</td>
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
											echo'<td><strong>'.$row['mname'].' '.$row['mlastname'].'</strong></td>';
											echo'<td>'.$row['mdate2'].'</td>';
											echo'<td>'.$row['cbank'].'</td>';
											echo'<td>'.$row['caccount_holder'].'</td>';
											echo'<td>Rp. '.number_format($row['camount']).'</td>';
											echo'<td>';
												if($row['cbukti_trf']!=""):
													echo'<a href="'.$GLOBALS['UPLOAD_FOLDER'].''.$row['cbukti_trf'].'" target="_blank" style="color:#00CC66;">View File</a>';
												else:
													echo'-';
												endif;	
											echo'</td>';
											echo'<td>';
												if($row['cstatus']==0):
													echo'<strong style="color:#FF6633;">Waiting Confirmation</strong>';
												
												elseif($row['cstatus']==1):
													echo'<strong style="color:#00CC33;">Confirmed</strong>';
												
												else:
													echo'<strong style="color:#FF6633;">Fail</strong>';
												endif;
											echo'</td>';
											
											echo'<td>';
												if($row['cstatus']==0):
													echo'<a href="eccomerce/update_topup_komisilist.php?iddata='.$row['cid'].'" class="nuke-fancied2" style="color:#0066CC;">Validasi!</a>';
												else:
													echo'#';
												endif;	
											echo'</td>';											
										echo'</tr>';
									$i++;
								endwhile;
							else:
								echo'<tr><td colspan="10"><span class="no-page">Records not found.</span></td></tr>';
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