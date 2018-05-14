<?php 
@session_start();
error_reporting(E_ALL);
require('function_admin.php');
require('../config/nuke_library.php');
require('../config/cmsconfig.php');
?>
<!DOCTYPE HTML>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta charset="UTF-8">
<meta name="copyright" content="Nukegraphic" />
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		<title>Welcome to <?php echo $GLOBALS['SITE_NAME'];?> - Content Management System</title>
		<link rel="shortcut icon" href="pavico.png" />
		<link href="css/reset.css" rel="stylesheet" type="text/css" />
		<link href='css/google-roboto-font.css' rel='stylesheet' type='text/css'>
		<link href="css/cms.css" rel="stylesheet" type="text/css" />
		<script src="js/jquery-1.11.0.min.js" type="text/javascript"></script>

		<!-- FANCYBOX -->
		<link rel="stylesheet" type="text/css" href="js/fancybox2/jquery.fancybox.css" media="screen" />
		<script type="text/javascript" src="js/fancybox2/jquery.fancybox.pack.js"></script>
		<!-- CKEDITOR -->
		<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>

		<!-- VALIDATE -->
		<script type='text/javascript' src='js/jquery.validate.js'></script>

		<!-- OVERLAY MESSAGE -->
		<script src="js/overlay-js/iosOverlay.js" type="text/javascript"></script>
		<script src="js/overlay-js/spin.min.js" type="text/javascript"></script>
		<link rel="stylesheet" href="js/overlay-js/iosOverlay.css">


		<!-- dragable -->
		<script type="text/javascript" src="js/scripts_draggable/jquery.tablednd.js"></script>
		<script src="js/jquery.chained.js" type="text/javascript" ></script>
		<!-- OUR SCRIPT JS -->
		<script type='text/javascript' src='js/nuke_ajax.js'></script>
		<!-- DATE TIME PICKER -->
		<script type="text/javascript" src="js/jquery.datetimepicker/jquery.datetimepicker.full.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/jquery.datetimepicker/jquery.datetimepicker.css"></link>

		<!-- DATE AND TIME PICKER -->
		<script type="text/javascript" src="js/timepicker/jquery.timepicker.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/timepicker/jquery.timepicker.css" />

	    <script type="text/javascript" src="js/timepicker/bootstrap-datepicker.js"></script>
	    <link rel="stylesheet" type="text/css" href="js/timepicker/bootstrap-datepicker.css" />

		<!-- COLOR PICKER-->
		<script type="text/javascript" src="js/color_piker/spectrum.js"></script>
   		<script type='text/javascript' src='js/color_piker/docs.js'></script>
    	<link rel="stylesheet" type="text/css" href="js/color_piker/spectrum.css">
</head>
<body>

<div id="centered">

	<div id="cms-top" class="clearfix">

		<div class="ct-left left">

            <a href="<?php echo $GLOBALS['WEBSITE_NAME'];?>" title="" target="_blank">

            <img src="<?php echo $GLOBALS['UPLOAD_FOLDER'].$web_config['logo_image'];?>" alt="Client Logo Here" class="client-logo" /></a>			

            <span class="lu">&nbsp;</span>

		</div><!-- .ct-left -->

		<div class="ct-right">

		  &nbsp;&nbsp;

		</div><!-- .ct-right #ini logo nuke -->

	</div><!-- #cms-top -->

	

	<div id="cms-menu" class="clearfix">

		<?php show_top_menu(); ?>

		<a href="logout.php" title="Logout" class="logout-btn right">Logout</a>

	</div><!-- #cms-menu -->

   

   <?php if(isset($_GET['msgpre'])): 

			echo'<div style="padding-bottom:15px; color:#FF3300;">';

					echo $_GET['msgpre'];

			echo'</div>';

	 endif;

   ?>

