<?php



$SITE_NAME 			= "Fixco Mart";



//$SITE_URL  			= "http://localhost:8080/fixco/";



//$WEBSITE_NAME 		= "http://localhost:8080/fixco"; //add by RNT 27 nov 15





$SITE_URL  			= "http://localhost/fixcomart/";

$WEBSITE_NAME 		= "http://localhost/fixcomart"; //add by RNT 27 nov 15





$HOME_URL  			= $SITE_URL."index";







/** APA BILA ADA PERGANTIAN NAMA FOLDER, HARAP DI PERHATIKAN, NAMA FOLDER SECARA FISIK dan HTACCESS nya **/



$ADMIN_URL  		= $SITE_URL."4dm1nPage2015/";



$ADMIN_HOME  		= $ADMIN_URL."user-change-password.php";



$ADMIN_LOGIN  		= $ADMIN_URL."index.php";







$TABLE_PREFIX 		= ""; //PREFIX BELUM SEMPURNA //"amw1_";



$UPLOAD_FOLDER 		= $SITE_URL."uploads/";







$SITE_TOKEN			= sha1(preg_replace("/[^a-zA-Z]+/", "", $SITE_URL));



date_default_timezone_set('Asia/Jakarta');



?>