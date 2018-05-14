<?php 
require('../../config/nuke_library.php');

$id = $_POST['id'];

$name				= $db->real_escape_string($_POST['name']);
$privilege 	= implode(',',$_POST['menu']);

$txtquery = "UPDATE `master_privilege` SET `name` = '$name',`menu` = '$privilege' WHERE `id` = '$id'";
$query 		= $db->query($txtquery);

if($query) {
	
	flash_success("Privilege Edit Success");
	redirect($GLOBALS['ADMIN_URL']."privilege-view.php");
	
} else {
	
	flash_success("Privilege Edit Success");
	redirect($GLOBALS['ADMIN_URL']."privilege-view.php");

}
?>
