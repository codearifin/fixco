<?php
session_start();
require('../../config/nuke_library.php');
include('config/config.php');
//print_exit($_POST);

function up_images($tmp,$img,$tmpt_folder='', $suffix){

		$eL = ($tmpt_folder=='media') ? 4 : 16;

		$imgbaru = $suffix.'-'.substr(hash_hmac('sha256', uniqid(), uniqid(), false), 0, $eL);

		$ext = '.' . pathinfo($img, PATHINFO_EXTENSION);

		$folder_lib_to_upload_folder = "../../uploads/";
		$destname = $folder_lib_to_upload_folder.$imgbaru.$ext;

		move_uploaded_file($tmp,$destname);

    return $imgbaru.$ext;		
}

 $section = $_POST['section'];	
 
 if($section=='revo'){
	  $clause 		= (int) $_POST['clause'];
	  $rid 				= (int) $_POST['rid'];
	  $marker 		= (int) $_POST['marker'];
	  $type_huruf = $_POST['type_huruf'];
	  $table_name = $_POST['table_name'];
	  
	  $rc = $db->query("SELECT MAX(layer) FROM big_revoslide WHERE rid=$rid AND table_name='".$table_name."'");
	  list($layer) = $rc->fetch_row();
	  
	  if($clause==1){
	    $tmp = $_FILES['image']['tmp_name'];
	    $img = $_FILES['image']['name'];
	    $tmpt_folder = 'revo';
	    $out = up_images($tmp,$img,$tmpt_folder, 'rslide');
	  } elseif($clause==2){
	    $out = nl2br($_POST['text']);
	  } elseif($clause==3){
	    $out = nl2br($_POST['button']);
	  } elseif($clause==4){
	    $out = $_POST['button'];
	  }
	  
	  $db->query("INSERT INTO big_revoslide(rid, slide, type, layer, type_text, table_name) VALUES($rid, '$out', $clause, $layer + 1,$type_huruf, '".$table_name."')");
	  if($db->affected_rows>0){
	    $sid = $db->insert_id;
	    $caption = unserialize(CAPTION);
	    $ease = unserialize(EASING);

	    flash_success("Detail Revoslide berhasil di tambahkan");
	    redirect("revoslide.php?id=".$_POST['rid']."&table_name=".$table_name);
	
	  } else {
	  	
	  	flash_error("Detail Revoslide gagal di tambahkan");
	    redirect("revoslide.php?id=".$_POST['rid']."&table_name=".$table_name);
	  }
		
  }
?>