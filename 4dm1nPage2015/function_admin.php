<?php 
//add RNT
function getsubkatnamekey($idkat){
	global $db;
	$query = $db->query("SELECT `name` FROM `subcategory` WHERE `id` = '$idkat' ");
	$row = $query->fetch_assoc();
	$namesub = replacekeyword($row['name']);
	return $namesub;
}

function autokeywordproduct(){
	global $db;
	$query = $db->query("SELECT `product_id` FROM `search_product_latest` ");
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$row = $query->fetch_assoc();	
		$latest_id = $row['product_id'];	
	else:
		$latest_id = 0;	
	endif;	
	
	$quepp = $db->query("SELECT `id` FROM `product` WHERE `publish` = 1 ORDER BY `id` DESC ");
	$data = $quepp->fetch_assoc();	
	$prodid = $data['id'];
	
	if($latest_id == $prodid):
		//no action
	else:
		$tmpdata = ''; $lastidprod = 0; $productname = '';
		$quepyy = $db->query("SELECT `id`,`idsubkat`,`name` FROM `product` WHERE `publish` = 1 ORDER BY `id` ASC ");
		$jumprpod = $quepyy->num_rows;
		$pointer = 1;
		while($res = $quepyy->fetch_assoc()):
			if($res['name']!=""):
				$productname = replacekeyword($res['name']);
				$subkatname = getsubkatnamekey($res['idsubkat']);
				
				if($pointer == $jumprpod):
					$tmpdata.='"'.$res['id'].'": "'.$productname.' di '.$subkatname.'"';	
				else:
					$tmpdata.='"'.$res['id'].'": "'.$productname.' di '.$subkatname.'",';	
				endif;
			
			endif;	
			
			$lastidprod = $res['id'];
			$pointer++;
		endwhile;
		
		if($tmpdata!=""):
			$final_ajak = 'var results = {'.$tmpdata.'}';			   
			$generate_file = file_put_contents("../js/autocomplete/searchresult.js", $final_ajak);
			
			if($generate_file):		   
				$qupp = $db->query("INSERT INTO `search_product_latest` (`product_id`) VALUES ('$lastidprod') ");	
			endif;	
			
			//echo $final_ajak;
		endif;
	endif;	

}

function replacekeyword($text){
	//next
	$str = array("’", ",", ":", "\"");
	$newtext=str_replace($str,"",$text);
	return $newtext;
}

function getmenurntlist($namamenu){
	global $db;
	$namamenu2 = strtolower($namamenu);
	$query = $db->query("SELECT `top_parent_id` FROM `m3nu_4dm1n` WHERE `table_name_no_prefix` = '$namamenu2' and `top_parent_id` > 0 ");
	$jumpage = $query->num_rows;
	if($jumpage>0):
		$row = $query->fetch_assoc();
		$data = global_select_single("m3nu_4dm1n", "`url`", "`id` = '".$row['top_parent_id']."'");
		return $data['url'];
	else:
		$query = $db->query("SELECT `url` FROM `m3nu_4dm1n` WHERE `table_name_no_prefix` = '$namamenu2' and `top_parent_id` = 0 ");
		$row = $query->fetch_assoc();
		return $row['url'];
	endif;	
}

/// START EDIT BY ANDI
function table_prefix_add($table_name){

	return $GLOBALS['TABLE_PREFIX'].$table_name;

}



function get_popup_menu($table_name) {

  $theUrl = global_select_field("m3nu_4dm1n", "url", "`table_name_no_prefix` = '".$table_name."' AND `top_or_left` = 'popup'");

  return $theUrl;

}



function show_top_menu(){ 

    $arr_top_menu = global_select("m3nu_4dm1n", "*", "`top_or_left` = 'top' AND `publish` = 1", "`sortnumber`");

    

    $output = '<ul id="main-nav" class="left clearfix">';





    if($arr_top_menu) {

    	foreach($arr_top_menu AS $menu) {

    		if($menu['specific_url'] != '') {

    			$output .= '	<li class="top-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].$menu['specific_url'].'" title="'.$menu['name'].'">'.$menu['name'].'</a></li>';

    		} else if($menu['specific_id'] != '0') {

    			$output .= '	<li class="top-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].'edit-form.php?menu='.$menu['url'].'&id='.$menu['specific_id'].'" title="Edit '.$menu['name'].'">'.$menu['name'].'</a></li>';

        } else {

    			$output .= '	<li class="top-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].'view.php?menu='.$menu['url'].'" title="'.$menu['name'].'">'.$menu['name'].'</a></li>';

    		}

    	}



    } else {

    	echo 'Cannot load menu list';

    	exit;

    }

    $output .= '</ul><!-- #main-nav -->';

    

    echo $output;

}



function show_left_menu($theMenu){

    $arr_left_header  = global_select("m3nu_4dm1n", "*", "`top_parent_id` = '".$theMenu['id']."' AND `top_or_left` = 'left-header' AND `publish` = 1", "`sortnumber`");

    $arr_left_menu    = global_select("m3nu_4dm1n", "*", "`top_parent_id` = '".$theMenu['id']."' AND `top_or_left` IN ('left', 'left-header') AND `publish` = 1", "`sortnumber`");



    if($arr_left_header) { 

      $ctr_header = 0;

      $output = '

      <div class="cms-sidebar left">';



          $output .= '

          <ul class="side-menu">';



            if($arr_left_menu) { foreach($arr_left_menu AS $menu) {

              if($menu['top_or_left'] == "left-header") {

                $ctr_header++;

                if($ctr_header > 1) { 

                  $output .= '

                    </ul><!-- .side-menu --><br/>

                    <ul class="side-menu">';

                }

                $output .= '<li class="sm-header">'.$menu['name'].'</li>';

              } else if($menu['specific_url'] != '') {

                $output .= '  <li class="left-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].$menu['specific_url'].'" title="'.$menu['name'].'">'.$menu['name'].'</a></li>';

              } else if($menu['specific_id'] != '0') {

                $output .= '  <li class="left-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].'edit-form.php?menu='.$theMenu['url'].'&submenu='.$menu['url'].'&id='.$menu['specific_id'].'" title="Edit '.$menu['name'].'">'.$menu['name'].'</a></li>';

              } else {

                $output .= '  <li class="left-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].'view.php?menu='.$theMenu['url'].'&submenu='.$menu['url'].'" title="'.$menu['name'].'">'.$menu['name'].'</a></li>';

              }

            }}



          $output .= '

          </ul><!-- .side-menu --><br/>';



      $output .= '

      </div><!-- .cms-sidebar -->';



      echo $output;



    } else if($arr_left_menu) {

    	$output = '

    	<div class="cms-sidebar left">

    		<ul class="side-menu">';

		    	$output .= '<li class="sm-header">'.$theMenu['name'].'</li>';

		    	foreach($arr_left_menu AS $menu) {

		    		if($menu['specific_url'] != '') {

		    			$output .= '	<li class="left-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].$menu['specific_url'].'" title="'.$menu['name'].'">'.$menu['name'].'</a></li>';

		    		} else if($menu['specific_id'] != '0') {

		    			$output .= '	<li class="left-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].'edit-form.php?menu='.$theMenu['url'].'&submenu='.$menu['url'].'&id='.$menu['specific_id'].'" title="Edit '.$menu['name'].'">'.$menu['name'].'</a></li>';

		    		} else {

		    			$output .= '	<li class="left-'.$menu['url'].'"><a href="'.$GLOBALS['ADMIN_URL'].'view.php?menu='.$theMenu['url'].'&submenu='.$menu['url'].'" title="'.$menu['name'].'">'.$menu['name'].'</a></li>';

		    		}

		    	}



	    $output .= '

	    		</ul><!-- .side-menu -->

	    	</div><!-- .cms-sidebar -->';



	    echo $output;



    } else {

    	//echo 'No Left Menu';

    }

}



function curURL() {

  $thisURL = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

  return $thisURL;

}



function get_field_info($table_name, $field_name){

  global $db;

  

  //$str = "SELECT ".$field_name. " FROM ".$table_name;

  $str = "SHOW FIELDS FROM ".$table_name." where Field ='".$field_name."'";



  $result = $db->query($str);

  

  $row  = $result->fetch_field();

  

  return $row ->type;    

}

/// END EDIT BY ANDI





function check_privileges(){

	global $db, $SITE_TOKEN; 

	

	if(!isset($_SESSION[$SITE_TOKEN.'userID'])) {

		echo "Sorry you dont have any access to Admin Page";

		exit;

	}



	$theUser 	= global_select_single("1001ti14_vi3w2014", "*", "`id` = '".$_SESSION[$SITE_TOKEN.'userID']."'");

	if(!$theUser) {

		echo "Sorry you dont have user privileges";

		exit;

	}



	if($theUser['id'] > 1) { // WE ASSUME : 1 is for Superadmin



		$thePrivilege = global_select_single("master_privilege", "*", "`id` = '".$theUser['id_master_privilege']."'");

		if(!$thePrivilege) {

			echo "Sorry you dont have user privileges";

			exit;

		}



		$list_akses = explode(',',$thePrivilege['menu']);

		

		if(!in_array($GLOBALS['hak_akses'], $list_akses)){			

			echo "Sorry cannot access this page, Please check user privileges";

			exit;

		}



	}

}



function getjumsortPage_asc($posisi,$batas,$act,$keyword,$field,$parent_keyword=""){

	global $db;



	if($field){

		$i = 0;

		foreach ($field as $key => $value) {

			$field[$i] = "AND `$value` LIKE '%$keyword%'";

			$i++;

			//$sql_keyword = $field[$i];

		}

		$sql_keyword = implode(" ",$field);

	}



	$txtquery = "SELECT `sortnumber` FROM `$act` WHERE 1=1 ".($keyword!="" ? $sql_keyword: "")." $parent_keyword ORDER BY `sortnumber` ASC LIMIT $posisi,$batas";

	

	$query = $db->query($txtquery);

	$row = $query->fetch_assoc();



	return $row['sortnumber'];



}



function get_user_privilege_level_name($id_master_privilege){

	global $db;

	$txtquery = "SELECT `1001ti14_vi3w2014`.`id_master_privilege`,`master_privilege`.`name` AS `name` FROM `1001ti14_vi3w2014` LEFT JOIN `master_privilege` ON `1001ti14_vi3w2014`.`id_master_privilege` = `master_privilege`.`id` WHERE `1001ti14_vi3w2014`.`id_master_privilege` = $id_master_privilege";

	$query = $db->query($txtquery);

	$row = $query->fetch_assoc();

	$name = $row['name'];

	return $name;

}





function paging($tablename,$html,$url,$batas){

    

  $num_rec       = num_rows($tablename,'id', "`publish`= '1'");

  $total_page    = ceil($num_rec/$batas);

  $halaman       = 0; 



  isset($_GET['halaman']) ? $halaman = $_GET['halaman']: $halaman = 1;



  $html          = explode('#',$html);

  $output        = '';



  $output       .= $html[0]; 

     

  if($halaman > 1) { //prev button

    $output       .= '<li class="button"><a href="'.$url.'/'.($halaman - 1).'">Prev</a></li>';

  } else {

    $output       .= '<li class="button"><a href="#">Prev</a></li>';

  } 





  for($i=1;$i<=$total_page;$i++){     

    if($halaman <> $i ){ 

      $output   .= '<li><a href="'.$url.'/'.$i.'">'.$i.'</a></li>';

    }else if($halaman == 1 || $halaman == $i) {

      $output   .= '<li><a class="current" href="#">'.$i.'</a></li>'; 

    }

  }



  if($halaman >= 1 && $halaman < $total_page) { //prev button

    $output       .= '<li class="button"><a href="'.$url.'/'.($halaman + 1).'">Next</a></li>';

  }else { 

    $output       .= '<li class="button"><a href="#">Next</a></li>';

  }



  $output       .= $html[1];



  echo $output;

    

}



function show_list_add($fname,$cur_select_input,$act) {

  $field  = $cur_select_input['show_field'];

  $result = global_select($cur_select_input['get_from_table'], "*", "publish = '1'", "sortnumber ASC");

  

  //modif RNT

  $namaKelas = global_select_field("0pti0n_s3tt1ng", "class_name", "`get_from_table` = '".$cur_select_input['get_from_table']."'");

  	

  //if(!$result){ echo 'Cannot load list'; exit(); } 

  

  $output = '';

  

  if($cur_select_input['input_type'] == 'select'){ 

  

    $bracket1 = '<select id="'.$cur_select_input['get_from_table'].'" name="'.$fname.'">';

	$inner_prev = '<option value="" selected="selected">Please Select '.replace_to_space($cur_select_input['get_from_table']).'</option>';

    $inner      = '<option #value# #selected#>#label#</option>';

    $bracket2 = '</select>';

  

  } else if($cur_select_input['input_type'] == 'radio') {

    $inner    = '<input name='.$cur_select_input['get_from_table'].'"  #value# type="radio">#label#';

  }  

  

  if(isset($bracket1))   $output .= $bracket1; 

  if(isset($inner_prev)) $output .= $inner_prev;

  

  if($result) {

    foreach($result AS $row) {

      if(isset($_GET['parent_id'])) {

        if($_GET['parent_id']['field'] != "" AND $_GET['parent_id']['id'] != "") {

          if($_GET['parent_id']['field'] == $cur_select_input['field_name']) {

            $cur_select_input['selected'] = $_GET['parent_id']['id'];

          }

        }

      }

	  	

	 //modif RNT

	  if($namaKelas==""): 	

      	$inner2 = str_replace('#value#','class="'.$row['id'].'" value="'.$row['id'].'"', $inner);

		$inner3 = str_replace('#selected#',is_selected($cur_select_input['selected'],$row['id']), $inner2);

	  else:

	  	 $inner2 = str_replace('#value#','class="'.$row[$namaKelas].'" value="'.$row['id'].'"', $inner);

		 $inner3 = str_replace('#selected#',is_selected($cur_select_input['selected'],$row['id']), $inner2);

	  endif;

	 

      $output .=  str_replace('#label#',ucwords($row[$field]), $inner3);

    }

  }

      

  if(isset($bracket2)) $output .= $bracket2;

  

  //chained select upto 3 select

  

  return $output;

}



function get_sql_keyword($array_search_by,$keyword){

    if($array_search_by){

        $i = 0;

        foreach ($array_search_by as $key => $value) {

            if($i == 0){

                $field[$i] = "AND $value LIKE '%$keyword%'";

            }else{

                $field[$i] = "OR $value LIKE '%$keyword%'";

            }

            $i++;

        }

    }

    $sql_keyword = implode(" ",$field);

    return $sql_keyword;

}

?>

