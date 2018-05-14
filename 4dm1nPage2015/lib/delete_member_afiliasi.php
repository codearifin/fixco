<?php
require('../../config/connection.php');
$iddata = $_GET['iddata'];
$queppp = $db->query("DELETE FROM `affiliate_memberid` WHERE `id` = '$iddata' ");
	
echo'<script language="JavaScript">';
	echo'window.location="../affiliate_memberlist.php?msg=Delete successful.";';
echo'</script>';
?>