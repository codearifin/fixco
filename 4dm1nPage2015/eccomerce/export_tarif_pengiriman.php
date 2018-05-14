<?php
	@session_start();
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	
		function replacename($text){
				$str = array("/", "?","%", ",", "!" , "#", "$", "@", "^" ,"&","\"","\\","\r\n", "\n", "\r");
				$newtext=str_replace($str,"",$text);
				return $newtext;
		}

		function getnamekurir($id){
			global $db; 
			$query = $db->query("SELECT `kurir_name` FROM `kurir_list` WHERE `id`='$id'");
			$res = $query->fetch_assoc();
			return replacename($res['kurir_name']);
		}
				
		function getnamekota($id){
			global $db; 
			$query = $db->query("SELECT `nama_kota` FROM `ongkir` WHERE `id`='$id'");
			$res = $query->fetch_assoc();
			return replacename($res['nama_kota']);		
		}		
				
		
		$idkurir = $_GET['idkurir'];
		$output = "";
		
		$output .="ID [dont change],";
		$output .="ID KURIR [dont change],";
		$output .="KURIR NAME [dont change],";
		$output .="ID KOTA [dont change],";
		$output .="KOTA [dont change],";
		$output .="RATES,";
		$output .="BIAYA ADM,";
		$output .="MINIMUM WEIGHT,";
		$output .="\n";
		
				
		$query = $db->query("SELECT * FROM `tarif_pengiriman` WHERE `kurir_name_id` = '$idkurir' ") or die($db->error);
		while($data = $query->fetch_assoc()):

			$output .="".$data['id'].",";
			$output .="".$data['kurir_name_id'].",";
			$output .="".getnamekurir($data['kurir_name_id']).",";
			$output .="".$data['nama_kota_id'].",";
			$output .="".getnamekota($data['nama_kota_id']).",";
			$output .="".$data['rates_price'].",";
			$output .="".$data['adm_price'].",";
			$output .="".$data['min_weight'].",";
			$output .="\n";
						
		endwhile;
		

	// Download the file
	$filename = "rates_biaya_pengiriman.csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $output;
	exit;
?>
