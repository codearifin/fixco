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

$id = $_POST['id'];

if($_POST['submit']){
	foreach ($_POST as $key => $value) {
		if(!in_array($key, $arr_dont_save_me)){
			$array_data[$key] = $value;
		}
	}

	if($_FILES){
		$rename_to_prefix = (isset($_POST['name']) ? $_POST['name'] : (isset($_POST['title']) ? $_POST['title'] : ""));

		foreach ($_FILES as $key => $value) {
			if($value['name'] != ''){

				# REMOVE OLD FILE
						$theRow = global_select_single($table_name, "*", "id = '".$id."'");

						if($theRow[$key]!='') { 
							$fileName = $folder_lib_to_upload_folder.$theRow[$key];
							if(file_exists($fileName)) {
								unlink($fileName);
							}
						}
				# REMOVE OLD FILE
				
				$array_data[$key] = upload_myfile($key,$folder_lib_to_upload_folder,replace_to_dash($rename_to_prefix.$key));
			}
		}
	}
 
  $array_data['modified_datetime'] = date('Y-m-d H:i:s');
  $array_data['modified_by'] = $_SESSION[$SITE_TOKEN.'userID'];
    
	$update = global_update($table_name,$array_data,"`id` = '".$id."'");

	if($update) {
		flash_success("Data berhasil di update");
	} else {
		flash_error("Data tidak berhasil di update");
	}
	
	redirect($redirect_url);
}
?>