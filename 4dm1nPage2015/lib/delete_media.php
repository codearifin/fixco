<?php
	require('../../config/connection.php');
	$idpage = $_GET['id'];
	//img detail
	$quede = $db->query("SELECT `images` FROM `media` WHERE `id`='$idpage' ");
	$ros = $quede->fetch_assoc();
	if($ros['images']!='') unlink("../../".$ros['images']);	

	$query = $db->query("DELETE FROM `media` WHERE `id`='$idpage' ");

	echo'<script language="JavaScript">';
		echo'window.location="../media.php?msg=Delete successful.";';
	echo'</script>';		
?>