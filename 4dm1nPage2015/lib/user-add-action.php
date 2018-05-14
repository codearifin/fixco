<?php
require("../../config/nuke_library.php");

$token_admin 	= '1abc18908aa11001';
$token_admin2 = '200a11abc18908aa11001';

$name 				= $_POST['name'];
$username 		= $_POST['name_member']; 
$passwordfrm 	= $_POST['dash_newpass'];
$id_master_privilege = ($_POST['privilege_level'] != "" ? $_POST['privilege_level'] : 0);

$user 			= sha1($username);
$user2 			= sha1($user);
$user3 			= md5($user2.'U100101U');
$user4 			= sha1($user3);
$user5 			= sha1($user4.'U100101U');
$password 	= sha1($passwordfrm);
$password2 	= sha1($password);
$password3 	= md5($password2.'U100101U');
$password4 	= sha1($password3);
$password5 	= sha1($password4.'U100101U');

$que2 = $db->query("SELECT `id` FROM `1001ti14_vi3w2014` WHERE `A10010289` = AES_ENCRYPT('$user5','$token_admin')");	
$jumpage = $que2->num_rows;
if($jumpage < 1):
	
	$quip22 = $db->query("SELECT max(id) as lastid FROM `1001ti14_vi3w2014`");
	$res 		= $quip22->fetch_assoc();
	$iduser = $res['lastid']+1;
	
	$query = $db->query("
		INSERT INTO `1001ti14_vi3w2014` (`id`,`A10010289`,`B10010289`,`C10010289`,`user_badgedame`, `id_master_privilege`)
		VALUES (
			'$iduser',
			AES_ENCRYPT('$user5','$token_admin'),
			AES_ENCRYPT('$password5','$token_admin2'),
			AES_ENCRYPT('$username','$token_admin'),
			'$name',
			'$id_master_privilege')
	");
	
	echo 200;
else:
	echo 300;
endif;
?>