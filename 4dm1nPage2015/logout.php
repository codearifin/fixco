<?php 
@session_start();
require('../config/nuke_library.php'); 

session_destroy();
redirect($GLOBALS['ADMIN_LOGIN']);
?>