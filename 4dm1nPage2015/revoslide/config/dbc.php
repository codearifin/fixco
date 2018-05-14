<?php
  if(!isset($db)){
    //$db = new mysqli('localhost', 'desain_ccl', '12qwaszx', 'desain_ccl');
    $db = new mysqli('localhost', 'root', '', 'cicilannolpersen');
    if($db->connect_errno){
      die("We're Sorry. We are under Maintenance within 3 hours. Thank You");
	  exit();
    }
  }
?>