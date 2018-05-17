<?php 

@session_start();	

require('../config/connection.php');

require('webconfig-parameters.php');

require('../config/myconfig.php');


function replace_to_dash($text){

		$str = array("’" , "/", " " , ":", "(", ")" , "?" , "%" , "," , "." , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "rsquo;","_");

		$newtext=str_replace($str,"-",strtolower($text));

		return $newtext;

}

function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}	

function upload_myfile($name,$folder,$rename_to,$i){

  // get file name & upload

  $tmpt_folder    = $folder;

  $image          = $_FILES[$name]['tmp_name'][$i];                   // get temp name

  $image_name     = $_FILES[$name]['name'][$i];                       // get name

  $image_baru     = multi_upload($image,$image_name,$tmpt_folder,$rename_to,$i);

  if($image_baru){

      return $image_baru;

  } else{

      return false;

  }

}

function multi_upload($img, $img_, $tmpt_folder, $rename_to,$i){

	$imgbaru = $rename_to."-".date("Y-m-d-His").$i;

	$extG    = get_file_extension($img_);

	$ext     = '.'.$extG;

	$checkextimg = strtolower($extG);


	if(in_array($checkextimg, array("jpg", "jpeg", "png", "gif", "pdf", "xls", "doc", "mov", "ico", "mp4", "ogv", "webm"))):



  	$imgname = $imgbaru.$ext;

  	$dest    = $tmpt_folder.$imgname;



  	if($img_ == '') {

      $nama_gambar = '';

    } else {

      $nama_gambar = $imgname;

    }


    move_uploaded_file($img,$dest);

		return $nama_gambar;

	else:

		$nama_gambar='kosong';

	endif;	

}

if(isset($_POST['submit'])){
	global $db;
	$array_data = Array();
	$folder_lib_to_upload_folder = '../uploads/';

	if($_FILES){

		$rename_to_prefix = "";



		foreach ($_FILES as $key => $value) {

			if($value['name'] != ''){
				for ($i=0; $i < sizeof($value['name']); $i++) {
					$array_data[$i] = upload_myfile($key,$folder_lib_to_upload_folder,replace_to_dash($rename_to_prefix.$key),$i);
				}
			}

		}
	}

	$user_token = $_SESSION['user_token'];
	$query_user = $db->query("SELECT `id`,`email`,`name`,`lastname` FROM `member` WHERE `tokenmember` = '$user_token'") or die($db->error);
	$user = $query_user->fetch_assoc();

	$user_name = $user['name'].' '.$user['lastname'];
	$user_email = $user['email'];

	date_default_timezone_set('Asia/Jakarta');
	$dateNow = date("Y-m-d H:i:s");

	$query_header = $db->query("INSERT INTO `quotation_header` (`creator_name`,`creator_email`,`publish`, `created_date`) VALUES('$user_name', '$user_email', 1, '$dateNow')") or die($db->error);
	$get_header_id = $db->query("SELECT `id` FROM `quotation_header` WHERE `created_date` = '$dateNow' AND `creator_email` = '$user_email'");
	$header_id_arr = $get_header_id->fetch_assoc();
	$header_id = $header_id_arr['id'];

	for ($i=0; $i < sizeof($_POST['nama_produk']); $i++) { 
		$nama_produk = filter_var($_POST['nama_produk'][$i], FILTER_SANITIZE_STRING);
		$jumlah 	 = filter_var($_POST['jumlah'][$i], FILTER_SANITIZE_STRING);
		$id_satuan   = filter_var($_POST['satuan'][$i], FILTER_SANITIZE_STRING);
		$keterangan  = filter_var($_POST['keterangan'][$i], FILTER_SANITIZE_STRING);
		$image 		 = $array_data[$i];
		$query_header = $db->query("INSERT INTO `quotation_detail` (`id_quotation_header`,`nama_produk`,`jumlah`,`id_satuan`,`keterangan`,`image`,`publish`,`modified_datetime`) VALUES('$header_id','$nama_produk','$jumlah',$id_satuan,'$keterangan','$image',1,'$dateNow')") or die($db->error);
	}

	require('quotation_request_email.php');
	$_SESSION['error_msg'] = 'addquotationoke';
	echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'index"</script>';
}

?>