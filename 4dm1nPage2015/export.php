<?php
	include "../config/nuke_library.php";

   function replacename($text){
 		$str = array("/", "." , "?","%", ",", "!" , "#", "$", "@", "^" ,"&","\"","\\","\r\n", "\n", "\r");
		$newtext=str_replace($str," ",$text);
		return $newtext;
   }
  
	if(!isset($_GET['table_name']) AND !isset($table_name)) {
	  echo "Maaf, Anda belum memilih table yang ingin Anda export"; exit;
	}


	if(isset($_GET['table_name'])) {
	  $theTable = $db->query('SELECT 1 FROM '.$_GET['table_name'].' LIMIT 1') or die($db->error);
	  if( !($theTable !== FALSE) ) {
	    //I can't find it...
	    echo "Your table is not exists."; exit;
	  }
	}

  
  $table_name = (isset($_GET['table_name']) ? $_GET['table_name'] : $_POST['table_name']);
  $query_structure 	= $db->query('SHOW COLUMNS FROM '.$table_name) or die($db->error);
  $query_data 		= $db->query("SELECT * FROM `".$_GET['table_name']."` ORDER BY `id` ASC");

	$output = "";
	$temp = array();
	while ($row = $query_structure->fetch_assoc()){
		$output .= strtoupper($row['Field']).',';
		array_push($temp, $row);
	}
	$arr_structure = $temp;
	$output .="\n";

	// Get Records from the table
	while ($row = $query_data->fetch_assoc()){
		foreach ($arr_structure as $field) {
			$field_name = $field['Field'];
			$output .= '"'.str_replace(array("\n","\r","\r\n", "\t"), " ", $row[$field_name]).'",';
			#$output .= $row[$field_name].',';
		}
		$output .="\n";
	}

	// Download the file
	$filename = date("Y.m.d-H.i.s-").$table_name.".csv";
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $output;
	exit;
?>



