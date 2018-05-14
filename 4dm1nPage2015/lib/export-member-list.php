<?php
	require("../../config/connection.php");

	function replacenamesingle($text){
			$str = array(",");
			$newtext=str_replace($str," ",$text);
			return $newtext;
	}
	
	function replacename($text){
			$str = array("/", "?","%", ",", "!" , "#", "$", "@", "^" ,"&","\"","\\","\r\n", "\n", "\r");
			$newtext=str_replace($str," ",$text);
			return $newtext;
	}	

	function getmembername($idmember){
		global $db;
		$query = $db->query("SELECT `name`,`last_name` FROM `users` WHERE `id`='$idmember'");
		$row = $query->fetch_assoc();
		return $row['name'].' '.$row['last_name'];	
	}
	
	function gettotalqtyorder($tokenpay){
		global $db;
		$totQty = 0;
		$query = $db->query("SELECT `qty` FROM `order_detail` WHERE `tokenpay` = '$tokenpay'");
		while($row = $query->fetch_assoc()):
			$totQty = $totQty+$row['qty'];
		endwhile;
		
		return $totQty;
	}
	
	function getcityname($id){
		global $db;
		$query = $db->query("SELECT * FROM `list_kota` WHERE `id` = '$id' ");
		$res = $query->fetch_assoc();	
		return $res['city'];	
	}
		
	$sql = $db->query("SELECT *,DATE_FORMAT(`date`,'%d %M %Y') as tgl FROM `member` ORDER BY `id` ASC");
	$output = "";
	$output .= 'NO,';
	$output .= 'MEMBER ID,';
	$output .= 'REGISTER DATE,';
	$output .= 'FIRST NAME,';
	$output .= 'LAST NAME,';
	$output .= 'EMAIL,';
	$output .= 'PHONE,';
	$output .= 'MOBILE PHONE,';
	$output .= 'GENDER,';
	$output .="\n";

	// Get Records from the table
	$pointer = 1;
	while ($row = $sql->fetch_assoc()){
			
			if($row['gender'] == "Laki-Laki"):
				$gendername = "MALE";
			else:
				$gendername = "FEMALE";
			endif;
						
			$output .= $pointer.',';
			$output .= $row['id'].',';
			$output .= $row['tgl'].',';
			$output .= replacenamesingle($row['name']).',';
			$output .= replacenamesingle($row['lastname']).',';
			$output .= $row['email'].',';
			$output .= "'".$row['phone'].',';
			$output .= "'".$row['mobile_phone'].',';
			$output .= "'".$gendername.',';
			$output .="\n";
				
			$pointer++;
		
	}	
	// end Get Records from the table
	
		
	// Download the file
	$filename = "order_headerlist.csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
		echo $output;
	exit;
?>
