<div class="overlay-all"></div>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-ui/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/modernizr.js" type="text/javascript"></script>
<script type='text/javascript'>
  Modernizr.addTest('firefox', typeof InstallTrigger !== 'undefined')
  Modernizr.addTest('ie', (navigator.appName == 'Microsoft Internet Explorer') || (Object.hasOwnProperty.call(window, "ActiveXObject") && !window.ActiveXObject))
</script>
<!--[if lte IE 8]>
	<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-1.11.1.min.js" type="text/javascript"></script>
<![endif]-->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
<!--[if IE]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery-migrate-1.2.1.min.js"></script>
<!-- FANCYBOX -->
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/fancybox2/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/fancybox2/helpers/jquery.fancybox-media.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/fancybox2/helpers/jquery.fancybox-thumbs.js"></script>
<!-- OWL CAROUSEL -->
<script type="text/javascript" language="javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/owl-carousel/owl.carousel.min.js"></script>
<!-- FLEXSLIDER -->
<script type="text/javascript" language="javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery.flexslider-min.js"></script>
<!-- LAZYLOAD -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/lazysizes.min.js" async></script>
<!-- RESPONSIVE IMAGE PLUGIN  -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/respimage.min.js" async></script>
<!-- isotope  -->
<script type="text/javascript" src="<?php echo $GLOBALS['SITE_URL'];?>js/isotope.pkgd.min.js"></script>
<!-- ELEVATE ZOOM -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery.elevateZoom-3.0.8.min.js"></script>
<!-- RATY -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/raty/jquery.raty.js"></script>
<!-- CLIPBOARD -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/clipboard/clipboard.min.js"></script>

<!-- AUTOCOMPLETE -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/autocomplete/jquery.autocomplete.min.js"></script>
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/autocomplete/searchresult.js"></script>

<!-- SLICK CAROUSEL -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/slick/slick.min.js" type="text/javascript"></script>

<!-- COUNTDOWN -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/countdown/jquery.countdown.min.js" type="text/javascript"></script>
<!-- JSSOCIALS -->
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jssocials/jssocials.min.js" type="text/javascript"></script>

<!-- FIXCO JS -->
<script type='text/javascript' src='<?php echo $GLOBALS['SITE_URL'];?>ecommerce.js'></script>
<script type='text/javascript' src='<?php echo $GLOBALS['SITE_URL'];?>fixco.js'></script>

<script>
	$(window).on('scroll', function() {
		var scrollTop = $(this).scrollTop();
		
		if ( scrollTop > 50 ) {
			// do something
			$("header").addClass("stuck");
		} else {
			$("header").removeClass("stuck");
		}
	});
</script>

<script>
	$(document).ready(function() {
		$zopim(function() {
	      $zopim.livechat.window.setOffsetVertical(52);
		});
	});
</script>

<script src="<?php echo $GLOBALS['SITE_URL'];?>js/sweet_alert/sweetalert-dev.js"></script>
<script src="<?php echo $GLOBALS['SITE_URL'];?>js/jquery.validate.js"></script>
<script type='text/javascript' src='<?php echo $GLOBALS['SITE_URL'];?>rnt_ajax.js'></script>

<?php include("include/pupup_msg_notif.php");?>