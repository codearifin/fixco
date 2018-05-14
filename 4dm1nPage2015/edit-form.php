<?php include("header.php"); 

$id_record  = $_GET['id'];

$data = global_select_single($table_name, "*", "`id` = '".$id_record."'");

$result           = $db->query('SHOW COLUMNS FROM '.$table_name) or die($db->error);
$field_name       =  array();
$output_field     = '';
$to_validate      = '';
$to_validate_msg  = '';
$ctr_image = -1;

$redirect_url = $_SERVER["HTTP_REFERER"];
if(isset($theSubMenu)) {
  if($theSubMenu['specific_id'] != "0") {
    $redirect_url = $_SERVER['REQUEST_URI'];
  }
} else if($theMenu['specific_id'] != "0") {
  $redirect_url = $_SERVER['REQUEST_URI'];
}

$array_field = array();

foreach($select_input AS $key => $val){
  array_push($array_field,$key);
}

while($column = $result->fetch_array()) {
     
    $fname = $column['Field'];
    $ftype = $column['Type'];
    //print_block($column);
    if($excluded_input_field != NULL) { 

      if(!in_array($fname,$excluded_input_field)) { 
           
          $output_field .= '
          <tr>
          
              <td class="td1">
                '.ucwords(replace_to_space($fname));
                
                if(strpos($fname, "image") !== false AND count($image_size) > 0) {
                  $ctr_image++;
                  if(count($image_size) > 0) {
                    if(isset($image_size[$ctr_image])) {
                      $output_field .=  "<br /><small>Recomended size : ".$image_size[$ctr_image]."</small>";
                    }
                  }
                }

                $output_field .= '
              </td>';

			  //khusus untuk fixco attribute--
			  if($fname=="attribute_id"){ 
				  $nameattribute = global_select_field("maste_attribute_product", "attribute_title", " `id` = '".$data[$fname]."' ");	 
				  $output_field  .= '<td><select name = '.$fname.' >';
					  $output_field .= '<option value="'.$data[$fname].'" selected="selected">'.$nameattribute.'</option>';	
				 $output_field  .= '</select></td>';
			  //end khusus untuk fixco attribute--	 
			            
              } else if($ftype == 'tinyint(1)') {
     
                  $output_field .= '
                  <td>
                    <input type="radio" name="'.$fname.'" value="1" '.($data[$fname] == '1' ? 'checked="checked"':'').' /> Yes &nbsp;&nbsp;
                    <input type="radio" name="'.$fname.'" value="0" '.($data[$fname] == '0' ? 'checked="checked"':'').' /> No
                  </td>';
              
              } else if($ftype == "text") { 
               
                  $output_field .= '<td><textarea class="ckeditor" name="'.$fname.'">'.$data[$fname].'</textarea></td>';
              
              } else if(strpos($ftype,'enum') !== false) {

                  $enumList = explode(",", str_replace("'", "", substr($ftype, 5, (strlen($ftype)-6))));
                  $output_field  .= '<td><select name = '.$fname.' >';
                  
                  foreach($enumList as $value){ 
                      $output_field .= '<option '.is_selected($data[$fname],$value).' value="'.$value.'" >'.ucwords($value).'</option>';
                   }
                   
                  $output_field  .= '</select></td>';
              
              } else if(substr($ftype, 8, (strlen($ftype)-9)) >= 5000 ) {
                  //untu type varchar yang lengthnya llebih dari 5000 char..
                  
                  $output_field .= '<td><textarea name="'.$fname.'">'.$data[$fname].'</textarea></td>';
          
              } else if(strpos($fname,'image') !== false || strpos($fname,'file') !== false) { 
                  $folder_lib_to_upload_folder = str_replace($GLOBALS['SITE_URL'], "../", $upload_folder);
                  $fileName = $folder_lib_to_upload_folder.$data[$fname];

                  $output_field .= '
                    <td>
                    <input type="file" name="'.$fname.'" />';                    
                    
                    if(file_exists($fileName)) {
                      
                      if(strpos($fname,'image') !== false) {
                        list($width, $height) = getimagesize($upload_folder.$data[$fname]);
                        $output_field .=  '
                          <br/><br />
                          <a href="'.$fileName.'" class="nuke-fancied">
                            <img src = "'.$fileName.'" width="'.($width < 120 ? $width : 120).'" alt="'.$fname.'" />
                          </a>
                          <br />
                          <span><a href="'.$upload_folder.$data[$fname].'" class="nuke-fancied">'.$upload_folder.$data[$fname].'</a></span>';

                      } else if(strpos($fname,'file') !== false) {
                        $output_field .= '<br /><span><a href="'.$upload_folder.$data[$fname].'" target="_blank">'.$upload_folder.$data[$fname].'</a></span>';
                      }

                    }

                    $output_field .= '
                  </td>';
                  
              } else if(strpos($fname,'color') !== false) {

                  $output_field .= '
                    <td>
                      <input style="background-color:#fabe50; display:none;" class="wrapper" readonly="readonly" value="" />
                      <input type="hidden" name="'.$fname.'" id="color_set" value="'.$data[$fname].'" />
                      <input type="hidden" value="#fabe50" name="bgcolor" id="color_select" readonly="readonly" style="border:1px solid #666; width:100px; padding:5px; font-size:20px;" />

                      <input id="full" />  
                    </td>
                  ';

              }  else if(strpos($fname, "link") !== false) { 
                  
                  $output_field .= '<td><input type="text" placeholder="http://www." value="'.$data[$fname].'" name="'.$fname.'" /></td>';
                 
              } else if(strpos($fname, "datetime") !== false) {
              
                  $output_field .= '<td><input type="text" class="datetime-pick" name="'.$fname.'" value="'.$data[$fname].'" /></td>';
                  
              } else if(strpos($fname, "time") !== false) {
                  
                  $output_field .= '<td><input type="text" class="time-pick" name="'.$fname.'" value="'.$data[$fname].'" /></td>';
                  
              } else if(strpos($fname,'date') !== false) { 
                  
                  $output_field .= '<td><input type="text" class="date-pick" value="'.$data[$fname].'" name="'.$fname.'" /></td>';
              
              } else if(in_array($fname,$array_field)) {
                  $select_input[$fname]['selected'] = $data[$fname];
                  $output_field .= '<td>'.show_list_add($fname, $select_input[$fname],"EDIT").'</td>';
                  
              } else { 
              
                  $output_field .= '<td><input type="text" value="'.$data[$fname].'" name="'.$fname.'" /></td>';
              }
              
              echo '</tr>';
       
              //save to array untuk variable validate form
              if(!in_array($fname,$excluded_input_validation)) {
                if(strpos($fname,'image') !== false || strpos($fname,'file') !== false) {
                  if($data[$fname] != "") { continue; } // if image or file, but no update for the file, skip the validation
                }
                  $to_validate      .= $fname.':{ required: true },';
                  $to_validate_msg  .= $fname.':{ required: "* This field can\'t be empty." },'; 
              }

      } //end of check exluded field

    } else  {

      $output_field .= '
        <tr>
          <td class="td1">'.ucwords(replace_to_space($fname)).'<br /><small></small></td>
          <td><input type="text" name="'.$fname.'" /></td>
        </tr>';
    }

}

?>
<script>
$(document).ready(function() { // execute when window open
	$("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
	$(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
	
  $('.datetime-pick').datetimepicker({value:'2015/04/15 05:03',step:10});

  $('.time-pick').timepicker({ 'showDuration': true, 'timeFormat': 'H:i' });
  $('.date-pick').datepicker({ 'format': 'yyyy/mm/dd', 'autoclose': true });

	// jquery validate
	$('form#validate').validate({
	  rules:      { <?php echo rtrim($to_validate,', '); ?> },
    messages :  { <?php echo rtrim($to_validate_msg,', ');  ?> }
	});
});
</script>


<div id="cms-content" class="clearfix">
	<?php show_left_menu($theMenu); ?>
	<div class="cms-main-content right">
    <?php check_privileges();?>
	  <div class="cm-top">
      <h2>
      <?php if(isset($theMenu)) { ?>
        <a href="<?php echo $GLOBALS['ADMIN_URL']."view.php?menu=".$theMenu['url'];?>"><?php echo $theMenu['name'];?></a> &raquo; 
      <?php }?>
      <?php if(isset($theSubMenu)) { ?>
        <a href="<?php echo $GLOBALS['ADMIN_URL']."view.php?menu=".$theMenu['url']."&submenu=".$theSubMenu['url'] ?>"><?php echo $theSubMenu['name'];?></a> &raquo; 
			<?php }?>
        
        Edit</h2>
		</div><!-- .cm-top -->
		<div class="cm-mid">

			<form action="lib/do_edit.php" method="post" class="general-form" id="validate" enctype="multipart/form-data">        
				<input type="hidden" name="redirect_url" value="<?php echo $redirect_url;?>"/>
        <input type="hidden" name="table_name" value="<?php echo $table_name?>">
        <input type="hidden" name="upload_folder" value="<?php echo $upload_folder?>">
        
				<input type="hidden" name="id" value="<?php echo $data['id'];?>" />
				
          <?php 
          if(!$data) { echo "Sorry, wrong specific ID"; exit; }

          foreach ($data as $key => $value){
            if (strpos($key,'image') !== false){ ?>
              <input type="hidden" name="<?php echo $key?>" value="<?php echo $value;?>" />
            <?php
            }
          }
          ?>

				<table cellspacing="0" cellpadding="0" class="browse-table">

					<?php echo $output_field; ?>

					<tr>
						<td class="td1"></td>
						<td>
							<div class="btn-area clearfix">
								<input type="submit" value="SAVE" class="submit-btn left" name="submit" />
								<input type="reset" value="RESET" class="delete-btn left" />
							</div><!-- .btn-area -->
						</td>
					</tr>
				</table>
			</form>
		</div><!-- .cm-mid -->
	</div><!-- .cms-main-content -->
</div><!-- #cms-content -->
	
<?php include("footer.php"); ?>