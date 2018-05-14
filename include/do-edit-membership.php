<?php 
@session_start();	
require('../config/connection.php');
require('../config/myconfig.php');

function upload_images($img,$img_name)
	{
		$imgbaru = date("Y-m-d-His");
		$extG = get_file_extension22($img_name);
		$ext = '.'.$extG;
		
		$checkextimg = strtolower($extG);
		if($checkextimg=="php" or $checkextimg=="asp" or $checkextimg=="js" or $checkextimg=="xml" or $checkextimg=="html"):
			$nama_gambar='';
		else:
			
			$imgname = "corporate-npwp-".$imgbaru.$ext;
			$folder = "../uploads/".$imgname;
			if($img_name == ''){ $nama_gambar = ''; } else { $nama_gambar = $imgname; }
			
			move_uploaded_file($img,$folder);
			return $nama_gambar;				
		endif;			
}

function get_file_extension22($file_name) {
   return substr(strrchr($file_name,'.'),1);
}

	
if(isset($_POST['submit'])){
		
		$trype = $_POST['submit'];
		
		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
		$res = $query->fetch_assoc();
		$member_id = $res['id']; 

		$quppp = $db->query("SELECT `file_member` FROM `member_membership_data` WHERE `idmember`='$member_id'");
		$data = $quppp->fetch_assoc();
				 		
		$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
		$npwp = filter_var($_POST['npwp'], FILTER_SANITIZE_STRING);
		
		//images--
		$img = $_FILES['filecompany_edt']['tmp_name'];
		$img_name = $_FILES['filecompany_edt']['name'];
		
		if($img!=""):
			unlink('../uploads/'.$data['file_member']);
			$nama_gambar = upload_images($img,$img_name);	
		else:
			$nama_gambar = $data['file_member'];
		endif;	
		
		$querydata = $db->query("UPDATE `member_membership_data` SET `company_name` = '$company',`npwp` = '$npwp', `file_member` = '$nama_gambar' WHERE `idmember` = '$member_id' ");
		
		if($trype=="SIMPAN"):
			$_SESSION['error_msg']='update-oke';	
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'upgrade-membership"</script>';
		else:
			$_SESSION['error_msg']='update-oke';	
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'membership-payment"</script>';	
		endif;	
			 
					
}
?>		
	