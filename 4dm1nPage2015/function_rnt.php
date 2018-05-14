<?php
function getnamegenal($id,$act,$field){
	global $db;
	$query = $db->query("SELECT `".$field."` FROM `".$act."` WHERE ".$id." ");
	$row = $query->fetch_assoc();		
	return $row[$field];
}

function replaceamount($amount){
	$str = array(","," ");
	$newtext=str_replace($str,"",$amount);
	return $newtext;
}

function replacetanggal($amount){
	$str = array("/");
	$newtext=str_replace($str,"-",$amount);
	return $newtext;
}

function getmemberlistafil($idmember){
	global $db;
	$query = $db->query("SELECT `name`,`lastname`,`member_category` FROM `member` WHERE `id` = '$idmember' ");
	$row = $query->fetch_assoc();		
	echo '<strong>'.$row['name'].' '.$row['lastname'].'</strong> <br />'.$row['member_category'];
}

function getmemberlistafildetail($idmember){
	global $db;
	$query = $db->query("SELECT * FROM `member_membership_data` WHERE `idmember` = '$idmember' ");
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$row = $query->fetch_assoc();		
		echo '<strong>'.$row['company_name'].'</strong><br />';	
	endif;
	
	echo'<a href="eccomerce/detail_member_list.php?iddata='.$idmember.'" class="nuke-fancied2" style="color:#009999;">View Detail</a>';
}
?>