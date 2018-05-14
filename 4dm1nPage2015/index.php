<?php 
@session_start();
require('../config/nuke_library.php');

function generateFormToken($form) {
	$timeTg = date("YmdHms");
	$tokenIdFormall = $timeTg;
	$tokenIdForm = sha1($tokenIdFormall);
	$_SESSION[$form.'_token'] = $tokenIdForm;
	return $tokenIdForm;
}

function verifyFormToken($form) {
	if(!isset($_SESSION[$form.'_token'])) { 
		$statusform2 = 0;
		return $statusform2;
	}

	if(!isset($_POST['tokenId'])) {
		$statusform2 = 0;
		return $statusform2; 
	}

	if ($_SESSION[$form.'_token']!== $_POST['tokenId']) {
		$statusform2 = 0;
		return $statusform2; 
	}

	$statusform2 = 1;
	return $statusform2;
}


if(isset($_POST['submit'])){
	if(isset($_POST['login'])){
		require('lib/file_config1001.php');
		$Login = $_POST['login'];
		$TokenIdform = verifyFormToken('loginCTI20213Form');
		$user = $_POST['username'];
		$pass = $_POST['password'];
		if($TokenIdform==1){
			c110ekvalidasiuser($user,$pass);
		} else{
			$_SESSION['login_warning_text'] = 1;
			redirect($GLOBALS['ADMIN_LOGIN']);
		}
	}
}

$web_config = global_select_single("web_config", "*");
?>
<!DOCTYPE HTML>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]--><head>
<meta charset="UTF-8">
<meta name="copyright" content="Nukegraphic" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<title>Welcome to <?php echo $GLOBALS['SITE_NAME']; ?> - Content Management System</title>
	<link rel="shortcut icon" href="pavico.png" />
	<link href="<?php echo $ADMIN_URL;?>css/reset.css" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700' rel='stylesheet' type='text/css'>
	<link href="<?php echo $ADMIN_URL;?>css/login.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $ADMIN_URL;?>js/jquery-1.6.4.min.js" type="text/javascript"></script>
	<!--<script src="js/jquery.mobile-1.3.1.min.js" type="text/javascript"></script>-->
	<!--[if lte IE 8]>
		<script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
	<![endif]-->
</head>

<body>
<div id="login-area">
	<div id="login-top">
    	<h1><img src="<?php echo $GLOBALS['SITE_URL'];?>4dm1nPage2015/images/logo-nuke.png" class="cms-logo" /></h1>
        <a href="<?php echo $GLOBALS['WEBSITE_NAME'];?>" title="" target="_blank"><img src="<?php echo $GLOBALS['UPLOAD_FOLDER'].$web_config['logo_image'];?>" alt="" class="client-logo"/></a>
    </div><!-- #login-top -->
    <div id="login-mid">
    	<form action="" method="post" class="login-form">
            <input type="text" placeholder="username" class="i-user i-normal" name="username" maxlength="50" />                     
            <input type="password" placeholder="password" class="i-pass i-normal" maxlength="20" name="password" />
            <div class="wrap-error">
            	<?php if(isset($_SESSION['login_warning_text'])!=""): echo'Login Unsuccessful, <br />Please check username and password.'; endif;?>
            </div>

           <div class="login-btn clearfix">
            	 <?php $newToken = generateFormToken('loginCTI20213Form');?>
                 <input type="hidden" name="tokenId" value="<?php echo $newToken;?>" />
                 <input type="hidden" name="login" value="login" />           
            	<input type="submit" class="submit-btn left" name="submit" value="Login" />
            </div><!-- .login-btn -->
        </form><!-- .login-form -->
    </div><!-- #login-mid -->
    <div id="login-footer">
    	Copyright &copy; 2013 <a href="http://nukegraphic.com/" title="Nukegraphic" target="_blank">Nukegraphic</a>. All rights reserved.
    </div><!-- #login-footer -->
</div><!-- #login-area -->
</body>
</html>

<?php if(isset($_SESSION['flashdata'])) { ?>
	<!-- ALERTIFY -->
	<script src="js/alertifyjs/alertify.min.js"></script>
	<link rel="stylesheet" href="js/alertifyjs/css/alertify.min.css" />
	<link rel="stylesheet" href="js/alertifyjs/css/themes/default.min.css" />
	<script>
		$('document').ready(function(){
		alertify.dialog('<?php echo ($_SESSION['flashdata']['type'] == 'error' ? "alert":"confirm")?>').set({
			transition:'flipx',
			title:'<?php echo ($_SESSION['flashdata']['type'] == 'error' ? "Alert":"Confirm")?>',message: '<?php echo $_SESSION['flashdata']['message']?>'}).show();
	});

	</script>
	<?php 
	unset($_SESSION['flashdata']);
} ?>