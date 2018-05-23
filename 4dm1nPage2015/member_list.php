<?php include("header.php");
	  include("eccomerce/function.php");	
	  include("function_rnt.php");	 

// config name
$name = "member_list";

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

if(isset($_GET['idmember']) <>'' ):
	$idmember = $_GET['idmember'];
else:
	$idmember = 0;
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

if(isset($_POST['member_type'])){
	$_SESSION['member_type'] = $_POST['member_type'];
	$member_type = $_SESSION['member_type'];
}else{
	if(isset($_SESSION['member_type'])){
		$member_type = $_SESSION['member_type'];
	}else{
		$member_type = "";
	}
}

//main Query
if($keyword!=""):
	if($member_type != ""){
		$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tgl FROM `member` WHERE 1=1
	and ( `name` LIKE '%$keyword%' or `lastname` LIKE '%$keyword%' or `email` LIKE '%$keyword%' ) AND `member_category` LIKE '%$member_type%' ORDER BY `id` DESC LIMIT $posisi,$batas") or die($db->error);
	}else{
		$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tgl FROM `member` WHERE 1=1
	and ( `name` LIKE '%$keyword%' or `lastname` LIKE '%$keyword%' or `email` LIKE '%$keyword%' ) ORDER BY `id` DESC LIMIT $posisi,$batas") or die($db->error);
	}
else:
	if($member_type != ""){
		$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tgl FROM `member` WHERE 1=1 AND `member_category` LIKE '%$member_type%' ORDER BY `id` DESC LIMIT $posisi,$batas");
	}else{
		$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y %H:%i:%s') as tgl FROM `member` WHERE 1=1 ORDER BY `id` DESC LIMIT $posisi,$batas");
	}
	
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
				<h2><a href="member_list.php">Member List</a></h2>
                <?php if(isset($_GET['msg']) <>'' ): echo'<span class="error_ordernotif">* '.$_GET['msg'].'</span>'; endif;?>
				<form class="search-form" action="member_list.php" method="post">
					<input type="text" placeholder="Find" name="keyword" />
					<input type="submit" value="SUBMIT" class="submit-btn" />
				</form>
			</div><!-- .cm-top -->
			<div class="meta-top clearfix">
				<form action="member_list.php" method="post">

                	<span style="display:inline-block; width:130px; float:left;">Filter by member type : </span> 

					<select name="member_type">
						<option value="">All</option>
						<option value="REGULAR MEMBER">REGULAR MEMBER</option>
						<option value="CORPORATE MEMBER">CORPORATE MEMBER</option>
					</select>

					<input type="submit" value="FIND" class="cari-btntgl" />

                </form>
                <br>
				<a href="lib/export-member-list.php" title="" class="add-btn left">Export to Excel</a>
                
                <div class="right paging-meta">
                      <form action="" method="post" class="meta-form"> 
                        <span style="display:inline-block; width:700px;"></span>
                        Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-member_list" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>
						&nbsp; records, Page:&nbsp;
						<label class="cus-select">
							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">
								<?php
									if($keyword!=""):
										if($member_type != ""){
											$sql2 = $db->query("SELECT `id` FROM `member` WHERE 1=1 and 
										( `name` LIKE '%$keyword%' or `lastname` LIKE '%$keyword%' or `email` LIKE '%$keyword%' ) AND `member_category` LIKE '%$member_type%' ORDER BY `id` DESC");
										}else{
											$sql2 = $db->query("SELECT `id` FROM `member` WHERE 1=1 and 
										( `name` LIKE '%$keyword%' or `lastname` LIKE '%$keyword%' or `email` LIKE '%$keyword%' ) ORDER BY `id` DESC");
										}
										
									else:
										if($member_type != ""){
											$sql2 = $db->query("SELECT `id` FROM `member` WHERE 1=1 AND `member_category` LIKE '%$member_type%' ORDER BY `id` DESC");
										}else{
											$sql2 = $db->query("SELECT `id` FROM `member` WHERE 1=1 ORDER BY `id` DESC");	
										}
										
									endif;
									$jmldata = $sql2->num_rows;
									$jmlhalaman = ceil($jmldata/$batas); 
									
									for($y=1;$y<=$jmlhalaman;$y++):
										if($y<>$page): 
											if($keyword!=""):
												echo'<option value="member_list.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';
											else:
												echo'<option value="member_list.php?page='.$y.'">'.$y.'</option>';
											endif;
										else :
											if($keyword!=""):
												echo'<option value="member_list.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';
											else:
												echo'<option value="member_list.php?page='.$y.'" selected="selected">'.$y.'</option>';
											endif;	
										endif;
									endfor;
								?>	
							</select>
                            </label>
                      </form>  
                 </div>
			</div><!-- .meta-top -->
            
            
			<div class="cm-mid">
				<form action="#" method="post">
				<input type="hidden" value="<?php echo $baselink ?>" name="urel" id="urelid_page" />
				<input type="hidden" value="member_list" name="actpage" />	
				<table cellpadding="0" cellspacing="0" class="general-table">
					<thead>
						<tr>
                        	<td width="3%">No.</td>
                            <td width="12%">Register Date</td>
                            <td width="15%">Member Name</td>
                            <td width="10%">Email</td>
                            <td width="12%">Contact</td>
                            <td width="13%">Type Member</td>	
                            <td width="8%">Status</td>	
                            <td width="8%">Action</td>	
						</tr>
					</thead>
                    
					<tbody>
						<?php
							if($query->num_rows>0):
								$i = 0; $totalorder = 0; $statusbayar = 0;
								while($row = $query->fetch_assoc()):
										$pageNo = $i+$posisi+1;
										
										echo'<tr id="wrap-media-'.$row['id'].'">';	
											echo'<td>'.$pageNo.'. </td>';
											echo'<td>'.$row['tgl'].'<br /><a href="eccomerce/detail_member_list.php?iddata='.$row['id'].'" class="nuke-fancied2" style="color:#009999;">View Detail</a></td>';
											echo'<td><strong>'.$row['name'].' '.$row['lastname'].'</strong>';
												if($row['status']=="InActive" and $row['member_category']=="CORPORATE MEMBER"):
													echo'<br /><a href="eccomerce/do_update_membership_memberlist.php?idmember='.$row['id'].'" style="color:#FF6633;"
													onclick="return confirm(\'Anda yakin untuk mengkonfirmasi member ini sebagai corporate member?\');">Konfirmasi Member!</a>';
												endif;
											
											echo'</td>';
											echo'<td>'.$row['email'].'</td>';
											echo'<td>'.$row['phone'].'<br />'.$row['mobile_phone'].'</td>';
											echo'<td>';
												if($row['member_category']=="CORPORATE MEMBER"):
													$statuslist = 1;
													echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:120px; background:#FF6633; color:#fff;">CORPORATE MEMBER</span>';
												else:
													$statuslist = 0;
													echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:120px; background:#00CC33; color:#fff;">REGULAR MEMBER</span>';
												endif;
											echo'</td>';
											
											echo'<td>';
												if($row['status']=="Active"):
													echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:52px; background:#00CC33; color:#fff;">Active</span>';
												else:
													echo'<span style="font-size:11px; font-weight:600; padding:3px 4px 3px 4px; text-align:center; display:inline-block; width:52px; background:#FF6633; color:#fff;">In Active</span>';
												endif;
											echo'</td>';
											
											echo'<td>';
												echo'<ul class="btn-list">';
													echo'<li><a href="edit-member-list.php?idmember='.$row['id'].'" class="bl-edit" 
															  style="height:16px; width:16px; background-image:url(https://cdn2.iconfinder.com/data/icons/bitsies/128/EditDocument-16.png);" title="Edit">Edit</a></li>';
													
													echo'<li><a href="lib/delete_member.php?idmember='.$row['id'].'&status='.$statuslist.'" class="bl-delete delete_btnall" title="Delete"
													onclick="return confirm(\'Anda yakin akan menghapus member ini dan seluruh data order list dan table yang terhubung dengan member ini?\');">Delete</a></li>';
												echo'</ul><!-- .btn-list -->';
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
	<script>$("select[name='member_type']").val("<?php echo $member_type;?>");</script>
<?php include("footer.php"); ?>