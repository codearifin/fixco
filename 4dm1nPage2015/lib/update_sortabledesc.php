<?php
require("../../config/nuke_library.php");

$actpageID 	= $_POST['actpageID'];
$itemid 		= $_POST['itemid'];
$MaxSortID 	= $_POST['MaxSortID'];

$tagsbrand 	= explode('-',$itemid);
$tags 			= count($tagsbrand);
$jumtags 		= $tags-1;

for($xx=0;$xx<$jumtags;$xx++){
	$idteam = $tagsbrand[$xx];     
	$query = $db->query("UPDATE `".$actpageID."` SET `sortnumber`='$MaxSortID' WHERE `id`='$idteam'");
	$MaxSortID--;
}
?>