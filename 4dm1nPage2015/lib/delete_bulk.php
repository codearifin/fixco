<?php
require("../../config/nuke_library.php");
$act 						= $_POST['actpage'];
$upload_folder 	= $_POST['upload_folder'];

$folder_lib_to_upload_folder = str_replace($GLOBALS['SITE_URL'], "../../", $upload_folder);

for ($i = 0; $i < count($_POST['to_be_deleted']); $i++){

	$theRow = global_select_single($act, "*", "id = '".$_POST['to_be_deleted'][$i]."'");
	
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
		
		global_delete($act, "id = '".$_POST['to_be_deleted'][$i]."'");
	}
}

flash_success("Data Anda berhasil di delete");
redirect($_POST['urel']);
?>