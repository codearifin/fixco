<?php 
	require('../../config/connection.php');
	require('../../config/myconfig.php');

	function upload_images($img,$img_,$tmpt_folder,$extensiImg,$namagambar_img)
	{
		$imgbarutgl = date("is");  
		$imgbaru = $namagambar_img;
		$imgname="uploads/".$tmpt_folder.'_'.$imgbaru.'_'.$imgbarutgl.'.'.$extensiImg;
		$folder="../../uploads/".$tmpt_folder.'_'.$imgbaru.'_'.$imgbarutgl.'.'.$extensiImg;
		if($img_==''){$nama_gambar='';}else{$nama_gambar=$imgname;}
		
		move_uploaded_file($img,$folder);
        return $nama_gambar;		
	}	

	function get_file_extension($file_name) {
		return substr(strrchr($file_name,'.'),1);
	}	

	function replace($text){
			$str = array("’" , "/", " " , ":", "(", ")" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "rsquo;");
			$newtext=str_replace($str,"-",strtolower($text));
			return $newtext;
	}	
	
	function replacehtml($text){
			$str = array("'");
			$newtext=str_replace($str,"&rsquo;",$text);
			return $newtext;
	}

if(isset($_POST['submit'])){
	$name = replacehtml($_POST['name']);
	
	$img = $_FILES['images']['tmp_name'];
	$img_name = $_FILES['images']['name'];
	$tmpt_folder = "media"; 

	$ekt_img = get_file_extension($img_name);
	$getextImg = strtolower($ekt_img);
	$namagambar1 = replace($name);
	$imgbaru = upload_images($img,$img_name,$tmpt_folder,$getextImg,$namagambar1);	
	$url = $WEBSITE_NAME.'/'.$imgbaru;
				
	$query = $db->query("INSERT INTO `media` (`name`,`images`,`urel`) VALUES ('$name','$imgbaru','$url') ");
	
	echo'<script language="JavaScript">';
		echo'window.location="../media.php?msg=Insert successful.";';
	echo'</script>';
}		
?>
