<?php include("header.php"); 

$table_name = "big_revohead_home";

// ========================================================================================================//
$keyword        = '';									# Keyword Saat search
$sql_keyword 	  = '';									# Deklarasi Variable Query Tambahan untuk Search
$error_message  = '';									# Deklarasi Value Kosong untuk Notif Error Mesage

$sort           = 'ORDER BY '.$order_by[0].' '.$order_by[1];  # sort paramaters..
$reff_field     = array();
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

$txt_mainquery = "SELECT * FROM `$table_name` WHERE 1 = 1 $sql_keyword $sort LIMIT $posisi,$batas";

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
			
      <?php if(!empty($searchable_field)){  //untuk view yang didefine $searchable_fieldnya aja ?>

          <form class="search-form" action="<?php echo $baselink;?>" method="get">
    				<input type="hidden" name="table" value="<?php echo $urlmenu ?>" />
            <input type="text" placeholder="Find.." name="keyword" />
    				<input type="submit" value="SUBMIT" class="submit-btn" />
    			</form>

	    <?php } ?>

            
    </div><!-- .cm-top -->
		
    <div class="meta-top clearfix">
			
      <?php if($addbutton == 1){ ?>
          <a href="<?php echo $addlink;?>" title="" class="add-btn left">Add New</a>
      <?php } ?>

      <?php if($deletebutton == 1){ ?>
          <a href="#" title="" class="delete-btn left" id="bulk_deleteid">Bulk Delete</a>
      <?php } ?>
            
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
                    $txt_sql2 = "SELECT `id` FROM `$table_name` WHERE 1 = 1 $sql_keyword";

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
				<input type="hidden" value="<?php echo $baselink ?>" name="urel" id="urelid_page" />
				<input type="hidden" value="<?php echo $table_name ?>" name="actpage" />
        <input type="hidden" name="upload_folder" value="<?php echo $upload_folder?>">

				<!-- Start Content -->

				<table cellpadding="5" cellspacing="0" class="general-table <?php if($draggable == '1'){echo 'drag_table';} ?>">
					<thead>
						<tr>
              <?php if($deletebutton == 1){?>
							  <td><input type="checkbox" class="input" name="pilih" onclick="pilihan()" /></td>
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
                            <td>Detail</td> 
                            <td>action</td>
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
                                        
                                        echo '
                                          <a class="nuke-fancied2" href="view-popup.php?popup='.get_popup_menu($select_input[$val]['get_from_table']).'&id='.$row[$val].'">
                                            '.global_select_field($select_input[$val]['get_from_table'], $select_input[$val]['show_field'], "`id` = '".$row[$val]."'").'
                                          </a>
                                        ';
                                            
                                    } else {
                                        
                                        echo global_select_field($select_input[$val]['get_from_table'], $select_input[$val]['show_field'], "`id` = '".$row[$val]."'");
                                    
                                    }
                                
                                
                                echo '</td>';
                            
                            } else if(strpos($val, "image") !== false) { 
                                
                                echo '<td>';
                                
                                if($row[$val] != NULL) { 
                                    echo '<a href="'.$upload_folder.$row[$val].'" class="nuke-fancied">
                                            <img src = "'.$upload_folder.$row[$val].'" width="120" alt = "'.$row[$val].'" />
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
                                echo '<td>'.substr(strip_tags($row[$val]),0,350).' &hellip;</td>';
                            
                            } else if($val == 'publish'){
                                
                                if($row[$val]=='1') { 
                                    echo '<td><a href="'.$baselink.'&changestat=0&id='.$row['id'].'">
                                                <span class="publish_ok" title="Make Unpublished?">Published</span>
                                              </a>
                                          </td>';
                                } else { 
                                    echo '<td><a href="'.$baselink.'&changestat=1&id='.$row['id'].'">
                                                <span class="publish_no" title="Make published?">Unpublished</span> 
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
                    echo '
                      <td>
                        <span class="revoslide-popup" tableName="'.$table_name.'" slideid="'.$row['id'].'">Edit Detail</span>
                      </td>';
                    echo '<td>';
								    echo '<ul class="btn-list">';

                    if($editbutton == 1){
                      echo '<li><a href="'.$editlink.'&id='.$row['id'].'" class="bl-edit" title="Edit">Edit</a></li>';
                    }
                    
                    if($deletebutton == 1){
                      echo '<li><a href="#" class="bl-delete delete_btnall" title="Delete" id="itemdel-'.$row['id'].'-'.$table_name.'-'.$upload_folder.'">Delete</a></li>';
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
				<?php if($draggable == '1'){ $jumsortPage = getjumsortPage_asc($posisi,$batas,$table_name,$keyword,$searchable_field);} ?>
            	<input type="hidden" id="MaxSortID" value="<?php echo $jumsortPage;?>" />
            	<input type="hidden" id="actpageID" value="<?php echo $table_name ?>" />
				<input type="submit" value="Submit" class="submit_bulk" style="display:none;" />
			</form>
		</div><!-- .cm-mid -->
	</div><!-- .cms-main-content -->
</div><!-- #cms-content -->
		
<?php include("footer.php"); ?>