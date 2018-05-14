<?php
@session_start();
require('../config/connection.php');
require('function.php');

$kabupaten = $_POST['kabupaten'];
echo'<option value="" selected="selected">- Pilih Kota -</option>';
$query = $db->query("SELECT `id`,`nama_kota` FROM `ongkir` WHERE `kabupaten`='$kabupaten' ORDER BY `nama_kota` ASC");
while($row = $query->fetch_assoc()):
	echo '<option value="'.$row['id'].'">'.ucwords($row['nama_kota']).'</option>';
endwhile;	
?>