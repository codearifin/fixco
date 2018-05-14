<?php
session_start();
require('../../config/nuke_library.php');
include('config/config.php');
//print_exit($_GET);
	
	$folder_lib_to_upload_folder = "../../../uploads/";
  
  $id 				= $_GET['id'];
  $table_name = $_GET['table_name'];

		$theRevoSlide = global_select_single("big_revoslide", "slide,type,rid", "id='".$id."' AND table_name='".$table_name."'");
		//print_exit($theRevoSlide);
		if($theRevoSlide) {
			if($theRevoSlide['type'] == "1") { // IMAGE
				$fileName = $folder_lib_to_upload_folder.$theRevoSlide['slide'];

				if(file_exists($fileName)) {
					unlink($fileName);
				}
			}
			
			global_delete("big_revoslide", "id=$id AND table_name='".$table_name."'");

		  flash_success("Detail Revoslide berhasil di hapus");
	    redirect("revoslide.php?id=".$theRevoSlide['rid']."&table_name=".$table_name);
		} else {

		  flash_error("Detail Revoslide gagal di hapus");
	    redirect("revoslide.php?id=".$theRevoSlide['rid']."&table_name=".$table_name);

		}
	    
		
?>