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

		if(isset($_SESSION['user_token'])): $Usertokenid = $_SESSION['user_token']; else: $Usertokenid = ''; endif;
		$query = $db->query("SELECT `id` FROM `member` WHERE `tokenmember`='$Usertokenid'");
		$res = $query->fetch_assoc();
		$member_id = $res['id']; 
		 		
		$company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
		$npwp = filter_var($_POST['npwp'], FILTER_SANITIZE_STRING);
		
		//images--
		$img=$_FILES['filecompany']['tmp_name'];
		$img_name=$_FILES['filecompany']['name'];
		
		
		$quem = $db->query("SELECT * FROM `member_membership_data` WHERE `idmember`='$member_id' ");
		$jumdata = $quem->num_rows;
		if($jumdata > 0):

			$_SESSION['error_msg']='uploadgagal';
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'upgrade-membership"</script>';
			 
		else:

			 date_default_timezone_set('Asia/Jakarta');
			 $dateNow = date("Y-m-d H:i:s");
			 $nama_gambar=upload_images($img,$img_name);	
			 $querydata = $db->query("INSERT INTO `member_membership_data` (`idmember`,`company_name`,`npwp`,`file_member`,`status`,`date`) VALUES ('$member_id','$company','$npwp','$nama_gambar','0','$dateNow')");
			
			 echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].'membership-payment"</script>';
			 
		endif;
					
}
?>		
	