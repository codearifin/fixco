function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function jumpage_admin(keycode, thisvalue, thisid){
	if((keycode >= 48 && keycode <= 57) || keycode == 8 || keycode == 46 || 
		keycode == 9 || (keycode >= 37 && keycode <= 40) || keycode == 13 || (keycode >= 96 && keycode <= 105)) {

    }else{
			 var actpage  = thisid;	       
			 alert("Sorry, Please enter numeric only.");
			 $("#"+actpage).val("");	
	}		
}

function pilihan() {
	$(".filed_pilih").click();		
}

function rupiah(objek, separator) {		 
		  var separator = ',';
		  a = objek.value;
		  b = a.replace(/[^\d]/g,"");
		  c = "";
		  panjang = b.length;
		  j = 0;
		  for (i = panjang; i > 0; i--) {
			j = j + 1;
			if (((j % 3) == 1) && (j != 1)) {
			  c = b.substr(i-1,1) + separator + c;
			} else {
			  c = b.substr(i-1,1) + c;
			}
		  }
		  objek.value = c; 	
 }


function overlay_msg(msg,icon){
	var opts = {
		lines: 13, // The number of lines to draw
		length: 11, // The length of each line
		width: 5, // The line thickness
		radius: 17, // The radius of the inner circle
		corners: 1, // Corner roundness (0..1)
		rotate: 0, // The rotation offset
		color: '#FFF', // #rgb or #rrggbb
		speed: 1, // Rounds per second
		trail: 60, // Afterglow percentage
		shadow: false, // Whether to render a shadow
		hwaccel: false, // Whether to use hardware acceleration
		className: 'spinner', // The CSS class to assign to the spinner
		zIndex: 2e9, // The z-index (defaults to 2000000000)
		top: 'auto', // Top position relative to parent in px
		left: 'auto' // Left position relative to parent in px
	};
	var target = document.createElement("div");
	document.body.appendChild(target);
	var spinner = new Spinner(opts).spin(target);
	var overlay = iosOverlay({
		text: "Loading",
		spinner: spinner
	});

	window.setTimeout(function() {
		overlay.update({
			icon: icon,
			text: msg
		});
	}, 3e3);

	window.setTimeout(function() {
		overlay.hide();
	}, 5e3);

	return false;
}

$(document).ready(function() { 
	
	//RNT
	$("#subcategory").chained("#category");
	$("#sub_level_category").chained("#subcategory");
	$("#store_city").chained("#store_province");
	$("#listing_kota").chained("#master_lokasi");
	
	 
	$('.clearme').focus(
		function() {
			if (this.value == this.defaultValue) {
				this.value = '';
			}
		}
	);
	$('.clearme').blur(
		function() {
			if (this.value == '') {
				this.value = this.defaultValue;
			}
		}
	);
	
	// FANCYBOX
	$(".nuke-fancied").fancybox();
	
	$(".nuke-fancied2").fancybox({
		padding: 0,
		type: "ajax"
	});
	
	jQuery.validator.addMethod("phonenumbernumeric", function(value, element) {
	//    return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
	return this.optional(element) || /^[0-9 +-]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 

	jQuery.validator.addMethod("angkaformat", function(value, element) {
	//    return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
	return this.optional(element) || /^[0-9]+$/i.test(value);
		}, "Letters, numbers or underscores only");	

	//change pass
	$('form#change_passform').validate({
	  rules: {
			dash_newpass:{
				required: true,
				minlength: 5,
			},
			dash_cpass:{
				required: true,
				equalTo: "#dash_newpass"
			}
			
	  },
	  messages: {
			dash_newpass:{
				required:"* This field can't be empty.",
				minlength:"* Enter 5 or more characters long"
			},
			dash_cpass:{
				required:"* This field can't be empty.",
				equalTo: "* Confirm password not same with password"	
			}		
	  },
	  errorPlacement: function(error, element){
		//error.insertBefore(element);
		error.insertAfter(element);
	  },
	   submitHandler:function(form){		
			  $(".imgload").removeAttr("style");		 
			  var formData = new FormData($("form#change_passform")[0]);
			  $.ajax({
				  url:'lib/user-pasword-save.php',
				  type:'POST',
				  data:formData,
				  dataType:'HTML',
				  contentType:false,
				  processData:false,
				  cache:false,
				  success:function(html){
					  $(".imgload").attr("style","display:none;");	
					  if($.trim(html)==200){
							overlay_msg("Success!","js/overlay-js/check.png");
							$("form#change_passform").find('input:password').val("");
					  }else{
							overlay_msg("Error!","js/overlay-js/cross.png");
							$("form#change_passform").find('input:password').val("");						
					  }  
				}
			  });			  
	  
	   }
	});	
	
	
	//add user form
	$('form#adduser_form').validate({
	  rules: {
			name:{ 
				 required: true
			},
			name_member:{ 
				 required: true
			},
			dash_newpass:{
				required: true,
				minlength: 5,
			},
			dash_cpass:{
				required: true,
				equalTo: "#dash_newpass"
			}
			
	  },
	  messages: {
			name:{ 
				 required:"* This field can't be empty."
			},		  
			name_member: {
				required:"* This field can't be empty."
			},
			dash_newpass:{
				required:"* This field can't be empty.",
				minlength:"* Enter 5 or more characters long"
			},
			dash_cpass:{
				required:"* This field can't be empty.",
				equalTo: "* Confirm password not same with password"	
			}		
	  },
	  errorPlacement: function(error, element){
		//error.insertBefore(element);
		error.insertAfter(element);
	  },
	   submitHandler:function(form){	
			  $(".imgload").removeAttr("style");	
			  var formData = new FormData($("form#adduser_form")[0]);
			  $.ajax({
				  url:'lib/user-add-action.php',
				  type:'POST',
				  data:formData,
				  dataType:'HTML',
				  contentType:false,
				  processData:false,
				  cache:false,
				  success:function(html){
					  $(".imgload").attr("style","display:none;");	
					  if($.trim(html)==200){
							overlay_msg("Success!","js/overlay-js/check.png");
							$("form#adduser_form").find('input:password,input:text').val("");	
							$("form#adduser_form").find('input:checkbox').removeAttr("checked");	
					  }else{
							overlay_msg("Error!<br />Username exist.","js/overlay-js/cross.png");
							$("form#adduser_form").find('input:password,input:text').val("");	
							$("form#adduser_form").find('input:checkbox').removeAttr("checked");	
					  }  
				}
			  });			  
	  
	   }
	});	
		

	//edit user
	$('form#change_passformepass2').validate({
	  rules: {
			name:{
				required: true
			},
			old_password:{ 
				 required: true
			},
			dash_newpass:{
				required: true,
				minlength: 5,
			},
			dash_cpass:{
				required: true,
				equalTo: "#dash_newpass"
			}
	  },
	  messages: {
			name:{
				required:"* This field can't be empty."
			}, 
			old_password: {
				required:"* This field can't be empty."
			},
			dash_newpass:{
				required:"* This field can't be empty.",
				minlength:"* Enter 5 or more characters long"
			},
			dash_cpass:{
				required:"* This field can't be empty.",
				equalTo: "* Confirm password not same with password"	
			}		
	  },
	  errorPlacement: function(error, element){
		//error.insertBefore(element);
		error.insertAfter(element);
	  },
	   submitHandler:function(form){	
			  $(".imgload").removeAttr("style");	
			  var formData = new FormData($("form#change_passformepass2")[0]);
			  $.ajax({
				  url:'lib/user-edit-action.php',
				  type:'POST',
				  data:formData,
				  dataType:'HTML',
				  contentType:false,
				  processData:false,
				  cache:false,
				  success:function(html){
					  $(".imgload").attr("style","display:none;");	
					  if($.trim(html)==200){
							overlay_msg("Success!","js/overlay-js/check.png");
							$("form#change_passformepass2").find('input:password').val("");
							var count = 4;
							countdown = setInterval(function(){					
							if(count==0) { window.location.replace("user-view.php"); clearInterval(countdown);}
							 if(count>0) {count--;}
							}, 1000);								
					  }else{
							overlay_msg("Error!<br />Please contact your Administrator","js/overlay-js/cross.png");
							$("form#change_passformepass2").find('input:password').val("");								
					  }  
				}
			  });			  
	  
	   }
	});
	
	$("a.del_admin").click(function(e) {
		var idadd=$(this).attr("id");
		if(confirm("Are you sure want to delete this records?")){
				$.post("lib/delete_admin.php", {"idpage": idadd},
				 function(data) {
					if(data==1){
						$("#wrap-user-"+idadd).fadeOut();	
					}else{
						alert("Delete unsuccessful, Please try again!");	
					} 
				}); 
		}
		return false;
		e.preventDefault();	 
	});		
	
	$("a.loadbtn").click(function(e) {
			var actpage = $(".jumpage").attr("id");
			var jumpage = $(".jumpage").val();
			var urelid_page = $("#urelid_page").val();
			
			if(jumpage>0){
				$.post("lib/setsession_page.php", {"jumpage": jumpage, "act": actpage},
				function(data) {
					//window.location.replace(urelid_page); 	
					window.location.reload();
				}); 
			}else{
				alert("Sorry, Please enter numeric only.");	
			}	
	
		return false;
		e.preventDefault();	 
	});			
	
	$("a#bulk_deleteid").click(function(e) {
		if(confirm("Are you sure want to delete this records?")){
			$(".submit_bulk").click();	
		}
		return false;
		e.preventDefault();	 
	});

	$("a#bulk_exportid").click(function(e) {
		if(confirm("Are you sure want to export this records?")){
			$(".submit_export").click();	
		}
		return false;
		e.preventDefault();	 
	});

	$("a#bulk_printid").click(function(e) {
		if(confirm("Are you sure want to print this records?")){
			$(".submit_print").click();	
		}
		return false;
		e.preventDefault();
	});


	table_1 = $(".drag_table");
	table_1.tableDnD({
	   onDragClass: "myDragClass",
	   onDrop: function(table, row) {
			var actpageID = $("#actpageID").val(); 
			var MaxSortID = $("#MaxSortID").val();
			var itemid = "";
			
			var rows = table.tBodies[0].rows;
			for (var i=0; i<rows.length; i++) {
				itemid +=rows[i].id+"-";		
			}	
			$.post("lib/update_sortable.php", {"actpageID": actpageID, "itemid": itemid, "MaxSortID": MaxSortID}, function(data) { location.reload(); });
			
			
	   }
			
	});

	table_1 = $(".drag_tabledesc");
	table_1.tableDnD({
	   onDragClass: "myDragClass",
	   onDrop: function(table, row) {
			var actpageID = $("#actpageID").val(); 
			var MaxSortID = $("#MaxSortID").val();
			
			var itemid = "";
			var rows = table.tBodies[0].rows;
			
			for (var i=0; i<rows.length; i++) {
				itemid +=rows[i].id+"-";		
			}

			$.post("lib/update_sortabledesc.php", {"actpageID": actpageID, "itemid": itemid, "MaxSortID": MaxSortID},
			function(data) {}); 
	   }
			
	});
	

	$('form.general_forminput').validate({
	  rules: {
			name:{ 
				 required: true
			},
			desctext:{
				required: true
			},			
			images:{
				required: true
			}
			
	  },
	  messages: {
			name: {
				required:"* This field can't be empty."
			},
			desctext:{
				required:"* This field can't be empty."
			},				
			images:{
				required:"* This field can't be empty."
			}		
	  },
	  errorPlacement: function(error, element){
		//error.insertBefore(element);
		error.insertAfter(element);
	  },
	  submitHandler:function(form){
			form.submit();  
	   }
	});	
	

	$("a.delete_btnall").click(function(e) {
		var idadd 				= $(this).attr("id");
		var itemExplode 	= idadd.split("-");
		var idpage 				= itemExplode[1];
		var actpage 			= itemExplode[2];
		var upload_folder	= itemExplode[3];

		if(confirm("Are you sure want to delete this records?")){
				$.post("lib/delete_single.php", {"idpage": idpage, "actpage": actpage, "upload_folder": upload_folder},
				 function(data) {				 
					if(data==1){
						$("#"+idpage).fadeOut();
						location.reload();
					}else{
						alert("Delete unsuccessful, Please try again!");	
					} 

				}); 
		}
		return false;
		e.preventDefault();	 
	});

	if($(".cms-sidebar").length <= 0) {
		$(".cms-main-content").addClass("full");
	}

	$(".revoslide-popup").click(function(){
		var slideid 		= $(this).attr("slideid");
		var tableName 	= $(this).attr("tableName");
		
		location.href = 'revoslide/revoslide.php?id='+slideid+'&table_name='+tableName;
		/*$.fancybox.open({
		    padding : 0,
		    href: 'revoslide/revoslide.php?id='+slideid,
		    type: 'iframe',
		    fitToView: false,
		    beforeShow: function () {
		        this.width = 1050;
		        this.height = 1000;
		    }
		});*/
	});

});