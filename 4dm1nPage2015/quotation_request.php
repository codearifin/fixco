<?php include("header.php");

	  include("eccomerce/function.php");	

	  include("function_rnt.php");	 



// config name

$name = "quotation_request";



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

	$LabelCari = " and DATE_FORMAT(`created_date`,'%Y-%m-%d') BETWEEN '$tglcari_start' and '$tglcari_end' ";

	

else:

	$startdate = '';  $enddate = '';

	$LabelCari = '';

	

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

	$query = $db->query("SELECT * FROM `quotation_header` WHERE 1=1 ".$LabelCari." 

	and (`creator_name` LIKE '%$keyword%' or `creator_email` LIKE '%$keyword%' ) ORDER BY `created_date` DESC LIMIT $posisi,$batas") or die($db->error);

else:

	$query = $db->query("SELECT * FROM `quotation_header` WHERE 1=1 ".$LabelCari." ORDER BY `created_date` DESC LIMIT $posisi,$batas");

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

				<h2><a href="quotation_request.php">Quotation Request</a></h2>

                <?php if(isset($_GET['msg']) <>'' ): echo'<span class="error_ordernotif">* '.$_GET['msg'].'</span>'; endif;?>

				<form class="search-form" action="quotation_request.php" method="post">

					<input type="text" placeholder="Find" name="keyword" />

					<input type="submit" value="SUBMIT" class="submit-btn" />

				</form>

			</div><!-- .cm-top -->

			<div class="meta-top clearfix">

                <form action="eccomerce/filter_bydatequotationrequest.php" method="post">

                	<span style="display:inline-block; width:90px; float:left;">Filter by date : 

					<?php if( isset($_SESSION['startdate']) <>'' and isset($_SESSION['enddate']) <>''): echo'<br /><a href="eccomerce/filter_bydatequotationrequest.php" class="datereset">Reset Date</a>'; endif;?></span> 

                    <input type="text" class="date-pick tngglinput" name="startdate" value="<?php echo $startdate;?>" placeholder="Start Date" />  

                    <input type="text" class="date-pick tngglinput" name="enddate" value="<?php echo $enddate;?>" placeholder="End Date" />

					<input type="submit" value="FIND" class="cari-btntgl" />

                </form>

				<div style="clear:both; height:15px;"></div>

				

                <div class="filter-order">

                      <form action="" method="post" class="meta-form"> 

                        <span style="display:inline-block; width:700px;"></span>

                        Show &nbsp;<input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-quotation_header" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> <a href="#" class="loadbtn">&nbsp;</a>

						&nbsp; records, Page:&nbsp;

						<label class="cus-select">

							<select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">

								<?php

									if($keyword!=""):

										$sql2 = $db->query("SELECT `id` FROM `quotation_header` WHERE 1=1 ".$LabelCari." and 

										(`creator_name` LIKE '%$keyword%' or `creator_email` LIKE '%$keyword%' ) ORDER BY `created_date` DESC");

									else:

										$sql2 = $db->query("SELECT `id` FROM `quotation_header` WHERE 1=1 ".$LabelCari." ORDER BY `created_date` DESC");

									endif;

									$jmldata = $sql2->num_rows;

									$jmlhalaman = ceil($jmldata/$batas); 

									

									for($y=1;$y<=$jmlhalaman;$y++):

										if($y<>$page): 

											if($keyword!=""):

												echo'<option value="quotation_request.php?page='.$y.'&keyword='.$keyword.'">'.$y.'</option>';

											else:

												echo'<option value="quotation_request.php?page='.$y.'">'.$y.'</option>';

											endif;

										else :

											if($keyword!=""):

												echo'<option value="quotation_request.php?page='.$y.'&keyword='.$keyword.'" selected="selected">'.$y.'</option>';

											else:

												echo'<option value="quotation_request.php?page='.$y.'" selected="selected">'.$y.'</option>';

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

				<input type="hidden" value="quotation_request" name="actpage" />	

				<table cellpadding="0" cellspacing="0" class="general-table">

					<thead>

						<tr>

                        	<td width="3%">No.</td>

                            <td width="10%">Creator Name</td>

                            <td width="10%">Creator Email</td>

                            <td width="8%">Created Date</td>

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

											echo'<td>'.$row['creator_name'].'</td>';

											echo'<td>'.$row['creator_email'].'</td>';

											echo'<td>'.$row['created_date'].'</td>';

											echo'<td><a href="eccomerce/detail_quotation_request.php?idheader='.$row['id'].'" class="nuke-fancied2">View Detail</a></td>';

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