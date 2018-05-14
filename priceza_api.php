<?php
  include("config/connection.php");
  include("config/myconfig.php");
 
  function replacebr($text){
	//fisrt replace
	$str_awal = array("<br>", "<br >", "<br />" , "<br/>");
	$text_awal = str_replace($str_awal," ",$text);
	return $text_awal;
  }

 function replace($text){
		//fisrt replace
		$str_awal = array("<br>", "<br >", "<br />" , "<br/>");
		$text_awal = str_replace($str_awal," ",strtolower($text));
		
		//next
		$str = array("’", " " , "/" , "?" , "%" , "," , "!" , "#" , "$" , "@" , "^" , "&" , "\"" , "\\" , "\r\n" , "\n" , "\r" , "'", "." , "rsquo;");
		$newtext=str_replace($str,"-",strtolower($text_awal));
		return $newtext;
 }
		 
  function getcategoryname($idkat){
  	 global $db;
	 $query = $db->query("SELECT * FROM `category` WHERE `id` = '$idkat' ");
	 $res = $query->fetch_assoc();
	 $namekat = replacebr($res['name']);
	 return $namekat;
  }
  
  $listitem['product'] = '';
  $query = $db->query("SELECT * FROM `product` WHERE `publish` = 1 ORDER BY `id` ASC ");
  $jumpage = $query->num_rows;
  if($jumpage>0):
  	$items_details = array(); 
	$data_prod_list_tmp = array(); 
	
	$data_prod_list = ''; $imageUrl = ''; $cate_name = '';
	while($res = $query->fetch_assoc()):
		
		$imageUrl = $GLOBALS['UPLOAD_FOLDER'].$res['image'];	
		$cate_name = getcategoryname($res['idkat']);
		$urelwebsite = $GLOBALS['SITE_URL'].'product-detail/'.replace($res['name']).'/'.$res['id'];
		
		$data_prod_list = array(
					
					'id' => $res['id'],
					'name' => $res['name'],
					'price' => $res['price'],
					'description' => strip_tags($res['short_description']),
					'url' => $urelwebsite,
					'image_url' => $imageUrl,
					'category_id' => $res['idkat'],
					'category_name' => $cate_name,
					'base_price' => 0,
					'upc' => $res['sup_sku_id'],
					'keyword' => $res['name']
				);
				
		$data_prod_list_tmp = array_push($items_details, $data_prod_list);
				
	endwhile;
	$data_prod_list_tmp = array( 'product' => $items_details );
	
	echo json_encode($data_prod_list_tmp);
	
  else:
  	
	echo 'Records not found.';
	
  endif;
  
  
?>