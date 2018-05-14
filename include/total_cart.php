<?php 
	include_once('../scripts/jcart/jcart.php');
	@session_start();
	echo number_format($jcart->items_num());
?>