<?php 
	require('../../config/connection.php');
	require('../../config/myconfig.php');

if(isset($_POST['submit'])){
	$iddata = $_POST['iddata'];
	$komisi_persen = $_POST['komisi_persen'];
	
	$query = $db->query("UPDATE `affiliate_memberid` SET `komisi_persen` = '$komisi_persen' WHERE `id` = '$iddata' ");
	
	echo'<script language="JavaScript">';
		echo'window.location="../affiliate_memberlist.php?msg=Update successful.";';
	echo'</script>';
}		
?>
