<?php include("header.php"); 

# view Config #

# UPDATE 13 AUG 2015

# BY HH --- 05:23 AM

// ========================================================================================================//

$keyword        = '';									# Keyword Saat search

$sql_keyword 	  = '';									# Deklarasi Variable Query Tambahan untuk Search

$parent_keyword = '';                 # Deklarasi Variable Query Tambahan untuk Sub Level / Parent Child

$error_message  = '';									# Deklarasi Value Kosong untuk Notif Error Mesage





if(isset($_GET['menu'])): $labelmenu = $_GET['menu']; else: $labelmenu = ""; endif;

if(isset($_GET['submenu'])): $labelsubmenu = $_GET['submenu']; else: $labelsubmenu = ""; endif;

if( $labelmenu == "product" and ( $labelsubmenu == "product" or $labelsubmenu == "" ) ):

	//update RNT

	$sort           = 'ORDER BY sortnumber DESC';  # sort paramaters..

else:

	$sort           = 'ORDER BY '.$order_by[0].' '.$order_by[1];  # sort paramaters..

endif;


if(($labelmenu=="product" and $labelsubmenu=="") or ($labelmenu=="product" and $labelsubmenu=="product")):
	autokeywordproduct();
endif;

$reff_field     = array();



$redirect_url = $_SERVER['REQUEST_URI'];

// ========================================================================================================//



if(isset($_SESSION['page-'.$urlmenu.'']) AND $_SESSION['page-'.$urlmenu.'']>0){$batas = $_SESSION['page-'.$urlmenu.'']; }else{ $batas = 10;}

if(isset($_GET['page'])){$i = 0; $page = $_GET['page'];}

if(empty($page)){ $posisi=0; $page=1;	} else{ $posisi= (($page-1) * $batas ); }

if(isset($_GET['msg']) AND $_GET['msg'] != ''){	$error_message = $_GET['msg']; }



// change stat by click

if(isset($_GET['changestat']) and isset($_GET['id'])){

	$query = $db->query("UPDATE `$table_name` SET `publish`='$_GET[changestat]' WHERE `id`='$_GET[id]'");

  flash_success("Anda sudah berhasil mengupdate Publish Status untuk ID : ".$_GET['id']);

  redirect($_SERVER["HTTP_REFERER"]);

}



//featured

if(isset($_GET['featured']) and isset($_GET['id'])){

	$query = $db->query("UPDATE `$table_name` SET `is_featured`='$_GET[featured]' WHERE `id`='$_GET[id]'");

  flash_success("Anda sudah berhasil mengupdate Featured Status untuk ID : ".$_GET['id']);

  redirect($_SERVER["HTTP_REFERER"]);

}



// jika ada keyword search

if(isset($_GET['keyword']) AND $_GET['keyword']!=""){

	$keyword     = $_GET['keyword'];

	$sql_keyword = get_sql_keyword($searchable_field,$keyword);

}



// jika ada parent_id (filter by TOP level Category)

if(isset($_GET['parent_id'])){

  if($_GET['parent_id']['field'] != "" AND $_GET['parent_id']['id'] != "")

  $parent_keyword = "AND ".$_GET['parent_id']['field']." = '".$_GET['parent_id']['id']."'";

}



$txt_mainquery = "SELECT * FROM `$table_name` WHERE 1 = 1 $parent_keyword $sql_keyword $sort LIMIT $posisi,$batas";



foreach($reffer_table AS $key => $val){

  array_push($reff_field, $key);

}



$query = $db->query($txt_mainquery);



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

			

      <?php if(count($searchable_field) > 0){  //untuk view yang didefine $searchable_fieldnya aja ?>



          <form class="search-form" action="<?php echo $redirect_url;?>" method="get">

    				<input type="hidden" name="menu" value="<?php echo $urlmenu ?>" />

            <?php if(isset($theSubMenu)) { ?>

            <input type="hidden" name="submenu" value="<?php echo $urlsubmenu ?>" />

            <?php }?>

            <input type="text" placeholder="Find.." name="keyword" value="<?php echo ($keyword != "" ? $keyword : "");?>" />

    				<input type="submit" value="SUBMIT" class="submit-btn" />

    			</form>



	    <?php } ?>



            

    </div><!-- .cm-top -->

		

    <div class="meta-top clearfix">

			

      <?php if($addbutton == 1){ ?>

        <a href="<?php echo $addlink.($parent_keyword != "" ? "&parent_id[field]=".$_GET['parent_id']['field']."&parent_id[id]=".$_GET['parent_id']['id']:"");?>" title="" class="add-btn left">Add New</a>

      <?php } ?> 



      <?php if($deletebutton == 1){ ?>

        <a href="#" title="" class="delete-btn left" id="bulk_deleteid">Bulk Delete</a>

      <?php } ?>



      <?php if($importable == '1'){ ?>
		
        <?php if($table_name=="tarif_pengiriman"):?>
				 
                 <a href="eccomerce/import_tarif_pengiriman.php" target="_blank" title="" class="delete-btn left">Import</a>
                 
		<?php else:?>
        	
            <a href="<?php echo $GLOBALS['ADMIN_URL']."import.php?table_name=".$table_name;?>" target="_blank" title="" class="delete-btn left">Import</a>
		
        <?php endif;?>

      <?php }?>



      <?php if($exportable == '1'){ ?>

        	
            <?php if($table_name=="tarif_pengiriman"):?>
            	
                <a href="eccomerce/export_tarif_pengiriman.php?idkurir=<?php echo $_GET['parent_id']['id'];?>" target="_blank" title="" class="delete-btn left">Export</a>
            
            <?php else:?>
            
            	<a href="<?php echo $GLOBALS['ADMIN_URL']."export.php?table_name=".$table_name;?>" target="_blank" title="" class="delete-btn left">Export</a>

			<?php endif;?>
            
            
      <?php }?>

      

            

			<span class="msg-err"><?php echo $error_message;?></span>

			

        <div class="right paging-meta">

			

          <form action="" method="post" class="meta-form">

  	          Show &nbsp;

              <input type="text" value="<?php echo $batas;?>" class="small jumpage" id="page-<?php echo $urlmenu ?>" onkeyup="jumpage_admin(event.keyCode, this.value, this.id)" /> 

              

              <a href="#" class="loadbtn">&nbsp;</a>

  	

              &nbsp; records, Page : &nbsp;

  	

              <label class="cus-select">

    		          <select class="cus-select" onChange="MM_jumpMenu('parent',this,0)">

    	

                    <?php



                    // sql untuk paging

                    $txt_sql2 = "SELECT `id` FROM `$table_name` WHERE 1 = 1 $parent_keyword $sql_keyword";



                    $sql2        = $db->query($txt_sql2);

                    $jmldata     = $sql2->num_rows;

                    $jmlhalaman  = ceil($jmldata/$batas); 



                    for($y=1;$y<=$jmlhalaman;$y++):

                        if($y<>$page): 

                            echo'<option value="'.$baselink.'&page='.$y.'">'.$y.'</option>';

                        else :

                            echo'<option value="'.$baselink.'&page='.$y.'" selected="selected">'.$y.'</option>';

                        endif;

                    endfor;



                    ?>	

    					    </select>

					   </label>

				  </form>

			</div><!-- .paging-meta -->

		</div><!-- .meta-top -->

		<div class="cm-mid">

			<form action="lib/delete_bulk.php" method="post">

				<input type="hidden" value="<?php echo $redirect_url ?>" name="urel" id="urelid_page" />

				<input type="hidden" value="<?php echo $table_name ?>" name="actpage" />

        <input type="hidden" name="upload_folder" value="<?php echo $upload_folder?>">



				<!-- Start Content -->



				<table cellpadding="5" cellspacing="0" class="general-table <?php if($draggable == '1'){echo 'drag_table';} ?>">

					<thead>

						<tr>

              <?php if($deletebutton == 1){ ?>

							<td width="2%"><input type="checkbox" class="input" name="pilih" onclick="pilihan()" /></td>

              <?php }?>

							<td width="2%">No.</td>

							

                            <?php //show column selected tablee

                                  $str      = 'SHOW COLUMNS FROM '.$table_name; 

                                  $result   = $db->query($str) or die($db->error);

                                  $field_name =  array();



                                     while($row = $result->fetch_array()) {

                                      

                                        //jika ada excluded field yang didefine

                                        if($excluded_view_field != NULL) { 

                                             

                                             //cek field mana yang ada di array excluded_field

                                             if(!in_array($row[0],$excluded_view_field)) { 



                                                //echo field yang tidak diexlude di config

                                                echo '<td width="">'.ucwords(replace_to_space($row[0])).'</td>';



                                                //save to array untuk sebagai key pada saat fetch

                                                array_push($field_name, $row[0]);



                                             }//end of check exluded field

                                        

                                        } else  { //jika tidak ada exluded field

                                                

                                             //echo field yang tidak diexlude di config

                                             echo '<td width="">'.ucwords($row[0]).'</td>';



                                             //save to array untuk sebagai key pada saat fetch

                                             array_push($field_name, $row[0]);

                                        

                                        }

                                     }//end of loop

                                    

                            ?>

                            <?php  if($_GET['menu'] == 'order_header' && !isset($_GET['submenu'])) {  ?> <td>Detail</td> <?php } ?>

                            <td width="8%">Action</td>

						</tr>

					</thead>

					<tbody>

						<?php



            if($query->num_rows>0):

	 

                $i = 0;

	

                while($row = $query->fetch_assoc()):



                    $pageNo = $i+$posisi+1;



    								echo '<tr id="'.$row['id'].'">';

                    if($deletebutton == 1){

    								  echo '<td><input name="to_be_deleted[]" type="checkbox" id="checkbox[]" value="'.$row['id'].'" class="filed_pilih"></td>';

                    }

    								echo '<td>'.$pageNo.'. </td>';

		

                        foreach ($field_name AS $key => $val ){

                            

                            if(array_key_exists($val, $select_input)) { 

                                

                                echo '<td>';

                                

                                

                                    

                                    if($select_input[$val]['clickable']) {

                                        

										if($row[$val] < 1): echo'None'; else:

											echo '

											  <a class="nuke-fancied2" href="view-popup.php?popup='.get_popup_menu($select_input[$val]['get_from_table']).'&id='.$row[$val].'">

												'.global_select_field($select_input[$val]['get_from_table'], $select_input[$val]['show_field'], "`id` = '".$row[$val]."'").'

											  </a>';

										endif;

										

                                            

                                    } else {

                                        

                                        echo global_select_field($select_input[$val]['get_from_table'], $select_input[$val]['show_field'], "`id` = '".$row[$val]."'");

                                    

                                    }

                                

                                

                                echo '</td>';

                          

						  	//add RNT 19 jan 16 25:58:01	

                            } else if(strpos($val, "price") !== false) { 

                                

                                echo '<td>'.number_format($row[$val]).'</td>';
								
						   
						   } else if(strpos($val, "stock") !== false and $labelsubmenu =="voucher_online" ) { 
							
								
								 echo '<td><strong style="font-size:15px;">'.$row[$val].' items</strong><br /><a href="add_more_brand.php?id_voucher='.$row['id'].'" class="nuke-fancied2">[Add Brand]</a></td>';
								  

                            } else if(strpos($val, "image") !== false) { 

                                

                                echo '<td>';

                                

                                if($row[$val] != NULL) { 

                                    list($width, $height) = getimagesize($upload_folder.$row[$val]);

                                    echo '<a href="'.$upload_folder.$row[$val].'" class="nuke-fancied">

                                            <img src = "'.$upload_folder.$row[$val].'" width="'.($width < 80 ? 80 : 80).'" alt = "'.$row[$val].'" />

                                          </a>';

                                } else { echo 'No image'; }

                                

                                echo '</td>';

                            

                            } else if(strpos($val, "file") !== false) { 

                                

                                echo '<td>';

                                

                                if($row[$val]!= NULL) { 

                                    echo '<a href="'.$upload_folder.$row[$val].'" target="_blank">'.

                                            $row[$val]

                                          .'</a>';

                                } else { echo 'No file'; }

                                

                                echo '</td>';

                            

                            } else if(get_field_info($table_name,$val) == '252') { //252 itu text 



                                //Semua type field "text" dipotong 300char aja max.

                                echo '<td>'.substr(strip_tags($row[$val]),0,200).' &hellip;</td>';

                            

                            } else if($val == 'publish'){

                                

                                if($row[$val]=='1'){ 

                                    echo '<td><a href="'.$baselink.'&changestat=0&id='.$row['id'].'">

                                                <div class="publish_ok" title="Make Unpublished?">Published</div>

                                              </a>

                                          </td>';

                                }else{ 

                                    echo '<td><a href="'.$baselink.'&changestat=1&id='.$row['id'].'">

                                                <div class="publish_no" title="Make Published?">Unpublished</div>

                                              </a>

                                          </td>';

                                }           

                                    

                            } else if($val == '1'){

                                

                                if($row[$val]==1){ 

                                    echo '<td><a href="'.$baselink.'&featured=0&id='.$row['id'].'">

                                                <span class="publish_ok" title="Make Unfeatured?">Featured</span>

                                              </a>

                                          </td>';

                                }else{ 

                                    echo '<td><a href="'.$baselink.'&featured=1&id='.$row['id'].'">

                                                <span class="publish_no" title="Make featured?">Not featured</span> 

                                              </a>

                                          </td>';

                                }           

                                 

                                

                            } else if($val == 'status_payment'){

                                

                                if($row[$val]=='confirmed'){ 

                                    

                                  echo '<td><span class="publish_ok">Confirmed</span></td>';

                                    

                                } else if($row[$val]=='pending'){ 

                                

                                  echo '<td><span class="pending">Pending</span></td>';

                                

                                } else {                                                

                                    

                                  echo '<td><span class="cancel">Cancelled by admin</span></td>';

                                

                                }           

                                 



                            } else if($val == 'status_delivery'){

                                

                                if($row[$val]=='shipped') { 

                                    

                                  echo '<td><span class="publish_ok">Shipped</span></td>';

                                    

                                } else{ 

                                

                                  echo '<td><span class="pending">Pending</span></td>';

                                        

                                }



                            } else if(strlen($row[$val]) > 200){

                                

                                echo '<td>'.substr($row[$val], 0,200).' ..</td>';



                            } else { 

                                

                                echo '<td>'.$row[$val].'</td>';

                            }

                            

                            

                        

                        }



                    echo '<td>';

								    echo '<ul class="btn-list">';



                    if($editbutton == 1){

                      echo '<li><a href="'.$editlink.'&id='.$row['id'].'" class="bl-edit" style="height:16px; width:16px; background-image:url(https://cdn2.iconfinder.com/data/icons/bitsies/128/EditDocument-16.png);" title="Edit">Edit</a></li>';

                    }

                    

                    if($deletebutton == 1){                    

                      echo '<li><a href="#" class="bl-delete delete_btnall" title="Delete" id="itemdel-'.$row['id'].'-'.$table_name.'-'.$upload_folder.'">Delete</a></li>';



                    }



                    if(count($arr_more_action) > 0) {

                      echo "<div style='clear:both; height:6px;'></div>

                        <select class='select-logo' title='Sub Level'>

                          <option style='background-image: url(".$ADMIN_URL."images/icon/more-action.png);'>&middot;&middot;&middot;</option>";

                          foreach($arr_more_action AS $more_action) {

                            echo "<option value='".($more_action['url'].'&parent_id[id]='.$row['id'])."' style='background-image: url(".$ADMIN_URL."images/icon/structure.png);'>".$more_action['label']."</option>";

                          }

                          

                      echo 

                        "</select>

                      ";

                    }

								    echo '</ul><!-- .btn-list -->';



							      echo '</td>';



                                



								echo'</tr>';

								$i++;

							endwhile;

						else:

							echo'<tr><td colspan="'.(count($field_name)+3).'"><span class="no-page">Records not found.</span></td></tr>';

						endif;

						?>	 

					</tbody>

				</table>

				<?php if($draggable == '1'){ $jumsortPage = getjumsortPage_asc($posisi,$batas,$table_name,$keyword,$searchable_field,$parent_keyword);} ?>

            	<input type="hidden" id="MaxSortID" value="<?php echo $jumsortPage;?>" />

            	<input type="hidden" id="actpageID" value="<?php echo $table_name ?>" />

				<input type="submit" value="Submit" class="submit_bulk" style="display:none;" />

			</form>

		</div><!-- .cm-mid -->

	</div><!-- .cms-main-content -->

</div><!-- #cms-content -->

		

<?php include("footer.php"); ?>

<script type="text/javascript">$(function(){

  $(".select-logo").change(function(){

    location.href = $(this).val();

  });

  

  // onclick='javascript:window.open(\"".($more_action['url'].'.'&parent_id[id]=''.$row['id'])."\", \"_self\");' 

});</script>