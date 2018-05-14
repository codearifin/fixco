<?php 
	include('../config/connection.php');
	include('../config/myconfig.php');
		
	$lastnomersort = $_POST['lastnomersort'];
	$query = $db->query("SELECT * FROM `testimonial` WHERE `publish`=1 and `sortnumber` > '$lastnomersort' ORDER BY `sortnumber` ASC LIMIT 0,6") or die($db->error);
	$jumpage = $query->num_rows;
	if($jumpage>0):
		while($row = $query->fetch_assoc()):
			echo'<div class="testi-item">';
            		if($row['profile_image']==''):
						echo'<div class="img-wrap"><img src="'.$UPLOAD_FOLDER.'testimoni.jpg" /></div>';
					else:
						echo'<div class="img-wrap"><img src="'.$UPLOAD_FOLDER.'uploads/'.$row['profile_image'].'" /></div>';
					endif;
              	 	echo'<span class="testimoner">'.$row['name'].'</span>';
              	    echo'<p>"'.$row['testimonial'].'"</p>';
              echo'</div><!-- .testi-item -->';			
		endwhile;	
	endif;
?>
<script src="<?php echo $SITE_URL;?>js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $SITE_URL;?>js/isotope.pkgd.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.testi-grid').isotope({
		// options
		itemSelector: '.testi-item'
	});
});	
</script>					   
