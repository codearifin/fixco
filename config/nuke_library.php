<?php 
include('myconfig.php');
include('connection.php');

$_GET = form_clean($_GET);
$_POST = form_clean($_POST);

function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}

function get_new_sortnumber($table_name, $sort_field_name='sortnumber') {
  $theLast = global_select_single($table_name, "MAX(`".$sort_field_name."`) AS max_sortnumber");
  return $theLast['max_sortnumber']+1;
}

function replace_to_dash($text){
		$str = array("’" , "/", " " , ":", "(", ")" , "?" , "%" , "," , "." , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "rsquo;","_");
		$newtext=str_replace($str,"-",strtolower($text));
		return $newtext;
}

function replace_to_space($text) {
  $str = array("-","_");
	$newtext = ucwords(str_replace($str, " ", strtolower($text)));
	return $newtext;
}

function upload_myfile($name,$folder,$rename_to){
  // get file name & upload
  $tmpt_folder    = $folder;
  $image          = $_FILES[$name]['tmp_name'];                   // get temp name
  $image_name     = $_FILES[$name]['name'];                       // get name
  $image_baru     = multi_upload($image,$image_name,$tmpt_folder,$rename_to);
  if($image_baru){
      return $image_baru;
  } else{
      return false;
  }
}

function multi_upload($img, $img_, $tmpt_folder, $rename_to){

	$imgbaru = $rename_to."-".date("Y-m-d-His");
	$extG    = get_file_extension($img_);
	$ext     = '.'.$extG;
	$checkextimg = strtolower($extG);
	if(in_array($checkextimg, array("jpg", "jpeg", "png", "gif", "pdf", "xls", "doc", "mov", "ico", "mp4", "ogv", "webm"))):

  	$imgname = $imgbaru.$ext;
  	$dest    = $tmpt_folder.$imgname;

  	if($img_ == '') {
      $nama_gambar = '';
    } else {
      $nama_gambar = $imgname;
    }
    
    move_uploaded_file($img,$dest);
		return $nama_gambar;
	else:
		$nama_gambar='';
	endif;	
}

function get_client_ip() {
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
	   $ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function is_selected($compare1,$compare2){
	if($compare1 == $compare2){
		$selected = "selected='selected'";
		return $selected;
	}else{
		return false;
	}
}

function is_checked($compare1,$compare2){
    if($compare1 == $compare2){
        $selected = "checked";
        return $selected;
    }else{
        return false;
    }
}

function form_clean($arr) {
    global $db;

    $new_arr = array();
    foreach($arr AS $key => $val) {
      if(is_array($val)) { // If the post data is an array
        foreach($val AS $key2 => $val2) {
          $new_arr[$key][$key2] = $db->real_escape_string($val2);
        }
      } else {
        $new_arr[$key] = $db->real_escape_string($val);
      }      
    }

    return $new_arr;
}

function global_insert($table_name, $arr_data, $debug=false) {
    global $db;
    $str_column = "";
    $str_values = "";
    foreach($arr_data AS $key => $val) {
        $str_column .= ($str_column == "" ? "":", ")."`".$key."`";
        $str_values .= ($str_values == "" ? "":", ")."'".$val."'";
    }

    $str = "INSERT INTO `".$table_name."`(".$str_column.") VALUES(".$str_values.")";
    if($debug) { echo $str; exit; }

    $result = $db->query($str) or die($db->error);
    if($result)
        return $db->insert_id;

    return false;
}

function global_update($table_name, $arr_data, $str_where, $debug=false) {
    global $db;

    $str_set = "";
    
    foreach($arr_data AS $key => $val) {
        $str_set .= ($str_set == "" ? "":", ")."`".$key."` = '".$val."'";
    }

    $str = "
        UPDATE `".$table_name."` 
        SET ".$str_set."
        WHERE ".$str_where."
    ";

    if($debug) { echo $str; exit; }
    
    $result = $db->query($str) or die($db->error);

    if($result)
        return true;

    return false;
}

function global_delete($table_name, $str_where) {
    global $db;

    $str = "DELETE FROM `".$table_name."` WHERE ".$str_where." ";

    $result = $db->query($str);

    if($result){
        return 1;
    }else{
        return 0;
    }
}

function global_select_single($table_name, $str_select, $str_where=false, $str_order=false, $str_limit=false, $debug_mode=false) { 
    global $db;

    $str = "
        SELECT ".$str_select." 
        FROM ".$table_name."
        ".($str_where ? "WHERE ".$str_where : "")."
        ".($str_order ? "ORDER BY ".$str_order : "")."
        ".($str_limit ? "LIMIT ".$str_limit : "")."
    ";
    if($debug_mode) { echo $str; exit; }

    $result = $db->query($str);
    if($result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        return $row;
    }
    
    return false;
}

function select_custom($str) {
	//update by RNT 27 nov 15  ( $result )
    global $db;
    $result = $db->query($str);
    if($result):
		if($result->num_rows > 0) { 
			$arr_return_data = array();
			while($row = $result->fetch_assoc()) {
				array_push($arr_return_data, $row);
			}
			return array(
					'row'=> $arr_return_data,
					'total'=>$result->num_rows
			);
		}
	endif;
    return false;
}

function global_select_field($table_name, $str_field_name, $str_where=false, $str_order=false, $str_limit=false, $debug_mode=false) { 
    global $db;

    $str = "
        SELECT ".$str_field_name." 
        FROM ".$table_name."
        ".($str_where ? "WHERE ".$str_where : "")."
        ".($str_order ? "ORDER BY ".$str_order : "")."
        ".($str_limit ? "LIMIT ".$str_limit : "")."
    ";
    if($debug_mode) { echo $str; exit; }

    $result = $db->query($str);
    if($result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        return $row[$str_field_name];
    }
    
    return false;
}

function global_select($table_name, $str_select, $str_where=false, $str_order=false, $str_limit=false, $debug_mode=false) {
    global $db;
    
    //if($str_order === false) { $str_order = "sortnumber ASC"; } 
    
    $str = "
        SELECT ".$str_select." 
        FROM ".$table_name."
        ".($str_where ? "WHERE ".$str_where : "")."
        ".($str_order ? "ORDER BY ".$str_order : "")."
        ".($str_limit ? "LIMIT ".$str_limit : "")."
    ";

    if($debug_mode) { echo $str; exit; }

    $result = $db->query($str);
    
    if($result->num_rows > 0) { 
        $arr_return_data = array();
        while($row = $result->fetch_assoc()) {
            array_push($arr_return_data, $row);
        }
        return $arr_return_data;
    }

    return false;
}

function num_rows($table_name, $str_select, $str_where=false, $str_order=false) {
    global $db;

    $str = "
        SELECT ".$str_select." 
        FROM `".$table_name."` 
        ".($str_where ? "WHERE ".$str_where : "")."
        ".($str_order ? "ORDER BY ".$str_order : "")."
    ";

    $result = $db->query($str);
    if($result->num_rows > 0) {
        return $result->num_rows;
    }

    return $result->num_rows;
}

function print_block($data, $title="PRINT BLOCK") {
    echo "<div style='margin:20px; padding:10px; border:1px solid #666; box-shadow:0px 0px 10px #ccc; border-radius:6px;'>";
    echo "  <div style='padding:10px 5px; margin-bottom:10px; font-weight:bold; font-size:120%; border-bottom:1px solid #666'>".$title."</div>";
    if(is_array($data) OR is_object($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo $data;
    }
    echo "</div>";
}

function print_exit($data, $title="PRINT BLOCK") {
    print_block($data, $title="PRINT BLOCK");
    exit;
}

function flashdata($type, $message) {
    if(isset($_SESSION['flashdata'])) {
      unset($_SESSION['flashdata']);
    }
    $_SESSION['flashdata'] = array("type" => $type, "message" => $message);
}

function flash_success($message) {
    flashdata("success", $message);
}

function flash_error($message) {
    flashdata("error", $message);
}

function redirect($url=false) {
  echo "<script>location.href='".$url."';</script>";
  exit;
}

function ymd_to_dmy($str) {
    if(str_replace(array("-", "/"), "", $str) == "")
        return "";
    return substr($str, 8, 2)."-".substr($str, 5, 2)."-".substr($str, 0, 4);
}

function dmy_to_ymd($str) {
    if(str_replace(array("-", "/"), "", $str) == "")
        return "";
    return substr($str, 6, 4)."-".substr($str, 3, 2)."-".substr($str, 0, 2);
}