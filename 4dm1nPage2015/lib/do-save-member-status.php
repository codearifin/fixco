<?php 
	require('../../config/connection.php');
	require('../../config/myconfig.php');

if(isset($_POST['submit'])){
	$idmember = $_POST['idmember'];
	$status = $_POST['status'];
	$member_category = $_POST['member_category'];
	
	$query = $db->query("UPDATE `member` SET `status` = '$status', `member_category` = '$member_category' WHERE `id` = '$idmember' ");
	
	echo'<script language="JavaScript">';
		echo'window.location="../member_list.php?msg=Update successful.";';
	echo'</script>';
}		
?>
