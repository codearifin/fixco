<?php include("header.php"); 
	
	  include("function_rnt.php");

// config name
$name = "affiliate_list";

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
	
	`affiliate_member`.`id` as aid,
	`affiliate_member`.`token_affiliate` as atoken_affiliate,
	`affiliate_member`.`status` as astatus,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `affiliate_member` LEFT JOIN `member` ON `member`.`id` = `affiliate_member`.`idmember_aid` 
	
	WHERE `member`.`name` LIKE '%$keyword%' or `member`.`lastname` LIKE '%$keyword%' 
	 
	 
	ORDER BY `affiliate_member`.`id` DESC LIMIT $posisi,$batas");
		
		
else:
	$query = $db->query("SELECT 
	
	`affiliate_member`.`id` as aid,
	`affiliate_member`.`token_affiliate` as atoken_affiliate,
	`affiliate_member`.`status` as astatus,
	
	`member`.`name` as mname,
	`member`.`lastname` as mlastname
	
	FROM `affiliate_member` LEFT JOIN `member` ON `member`.`id` = `affiliate_member`.`idmember_aid` ORDER BY `affiliate_member`.`id` DESC LIMIT $posisi,$batas");
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
				<h2>Affiliate Member</h2>
				<form class="search-form" action="affiliate_list.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
				<a href="affiliate-add.php" title="" class="add-btn left">Add New Affiliate</a>
                <span class="msg-err"><?php echo $error_message;?></span>
                <div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-affiliate_list" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `affiliate_member`.`id` as aid FROM `affiliate_member` LEFT JOIN `member` ON `member`.`id` = `affiliate_member`.`idmember_aid` 
	
											WHERE `member`.`name` LIKE '%$keyword%' or `member`.`lastname` LIKE '%$keyword%' 
											ORDER BY `affiliate_member`.`id` DESC");
											
									else:
										$sql2 = $db->query("SELECT `affiliate_member`.`id` as aid FROM `affiliate_member` LEFT JOIN `member` ON `member`.`id` = `affiliate_member`.`idmember_aid` ORDER BY `affiliate_member`.`id` DESC");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="affiliate_list.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="affiliate_list.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="affiliate_list.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="affiliate_list.php?page='.$y.'" selected="selected">'.$y.'</option>';
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
				<input type="hidden" value="affiliate_list" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="4%">No.</td>
                            <td width="12%">Member Name</td>
                            <td width="40%">URL Register</td>
                            <td width="10%">Status</td>
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
											echo'<td><a href="'.$GLOBALS['SITE_URL'].'affiliate/'.$row['atoken_affiliate'].'" style="color:#111;" target="_blank">'.$GLOBALS['SITE_URL'].'affiliate/'.$row['atoken_affiliate'].'</a></td>';
											echo'<td>';
												if($row['astatus']==0):
													echo'<strong style="color:#FF6633;">InActive</strong>';
												elseif($row['astatus']==1):
													echo'<strong style="color:#00CC33;">Active</strong>';
												else:
													echo'<strong style="color:#FF6633;">InActive</strong>';
												endif;
											echo'</td>';
											echo'<td><a href="eccomerce/update_affiliate_url.php?iddata='.$row['aid'].'" class="nuke-fancied2" style="color:#0066CC;">Update Status</a></td>';
										echo'</tr>';
									$i++;
								endwhile;
							else:
								echo'<tr><td colspan="5"><span class="no-page">Records not found.</span></td></tr>';
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