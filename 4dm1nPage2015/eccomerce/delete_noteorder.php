<?php
require("../../config/connection.php");
$idpage = $_POST['idpage'];
$query = $db->query("DELETE FROM `status_detailorder` WHERE `id`='$idpage' ");
if($query):
	echo 1;
else:
	echo 0;
endif;
?>