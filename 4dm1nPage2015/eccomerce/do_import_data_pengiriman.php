<?php
@session_start();
require("../../config/connection.php");
require("../../config/myconfig.php");

function insertdatalist($iddata,$idkurir,$idkota,$price,$biayaadm,$minberat){
	global $db;

	date_default_timezone_set('Asia/Jakarta');
	$dateNow = date("Y-m-d H:i:s");
		
	$quipp = $db->query("SELECT `id` FROM `tarif_pengiriman` WHERE ( `id` = '$iddata' ) or ( `kurir_name_id` = '$idkurir' and `nama_kota_id` = '$idkota' )  ") or die($db->error);
	$jumdata2 = $quipp->num_rows;				
	if($jumdata2>0):	

		$qu = $db->query("UPDATE `tarif_pengiriman` SET `rates_price` = '$price' , `adm_price` = '$biayaadm' , `min_weight` = '$minberat' WHERE ( `id` = '$iddata' ) or ( `kurir_name_id` = '$idkurir' and `nama_kota_id` = '$idkota' )  ") or die($db->error);
			
	else:

		$qum4 = $db->query("SELECT MAX(`sortnumber`) AS sortnumberlast FROM `tarif_pengiriman` ");
		$ros = $qum4->fetch_assoc();	
		$lastsort = $ros['sortnumberlast']+1;	
	
		$qu = $db->query("INSERT INTO `tarif_pengiriman` (`kurir_name_id`,`nama_kota_id`,`rates_price`,`adm_price`,`min_weight`,`sortnumber`,`modified_datetime`,`modified_by`)
		VALUES ('$idkurir','$idkota','$price','$biayaadm','$minberat','$lastsort','$dateNow','1')") or die($db->error);
	endif;
}	

if(isset($_POST['submit'])){

	$file1=$_FILES['images']['tmp_name'];
	$file2=$_FILES['images']['name'];
	$ext=substr($file2,strpos($file2,'.'),(strlen($file2)-strpos($file2,'.')));
	
	if($ext=='.csv'){					
			$row = 1;
			if (($handle = fopen($file1, "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 500000, ",")) !== FALSE) {
						
						$iddata = $data[0];
						$idkurir = $data[1];
						$idkota = $data[3];
						$price = $data[5];
						$biayaadm = $data[6];
						$minberat = $data[7];

						if($iddata > 0):
							insertdatalist($iddata,$idkurir,$idkota,$price,$biayaadm,$minberat);
						endif;
	
	
				}
				fclose($handle);
									
				echo'<script language="JavaScript">';
					echo'window.location="import_tarif_pengiriman.php?msg=Insert/Update Data successful.";';
				echo'</script>';	
	
			}
	}else{
			echo'<script language="JavaScript">';
				echo'window.location="import_tarif_pengiriman.php?msg=Error file, please insert CSV file";';
			echo'</script>';
	}

}
?>
