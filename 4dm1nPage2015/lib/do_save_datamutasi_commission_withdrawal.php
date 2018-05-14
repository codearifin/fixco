<?php 
	require('../../config/connection.php');
	require('../../config/myconfig.php');

	function replaceamount($amount){
		$str = array(",");
		$newtext=str_replace($str,"",strtolower($amount));
		return $newtext;
	}	
	
	function replaceamounttgl($amount){
		$str = array("/");
		$newtext=str_replace($str,"-",$amount);
		return $newtext;
	}

	function getkomisimemberlistTotal($idmember){
		global $db;
		$total = 0;
		$query = $db->query("SELECT * FROM `komisilist_member` WHERE `idmember` = '$idmember' ORDER BY `id` ASC") or die($db->error);
		$jumpage = $query->num_rows;
		if($jumpage>0):
			while($row = $query->fetch_assoc()):	
				if($row['status']==1):
					$total = $total+$row['amount'];
				else:
					$total = $total-$row['amount'];
				endif;
					
			endwhile;	
		endif;		
		
		return $total;		
	}
	
if(isset($_POST['submit'])){
	
	$datenow_time = date(" H:i:s");	
	
	$idmember = $_POST['memberid'];
	$datepost = replaceamounttgl($_POST['datepost']).''.$datenow_time;
	$jumlah_mutasi = replaceamount($_POST['jumlah_mutasi']);
	$description = $_POST['description'];
	
	$total_komisimember = getkomisimemberlistTotal($idmember);
	
	if($total_komisimember>0):
		
		if($total_komisimember>$jumlah_mutasi):
			$jum_mutasilist = $jumlah_mutasi;
		else:
			$jum_mutasilist = $total_komisimember;
		endif;
		
		
		$textdata = 'Mutasi Commission by Fixcomart';
		$query = $db->query("INSERT INTO `komisilist_member` (`idmember`,`date`,`description`,`amount`,`status`,`mutasi_status`) VALUES ('$idmember','$datepost','$textdata','$jum_mutasilist','0','1')");
		$quepp = $db->query("INSERT INTO `claim_komis_member` (`idmember_claim`,`bank_transfer`,`total`,`status_payment`,`date`) VALUES ('$idmember','$description','$jum_mutasilist','Paid','$datepost')");
					
		echo'<script language="JavaScript">';
			echo'window.location="../commission_withdrawal.php?msg=Insert Mutasi successful.";';
		echo'</script>';
			
	else:		
		echo'<script language="JavaScript">';
			echo'window.location="../commission_withdrawal.php?msg=Insert fail, this member dont have commission.";';
		echo'</script>';	
	endif;

}		
?>
