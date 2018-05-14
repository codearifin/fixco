<?php 
include_once('../scripts/jcart/jcart.php');
@session_start();
$id = $_POST['idprod'];

if($id > 0):
	 $jcart->remove_itemAll($id);	
	 echo 200;
else:
	echo 300;
endif;
?>