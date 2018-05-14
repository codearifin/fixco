<?php
	include_once('scripts/jcart/jcart.php');
	@session_start();
	include('config/connection.php');
	include "include/function.php";
	include "config/myconfig.php";

	//error_reporting(E_ALL & E_NOTICE);
	if(isset($_SERVER['REQUEST_URI'])):
		$server_uri = $_SERVER['REQUEST_URI'];
	else:
		$server_uri = '';	
	endif;	
	
	$seoitem = seo_page_general($server_uri);	
	$seo_title = $seoitem['seo_title'];  $seo_keyword = $seoitem['seo_keyword']; $seo_description = $seoitem['seo_description']; 
	
	//update facebook BY RNT 12 may 17
	//login via facebook
	$login_url = ''.$SITE_URL.'facebook_login/login.php';		
		
	//google plus
	require_once('login_google/googlesdk/src/Google_Client.php');		
	require_once('login_google/googlesdk/src/contrib/Google_Oauth2Service.php');
	$client = new Google_Client();
	$client->setApplicationName("Google UserInfo PHP Starter Application");
	$oauth2 = new Google_Oauth2Service($client);
    $authUrl = $client->createAuthUrl();	
?>

<!DOCTYPE HTML>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="<?php echo $seo_keyword;?>">
<meta name="description" content="<?php echo $seo_description;?>">
<meta name="robots" content="all,index,follow">
<meta name="googlebot" content="all,index,follow">
<meta name="revisit-after" content="2 days">
<meta name="author" content="fixcomart.com">
<meta name="rating" content="general">
<meta property="og:title" content="<?php echo $seo_title;?>">
<meta property="og:image" content="">
<meta property="og:site_name" content="FixcoMart">
<meta property="og:description" content="<?php echo $seo_description;?>">
<meta property="og:url" content="<?php echo $GLOBALS['WEBSITE_NAME'];?>">
<link rel="icon" type="image/x-icon" href="<?php echo $GLOBALS['SITE_URL'];?>favicon.png">
<title><?php echo $seo_title;?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['SITE_URL'];?>js/owl-carousel/owl.carousel.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['SITE_URL'];?>js/raty/jquery.raty.css" media="screen" />
<!-- FANCYBOX -->
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['SITE_URL'];?>js/fancybox2/jquery.fancybox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['SITE_URL'];?>js/fancybox2/helpers/jquery.fancybox-buttons.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $GLOBALS['SITE_URL'];?>js/fancybox2/helpers/jquery.fancybox-thumbs.css" media="screen" />
<!-- JQUERY UI -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-ui/jquery-ui.structure.min.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-ui/jquery-ui.theme.min.css" rel="stylesheet">
<!-- AUTOCOMPLETE -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/autocomplete/styles.css" rel="stylesheet">
<!-- SLICK CAROUSEL -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/slick/slick.css" rel="stylesheet">
<!-- COUNTDOWN -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/countdown/jquery.countdown.css" rel="stylesheet">
<!-- JSSOCIALS -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/jssocials/jssocials.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['SITE_URL'];?>js/jssocials/jssocials-theme-flat.css" rel="stylesheet">
<!-- FIXCOMART -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>fonts/font.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['SITE_URL'];?>css/ecommerce.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['SITE_URL'];?>css/style.css" rel="stylesheet">

<!-- ADD RNT -->
<link href="<?php echo $GLOBALS['SITE_URL'];?>rnt_style.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $GLOBALS['SITE_URL'];?>js/sweet_alert/sweetalert.css">

<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?48oGeaDs5KvJ1wcsZlpSh5SIvRkB36tv";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->

<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-79732161-1', 'auto');
ga('send', 'pageview');

</script>
