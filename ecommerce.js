$(document).ready(function() {
	// ALAMAT TOGGLE
	$(".first-child .aac-child").show();
	$(".aa-toggle").click(function(e) {
		if($(this).hasClass("opened")) {
			$(this).removeClass("opened");
			$(this).next(".aac-child").slideUp("fast");
		} else {
			$(".aa-toggle").removeClass("opened");
			$(".aac-child").slideUp("fast");
			$(this).addClass("opened");
			$(this).next(".aac-child").slideDown("fast");
		}
		e.preventDefault();
	});
	
	// PAYMENT TOGGLE
	$(".first-child .pac-child").show();
	$(".pa-toggle").click(function(e) {
		if($(this).hasClass("opened")) {
			$(this).removeClass("opened");
			$(this).next(".pac-child").slideUp("fast");
		} else {
			$(".pa-toggle").removeClass("opened");
			$(".pac-child").slideUp("fast");
			$(this).addClass("opened");
			$(this).next(".pac-child").slideDown("fast");
		}
		e.preventDefault();
	});
	
});