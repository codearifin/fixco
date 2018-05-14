<?php 
session_start();
require('function_admin.php');
require('../config/nuke_library.php');
require('../config/cmsconfig.php');

$id_record  = $_GET['id'];

$data = global_select_single($table_name, "*", "`id` = '".$id_record."'");

$result           = $db->query('SHOW COLUMNS FROM '.$table_name) or die($db->error);
$field_name       =  array();
$output_field     = '';
$ctr_image = -1;

$array_field = array();

foreach($select_input AS $key => $val){
  array_push($array_field,$key);
}

while($column = $result->fetch_array()) {
     
    $fname = $column['Field'];
    $ftype = $column['Type'];
    //print_block($column);
    if($excluded_view_field != NULL) { 

      if(!in_array($fname,$excluded_view_field)) { 
           
          $output_field .= '
          <tr>
          
              <td class="td1">
                '.ucwords(replace_to_space($fname));

                $output_field .= '
              </td>';
          
              if($ftype == 'tinyint(1)') {
     
                  $output_field .= '
                  <td>
                    '.($data[$fname] == '1' ? 'Yes':'No').'
                  </td>';
              
              } else if($ftype == "text") { 
               
                  $output_field .= '<td>'.$data[$fname].'</td>';
              
              } else if(get_field_info($table_name,$fname) == '254') { //enum valuee

                  $enumList = explode(",", str_replace("'", "", substr($ftype, 5, (strlen($ftype)-6))));
                  $output_field  .= '<td>'.$data[$fname].'</td>';
              
              } else if($ftype == '253' &&  substr($ftype, 8, (strlen($ftype)-9)) > 5000 ) {
                  //untu type varchar yang lengthnya llebih dari 5000 char..
                  
                  $output_field .= '<td>'.$fname.'</td>';
          
              } else if(strpos($fname,'image') !== false || strpos($fname,'file') !== false) { 
                  $folder_lib_to_upload_folder = str_replace($GLOBALS['SITE_URL'], "../", $upload_folder);
                  $fileName = $folder_lib_to_upload_folder.$data[$fname];

                  $output_field .= '
                    <td><img src = "'.$fileName.'" width="150" alt="'.$fname.'" /></td>';
                  
              } else if(strpos($fname,'color') !== false) {

                  $output_field .= '
                    <td><div style="background-color:'.$data[$fname].';width:20px; height:20px; border:1px solid #000; border-radius:4px;"></div></td>
                  ';

              }  else if(strpos($fname, "link") !== false) { 
                  
                  $output_field .= '<td>'.$data[$fname].'</td>';
                 
              } else if(strpos($fname,'date') !== false) { 
                  
                  $output_field .= '<td>'.$data[$fname].'</td>';
              
              } else if(in_array($fname,$array_field)) {
                  $output_field .= '<td>'.global_select_field($select_input[$fname]['get_from_table'], $select_input[$fname]['show_field'], "`id` = '".$data[$fname]."'").'</td>';
                  
              } else { 
              
                  $output_field .= '<td>'.$data[$fname].'</td>';
              }
              
              echo '</tr>';

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
  <style>
  .browse-table td { border:1px solid #ccc; padding:5px; }
  </style>

	<div style="padding:10px; margin:20px; width:500px;">
	  <div class="cm-top">
      <h2><?php echo $theMenu['name'];?></h2>
		</div><!-- .cm-top -->

		<div class="cm-mid">        
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

				<table cellspacing="0" cellpadding="0" border="1" class="browse-table" style="width:100%;">

					<?php echo $output_field; ?>

				</table>
			
		</div><!-- .cm-mid -->
	</div><!-- .cms-main-content -->