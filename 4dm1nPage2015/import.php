<?php   
include "../config/nuke_library.php";
$file_path = $UPLOAD_FOLDER."import_temporary.csv";
$file_path = str_replace($GLOBALS['SITE_URL'], "../", $file_path);

if(!isset($_GET['table_name']) AND !isset($table_name)) {
  echo "Maaf, Anda belum memilih table yang ingin Anda import"; exit;
}

if(isset($_GET['table_name'])) {
  $theTable = $db->query('SELECT 1 FROM '.$_GET['table_name'].' LIMIT 1') or die($db->error);
  if( !($theTable !== FALSE) ) {
    //I can't find it...
    echo "Your table is not exists."; exit;
  }
}

  $table_name = isset($_GET['table_name']) ? $_GET['table_name'] : $_POST['table_name'];
  $query_structure = $db->query('SHOW COLUMNS FROM '.$table_name) or die($db->error);

  $primary_key_field = false;
  $is_auto_increment = false;
  $arr_table_field = array();
  $arr_table_field_with_type = array();

  $output = "";
  $temp = array();
  while ($row = $query_structure->fetch_assoc()){
		$output .= strtoupper($row['Field']).',';
		array_push($temp, $row);
 }
 
 $arr_structure = $temp;
	
		 
  if($arr_structure){ 
	  	
		foreach($arr_structure AS $field){
			if($field['Key'] == "PRI") {
			  $primary_key_field = $field['Field'];
			}
			
			array_push($arr_table_field, $field['Field']);
			array_push($arr_table_field_with_type, $field['Field']." (".$field['Type'].")");
	  	}
 	 }



  if(!isset($_POST['submit']) AND !isset($_POST['submit_match_table']) AND !isset($_POST['submit_match_column'])) {

    echo "<strong>Database struktur untuk table : ".$table_name."</strong> <br />";



        echo "<table>";

        echo "<tr>";

        foreach($arr_table_field AS $field) {

          echo "<th>".$field."</th>";

        }

        echo "</tr>";

        for($i = 0; $i < 3; $i++) {    

          echo "<tr>";

          foreach($arr_table_field AS $field) {

            echo "<td>&nbsp;</td>";

          }

          echo "</tr>";

        }

        echo "</table><br /> Table information : <br />";



        foreach($arr_table_field_with_type AS $field) {

          echo "- ". $field."<br />";

        }



        echo "<br /><br />";

  }

?>



<style>

  * { line-height: 18px; color: #333; font-family: Helvetica, Arial, sans-serif; font-size:12px; }

  table { color: #333; font-family: Helvetica, Arial, sans-serif; width: 640px; border-collapse: collapse; border-spacing: 0; }

  td, th { border: 1px solid #CCC; height: 30px; }

  th { background: #F3F3F3; font-weight: bold; }

  td { background: #FAFAFA; text-align: left; }

  input { margin:3px; }

  td.sample { font-size: 9px; line-height:12px; }

</style>



<?php

if(isset($_POST['submit_match_column'])) {

  $arr_select = $_POST['arr_select'];



  if (($handle = fopen($file_path, "r")) !== FALSE) {



      $update_success = 0;

      $update_error   = 0;

      $insert_success = 0;

      $insert_error   = 0;

      while ($data = fgetcsv($handle,1000,",","'")) {

        $row_to_save = array();

        foreach($data AS $key_uploaded => $val) { 

          if($arr_select[$key_uploaded] ==  "dont" OR $arr_select[$key_uploaded] ==  "") { continue; }



          $the_key = $arr_select[$key_uploaded];

          if($the_key == "") { 

            //echo "ada field yang kosong"; exit;

            continue;

          }



          $row_to_save[$the_key] = $val;

        }

        //print_r($row_to_save);echo "<br />";



        $found_same_id = global_select_single($table_name, $primary_key_field, $primary_key_field." = '".$row_to_save[$primary_key_field]."'");

        if($found_same_id) {

          if($_POST['if_same_id'] == "ignore") {

            continue;

          } else if($_POST['if_same_id'] == "update") {

            $id = $row_to_save[$primary_key_field];

            unset($row_to_save[$primary_key_field]);

            $res = global_update($table_name, $row_to_save, $primary_key_field." = '".$id."'");

            if($res) {

              $update_success++;

            } else {

              $update_error++;

            }

          } else if($_POST['if_same_id'] == "insert") {

            $row_to_save[$primary_key_field] = global_select_field($table_name, "MAX(".$primary_key_field.")")+1;

            $res = global_insert($table_name, $row_to_save);

            if($res) {

              $insert_success++;

            } else {

              $insert_error++;

            }

          }

        } else {

          $row_to_save[$primary_key_field] = global_select_field($table_name, "MAX(".$primary_key_field.")")+1;

          $res = global_insert($table_name, $row_to_save);

          if($res) {

            $insert_success++;

          } else {

            $insert_error++;

          }

        }



      }



      echo "

        File already imported : <br />

        Insert Success : ".$insert_success." rows <br />

        Insert Error : ".$insert_error." rows <br />

        Update Success : ".$update_success." rows <br />

        Update Error : ".$update_error." rows <br />

      ";

  }



  exit;

}



if(isset($_POST['submit_match_table'])) {

  if (($handle = fopen($file_path, "r")) !== FALSE) {



      $update_success = 0;

      $update_error   = 0;

      $insert_success = 0;

      $insert_error   = 0;

      $row = 0;

      while ($data = fgetcsv($handle,1000,",","'")) { 

        $row++; if($row == 1){ continue; } // skip header row



        $row_to_save = array();

        foreach($arr_table_field AS $key_table => $fieldName) {

          $the_key = $_POST[$fieldName];

          if($the_key == "") { 

            //echo "ada field yang kosong"; exit;

            continue;

          }

          $row_to_save[$fieldName] = $data[$the_key];

        }

        //print_r($row_to_save);echo "<br />";



        $found_same_id = global_select_single($table_name, $primary_key_field, $primary_key_field." = '".$row_to_save[$primary_key_field]."'");

        if($found_same_id) {

          if($_POST['if_same_id'] == "ignore") {

            continue;

          } else if($_POST['if_same_id'] == "update") {

            $id = $row_to_save[$primary_key_field];

            unset($row_to_save[$primary_key_field]);

            $res = global_update($table_name, $row_to_save, $primary_key_field." = '".$id."'");

            if($res) {

              $update_success++;

            } else {

              $update_error++;

            }

          } else if($_POST['if_same_id'] == "insert") {

            $row_to_save[$primary_key_field] = global_select_field($table_name, "MAX(".$primary_key_field.")")+1;

            $res = global_insert($table_name, $row_to_save);

            if($res) {

              $insert_success++;

            } else {

              $insert_error++;

            }

          }

        } else {

          $row_to_save[$primary_key_field] = global_select_field($table_name, "MAX(".$primary_key_field.")")+1;

          $res = global_insert($table_name, $row_to_save);

          if($res) {

            $insert_success++;

          } else {

            $insert_error++;

          }

        }

      }



      echo "

        File already imported : <br />

        Insert Success : ".$insert_success." rows <br />

        Insert Error : ".$insert_error." rows <br />

        Update Success : ".$update_success." rows <br />

        Update Error : ".$update_error." rows <br />

      ";

  }



  exit;

}



if(isset($_POST['submit'])) {



  move_uploaded_file($_FILES['csv']['tmp_name'], $file_path);



  if($_POST['have_header'] == "0") {



    echo "<form action='".$_SERVER['HTTP_REFERER']."' method='post'>";

    echo "<input type='hidden' name='table_name' value='".$table_name."'>";

    echo "<input type='hidden' name='if_same_id' value='".$_POST['if_same_id']."'>";

    echo "<table cellspacing='0' cellpadding='5' border='1'>";

    echo "<tr>";



    if (($handle = fopen($file_path, "r")) !== FALSE) {



      if ($data = fgetcsv($handle,1000,",","'")) {

        foreach($data AS $key_uploaded => $val) { 

            echo "  <td>

                      <select name='arr_select[]'>";

            echo "      <option value=''>Select Field</option>";

            

            

            foreach($arr_table_field AS $key_table => $field) { 

              echo "    <option value='".$field."' ".($key_uploaded == $key_table ? "selected='selected'":"").">".$field."</option>";

            }



            echo "      <option value='dont'>Dont Input This Field</option>";

            echo "    </select>

                    </td>";

        }

      }



    }

    echo "</tr>";



    $row = 0;

    if (($handle = fopen($file_path, "r")) !== FALSE) {



      while ($data = fgetcsv($handle,1000,",","'")) { $row++;

        echo "<tr>";

        foreach($data AS $val) {

          echo "<td class='sample'>".$val."</td>";

        }

        echo "</tr>";



        if($row == 10){

          break;

        }

      }

      fclose($handle);

    }

    echo "</table>";





    $fp = file($file_path, FILE_SKIP_EMPTY_LINES);

    $total_rows = count($fp);

    echo "<br /> Total rows will be insert : ".$total_rows." rows<br />";



    echo "<input type='submit' value='Import Now' name='submit_match_column' />";

    echo "</form>";





  } else { #if($_POST['have_header'] == "1")



    $arr_uploaded_field = array();

    if (($handle = fopen($file_path, "r")) !== FALSE) {



      if ($data = fgetcsv($handle,1000,",","'")) {

        foreach($data AS $key => $val) {

          array_push($arr_uploaded_field, trim($val));

        }

        

      }

    }



    //print_r($primary_key_field);

    echo "<form action='".$_SERVER['HTTP_REFERER']."' method='post'>";

    echo "<input type='hidden' name='table_name' value='".$table_name."'>";

    echo "<input type='hidden' name='if_same_id' value='".$_POST['if_same_id']."'>";

    echo "<table cellspacing='0' cellpadding='5' border='1'>";

    echo "    <tr><th>Database Column</th><th>Your CSV Column</th><th>Status</th></tr>";

    

    foreach($arr_table_field AS $field){

      echo "  <tr>

                <td>".$field."</td>";

      echo "    <td>

                  <select name='".$field."'>

                    <option value=''>Select Field</option>";

      foreach($arr_uploaded_field AS $key => $field_uploaded){

        echo "      <option value='".$key."' ".($field_uploaded == $field ? "selected='selected'":"").">".$field_uploaded."</option>";

      }

      echo "      </select>

                </td>";

      echo "    <td>".(in_array($field, $arr_uploaded_field) ? "Match":"Unmatch")."</td>

              </tr>";

    }



    echo "</table>";



    echo "<input type='submit' name='submit_match_table' />";

    echo "</form>";



  }



  exit;



} # END OF : if(isset($_POST['submit']))

?>



<form action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data" method="post">

  <input type="hidden" name="table_name" value="<?php echo $_GET['table_name']?>">



  Apakah file csv yang Anda upload memiliki Header ?<br />

    <input type="radio" name="have_header" value="1"> Ada 

    <input type="radio" name="have_header" value="0"> Tidak ada<br /><br />

    

  Jika ada Primary Key dengan nilai yang sama, apa yang Anda ingin lakukan ?<br />

    <input type='radio' name='if_same_id' value='update' /> Update ID yang sama dengan nilai yang baru saya masukkan <br />

    <input type='radio' name='if_same_id' value='insert' /> Jangan update data yang lama dan tambahkan baris baru dalam database <br />

    <input type='radio' name='if_same_id' value='ignore' /> Jangan tambahkan ke database dan jangan update dengan nilai baru yang saya masukkan <br />



  <br />

  <input type="file" name="csv" /> <br />

  <span style="font-size:9px;">(Pastikan tanggal dengan format : <strong>yyyy-mm-dd</strong> atau <strong>yyyy-mm-dd hh:mm:ss</strong>)</span>

  <br /><br />



  

  <input type="submit" name="submit" value="Upload">

</form>



