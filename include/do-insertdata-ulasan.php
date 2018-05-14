<?php
	@session_start();
	require('../config/connection.php');
	if(isset($_POST['submit'])){
	
	 		$urlpage = $_POST['urlpage'];
			$idprod = filter_var($_POST['idprod'], FILTER_SANITIZE_STRING);
			$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$judul = filter_var($_POST['judul'], FILTER_SANITIZE_STRING);
			$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
			$score = $_POST['score'];
			
			//save data member
			date_default_timezone_set('Asia/Jakarta');
			$dateNow = date("Y-m-d H:i:s");
			$dateNow2 = date("Y-m-d");
			
			$quipe = $db->query("SELECT max(`id`) as makidlist FROM `ulasan_product` ");
			$res = $quipe->fetch_assoc();
			$idlistulasan = $res['makidlist']+1;
			
			$query = $db->query("INSERT INTO `ulasan_product` (`id`,`idproductlist`,`name`,`title_ulasan`,`description`,`rating`,`publish`,`date`,`sortnumber`,`modified_datetime`,`modified_by`)  VALUES ('$idlistulasan','$idprod',
			'$name','$judul','$description','$score','0','$dateNow2','$idlistulasan','$dateNow','1')");
						
			
			$_SESSION['error_msg'] = 'success-ulasan';
			
			echo'<script type="text/javascript">window.location="'.$GLOBALS['SITE_URL'].''.$urlpage.'"</script>';		 
	}
				
?>