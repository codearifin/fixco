<?php include("header.php"); 

// config name
$name = "media";

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
	$query = $db->query("SELECT * FROM `media` WHERE `name` LIKE '%$keyword%' ORDER BY `id` DECS LIMIT $posisi,$batas");	
else:
	$query = $db->query("SELECT * FROM `media` ORDER BY `id` DESC LIMIT $posisi,$batas");
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
				<h2>Media</h2>
				<form class="search-form" action="media.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
				<a href="media-add.php" title="" class="add-btn left">Add New</a>
				<div class="right paging-meta">
					<form action="" method="post" class="meta-form">
						 Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-media" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										$sql2 = $db->query("SELECT `id` FROM `media` WHERE `name` LIKE '%$keyword%' ORDER BY `id` DESC");
									else:
										$sql2 = $db->query("SELECT `id` FROM `media` ORDER BY `id` DESC");
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="media.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="media.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="media.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="media.php?page='.$y.'" selected="selected">'.$y.'</option>';
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
				<input type="hidden" value="media" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="4%">No.</td>
                            <td width="15%">Title</td>
                            <td width="15%">File</td>
                            <td>Copy URL</td>
                            <td>Action</td>
						</tr>
					</thead>
					<tbody>
						<?php
							if($query->num_rows>0):
								$i = 0;
								while($row = $query->fetch_assoc()):
										$pageNo = $i+$posisi+1;
										echo'<tr id="wrap-media-'.$row['id'].'">';
											echo'<td>'.$pageNo.'. </td>';
											echo'<td><strong>'.$row['name'].'</strong></td>';
											echo'<td><a href="../'.$row['images'].'" target="_blank">View File!</a></td>';
											echo'<td><textarea style="width:600px; height:20px; padding:5px;">'.$row['urel'].'</textarea></td>';
											echo'<td>';
												echo'<ul class="btn-list">';
													echo'<li><a href="lib/delete_media.php?id='.$row['id'].'" class="bl-delete" title="Delete">Delete</a></li>';
												echo'</ul><!-- .btn-list -->';
											echo'</td>';
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