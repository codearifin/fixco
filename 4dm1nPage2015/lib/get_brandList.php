<?php 
@session_start();
require('../../config/connection.php');
$idcate = $_POST['idcate'];
?>
<option value="">Select Brand</option>
<?php
 $quepp = $db->query("SELECT * FROM `brand` WHERE `idcate_product` = '$idcate' ORDER BY `id` ASC");
  while($rescate = $quepp->fetch_assoc()):
		echo'<option value="'.$rescate['id'].'">'.$rescate['name'].'</option>'; 
  endwhile;	
?>

