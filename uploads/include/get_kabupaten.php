<?php
@session_start();
require('../config/connection.php');
require('function.php');

$propinsi = $_POST['propinsi'];
echo'<option value="" selected="selected">- Pilih Kabupaten -</option>';
$query = $db->query("SELECT DISTINCT(`kabupaten`) FROM `ongkir` WHERE `provinsi`='$propinsi' ORDER BY `kabupaten` ASC");
while($row = $query->fetch_assoc()):
	echo '<option value="'.$row['kabupaten'].'">'.ucwords($row['kabupaten']).'</option>';
endwhile;		
?>