<?php

/// BEGIN : SEMUA TABLE PASTI KENA EXCEPTION INI ///////////////////

    $reffer_table             = array();

    $order_by                 = array('sortnumber','ASC');

    $upload_folder            = $GLOBALS['UPLOAD_FOLDER'];

/// END : SEMUA TABLE PASTI KENA EXCEPTION INI /////////////////



// VALIDASI SESSION

if(!isset($_SESSION[$SITE_TOKEN.'username']) or !isset($_SESSION[$SITE_TOKEN.'userID'])){

  flash_error("Login Unsuccessful, <br />Please check username and password.");

  redirect($GLOBALS['ADMIN_LOGIN']);

}

if($_SESSION[$SITE_TOKEN.'username']=="" or $_SESSION[$SITE_TOKEN.'userID']==""){

  flash_error("Login Unsuccessful, <br />Please check username and password.");

  redirect($GLOBALS['ADMIN_LOGIN']);

}



if(isset($_GET['menu']) AND isset($_GET['submenu'])) {

  $urlmenu      = $_GET['menu'];

  $urlsubmenu   = $_GET['submenu'];

  $theMenu      = global_select_single("m3nu_4dm1n", "*", "`url` = '".$urlmenu."' AND `top_parent_id` = '0' AND `top_or_left` = 'top'");

  $theSubMenu   = global_select_single("m3nu_4dm1n", "*", "`url` = '".$urlsubmenu."' AND `top_parent_id` = '".$theMenu['id']."'");

  $table_name   = table_prefix_add($theSubMenu['table_name_no_prefix']);

  $title_h2     = replace_to_space($theMenu['name']).' &raquo '.replace_to_space($theSubMenu['name']);  

  $paramlink    = '?menu='.$urlmenu.'&submenu='.$urlsubmenu;  

  $hak_akses    = $urlmenu.'+'.$urlsubmenu;

  $baselink = $GLOBALS['ADMIN_URL'].'view.php'.$paramlink;

  $addlink  = $GLOBALS['ADMIN_URL'].'add-form.php'.$paramlink;

  $editlink = $GLOBALS['ADMIN_URL'].'edit-form.php'.$paramlink;



} else if(isset($_GET['menu'])) {



  $urlmenu      = $_GET['menu'];

  $theMenu      = global_select_single("m3nu_4dm1n", "*", "`url` = '".$urlmenu."' AND `top_parent_id` = '0' AND `top_or_left` IN ('top', 'sub-level')");  

  $table_name   = table_prefix_add($theMenu['table_name_no_prefix']);  

  $title_h2     = replace_to_space($theMenu['name']);

  $paramlink    = '?menu='.$urlmenu;

  $hak_akses    = $urlmenu;

  $baselink = $GLOBALS['ADMIN_URL'].'view.php'.$paramlink;

  $addlink  = $GLOBALS['ADMIN_URL'].'add-form.php'.$paramlink;

  $editlink = $GLOBALS['ADMIN_URL'].'edit-form.php'.$paramlink;

  

} else if(isset($_GET['popup'])) {



  $urlmenu      = $_GET['popup'];

  $theMenu      = global_select_single("m3nu_4dm1n", "*", "`url` = '".$urlmenu."' AND `top_parent_id` = '0' AND `top_or_left` = 'popup'");

  $table_name   = table_prefix_add($theMenu['table_name_no_prefix']);  

  $title_h2     = replace_to_space($theMenu['name']);

  $paramlink    = '?menu='.$urlmenu;

  $hak_akses    = $urlmenu;

  $baselink = $GLOBALS['ADMIN_URL'].'view.php'.$paramlink;

  $addlink  = $GLOBALS['ADMIN_URL'].'add-form.php'.$paramlink;

  $editlink = $GLOBALS['ADMIN_URL'].'edit-form.php'.$paramlink;



} else { // SPECIFIC URL AT TOP OR LEFT MENU



  $thisPage     = curURL();

  $theSubMenu   = global_select_single("m3nu_4dm1n", "*", "(`specific_url` = '".$thisPage."' OR `specific_add` = '".$thisPage."' OR `specific_edit` = '".$thisPage."' OR `specific_delete` = '".$thisPage."') AND `top_parent_id` <> 0");

  if($theSubMenu) {  // SPECIFIC URL AT LEFT MENU

    $urlsubmenu   = $theSubMenu['url'];

      $theMenu      = global_select_single("m3nu_4dm1n", "*", "`id` = '".$theSubMenu['top_parent_id']."' AND `top_parent_id` = '0' AND `top_or_left` = 'top'");

      $urlmenu      = $theMenu['url'];

      $table_name   = table_prefix_add($theSubMenu['table_name_no_prefix']);

      $title_h2     = replace_to_space($theMenu['name']).' &raquo '.replace_to_space($theSubMenu['name']);

      $paramlink    = '?menu='.$urlmenu.'&submenu='.$urlsubmenu;

      $hak_akses    = $urlmenu.'+'.$urlsubmenu;

      $baselink = $GLOBALS['ADMIN_URL'].$theSubMenu['specific_url'];

      $addlink  = $GLOBALS['ADMIN_URL'].$theSubMenu['specific_add'];

      $editlink = $GLOBALS['ADMIN_URL'].$theSubMenu['specific_edit'];



  } else { // SPECIFIC URL AT TOP MENU



    $theMenu = global_select_single("m3nu_4dm1n", "*", "(`specific_url` = '".$thisPage."' OR `specific_add` = '".$thisPage."' OR `specific_edit` = '".$thisPage."' OR `specific_delete` = '".$thisPage."') AND `top_parent_id` = '0' AND `top_or_left` = 'top'");

    if(!$theMenu) { 

      // THIS MENU NEVER REGISTERED IN DATABASE

      echo "Sorry, your menu never been registered before."; exit;

    }

    $urlmenu      = $theMenu['url'];

    $table_name   = table_prefix_add($theMenu['table_name_no_prefix']);   

    $title_h2     = replace_to_space($theMenu['name']);

    $paramlink    = '?menu='.$urlmenu;

    $hak_akses    = $urlmenu;

    $baselink = $GLOBALS['ADMIN_URL'].$theMenu['specific_url'];

    $addlink  = $GLOBALS['ADMIN_URL'].$theMenu['specific_add'];

    $editlink = $GLOBALS['ADMIN_URL'].$theMenu['specific_edit'];

  }

}



if(isset($theSubMenu)) {

  $addbutton                  = $theSubMenu['add_button'];

  $editbutton                 = $theSubMenu['edit_button'];

  $deletebutton               = $theSubMenu['delete_button'];

  $draggable                  = $theSubMenu['draggable'];

  $exportable                 = $theSubMenu['exportable'];

  $importable                 = $theSubMenu['importable'];

  $searchable_field           = explode(",", $theSubMenu['searchable_field']);

  $excluded_view_field        = explode(",", $theSubMenu['excluded_view_field']);

  $excluded_input_field       = explode(",", $theSubMenu['excluded_input_field']);

  $excluded_input_validation  = explode(",", $theSubMenu['excluded_input_validation']);

  $image_size                 = explode(",", $theSubMenu['image_size']);

} else {



  $addbutton                  = $theMenu['add_button'];

  $editbutton                 = $theMenu['edit_button'];

  $deletebutton               = $theMenu['delete_button'];;

  $draggable                  = $theMenu['draggable'];

  $exportable                 = $theMenu['exportable'];

  $importable                 = $theMenu['importable'];

  $searchable_field           = explode(",", $theMenu['searchable_field']);

  $excluded_view_field        = explode(",", $theMenu['excluded_view_field']);

  $excluded_input_field       = explode(",", $theMenu['excluded_input_field']);

  $excluded_input_validation  = explode(",", $theMenu['excluded_input_validation']);

  $image_size                 = explode(",", $theMenu['image_size']);

}



/*

# UPDATED BY HERMAN 24 NOv 2015

if($searchable_field[0] == "" AND $table_name != "") {

  $searchable_field = array();

  $arr_column = select_custom('SHOW COLUMNS FROM '.$table_name);

  foreach ($arr_column['row'] as $v) {

    if(!in_array($v['Field'], array("id", "sortnumber", "publish", "modified_by", "modified_datetime"))) {

      array_push($searchable_field, $v['Field']);

    }

  }

}*/



//update by RNT 27 nov 15  ( $searchable_field )

/*update by herman. gausah kita otak atik & seting search lagi. dimanapun kapanpun sampai selama2nya*/

$str_search      = select_custom('SHOW COLUMNS FROM '.$table_name);

if($str_search<>''):

	foreach ($str_search['row'] as $k => $v) {

	  $field[$k] = $v['Field']; 

	}

	$searchable_field    = $field;               # Array Search By

else:

	$searchable_field = '';

endif;





#Added by Andi 26 Nov 2015 # SUB LEVEL CATEGORY BUTTON

$arr_more_action = array();

if($table_name != "") {

  $arr_sub_level = global_select("0pti0n_s3tt1ng", "*", "get_from_table = '".$table_name."'");

  if($arr_sub_level) { foreach($arr_sub_level AS $sub_level){

    $arr_additional_action = global_select("m3nu_4dm1n", "*", "top_or_left = 'sub-level' AND `table_name_no_prefix` = '".$sub_level['table_name']."'");

    if($arr_additional_action) { foreach ($arr_additional_action AS $additional_action) {

      array_push($arr_more_action, array(

        "label" => $additional_action['name'],

        "url" => $GLOBALS['ADMIN_URL'].'view.php?menu='.$additional_action['url'].'&parent_id[field]='.$sub_level['field_name']

        ));

    }}

  }}

}



#Added by Andi 26 Nov 2015 # SUB LEVEL CATEGORY BREADCRUMB

if(isset($_GET['parent_id'])) {
  if($_GET['parent_id']['field'] != "" AND $_GET['parent_id']['id'] != "") {
    
	$theOptionSetting = global_select_single("0pti0n_s3tt1ng", "*", "field_name = '".$_GET['parent_id']['field']."'");
	if($theOptionSetting) {
      //EDIT RNT 9 may 16
	  $Menu_first = getmenurntlist($theOptionSetting['get_from_table']);
	  if($Menu_first==$theOptionSetting['get_from_table'] or $Menu_first=="#"):
	  	$title_h2 = '<a href="view.php?menu='.$Menu_first.'">'.replace_to_space($theOptionSetting['get_from_table']).'</a> &raquo '.replace_to_space($theMenu['name']);
	  else:
      	$title_h2 = '<a href="view.php?menu='.$Menu_first.'&submenu='.$theOptionSetting['get_from_table'].'">'.replace_to_space($theOptionSetting['get_from_table']).'</a> &raquo '.replace_to_space($theMenu['name']);
	  endif;
	  
    }
	
  }
}



$web_config = global_select_single("web_config", "*");

$select_input       = array();

$arr_option_select  = global_select("0pti0n_s3tt1ng", "*");



if($arr_option_select) { foreach ($arr_option_select as $option) {

  $select_input[$option['field_name']] = array(

      "field_name"      => $option['field_name'], #ini diperlukan saat ambil value dari parent id see function : show_list_add($fname,$cur_select_input)

      "get_from_table"  => $option['get_from_table'],

      "input_type"      => $option['input_type'],

      "show_field"      => $option['show_field'],

      "selected"        => $option['selected'],

      "clickable"       => $option['clickable']

    );

}}

?>