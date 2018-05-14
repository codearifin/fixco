<?php include("header.php"); 

// config name
$name = "master_privilege";

// start pagging
if(isset($_SESSION['page-'.$name.'']) AND $_SESSION['page-'.$name.'']>0){
	$batas = $_SESSION['page-'.$name.'']; 
}else{
	 $batas = 10;
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
	$query = $db->query("SELECT * FROM `$name` WHERE 1=1 AND `name` LIKE '%$keyword%' and `id`<>1 ORDER BY `id` ASC LIMIT $posisi,$batas");	
else:
	$query = $db->query("SELECT * FROM `$name` WHERE 1=1 ORDER BY `id` ASC LIMIT $posisi,$batas");
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
				<h2><?php echo ucwords($title_h2) ?></h2>
				<form class="search-form" action="privilege-view.php" method="post">
					<input type="text" placeholder="Find user" name="keyword" value="<?php echo ($keyword != "" ? $keyword : "");?>" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
				<a href="privilege-add.php" title="" class="add-btn left">Add New</a>
				<div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-admin" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `id` FROM `$name` WHERE `name` LIKE '%$keyword%' and `id`<>1 ORDER BY `id` ASC");
									else:
										$sql2 = $db->query("SELECT `id` FROM `$name` WHERE `id`<>1 ORDER BY `id` ASC");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="user-view.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="user-view.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="user-view.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="user-view.php?page='.$y.'" selected="selected">'.$y.'</option>';
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
				<input type="hidden" value="master_privilege" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
							<td width="2%">No.</td>
							<td width="20%">Name</td>
							<td width="40%">Privilages</td>
							<td width="4%">Action</td>
						</tr>
					</thead>
					<tbody>
						<?php
							if($query->num_rows>0):
								$i = 0;
								while($row = $query->fetch_assoc()):
									$menu_list = explode(",",$row['menu']);
									
									$pageNo = $i+$posisi+1;
									echo'<tr id="'.$row['id'].'">';
										echo'<td>'.$pageNo.'. </td>';
										echo'<td><strong>'.$row['name'].'</strong></td>';
										echo'<td><strong>';
										foreach ($menu_list as $key => $value) {
											$arr_selected_menu = explode("+", $value);

											if(count($arr_selected_menu) == 2) { // this is submenu
												$theMenu = global_select_single("m3nu_4dm1n", "*", "`url` = '".$arr_selected_menu[0]."'");
												if($theMenu) {
													echo $theMenu['name'];
													$theSubmenu = global_select_single("m3nu_4dm1n", "*", "`url` = '".$arr_selected_menu[1]."' AND `top_parent_id` = '".$theMenu['id']."'");
													if($theSubmenu) {
														echo "&nbsp;&nbsp;&raquo;&raquo;&nbsp;&nbsp;".$theSubmenu['name'].'<br/>';
													}
												}
											} else {
												$theMenu = global_select_single("m3nu_4dm1n", "*", "`url` = '".$arr_selected_menu[0]."'");
												if($theMenu) {
													echo $theMenu['name'].'<br/>';
												}
											}

										}
										echo '</strong></td>';
										echo'<td>';
											echo'<ul class="btn-list">';
												echo'<li><a href="privilege-edit.php?id='.$row['id'].'" class="bl-edit" title="Change Password">Change Password</a></li>';
												echo'<li><a href="#" class="bl-delete delete_btnall" title="Delete" id="itemdel-'.$row['id'].'-'.$name.'">Delete</a></li>';
											echo'</ul><!-- .btn-list -->';
										echo'</td>';
									echo'</tr>';
									$i++;
								endwhile;
							else:
								echo'<tr><td colspan="4"><span class="no-page">Records not found.</span></td></tr>';
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