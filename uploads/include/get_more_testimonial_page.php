<?php 
	include('../config/connection.php');
		
	$lastnomersort = $_POST['lastnomersort'];
	$query = $db->query("SELECT `sortnumber` FROM `testimonial` WHERE `publish`=1 and `sortnumber` > '$lastnomersort' ORDER BY `sortnumber` ASC LIMIT 0,6") or die($db->error);
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$sortnumber = 0;
		while($row = $query->fetch_assoc()):
			$sortnumber = $row['sortnumber'];	
		endwhile;
			echo $sortnumber;
	else:
		echo $lastnomersort;		
	endif;
?>