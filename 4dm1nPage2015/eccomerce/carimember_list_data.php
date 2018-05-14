<?php
	@session_start();
	require("../../config/connection.php");
	
	$keyword = $_POST['keyword'];
	
	$que = $db->query("SELECT * FROM `member` WHERE `name` LIKE '%$keyword%' or `lastname` LIKE '%$keyword%' or `mobile_phone` LIKE '%$keyword%' or `email` LIKE '%$keyword%' ORDER BY `name` ASC ");
	$jumpage = $que->num_rows;
	if($jumpage>0):
		echo'Search result: <br />';
		while($row = $que->fetch_assoc()):
			echo'&bull; <a href="ordermember.php?idmember='.$row['id'].'">'.$row['name'].' '.$row['lastname'].' ( '.$row['email'].' )</a><br />';
		endwhile;
	else:
		echo 'Record not found.';
	endif;	
			
?>                    