<?php
require('../../config/nuke_library.php');

$idpage 	= $_POST['idpage'];
$actpage 	= $_POST['actpage'];
$upload_folder 	= $_POST['upload_folder'];

$folder_lib_to_upload_folder = str_replace($GLOBALS['SITE_URL'], "../../", $upload_folder);

if($actpage != '' AND $idpage != ''){

	$theRow = global_select_single($actpage, "*", "id = '".$_POST['idpage']."'");

	if($theRow) {
		foreach($theRow AS $key => $val) {
			if(strpos($key,'image') !== false) {
				if($theRow[$key]!='') { 
					$fileName = $folder_lib_to_upload_folder.$theRow[$key];
					if(file_exists($fileName)) {
						unlink($fileName);
					}
				}
			}
		}

		foreach($theRow AS $key => $val) {
			if(strpos($key,'file') !== false) {
				if($theRow[$key]!='') { 
					$fileName = $folder_lib_to_upload_folder.$theRow[$key];
					if(file_exists($fileName)) {
						unlink($fileName);
					}
				}
			}
		}

		$query = global_delete($actpage,"`id` = '".$idpage."'",$actpage);
	}
}

if($query):
	echo 1;
else:
	echo 0;
endif;
?>