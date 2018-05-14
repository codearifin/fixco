function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

function cekstockdata(keycode, thisvalue, thisid){
	if((keycode >= 48 && keycode <= 57) || keycode == 8 || keycode == 46 || keycode == 9 || (keycode >= 37 && keycode <= 40) || keycode == 13 || (keycode >= 96 && keycode <= 105) ) {	
		 var qtyprd = thisvalue;
		 $(".qty_prodlist").val(qtyprd);
	}else{
		$(".qty_prodlist").val(1);
	}	
}

function formatAngka(objek, separator) {		 
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

function rupiahtext(number) {
	number = '' + number;
	if (number.length > 3) {
	var mod = number.length % 3;
	var output = (mod > 0 ? (number.substring(0,mod)) : '');
	for (i=0 ; i < Math.floor(number.length / 3); i++) {
		if ((mod == 0) && (i == 0))
			output += number.substring(mod+ 3 * i, mod + 3 * i + 3);
		else
			output+= ',' + number.substring(mod + 3 * i, mod + 3 * i + 3);
		}
	return (output);
	}
	else return number;
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

function rupiahCekWithdrawal(objek, separator) {		 
		  var url_siteid = $(".url_siteid").val();	
		  
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
		
		  $.post(""+url_siteid+"include/cek_validasi_bonus.php", {"total_claim": c},
		  function(data){
			var testhasil = parseInt(data);  	
			if(testhasil==99){
				swal("ERROR!", "Silahkan cek kembali Total Komisi Anda.", "error");
				$(".totalamount").val(0);
			}
			
		  });			  
 
}


function update_qtyJcart(objek, separator, id) {		 
		  var url_siteid = $(".url_siteid").val();	
		  var separator = '';
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
          objek.value = c; var totalqtybaru = c;
			
		 var itemid = objek.id;
		 var itemExplode = itemid.split("-");
		 var idprod = itemExplode[1];
		 var qtylama = $("#totalQtyitem-"+idprod).val();
		
		if(totalqtybaru>0){
			if(totalqtybaru<qtylama){
						//kurang data
						$.post(""+url_siteid+"include/add_proditem.php", {"idprod": idprod, "newqtyprod": totalqtybaru},
							function(data){
								if(data==200){
									window.location.reload(); 	
								}else{
									swal("ERROR!", "Please refresh your computer and try again.", "error");
								}	
						});	
						
			}else if(totalqtybaru>qtylama){
					//tambah data
					$.post(""+url_siteid+"include/add_proditem.php", {"idprod": idprod, "newqtyprod": totalqtybaru},
						function(data){
							if(data==200){
								window.location.reload(); 	
							}else{
								swal("ERROR!", "Please refresh your computer and try again.", "error");
							}
					});	 				
			}
			
		 }else if(totalqtybaru<1 && totalqtybaru!=""){
			objek.value = qtylama;	 
		 }
}

$(document).ready(function() {

	$("a.bayar_btndd").click(function(e){	
	     var bank_transferid = $("#bank_transferid").val();
		 var termconditionid = $("#termconditionid").val();
		 if(bank_transferid==1 && termconditionid==1){
			$(".saveorder_load").removeAttr("style");
			$(".bayar_btndd").attr("style","display:none;");
			$("#btn_submitorder").click();
		 }else{
			alert("Please select a payment method and make sure you agree to the terms & conditions apply.");  
		 }
		 e.preventDefault();	
    });	

	//corporate val
	$("a.bayar_btndd_cop").click(function(e){	
	     var bank_transferid = $("#bank_transferid").val();
		 var termconditionid = $("#termconditionid").val();
		 if(bank_transferid==1 && termconditionid==1){
			$(".quote_token").val(0);    	
			$(".saveorder_load").removeAttr("style");
			$(".bayar_btndd_cop, .smpn_quote").attr("style","display:none;");
			$("#btn_submitorder").click();
		 }else{
			alert("Please select a payment method and make sure you agree to the terms & conditions apply.");  
		 }
		 e.preventDefault();	
    });	
	
	$("a.smpn_quote").click(function(e){	
	     var termconditionid = $("#termconditionid").val();
		 if(termconditionid==1){
			$(".quote_token").val(1);  	
			$(".saveorder_load").removeAttr("style");
			$(".bayar_btndd_cop, .smpn_quote").attr("style","display:none;");
			$("#btn_submitorder").click();
		 }else{
			alert("Please make sure you agree to the terms & conditions apply.");  
		 }
		 e.preventDefault();	
    });	
	
	
	$(".banktransfer").click(function(){
		if ($(this).is(':checked')) {	
			$("#bank_transferid").val(1);		
		}else{
			$("#bank_transferid").val(0);		
		}
	});

	$(".termchecklist").click(function(){
		if ($(this).is(':checked')) {							   	
			$("#termconditionid").val(1);	
		}else{
			$("#termconditionid").val(0);	
		}	
	});
	
	$(".addressmember-list").click(function(){								
		var url_siteid = $(".url_siteid").val();	
		$(".selectshipporder").removeAttr("style");												
		var notememberlist = $(".notemember").val();
		var kurir_lainnya = $(".kurir_lainnya").val();
		
		$.post(""+url_siteid+"include/unset_idabookmember.php", {"notememberlist": notememberlist, "kurir_lainnya": kurir_lainnya},
		function(data){
			window.location.reload();		
		});
	});

	$(".addressmember-listabook").click(function(){
			var url_siteid = $(".url_siteid").val();	
			$(".selectshipporder").removeAttr("style");	
			var itemid = $(this).attr("id");
			var itemExplode = itemid.split("-");
			var idabook = itemExplode[1];
		    var notememberlist = $(".notemember").val();
			var kurir_lainnya = $(".kurir_lainnya").val();
			
			$.post(""+url_siteid+"include/set_idabookmember.php", {"idabook": idabook, "notememberlist": notememberlist, "kurir_lainnya": kurir_lainnya},
			function(data){
				window.location.reload();		
			});
		
	});

	$(".addressmemberlistdraf").click(function(){
		var url_siteid = $(".url_siteid").val();	
		$(".selectshipporder").removeAttr("style");												
		var notememberlist = $(".notemember").val();
		var kurir_lainnya = $(".kurir_lainnya").val();
		
		$.post(""+url_siteid+"include/set_idaddress_draf.php", {"notememberlist": notememberlist, "kurir_lainnya": kurir_lainnya},
		function(data){
			window.location.reload();		
		});
	});
	
	$(".remove-item").click(function(e){
			 var url_siteid = $(".url_siteid").val();						 	
			 var itemid = $(this).attr("id");
			 var itemExplode = itemid.split("-");
			 var idprod = itemExplode[0];

			if(confirm("Are you sure want to delete this records?")){
				$.post(""+url_siteid+"include/delete_prd.php", {"idprod": idprod},
					function(data){
					if(data==200){
						window.location.reload();	
					}else{
						swal("ERROR!", "Please refresh your computer and try again.", "error");
					}
				});				
			}
			return false;
			
			e.preventDefault();	
	});

	$(".btntambah").click(function(e){
				function CommaJ2(number) {
						number = '' + number;
						if (number.length > 3) {
						var mod = number.length % 3;
						var output = (mod > 0 ? (number.substring(0,mod)) : '');
						for (i=0 ; i < Math.floor(number.length / 3); i++) {
						if ((mod == 0) && (i == 0))
						output += number.substring(mod+ 3 * i, mod + 3 * i + 3);
						else
						output+= ',' + number.substring(mod + 3 * i, mod + 3 * i + 3);
						}
						return (output);
						}
						else return number;
				}				  
				
			 var url_siteid = $(".url_siteid").val();	
			 var itemid = $(this).attr("id");
			 var itemExplode = itemid.split("-");
			 var idprod = itemExplode[1];
			
			 var totalqtyawal = $("#totalQtyitem-"+idprod).val();
			 var priceFormprd = $("#priceForm-"+idprod).val();
			 var newqtyprod = parseInt(totalqtyawal)+1;
			 var totalBelanja = $("#jumlah_price").val();
			 var hasiltambahtotal = parseInt(totalBelanja)+parseInt(priceFormprd);
			 var jumlah_qty = $("#jumlah_qty").val(); 
			 var totalQty =  parseInt(jumlah_qty)+1;
			 var priceGrantot = $("#priceGrantot-"+idprod).val();
			 var jumhasilItem = parseInt(priceGrantot)+parseInt(priceFormprd);
			 
			 if(newqtyprod<1001){
				 
					$.post(""+url_siteid+"include/add_proditem.php", {"idprod": idprod, "newqtyprod": newqtyprod},
						function(data){
							if(data==200){
								$("#qtyForm-"+idprod).val(newqtyprod);
								$(".jumlah_totalbel").html(CommaJ2(hasiltambahtotal));
								$("#total-jcartHeader").html(CommaJ2(hasiltambahtotal));
								$("#totalQtyjcart").html(totalQty);	
								$("#jumlah_qty").val(totalQty); 
								$("#jumlah_price").val(hasiltambahtotal);
								$(".item_total"+idprod).html(CommaJ2(jumhasilItem));
								$("#priceGrantot-"+idprod).val(jumhasilItem);
								$("#totalQtyitem-"+idprod).val(newqtyprod);
								swal("ITEM ADDED!", "The item has been updated.", "success");
							
							   var loadUrl = url_siteid+"include/total_cart.php";
								setTimeout(function(){
									$(".total_jcart").load(loadUrl)
								},500);	
						
							}else{
								 swal("ERROR!", "Please refresh your computer and try again.", "error");
							}
							
					});	
			 }
			
			e.preventDefault();
	});


	$(".btnkurang").click(function(e){
				function CommaJ3(number) {
						number = '' + number;
						if (number.length > 3) {
						var mod = number.length % 3;
						var output = (mod > 0 ? (number.substring(0,mod)) : '');
						for (i=0 ; i < Math.floor(number.length / 3); i++) {
						if ((mod == 0) && (i == 0))
						output += number.substring(mod+ 3 * i, mod + 3 * i + 3);
						else
						output+= ',' + number.substring(mod + 3 * i, mod + 3 * i + 3);
						}
						return (output);
						}
						else return number;
				}				  
			 
			 var url_siteid = $(".url_siteid").val();	
			 var itemid = $(this).attr("id");
			 var itemExplode = itemid.split("-");
			 var idprod = itemExplode[1];
			 var totalqtyawal = $("#totalQtyitem-"+idprod).val();
			 
			 if(totalqtyawal > 1){
						 var priceFormprd = $("#priceForm-"+idprod).val();
						 var newqtyprod = parseInt(totalqtyawal)-1;
						 var totalBelanja = $("#jumlah_price").val();
						 var hasiltambahtotal = parseInt(totalBelanja)-parseInt(priceFormprd);
						 var jumlah_qty = $("#jumlah_qty").val(); 
						 var totalQty =  parseInt(jumlah_qty)-1;
						 var priceGrantot = $("#priceGrantot-"+idprod).val();
						 var jumhasilItem = parseInt(priceGrantot)-parseInt(priceFormprd);	
						 
						$.post(""+url_siteid+"include/add_proditem.php", {"idprod": idprod, "newqtyprod": newqtyprod},
							function(data){
								if(data==200){
									$("#qtyForm-"+idprod).val(newqtyprod);
									$(".jumlah_totalbel").html(CommaJ3(hasiltambahtotal));
									$("#total-jcartHeader").html(CommaJ3(hasiltambahtotal));
									$("#totalQtyjcart").html(totalQty);	
									$("#jumlah_qty").val(totalQty); 
									$("#jumlah_price").val(hasiltambahtotal);
									$(".item_total"+idprod).html(CommaJ3(jumhasilItem));
									$("#priceGrantot-"+idprod).val(jumhasilItem);
									$("#totalQtyitem-"+idprod).val(newqtyprod);
									swal("ITEM UPDATED!", "The item has been updated.", "success");
								
									var loadUrl = url_siteid+"include/total_cart.php";
									setTimeout(function(){
										$(".total_jcart").load(loadUrl)
									},500);	
						
								}else{
									swal("ERROR!", "Please refresh your computer and try again.", "error");
								}
								
						});	 
			 }else{}
				 
			e.preventDefault();
	});	

	
	$(".add_to_cartbtn").click(function(e){
		 var url_siteid = $(".url_siteid").val();						 	
         var idprod = $(".idprodlist_uid").val();
		 var iddetail = $(".idproddetail_uid").val();
		 var qtyitem = $(".qty_productlist").val();

		 if(iddetail<1 || iddetail==null || iddetail=="undefined"){
			swal("ERROR!", "Please select product detail!", "error");
		 
		 }else if(qtyitem=="" || qtyitem==0){
			swal("ERROR!", "Please insert qty your order!", "error");
			
		 }else{
		 	  
			 $.post(""+url_siteid+"include/beli_product_jcart.php", {"idprod": idprod, "iddetail": iddetail, "qtyitem": qtyitem},
			 function(data){
				if(data==900){
						swal("STOCK ERROR!", "Please reduce your order qty.", "error");
				}else if(data==200){
						//alert suscess--
						swal("ITEM ADDED!", "The item has been added to your shopping cart.", "success");
						var loadUrl = url_siteid+"include/total_cart.php";
						setTimeout(function(){
							$(".total_jcart").load(loadUrl)
						},500);	
						
				}else{
					  swal("ERROR!", "Please refresh your computer and try again.", "error");
				}
			 });
			
			 
		 }
		 
		e.preventDefault();	
	 });

	$(".add_to_cartbtnList").click(function(e){
		 var url_siteid = $(".url_siteid").val();	
		 var itemid = $(this).attr("id");
		 var itemExplode = itemid.split("-");	
			
         var idprod = itemExplode[1];
		 var iddetail = itemExplode[2]
		 var qtyitem = 1;
		 
		 if(iddetail<1 || iddetail==null){
			swal("ERROR!", "Please select product detail!", "error");
		 }else{
		 
			 $.post(""+url_siteid+"include/beli_product_jcart.php", {"idprod": idprod, "iddetail": iddetail, "qtyitem": qtyitem},
			 function(data){
				if(data==900){
						swal("STOCK ERROR!", "Please reduce your order qty.", "error");
				}else if(data==200){
						//alert suscess--
						swal("ITEM ADDED!", "The item has been added to your shopping cart.", "success");
						var loadUrl = url_siteid+"include/total_cart.php";
						setTimeout(function(){
							$(".total_jcart").load(loadUrl)
						},500);	
						
				}else{
					  swal("ERROR!", "Please refresh your computer and try again.", "error");
				}
				
			 });
		 }
		 
		e.preventDefault();	
	 });
	
	
	$(".generalsortprodlistmember").change(function(){
		var namasession = $(".name_sortuidmember").val();
		var idstyle = $(this).val();
		var url_siteid = $(".url_siteid").val();
		$.post(""+url_siteid+"include/set_sesstion_prodlist.php", {"namasession": namasession, "idstyle": idstyle},
		function(data){	
			window.location.reload(); 
		});
	 }); 
	
	$(".generalsortprodlist").change(function(){
		var namasession = $(".name_sortuid").val();
		var idstyle = $(this).val();
		var url_siteid = $(".url_siteid").val();
		$.post(""+url_siteid+"include/set_sesstion_prodlist.php", {"namasession": namasession, "idstyle": idstyle},
		function(data){	
			window.location.reload(); 
		});
	 }); 

	$(".generalsortprodlist_paging").change(function(){
		var namasession = $(".name_sort_page").val();
		var idstyle = $(this).val();
		var url_siteid = $(".url_siteid").val();
		$.post(""+url_siteid+"include/set_sesstion_prodlist.php", {"namasession": namasession, "idstyle": idstyle},
		function(data){	
			window.location.reload(); 
		});
	 }); 
	
	$(".generalsortprodlist_size").change(function(){
		var namasession = $(".name_sortuid_size").val();
		var idstyle = $(this).val();
		var url_siteid = $(".url_siteid").val();
		$.post(""+url_siteid+"include/set_sesstion_prodlist.php", {"namasession": namasession, "idstyle": idstyle},
		function(data){	
			window.location.reload(); 
		});
	 }); 

	$(".province").change(function(){			
		 var url_siteid = $(".url_siteid").val();						   
		 var propinsi = $(this).val();			
		 $.post(""+url_siteid+"include/get_kabupaten.php", {"propinsi": propinsi},
		 function(data){
			$(".kabupaten").html(data); 
			 });
	}); 
				   
	$(".kabupaten").change(function(){
		 var url_siteid = $(".url_siteid").val();								
		 var kabupaten = $(this).val();			
		 $.post(""+url_siteid+"include/get_kotalist.php", {"kabupaten": kabupaten},
		 function(data){
			$(".city").html(data); 
		 });
	}); 

	$(".product_color").click(function(){
			var url_siteid = $(".url_siteid").val();						 	
			var itemid = $(this).attr("id");
			var itemExplode = itemid.split("-");			
			var iddetail = itemExplode[1];
			$(".idproddetail_uid").val(iddetail);
		    $(".idproddetail_size_uid").val(0);
			$(".load-img").removeAttr("style");
			$.post(""+url_siteid+"include/get_product_size.php", {"iddetail": iddetail},
			function(data){
				$(".product-size-wrapper").html(data);		
				$(".load-img").attr("style","display:none;");
			});
	 });
	 
	  jQuery.validator.addMethod("angkaval", function(value, element) {
			//return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
			return this.optional(element) || /^[0-9+ ]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 
	
		jQuery.validator.addMethod("abcval", function(value, element) {
			//return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
			return this.optional(element) || /^[a-z .,]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 
	
		jQuery.validator.addMethod("addressval", function(value, element) {
				//return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
				return this.optional(element) || /^[0-9:a-z+/.,_ \n-]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 

		jQuery.validator.addMethod("usernametext", function(value, element) {
				//return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
				return this.optional(element) || /^[0-9a-z.\n-]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 

		jQuery.validator.addMethod("tanggltext", function(value, element) {
				//return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
				return this.optional(element) || /^[0-9/\n-]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 

		jQuery.validator.addMethod("ponumbertext", function(value, element) {
				//return this.optional(element) || /^\w[\w\d\s]*$/.test(value);
				return this.optional(element) || /^[0-9a-z\n-]+$/i.test(value);
		}, "Letters, numbers or underscores only"); 

		$("#newsletteruidform").validate({
				rules: {
					email:{
						required: true,
						email:true
					}				
					
				},
				messages: {
					email:{
						required:"* Please enter your email address.",
						email:"* Please enter valid your email address."
					}					
				}
		});	
		
		$("#form_regis_drawal").validate({
				rules: {
					totalamount:{
						required: true
					},
					password:{
						addressval: true
					},
					bank_transfer:{
						required: true
					}					
					
				},
				messages: {
					totalamount:{
						 required:"* This field can't be empty."
					},
					password:{
						 required:"* This field can't be empty."
					},
					bank_transfer:{
						required:"* This field can't be empty."
					}					
				}
		});	


		$("#topupdepositUid").validate({
				rules: {				   
					jumlahdeposit: {
						required: true
					},
					bankname:{
						required: true
					},
					namapemilik:{
						required: true
					},
					tanggal:{
						required: true	
					}
					
				},
				messages: {
					jumlahdeposit:{
						 required:"* This field can't be empty."
					},
					bankname:{
						 required:"* This field can't be empty."
					},
					namapemilik:{
						 required:"* This field can't be empty."
					},
					tanggal:{
						 required:"* This field can't be empty."
					}
									
				}
		});	
						 
		$("#member_shipuid").validate({
				rules: {
					company:{
						required: true,
						addressval: true
					},
					npwp:{
						addressval: true
					}					
					
				},
				messages: {
					company:{
						 required:"* This field can't be empty.",
						 addressval:"* This field cannot insert special character , e.g: *($#&"
					},
					npwp:{
						 addressval:"* This field cannot insert special character , e.g: *($#&"
					}					
				}
		});	

		$("#login_formuid").validate({
				rules: {
					email:{
						required: true,
						email:true
					},
					password:{
						required: true
					}				
					
				},
				messages: {
					email:{
						required:"* Please enter your email address.",
						email:"* Please enter valid your email address."
					},
					password:{
						required:"* Please enter your password."
					}					
				}
		});	

		$("#login_formuidcp").validate({
				rules: {
					email:{
						required: true,
						email:true
					},
					password:{
						required: true
					}				
					
				},
				messages: {
					email:{
						required:"* Please enter your email address.",
						email:"* Please enter valid your email address."
					},
					password:{
						required:"* Please enter your password."
					}					
				}
		});	

		$("#saveorder_formid").validate({
				rules: {
					kurir_lainnya:{
						required: true	
					},
					notemember:{ 
						 addressval: true
					}
					
				},
				messages: {
					kurir_lainnya:{
						required:"* This field can't be empty."
					},	
					notemember:{ 
						 addressval:"* This field cannot insert special character , e.g: *($#&"
					}	
				}
		});

		$("#ratingproduct_list").validate({
				rules: {
					name:{ 
						 required: true,
						 addressval: true
					},
					judul:{
						required: true,	
						addressval: true	
					},
					description:{
						required: true		
					}
					
				},
				messages: {
					name:{ 
						 required:"* This field can't be empty.",
						 addressval:"* This field cannot insert special character , e.g: *($#&"
					},
					judul:{
						 required:"* This field can't be empty.",
						 addressval:"* This field cannot insert special character , e.g: *($#&"
					},					
					description:{
						required:"* This field can't be empty."				
					}
						
					
				}
		});
		
		
		
		$("#contact_formid").validate({
				rules: {
					name:{ 
						 required: true,
						 addressval: true
					},
					lastname:{
						addressval: true	
					},
					email:{
						required: true,
						email: true
					},
					phone:{
						required: true,
						angkaval: true		
					},
					pesanmember:{
						required: true,
						addressval: true		
					},
					kodevalidasi:{
						required: true
					}
					
				},
				messages: {
					name:{ 
						 required:"* This field can't be empty.",
						 addressval:"* This field cannot insert special character , e.g: *($#&"
					},
					lastname:{
						addressval:"* This field cannot insert special character , e.g: *($#&"
					},					
					email:{
						required:"* This field can't be empty.",
						email:"* Please enter valid email address."
					},
					phone:{
						required:"* This field can't be empty.",
						angkaval:"* This field only insert with character 0-9 + "						
					},
					pesanmember:{
						required:"* This field can't be empty.",
						addressval:"* This field cannot insert special character , e.g: *($#&"
					},
					kodevalidasi:{
						required:"* This field can't be empty."
					}
						
					
				}
			});

			$("#register_formcoor").validate({
					rules: {
						company:{
							required: true,
							abcval:true						
						},
						name:{
							required: true,
							abcval:true
						},
						lastname:{
							abcval:true
						},
						email:{
							required: true,
							email:true
						},
						reemail:{ 
							 required: true,
							 equalTo: "#email"
						},						
						phone:{
							angkaval: true		
						},						
						mobilephone:{
							required: true,
							angkaval: true		
						},
						password:{ 
							 required: true,
							 minlength: 6
						},
						repassword:{ 
							 required: true,
							 equalTo: "#password"
						},
						kodevalidasi:{
							 required: true	
						}
					
					
					},
					messages: {
						company:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."				
						},						
						name:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."
						},
						lastname:{
							abcval:"* This field contain alphabet only."
						},
						email:{
							required:"* This field can't be empty.",
							email:"* Please enter valid email address."
						},
						reemail:{ 
							required:"* This field can't be empty.",
							equalTo: "* Confirm email different with your email address."
						},							
						phone:{
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						mobilephone:{
							required:"* This field can't be empty.",
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						password:{ 
							 required:"* This field can't be empty.",
							 minlength: "* Please enter minimum length 6 character."
						},
						repassword:{ 
							  required:"* This field can't be empty.",
							  equalTo: "* Confirm password different with your password."
						},
						kodevalidasi:{
							required:"* This field can't be empty."
						}						
						
					}
				});	



			$("#register_form").validate({
					rules: {
						name:{
							required: true,
							abcval:true
						},
						lastname:{
							abcval:true
						},
						email:{
							required: true,
							email:true
						},
						reemail:{ 
							 required: true,
							 equalTo: "#email"
						},						
						phone:{
							angkaval: true		
						},						
						mobilephone:{
							required: true,
							angkaval: true		
						},
						password:{ 
							 required: true,
							 minlength: 6
						},
						repassword:{ 
							 required: true,
							 equalTo: "#password"
						},
						kodevalidasi:{
							 required: true	
						}
					
					
					},
					messages: {
						name:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."
						},
						lastname:{
							abcval:"* This field contain alphabet only."
						},
						email:{
							required:"* This field can't be empty.",
							email:"* Please enter valid email address."
						},
						reemail:{ 
							required:"* This field can't be empty.",
							equalTo: "* Confirm email different with your email address."
						},							
						phone:{
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						mobilephone:{
							required:"* This field can't be empty.",
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						password:{ 
							 required:"* This field can't be empty.",
							 minlength: "* Please enter minimum length 6 character."
						},
						repassword:{ 
							  required:"* This field can't be empty.",
							  equalTo: "* Confirm password different with your password."
						},
						kodevalidasi:{
							required:"* This field can't be empty."
						}						
						
					}
				});	

			$("#edit_register_form").validate({
					rules: {
						name:{
							required: true,
							abcval:true
						},
						lastname:{
							abcval:true
						},
						email:{
							required: true,
							email:true
						},
						gender:{
							required: true	
						},
						phone:{
							angkaval: true		
						},						
						mobilephone:{
							required: true,
							angkaval: true		
						},
						province:{ 
							 required: true
						},
						kabupaten:{ 
							 required: true
						},
						city:{
							 required: true	
						},
						address:{
							 required: true,
							 addressval: true	
						}
					
					
					},

					errorPlacement: function(error, element) {  
										
						if (element.is(".province")) { 
							$(".error_province").html(error);  
						}if (element.is(".kabupaten")) { 
							$(".error_kabupaten").html(error); 
						}if (element.is(".gender")) { 
							$(".error_gender").html(error);  	
						}if (element.is(".city")) { 
							$(".error_city").html(error);  								
						}else { element.next('span').html(error) }
					
					},
										
					messages: {
						name:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."
						},
						lastname:{
							abcval:"* This field contain alphabet only."
						},
						email:{
							required:"* This field can't be empty.",
							email:"* Please enter valid email address."
						},
						gender:{
							required:"* This field can't be empty."
						},						
						phone:{
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						mobilephone:{
							required:"* This field can't be empty.",
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						province:{ 
							 required:"* This field can't be empty."
						},
						kabupaten:{ 
							 required:"* This field can't be empty."
						},
						city:{
							 required:"* This field can't be empty."
						},
						address:{
							required:"* This field can't be empty.",
							addressval:"* This field cannot insert special character , e.g: *($#&"
						}						
						
					}
				});	

	
				$("#edit_register_formcrpporate").validate({
					rules: {
						company_name:{
							required: true,
							abcval:true							
						},
						name:{
							required: true,
							abcval:true
						},
						lastname:{
							abcval:true
						},
						email:{
							required: true,
							email:true
						},
						gender:{
							required: true	
						},
						phone:{
							angkaval: true		
						},						
						mobilephone:{
							required: true,
							angkaval: true		
						},
						province:{ 
							 required: true
						},
						kabupaten:{ 
							 required: true
						},
						city:{
							 required: true	
						},
						address:{
							 required: true,
							 addressval: true	
						}
					
					
					},

					errorPlacement: function(error, element) {  
										
						if (element.is(".province")) { 
							$(".error_province").html(error);  
						}if (element.is(".kabupaten")) { 
							$(".error_kabupaten").html(error); 
						}if (element.is(".gender")) { 
							$(".error_gender").html(error);  	
						}if (element.is(".city")) { 
							$(".error_city").html(error);  								
						}else { element.next('span').html(error) }
					
					},
										
					messages: {
						company_name:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."					
						},						
						name:{
							required:"* This field can't be empty.",
							abcval:"* This field contain alphabet only."
						},
						lastname:{
							abcval:"* This field contain alphabet only."
						},
						email:{
							required:"* This field can't be empty.",
							email:"* Please enter valid email address."
						},
						gender:{
							required:"* This field can't be empty."
						},						
						phone:{
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						mobilephone:{
							required:"* This field can't be empty.",
							angkaval:"* This field only insert with character 0-9 + "		
						},						
						province:{ 
							 required:"* This field can't be empty."
						},
						kabupaten:{ 
							 required:"* This field can't be empty."
						},
						city:{
							 required:"* This field can't be empty."
						},
						address:{
							required:"* This field can't be empty.",
							addressval:"* This field cannot insert special character , e.g: *($#&"
						}						
						
					}
				});	


	$("#konfirm_payform").validate({
			rules: {
				nama_bank:{
					required: true,
					 addressval: true
				},
				norek:{ 
					 required: true,
					 addressval: true
				},
				jumlah_transfer:{ 
					 required: true,
					 addressval: true
				},
				atas_nama:{
					required: true,
					 addressval: true
				},
				tanggal:{
					required: true
				},
				idorder:{
					required: true
				},
				nominal:{
					required: true	
				},
				transferke:{
					required: true	
				}									
				
			},
			messages: {
				nama_bank:{
					required:"* This field can't be empty.",
					addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				norek:{ 
					required:"* This field can't be empty.",
				    addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				jumlah_transfer:{ 
					required:"* This field can't be empty.",
					addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				atas_nama:{
					required:"* This field can't be empty.",
					addressval:"* This field cannot insert special character , e.g: *($#&"
				},
				tanggal:{
					required:"* This field can't be empty."
				},
				idorder:{
					required:"* This field can't be empty."
				},
				nominal:{
					required:"* This field can't be empty."
				},
				transferke:{
					required:"* This field can't be empty."	
				}
				
			}
		});


	//voucher mizuno
	$('form#voucher_idform').validate({
	  rules: {
			vouchercode:{ 
				 required: true
			}	
			
	  },
	  messages: {
			vouchercode:{ 
				required:"* This field can't be empty."
			}	
	  },
	  errorPlacement: function(error, element){
		error.insertAfter(element);
	  },
	   submitHandler:function(form){
			 $(".loadimgvou").removeAttr("style");
			 var url_siteid = $(".url_siteid").val();	
 			 var codevoucher = $(".vouchercode").val();
			 var codetext = $(".total-order-wrapper").attr("id");
			 var itemExplodeid = codetext.split("-");
			 //item	
			 var totalbelanja = parseInt(itemExplodeid[0]);
			 var ongkirprice = itemExplodeid[1];

			var notememberlist = $(".notemember").val();
			var kurir_lainnya = $(".kurir_lainnya").val();
			 
			 $.post(""+url_siteid+"include/cek_code_voucher.php", {"codevoucher": codevoucher, "totalbelanja": totalbelanja, "notememberlist": notememberlist, "kurir_lainnya": kurir_lainnya},
			 function(data){
					
					var itemdatag = data.split("-");
					var datanotif = itemdatag[0];
					var jumlahmintrx = itemdatag[1];
					var jumlahmax = itemdatag[2];
					
					if(datanotif=="TIDAK_ADA_DI_DATABSE"){
						$(".list_bonus_product").remove();
						
						$(".voucher_list_code").html("");
						$(".ada_voucherlist").attr("style","display:none");
						$(".kodevocher_alert").html("Maaf, kode voucher yang ada masukan salah.");	
						var totalbelanjabaru = (totalbelanja+parseInt(ongkirprice)); 
						$("#grandtotal_belanjaid").html(rupiahtext(totalbelanjabaru));	
						$(".loadimgvou").attr("style","display:none;");	
					
					} else if(datanotif=="STOK_VOUCHER_NOL"){
						$(".list_bonus_product").remove();
						
						$(".voucher_list_code").html("");
						$(".ada_voucherlist").attr("style","display:none");
						$(".kodevocher_alert").html("Maaf, kode voucher ini sudah tidak dapat digunakan.");	
						var totalbelanjabaru = (totalbelanja+parseInt(ongkirprice)); 
						$("#grandtotal_belanjaid").html(rupiahtext(totalbelanjabaru));	
						$(".loadimgvou").attr("style","display:none;");	
					
					} else if(datanotif=="SUDAH_DIGUNAKAN_MEMBER_INI"){
						$(".list_bonus_product").remove();
						
						$(".voucher_list_code").html("");
						$(".ada_voucherlist").attr("style","display:none");
						$(".kodevocher_alert").html("Maaf, kode voucher ini telah anda gunakan.");	
						var totalbelanjabaru = (totalbelanja+parseInt(ongkirprice)); 
						$("#grandtotal_belanjaid").html(rupiahtext(totalbelanjabaru));	
						$(".loadimgvou").attr("style","display:none;");							
					
					} else if(datanotif=="TOTAL_BELANJA_KURANG"){
						$(".list_bonus_product").remove();
						
						$(".voucher_list_code").html("");
						$(".ada_voucherlist").attr("style","display:none");
						if(parseInt(jumlahmax)>0){
							$(".kodevocher_alert").html("Maaf, kode voucher tidak dapat digunakan, <br />Kode voucher ini berlaku untuk nilai transaksi <br />Min. trx IDR "+rupiahtext(jumlahmintrx)+" dan Max. trx IDR "+rupiahtext(jumlahmax));
						}else{
							$(".kodevocher_alert").html("Maaf, kode voucher tidak dapat digunakan, <br />Kode voucher ini berlaku untuk nilai transaksi <br />Min. trx IDR "+rupiahtext(jumlahmintrx));
						}
						var totalbelanjabaru = (totalbelanja+parseInt(ongkirprice)); 
						$("#grandtotal_belanjaid").html(rupiahtext(totalbelanjabaru));	
						$(".loadimgvou").attr("style","display:none;");
						
					
					} else if(datanotif=="KODE_VOUCHER_EXPIRED"){
						$(".list_bonus_product").remove();
						
						$(".voucher_list_code").html("");
						$(".ada_voucherlist").attr("style","display:none");
						$(".kodevocher_alert").html("Maaf, masa berlaku kode voucher ini sudah habis.");	
						var totalbelanjabaru = (totalbelanja+parseInt(ongkirprice)); 
						$("#grandtotal_belanjaid").html(rupiahtext(totalbelanjabaru));	
						$(".loadimgvou").attr("style","display:none;");	
					
					} else if(datanotif=="KODE_ACCEPTED"){
						
						 window.location.reload(); 
							
					}else{
						$(".list_bonus_product").remove();
						
						$(".voucher_list_code").html("");
						$(".ada_voucherlist").attr("style","display:none");
						$(".kodevocher_alert").html("Maaf, kode voucher yang ada masukan salah.");	
						var totalbelanjabaru = (totalbelanja+parseInt(ongkirprice)); 
						$("#grandtotal_belanjaid").html(rupiahtext(totalbelanjabaru));	
						$(".loadimgvou").attr("style","display:none;");	
					}
					
					
			
			 });	 

	   }
	});	
	
	$(".remove_code_voucher").click(function(e){
		var url_siteid = $(".url_siteid").val();
		 $(".loadimgvou").removeAttr("style");
		 $.post(""+url_siteid+"include/remove_code_voucher.php", {},
		 function(data){
				window.location.reload(); 
		 });				
		e.preventDefault();	
	});

	$(".resetfilterprice").click(function(e){
		var url_siteid = $(".url_siteid").val();
		 $.post(""+url_siteid+"include/reset_filter_price.php", {},
		 function(data){
				window.location.reload(); 
		 });				
		e.preventDefault();	
	});
	
	
	$(".compareListData").click(function(){
		var url_siteid = $(".url_siteid").val();
		var idproditem = $(this).attr("id");
		var itemdatag = idproditem.split("-");
		var idproduct = itemdatag[1];
		
		if ($(this).is(':checked')) {

				$.post(""+url_siteid+"include/set_product_compareList.php", {"idproduct": idproduct},
				function(data){
					
					if(data=="DATAFULL"){
						swal("ERROR!", "Maaf mak. 4 item untuk perbandingan produk.", "error");
						$("#"+idproditem).removeAttr('checked');
						
					}else if(data=="DATAEXIST"){
						swal("ERROR!", "Maaf produk ini sudah ada di list perbandingan.", "error");
						$("#"+idproditem).removeAttr('checked');
						
					}else{
									
									swal({
									  title: "SUCCESS",
									  text: "Item produk ini telah ditambahkan ke list perbandingan.",
									  type: "success",
									  showCancelButton: true,
									  confirmButtonColor: "#da1205",
									  confirmButtonText: "Lihat List Perbandingan",
									  cancelButtonText: "Done!"
									 
									},
									function(){
									  	window.location.replace(""+url_siteid+"compare-product");
									});


					}
					
				});	
		
		}else{

			$.post(""+url_siteid+"include/set_product_compareList_no.php", {"idproduct": idproduct},
			function(data){
				
			});	
		
		}
		
	});


	$(".removecompare").click(function(e){
		var url_siteid = $(".url_siteid").val();
		var idproditem = $(this).attr("id");
		var itemdatag = idproditem.split("-");
		var idproduct = itemdatag[1];
		
		$.post(""+url_siteid+"include/set_product_compareList_no.php", {"idproduct": idproduct},
		function(data){
			$(".idproditemlist-"+idproduct).fadeOut();		
		});	
		
		e.preventDefault();	
	});
	
	$(".check_filter_prod").click(function(e){
		 var url_siteid = $(".url_siteid").val();
		 var urlpage = $(".Urel_pagelist").val();
		 
		 
		 //brand item
		 var brandlist1 = $("input.brandlist").length;
		 var brandlist2 = ""; var brandlist3 = ""; var brandListItem = "";
		 
		 if( parseInt(brandlist1) > 0 ){
			 for(var Q = 0; Q < parseInt(brandlist1); Q++){
				var testList = $("input.brandlistItem"+Q+":checked").val();
				if(testList!="undefined" && parseInt(testList) > 0){
					brandlist2 = brandlist2+testList+"#";		
				}	 
			 }	 
		 }
		 
		 if(brandlist2!=""){ brandlist2 = brandlist2+"AKHIRLISTING" }
		 
		 if(brandlist2!=""){ 
		 	brandlist3 =  brandlist2.split("#AKHIRLISTING"); 
			brandListItem = brandlist3[0];
		 }
		 
		 //price range
		 var status = parseInt($(".statusfilterprice").val());
		 if(status>0){
			 var priceRange1 = $(".priceRange1").val();
		 	 var priceRange2 = $(".priceRange2").val();
		 }else{
			var priceRange1 = 0; 
		 	var priceRange2 = 0;
		 }
		 
		 //rating
		 var rat5 = $("input.ratting_type5:checked").val();
		 var rat4 = $("input.ratting_type4:checked").val();
		 var rat3 = $("input.ratting_type3:checked").val();
		 var rat2 = $("input.ratting_type2:checked").val();
		 var rat1 = $("input.ratting_type1:checked").val();
		 var rat0 = $("input.ratting_type0:checked").val();
		 
		 //attribute
		var attributelist = '';  var attributelistListitem = ''; var attributelistListitem2 = ''; var hsilatrr = ''; var testListAttlist = ''; 
		var ListAttrilist = document.getElementsByClassName("attributelist");
		var Y;
		for (Y = 0; Y < ListAttrilist.length; Y++) {
			var listItem = $(ListAttrilist[Y]).attr("checked");
			if(listItem=="checked"){
				var listItemval = $(ListAttrilist[Y]).val();	
				attributelist = attributelist+listItemval+"#";	
			}				
		}
		 
		if(attributelist!=""){ attributelistListitem = attributelist+"AKHIRLISTING" }

		if(attributelist!=""){ 
		 	attributelistListitem2 =  attributelistListitem.split("#AKHIRLISTING"); 
			hsilatrr = attributelistListitem2[0];
		 }
		 
$.post(""+url_siteid+"include/set_filter_product_session.php", {"brandListItem": brandListItem, "priceRange1": priceRange1, "priceRange2": priceRange2, "rat5": rat5, "rat4": rat4, "rat3": rat3, "rat2": rat2, "rat1": rat1, "rat0": rat0, "hsilatrr": hsilatrr,"urlpage": urlpage},
function(data){
	window.location.reload(); 
});
	 
		e.preventDefault();	
	});	



	$(".idproddetail_uid").change(function(){
		var url_siteid = $(".url_siteid").val();
		var idprod = $(".idprodlist_uid").val();								   	
		var iddetail = $(this).val();
		$.post(""+url_siteid+"include/getprice_productdetail.php", {"idprod": idprod, "iddetail": iddetail},
		function(data){	
			$(".wrapper_price_product").html(data);
		});

		$.post(""+url_siteid+"include/gettabele_productdetail.php", {"idprod": idprod, "iddetail": iddetail},
		function(data){	
			$(".tablewrapper-detail").html(data);
		});
		
	 }); 
	


	$(".deposit_bayarlist").click(function(){
		var url_siteid = $(".url_siteid").val();
		var status = "OKE";
		var notememberlist = $(".notemember").val();
		var kurir_lainnya = $(".kurir_lainnya").val();
		
		$.post(""+url_siteid+"include/get_set_pilih_bayar_deposit.php", {"status": status, "notememberlist": notememberlist, "kurir_lainnya": kurir_lainnya},
		function(data){	
			window.location.reload();	
		});		
	});

	$(".deposit_bayarlistNo").click(function(){
		var url_siteid = $(".url_siteid").val();
		var status = "NO";
		var notememberlist = $(".notemember").val();
		var kurir_lainnya = $(".kurir_lainnya").val();
		
		$.post(""+url_siteid+"include/get_set_pilih_bayar_deposit.php", {"status": status, "notememberlist": notememberlist, "kurir_lainnya": kurir_lainnya},
		function(data){	
			window.location.reload();	
		});		
	});
	

	$(".kurir_listid").change(function(){
		var url_siteid = $(".url_siteid").val();					   	
		var namakurir = $(this).val();
	
		$.post(""+url_siteid+"include/set_namakurir.php", {"namakurir": namakurir},
		function(data){	
			//set kurir
			if(data=="OTHER"){
				$(".kurir_lainnya").val("");
				$("#kurir_lainnya_wrapper").fadeIn();
				$(".ongkir_span").html("Rp. 0");
				window.location.reload();	
			}else{		
				$(".kurir_lainnya").val("");
				$("#kurir_lainnya_wrapper").fadeOut();		
				window.location.reload();			
			}
		});	
		
	 }); 
	
});