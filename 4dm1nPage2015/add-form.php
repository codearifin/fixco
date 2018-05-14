<?php include("header.php"); 



$result           = $db->query('SHOW COLUMNS FROM '.$table_name) or die($db->error);

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



$array_field      = array();



foreach($select_input AS $key => $val){

  array_push($array_field,$key);

}



while($column = $result->fetch_array()) {

    $fname = $column['Field'];

    $ftype = $column['Type'];

    //print_block($column);

    if($excluded_input_field != NULL) { 



      if(!in_array($fname,$excluded_input_field)) { 

           

          $output_field .= '<tr>

          

          <td class="td1">

                '.ucwords(replace_to_space($fname));

                

                if(strpos($fname, "image") !== false AND count($image_size) > 0) {

                  $ctr_image++;

                  if(count($image_size) > 0) {

                    if(isset($image_size[$ctr_image]) AND $image_size[$ctr_image] != "") {

                      $output_field .=  "<br /><small>Recomended size : ".$image_size[$ctr_image]."</small>";

                    }

                  }

                }



                $output_field .= '

              </td>';

 

          //khusus untuk fixco attribute--

		  if($fname=="attribute_id"){ 

		  	  

			  $idprod = $_GET['parent_id']['id'];

			  

			  $idkat = global_select_field("product", "idkat", " `id` = '".$idprod."' ");	

			  $idsubkat = global_select_field("product", "idsubkat", " `id` = '".$idprod."' ");	

			  $idsublevel = global_select_field("product", "idsublevel", " `id` = '".$idprod."' ");	

			  

			  if($idsublevel>0 and $idsubkat>0 and $idkat>0):

			  	 

				 $listAttribute    = global_select("maste_attribute_product", "*", " `idcategory` = '$idkat' and `idsubcategory` = '$idsubkat' and `idsubcate_level` = '$idsublevel' ", "`sortnumber`");

				 

			  elseif($idsublevel < 1 and $idsubkat>0 and $idkat>0):

			  	

				 $listAttribute    = global_select("maste_attribute_product", "*", " `idcategory` = '$idkat' and `idsubcategory` = '$idsubkat' ", "`sortnumber`");

			  

			  elseif($idsublevel < 1 and $idsubkat < 1 and $idkat>0):

			  	 

				 $listAttribute    = global_select("maste_attribute_product", "*", " `idcategory` = '$idkat' ", "`sortnumber`");

				

			  endif;	 

			  

              

			  $output_field  .= '<td><select name = '.$fname.' >';

			  	  $output_field .= '<option value="" selected="selected">Select Attribute</option>';		

				  if($listAttribute) { 

					foreach($listAttribute AS $rowdata) {

						 $output_field .= '<option value="'.$rowdata['id'].'" >'.ucwords($rowdata['attribute_title']).'</option>';	

					}

				  } 

			   

             $output_field  .= '</select></td>';

		  //end khusus untuk fixco attribute--	  

			

			           

          }else if($ftype == 'tinyint(1)') { // Yes / No

           

              $output_field .= '

              <td>

                <input type="radio" name="'.$fname.'" value="1" checked="checked" /> Yes &nbsp;&nbsp;

                <input type="radio" name="'.$fname.'" value="0" /> No

              </td>';

          

          } else if($ftype == "text") { 

           

              $output_field .= '<td><textarea class="ckeditor" name="'.$fname.'"></textarea></td>';

          

          } else if(strpos($ftype,'enum') !== false) {

              

              $enumList = explode(",", str_replace("'", "", substr($ftype, 5, (strlen($ftype)-6))));

              $output_field  .= '<td><select name = '.$fname.' >';

              

              foreach($enumList as $value){ 

                  $output_field .= '<option value="'.$value.'" >'.ucwords($value).'</option>';

               }

               

              $output_field  .= '</select></td>';

          

          } else if(substr($ftype, 8, (strlen($ftype)-9)) >= 5000 ) {

              //untu type varchar yang lengthnya llebih dari 5000 char..

              

              $output_field .= '<td><textarea name="'.$fname.'"></textarea></td>';

          

          } else if(strpos($fname,'image') !== false || strpos($fname,'file') !== false) { 

              

              $output_field .= '<td><input type="file" name="'.$fname.'" /></td>';

              

          } else if(strpos($fname,'color') !== false) {



              $output_field .= '

                <td>

                  <input style="background-color:#fabe50; display:none;" class="wrapper" readonly="readonly" value="" />

                  <input type="hidden" name="'.$fname.'" id="color_set" value="#fabe50" />

                  <input type="hidden" value="#fabe50" name="bgcolor" id="color_select" readonly="readonly" style="border:1px solid #666; width:100px; padding:5px; font-size:20px;" />



                  <input id="full" />  

                </td>

              ';



          } else if(strpos($fname, "link") !== false) { 

              

              $output_field .= '<td><input type="text" placeholder="http://www." name="'.$fname.'" /></td>';

              

          } else if(strpos($fname, "datetime") !== false) {

              

              $output_field .= '<td><input type="text" class="datetime-pick" name="'.$fname.'" /></td>';

              

          } else if(strpos($fname, "time") !== false) {

              

              $output_field .= '<td><input type="text" class="time-pick" name="'.$fname.'" /></td>';

              

          } else if(strpos($fname, "date") !== false) { 

              

              $output_field .= '<td><input type="text" class="date-pick" name="'.$fname.'" /></td>';

              

          } else if(in_array($fname,$array_field)) {

              

              $output_field .= '<td>'.show_list_add($fname, $select_input[$fname],"ADD").'</td>';

          

          } else { 

          

              $output_field .= '<td><input type="text" name="'.$fname.'" /></td>';

          }

          

          echo '</tr>';

   

          //save to array untuk variable validate form

          if(!in_array($fname,$excluded_input_validation)) {

              $to_validate      .= $fname.':{ required: true },';

              $to_validate_msg  .= $fname.':{ required: "* This field can\'t be empty." },'; 

          }



       } //end of check exluded field



    } else { //jika tidak ada exluded field



      //echo field yang tidak diexclude di config

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

    rules: { <?php echo rtrim($to_validate,', '); ?> },    

    messages : { <?php echo rtrim($to_validate_msg,', ');  ?> }

    });

});

</script>

<style>input.wrapper{width:50px; border:none; height:25px; width:40px; }</style> 

	

<div id="cms-content" class="clearfix">

	<?php show_left_menu($theMenu); ?>

	<div class="cms-main-content right">

		<div class="cm-top">

			<h2><a href="<?php echo $redirect_url?>"><?php echo $title_h2;?></a> &raquo; Add New</h2>

		</div><!-- .cm-top -->

		<div class="cm-mid">         

			<form action="lib/do_add.php" method="post" class="general-form" id="validate" enctype="multipart/form-data">

        <input type="hidden" name="redirect_url" value="<?php echo $redirect_url?>">

        <input type="hidden" name="table_name" value="<?php echo $table_name?>">

        <input type="hidden" name="upload_folder" value="<?php echo $upload_folder?>">

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