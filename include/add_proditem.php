<?php 
include_once('../scripts/jcart/jcart.php');
@session_start();

$id = $_POST['idprod']; $newqtyprod = $_POST['newqtyprod'];
if($id > 0 and $newqtyprod > 0):
	 $jcart->update_itemmanual($id, $newqtyprod);	
	 echo 200;
else:
	echo 300;
endif;
?>