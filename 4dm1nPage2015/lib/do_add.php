<?php 
session_start();
require('../../config/nuke_library.php');
require('resize-class.php');

$table_name 		= $_POST['table_name'];
$redirect_url		= $_POST['redirect_url'];
$upload_folder 	= (isset($_POST['upload_folder']) ? $_POST['upload_folder'] : $GLOBALS['SITE_URL'].'uploads' );

$folder_lib_to_upload_folder = str_replace($GLOBALS['SITE_URL'], "../../", $upload_folder);

$arr_dont_save_me = array(
		'submit',
		'table_name',
		'redirect_url',
		'upload_folder',
		'bgcolor'
	);

if($_POST['submit']){
	$array_data = array();
	
	foreach ($_POST as $key => $value) { #loop array post kedalam $array_data
		if(!in_array($key, $arr_dont_save_me)){
      $array_data[$key] = $value;			
		}
		
	}

	if($_FILES){
		$rename_to_prefix = (isset($_POST['name']) ? $_POST['name'] : (isset($_POST['title']) ? $_POST['title'] : ""));

		foreach ($_FILES as $key => $value) {
			if($value['name'] != ''){
				$array_data[$key] = upload_myfile($key,$folder_lib_to_upload_folder,replace_to_dash($rename_to_prefix.$key));
			}
		}
	}
 
  $array_data['modified_datetime'] 	= date('Y-m-d H:i:s');
  $array_data['modified_by'] 				= $_SESSION[$SITE_TOKEN.'userID'];
  $array_data['sortnumber'] 				= get_new_sortnumber($table_name);
  
  $insert = global_insert($table_name, $array_data, false);

	if($insert) {
		flash_success("Data berhasil di input");
	} else {
		flash_error("Data tidak berhasil di input");
	}

	redirect($redirect_url);
}

?>