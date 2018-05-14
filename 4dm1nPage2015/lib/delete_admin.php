<?php
require("../../config/nuke_library.php");

$idpage = $_POST['idpage'];
if($idpage<>1):
	$query = $db->query("DELETE FROM `1001ti14_vi3w2014` WHERE `id`='$idpage' ");
endif;

if($query):
	echo 1;
else:
	echo 0;
endif;
?>