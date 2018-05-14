<?php
session_start();
require('../../config/nuke_library.php');
include('config/config.php');
//print_exit($_POST);

function up_images($tmp,$img,$tmpt_folder='', $suffix){

		$eL = ($tmpt_folder=='media') ? 4 : 16;

		$imgbaru = $suffix.'-'.substr(hash_hmac('sha256', uniqid(), uniqid(), false), 0, $eL);

		$ext= '.' . pathinfo($img, PATHINFO_EXTENSION);     

		$imgname= ((empty($tmpt_folder)) ? "img/".$imgbaru.$ext : (($tmpt_folder=='web' || 
		$tmpt_folder=='files' || $tmpt_folder=='media') ? $imgbaru.$ext : "img/".$tmpt_folder."/".$imgbaru.$ext ));

		$folder = 
		(
			(empty($tmpt_folder)) ? 
				"../../img/".$imgbaru.$ext : 
				(
					($tmpt_folder=='web') ? 
						"../../".$imgbaru.$ext : 
						(
							($tmpt_folder=='files' || $tmpt_folder=='media') ? 
							"../../".$tmpt_folder."/".$imgbaru.$ext : 
							"../../img/".$tmpt_folder."/".$imgbaru.$ext 
						)
				)
		);

		if($img==''){$nama_gambar='';}else{$nama_gambar=$imgname;}
		move_uploaded_file($tmp,$folder);

    return $nama_gambar;		
}  
 
 

 $section 		= $_POST['section'];
 $table_name 	= $_POST['table_name'];
 if($section=='revo-content' AND isset($_POST['sid'])){
	
	  $rid 				= (int) $_POST['rid'];
	  $sid 				= $_POST['sid'];
	  $caption 		= $_POST['caption'];
	  $xpos 			= $_POST['xpos'];
	  $ypos 			= $_POST['ypos'];
	  $speed 			= $_POST['speed'];
	  $delay 			= $_POST['delay'];
	  $ease 			= $_POST['ease'];
	  $layer 			= $_POST['layer'];
	  

	  $c = count($sid);
	  $success = 0;
	  for($i=0;$i<$c;$i++){
	    $db->query("
	    	UPDATE 
	    		big_revoslide 
	    	SET 
	    		class='".$caption[$i]."', 
	    		xpos='".$xpos[$i]."', 
	    		ypos='".$ypos[$i]."', 
	    		speed='".$speed[$i]."', 
	    		delay='".$delay[$i]."', 
					easing='".$ease[$i]."', 
					layer='".$layer[$i]."' 
				WHERE 
					id='".$sid[$i]."' AND 
					table_name='".$table_name."'
			");
			$success += 1;
	  }

	  if($success>0){
	    flash_success("Detail Revoslide berhasil di update");
	    redirect("revoslide.php?id=".$_POST['rid']."&table_name=".$table_name);
	  }else{
	  	flash_error("Detail Revoslide gagal di update");
	  	redirect("revoslide.php?id=".$_POST['rid']."&table_name=".$table_name);
	  }
	  
}
flash_success("Detail Revoslide berhasil di update");
redirect("revoslide.php?id=".$_POST['rid']."&table_name=".$table_name);
?>