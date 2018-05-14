(function($){
	"use strict";

	var toggles = document.querySelectorAll(".c-hamburger");
	
	for (var i = toggles.length - 1; i >= 0; i--) {
	var toggle = toggles[i];
	toggleHandler(toggle);
	};
	
	function toggleHandler(toggle) {
	toggle.addEventListener( "click", function(e) {
	  e.preventDefault();
	  (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
	});
	}
  
	function fixH3Heights() {
		var heights = new Array();
        
		// Loop to get all element heights
		$('.ctab-content-prod h3').each(function() {	
			// Need to let sizes be whatever they want so no overflow on resize
			$(this).css('min-height', '0');
			$(this).css('max-height', 'none');
			$(this).css('height', 'auto');

			// Then add size (no units) to array
	 		heights.push($(this).height());
		});

		// Find max height of all elements
		var max = Math.max.apply( Math, heights );

		// Set all heights to max height
		$('.ctab-content-prod h3').each(function() {
			$(this).css('height', max);
		});	
	}

	$(window).load(function() {
		// Fix heights on page load
		fixH3Heights();
		// Fix heights on window resize
		$(window).resize(function() {
			// Needs to be a timeout function so it doesn't fire every ms of resize
			setTimeout(function() {
	      		fixH3Heights();
			}, 120);
		});
	});
})(jQuery);

$(document).ready(function() { // execute when window open
	$(document).on('focus','.clearme', function(){
		if (this.value === this.defaultValue) {
			this.value = '';
		}
	});
	$(document).on('blur','.clearme', function(){
		if (this.value === '') {
			this.value = this.defaultValue;
		}
	});
	
	// FANCYBOX
	$(".nuke-fancied").fancybox({
		openEffect: "elastic"
	});
	$(".nuke-fancied2").fancybox({
		padding: 0,
		type: "ajax"
	});
	$(".photo-fancied").fancybox({
		openEffect: "elastic",
		closeEffect: "elastic"
	});
	$(".nuke-fancied-safe").fancybox({
		padding: 0,
		type: "ajax",
		helpers : { 
		  overlay : {closeClick: false}
		}
	});
	$(".fancy-video").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		padding: 40,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	
	// HEADER TOP LINK CLICK
	$(document).on('click','.ht', function(e){
		if($(this).hasClass("opened")) {
			$(".ht").removeClass("opened");
			$(".ht-hidden").removeClass("opened");
		} else {
			$(".ht").removeClass("opened");
			$(".ht-hidden").removeClass("opened");
			$(this).addClass('opened');
			$(this).next(".ht-hidden").addClass("opened");
		}
		e.preventDefault();
	});
	
	// FLEXSLIDER
	$('#desktop-flex').flexslider({
		animation: "slide",
		slideshow: true,
		slideshowSpeed: 6000,
		animationDuration: 500,
		pauseOnAction: false,
		pauseOnHover: true,
		animationLoop: true,
		directionNav: true,
		start: function(){
			$('.flexImages').fadeIn("fast", function() {
				$(this).css({"display":"block"});	
			}); 
		}
	});
	
	$(".owl-ctab").owlCarousel({
		autoPlay: 5000,
		items : 5,
		itemsDesktop : [1190,4],
		itemsDesktopSmall : [990,3],
		itemsTablet: [900,3],
		itemsTabletSmall : [640, 2],
        itemsMobile : [360, 1],
		navigation: true,
		pagination: false
	});
	$(".iec-carousel").owlCarousel({
		autoPlay: 5000,
		items : 1,
		itemsDesktop : [1190,1],
		itemsDesktopSmall : [990,1],
		itemsTablet: [900,1],
		itemsTabletSmall : [640, 1],
        itemsMobile : [360, 1],
		navigation: true,
		pagination: false
	});
	
	// SPECIAL TAB 
	$(".custom-tabbing a").click(function(e) {
		var dID = $(this).attr("href"); //get href
		$(".custom-tab").hide();
		$(dID).fadeIn();
		$(".custom-tabbing a, .custom-tab").removeClass("active");
		$(this).addClass("active");
		$(dID).addClass("active");
		e.preventDefault();
	});
	
	// INDEX ELEVATOR
	$(".iec-scroll").click(function(e) {
		var $this = $(this);
		var getID = $this.attr("href");
		if($(window).width() < 992) {
			var xHeader = 60;
		} else if($(window).width() > 991 && $(window).width() < 1200) {
			var xHeader = 110;
		} else {
			var xHeader = 130;	
		}
		$('html, body').stop(true).animate({
			scrollTop: $(getID).offset().top  - xHeader
		}, 600);
		e.preventDefault();
	});
	
	// MOBILE MENU DROPDOWN
	$(".mobile-nav a.has-sub").click(function(e){
		if($(this).hasClass("opened")) {
			$(this).removeClass('opened');
			$(this).next("ul").slideUp("fast");
		} else {
			$(this).addClass('opened');
			$(this).next("ul").slideDown("fast");
		}
		e.preventDefault();
	});
	$(document).on('click','.c-hamburger', function(e){
		if($(".mobile-nav-wrap").hasClass("opened")) {
			$(".mobile-nav-wrap").removeClass("opened");
		} else {
			$(".mobile-nav-wrap").addClass('opened');
		}
		$(".btn-user-area").removeClass("opened");
		e.preventDefault();
	});
	
	$('.aps-mason').isotope({
		// options
		itemSelector: '.apsc'
	});
	
	$(document).on('click','.all-cat', function(e){
		if($(this).hasClass("opened")) { // jika yang diklik lagi kebuka
			$(this).removeClass("opened");
			$(".allprod-nav-wrap").removeClass("opened");
		} else { // jika yang diklik belum kebuka
			$(this).addClass('opened'); 
			$(".allprod-nav-wrap").addClass("opened");
		}
		e.preventDefault();
	});
	$(document).on('click','.show-allprod-subnav', function(e){
		if($(this).hasClass("opened")) { // jika yang diklik lagi kebuka
			$(this).removeClass("opened");
			$(this).next(".allprod-subnav").removeClass("opened");
		} else { // jika yang diklik belum kebuka
			$(".show-allprod-subnav, .allprod-subnav").removeClass("opened");
			$(this).addClass('opened'); 
			$(this).next(".allprod-subnav").addClass("opened");
		}
		e.preventDefault();
	});
	$(document).on('click','#main-navigation a.has-sub', function(e){
		if($(this).hasClass("opened")) { // jika yang diklik lagi kebuka
			$(this).removeClass("opened");
			$(this).next(".cat-subnav-wrap").removeClass("opened");
		} else { // jika yang diklik belum kebuka
			$("#main-navigation a.has-sub, .cat-subnav-wrap").removeClass("opened");
			$(this).addClass('opened'); 
			$(this).next(".cat-subnav-wrap").addClass("opened");
		}
		e.preventDefault();
	});
	
	// GENERAL ACCORDION
	$('.general_accor h3.opened').next(".ga_content").show();
	$('.general_accor h3').on('click', function(e){
		if(!$(this).hasClass('opened')) {
			//it means navigation is not visible yet - open it and animate navigation layer
			$(this).addClass('opened');
			$(this).next(".ga_content").slideDown("fast");
			$(window).resize();
		} else {
			//animate cross icon into a menu icon
			$(this).removeClass('opened');
			$(this).next(".ga_content").slideUp("fast");
		}
		e.preventDefault();
	});
	
	// RATY
	$('div.prod-raty').raty({
	  score: function() {
		return $(this).attr('data-score');
	  },
	  path: 'images',
	  readOnly: true,
	  noRatedMsg : "Not rated yet!"
	});
	
	$('.raty-rating').raty({
	  path: 'images',
	  noRatedMsg : "Not rated yet!"
	}); 
	
	$(document).on('click','.check-toggle', function(e){
		if($(this).hasClass("opened")) {
			$(this).removeClass("opened");
			$(this).prev(".hidden").slideUp("fast");
			$(this).text("Selengkapnya");
		} else {
			$(this).addClass('opened');
			$(this).prev(".hidden").slideDown("fast");
			$(this).text("Sembunyikan");
		}
		e.preventDefault();
	});
	
	// TOGGLE FILTER
	$(".filter-button").click(function(e){
		$("#product-sidebar, .overlay-all").addClass('opened');
		e.preventDefault();
	});
	$(".close-filter").click(function(e){
		$("#product-sidebar, .overlay-all").removeClass('opened');
		e.preventDefault();
	});
	$(document).on('click','.overlay-all', function(e){
		if($("#product-sidebar").hasClass("opened")) {
			$("#product-sidebar, .overlay-all").removeClass('opened');
		} else {
			//
		}
		e.preventDefault();
	});
	
	$(document).on('click','.psc-toggle', function(e){
		if($(this).hasClass("closed")) {
			$(this).removeClass('closed');
			$(this).next(".psc").slideDown("fast");
		} else {
			$(this).addClass('closed');
			$(this).next(".psc").slideUp("fast");
		}
		e.preventDefault();
	});

	// autocomplete
	// Initialize autocomplete with custom appendTo:
	var searchresultsArray = $.map(results, function (value, key) { return { value: value, data: key }; });
    $('#autocomplete-desktop').autocomplete({
        lookup: searchresultsArray,
        appendTo: ".search-form"
    });
    $('#autocomplete-mobile').autocomplete({
        lookup: searchresultsArray,
        appendTo: ".mobile-search-form"
    });

    // DESKTOP SEARCH ON LOAD
	var getSelected = $(".hidden-select-desktop option:selected").text();
	$(".selected-cat span.text").text(getSelected);
	// DESKTOP SEARCH ON CHANGE
	$(".hidden-select-desktop").change(function() {
    	var getText = $(this).find("option:selected").text();
    	$(".selected-cat span.text").text(getText);
    });

    $('.below-banner-carousel .wrap').slick({
		dots: true,
		arrows: false,
		slidesToShow: 4,
		slidesToScroll: 1,
		infinite: true,
		responsive: [
		    {
		      breakpoint: 768,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 1
		      }
		    },
		    {
		      breakpoint: 414,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		    // You can unslick at a given breakpoint now by adding:
		    // settings: "unslick"
		    // instead of a settings object
		]
 	});

 	// added 16 mei 2017
 	$(document).on('click','.toggle-user-menu', function(e){
		if($(this).parent(".btn-user-area").hasClass("opened")) {
			$(this).parent(".btn-user-area").removeClass('opened');
		} else {
			$(this).parent(".btn-user-area").addClass('opened');
		}
		$(".mobile-nav-wrap").removeClass("opened");
		$(".c-hamburger").removeClass("is-active");
		e.preventDefault();
	});
	$(".user-menu a.has-sub").click(function(e){
		if($(this).hasClass("opened")) {
			$(this).removeClass('opened');
			$(this).next("ul").slideUp("fast");
		} else {
			$(this).addClass('opened');
			$(this).next("ul").slideDown("fast");
		}
		e.preventDefault();
	});

	// added 27 feb 2018
	$(".owl-flashtab").owlCarousel({
		autoPlay: 5000,
		items : 5,
		itemsDesktop : [1190,5],
		itemsDesktopSmall : [990,4],
		itemsTablet: [760,3],
		itemsTabletSmall : [540, 2],
        itemsMobile : [300, 1],
		navigation: false,
		pagination: true
	});

	// featured carousel
	$('.featured-carousel').slick({
		dots: true,
		arrows: false,
		slidesToShow: 5,
		slidesToScroll: 2,
		infinite: true,
		adaptiveHeight: true,
		autoplay: true,
  		autoplaySpeed: 5000,
		responsive: [
		    {
		      breakpoint: 1200,
		      settings: {
		        slidesToShow: 4
		      }
		    },
		    {
		      breakpoint: 768,
		      settings: {
		        slidesToShow: 3
		      }
		    },
		    {
		      breakpoint: 640,
		      settings: {
		        slidesToShow: 2
		      }
		    },
		    {
		      breakpoint: 300,
		      settings: {
		        slidesToShow: 1
		      }
		    }
		]
	});

	// ngc tabs
	$(document).on('click','.ngc-tabs a', function(e){
		var activeURL = $(this).attr("href");
		if( $(this).parent(".ngc-tabs").hasClass("clicked") ) {
			// tab dropdown lagi terbuka
			$(this).parent(".ngc-tabs").removeClass("clicked");
			$(this).parent(".ngc-tabs").find("a").removeClass("active");
			$(this).parents(".ngc-tab-wrap").find(".ngc-tab-container").removeClass("active");
			$(this).addClass("active");
			$(activeURL).addClass("active");
		} else {
			// tab dropdown masih tertutup
			if( $(this).hasClass("active") ) {
				$(".ngc-tabs").addClass("clicked");
			} else {
				$(this).parent(".ngc-tabs").find("a").removeClass("active");
				$(this).parents(".ngc-tab-wrap").find(".ngc-tab-container").removeClass("active");
				$(this).addClass("active");
				$(activeURL).addClass("active");
			}
		}
		e.preventDefault();
	});
	$(document).on('click','.prod-tab-wrap .ngc-tabs a', function(e){
		$('.tab-carousel-wrap .featured-carousel').slick("setPosition", 0);
		e.preventDefault();
	});

	// floor carousel
	$('.pfb-carousel').slick({
		dots: true,
		arrows: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		infinite: true,
		adaptiveHeight: true,
		autoplay: true,
  		autoplaySpeed: 5000
	});

	$('.pfb-content-carousel').slick({
		dots: false,
		arrows: false,
		slidesToShow: 4,
		slidesToScroll: 1,
		infinite: true,
		adaptiveHeight: true,
		autoplay: true,
  		autoplaySpeed: 5000,
		responsive: [
		    {
		      breakpoint: 992,
		      settings: {
		        slidesToShow: 3
		      }
		    },
		    {
		      breakpoint: 768,
		      settings: {
		        slidesToShow: 2
		      }
		    },
		    {
		      breakpoint: 540,
		      settings: {
		        slidesToShow: 1
		      }
		    }
		]
	});
});

$(window).load(function() {
	$("#zoom_01").elevateZoom({
		responsive:'true',
		gallery:'gallery_01',
		cursor: 'pointer',
		zoomType : "inner",
		galleryActiveClass: 'active',
		imageCrossfade: true,
		zoomWindowOffetx:35,
		zoomWindowOffety:0,
		borderSize:0,
		zoomWindowFadeIn: 300, zoomWindowFadeOut: 30, lensFadeIn: 300, lensFadeOut: 300,
		loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'
	});
});
