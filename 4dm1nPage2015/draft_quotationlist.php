<?php include("header.php");

	  include("eccomerce/function.php");	

	  include("function_rnt.php");	 



// config name

$name = "draft_quotationlist";



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

	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl, DATE_FORMAT(`expiry_date`, '%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE 1=1 ".$LabelCari." 

	and ( `id` LIKE '%$order_idkey%' or `nama_penerima` LIKE '%$keyword%' or `address_penerima` LIKE '%$keyword%' ) ORDER BY `date` DESC LIMIT $posisi,$batas") or die($db->error);

elseif($idmember>0):

	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl, DATE_FORMAT(`expiry_date`, '%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE 1=1 and `idmember_header`='$idmember' ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");

else:

	$query = $db->query("SELECT *,DATE_FORMAT(`date`, '%d/%m/%Y') as tgl, DATE_FORMAT(`expiry_date`, '%d/%m/%Y') as expiry_datetgl FROM `draft_quotation_header` WHERE 1=1 ".$LabelCari." ORDER BY `date` DESC LIMIT $posisi,$batas");

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

				<h2><a href="draft_quotationlist.php">Draft Quotation</a></h2>

                <?php if(isset($_GET['msg']) <>'' ): echo'<span class="error_ordernotif">* '.$_GET['msg'].'</span>'; endif;?>

				<form class="search-form" action="draft_quotationlist.php" method="post">

					<input type="text" placeholder="Find" name="keyword" />

					<input type="submit" value="SUBMIT" class="submit-btn" />

				</form>

			</div><!-- .cm-top -->

			<div class="meta-top clearfix">

            	<a href="eccomerce/cari_by_member_quotetation.php" title="" class="add-btn left nuke-fancied2" style="margin-right:20px;">Browse By Member</a>

                <form action="eccomerce/filter_bydatequotetation.php" method="post">

                	<span style="display:inline-block; width:90px; float:left;">Filter by date : 

					<?php if( isset($_SESSION['startdate']) <>'' and isset($_SESSION['enddate']) <>''): echo'<br /><a href="eccomerce/filter_bydatequotetation.php" class="datereset">Reset Date</a>'; endif;?></span> 

                    <input type="text" class="date-pick tngglinput" name="startdate" value="<?php echo $startdate;?>" placeholder="Start Date" />  

                    <input type="text" class="date-pick tngglinput" name="enddate" value="<?php echo $enddate;?>" placeholder="End Date" />

                    <input type="hidden" value="<?php echo $idmember;?>" name="idmember" />

					<input type="submit" value="FIND" class="cari-btntgl" />

                </form>

				<div style="clear:both; height:15px;"></div>

				

                <div class="filter-order">

                      <form action="" method="post" class="meta-form"> 

                        <span style="display:inline-block; width:700px;"></span>

                        Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-draft_quotationlist" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>

						&nbsp; records, Page:&nbsp;

						<label class="cus-select">

							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">

								<?php

									if($keyword!=""):

										$sql2 = $db->query("SELECT `id` FROM `draft_quotation_header` WHERE 1=1 ".$LabelCari." and 

										( `id` LIKE '%$order_idkey%' or `nama_penerima` LIKE '%$keyword%' or `address_penerima` LIKE '%$keyword%' ) ORDER BY `date` DESC");

										

									elseif($idmember>0):

										$sql2 = $db->query("SELECT `id` FROM `draft_quotation_header` WHERE 1=1 and `idmember_header`='$idmember' ".$LabelCari." ORDER BY `date` DESC");

									else:

										$sql2 = $db->query("SELECT `id` FROM `draft_quotation_header` WHERE 1=1 ".$LabelCari." ORDER BY `date` DESC");

									endif;

									$jmldata = $sql2->num_rows;

									$jmlhalaman = ceil($jmldata/$batas); 

									

									for($y=1;$y<=$jmlhalaman;$y++):

										if($y<>$page): 

											if($keyword!=""):

												echo'<option value="draft_quotationlist.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';

											elseif($idmember>0):

												echo'<option value="draft_quotationlist.php?page='.$y.'&idmember='.$idmember.'">'.$y.'</option>';

											else:

												echo'<option value="draft_quotationlist.php?page='.$y.'">'.$y.'</option>';

											endif;

										else :

											if($keyword!=""):

												echo'<option value="draft_quotationlist.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';

											elseif($idmember>0):

												echo'<option value="draft_quotationlist.php?page='.$y.'&idmember='.$idmember.'" selected="selected">'.$y.'</option>';

											else:

												echo'<option value="draft_quotationlist.php?page='.$y.'" selected="selected">'.$y.'</option>';

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

			<div class="cm-mid">

				<form action="#" method="post">

				<input type="hidden" value="<?php echo $baselink ?>" name="urel" id="urelid_page" />

				<input type="hidden" value="draft_quotationlist" name="actpage" />	

				<table cellpadding="0" cellspacing="0" class="general-table">

					<thead>

						<tr>

                        	<td width="3%">No.</td>

                            <td width="8%">Order Date</td>

                            <td width="8%">Expiry Date</td>

                            <td width="10%">Quotation ID</td>

                            <td width="12%">Total Quotation</td>

                            <td width="13%">Member Name</td>

                            <td width="13%">Status</td>	

                            <td width="8%">Action</td> 

						</tr>

					</thead>

                    

					<tbody>

						<?php

							if($query->num_rows>0):

								$i = 0; $totalorder = 0; $statusbayar = 0;

								while($row = $query->fetch_assoc()):

										$pageNo = $i+$posisi+1;

										$totalamount = $row['total_order']+$row['shippingcost'];

							

										echo'<tr id="wrap-media-'.$row['id'].'">';	

											echo'<td>'.$pageNo.'. </td>';

											echo'<td>'.$row['tgl'].'</td>';

											echo'<td><span class="error">'.$row['expiry_datetgl'].'</span></td>';

											echo'<td><strong style="color:#666; font-size:14px;">DQ-'.sprintf('%06d',$row['id']).'</strong></td>';

											echo'<td><strong style="color:#666; font-size:14px;">'.number_format($totalamount).'</strong></td>';

											echo'<td><strong>'.getnamegenal(" `id`='".$row['idmember_header']."' ", "member","name").' '.getnamegenal(" `id`='".$row['idmember_header']."' ", "member","lastname").'</strong></td>';

											echo'<td>';

												if($row['status_order']==1): echo'<em>Sudah di Order</em>'; else: echo'Belum di Order'; endif;

											echo'</td>';

											echo'<td><a href="eccomerce/detail_quuotation_list.php?orderid='.$row['id'].'" class="nuke-fancied2">View Detail</a></td>';

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