<?php 
require('../../config/nuke_library.php');

$name				= $db->real_escape_string($_POST['name']);
$privilege 	= implode(',',$_POST['menu']);

$txtquery = "INSERT INTO `master_privilege` (`name`,`menu`) VALUES ('$name','$privilege')";
$query 		= $db->query($txtquery);

if($query){

	flash_success("Privilege Insert Success");
	redirect($GLOBALS['ADMIN_URL']."privilege-view.php");

} else {
	
	flash_success("Privilege Insert Success");
	redirect($GLOBALS['ADMIN_URL']."privilege-view.php");
	
}
?>
