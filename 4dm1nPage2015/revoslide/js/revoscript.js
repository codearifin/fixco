function imageSelector(fileSelector, previewSelector, submitSelector, errorSelector, $width, $height){
	$(fileSelector).change(function(){
	  var file = this.files[0];
      var reader = new FileReader();
	  
      reader.onload = function(file){
		  var img = new Image();
		  
          $(previewSelector).html('<img src="'+file.target.result+'"/>');
		  
		  img.onload = function(){
		    if($width=='square' || $height=='square'){
		      if(img.width!=img.height){ 
		        $(submitSelector).attr({'disabled':'disabled'}).css('backgroundColor', '#9d9d9d').addClass('disabled');
			    $(errorSelector).html('Image dimension must be a square.');
		      }else{
		        $(submitSelector).removeAttr('disabled').css('backgroundColor', '').removeClass('disabled');
			    $(errorSelector).html('');
		      }
			}else if($width>0 && $height>0){
		      if(img.width!=$width || img.height!=$height){ 
		        $(submitSelector).attr({'disabled':'disabled'}).css('backgroundColor', '#9d9d9d').addClass('disabled');
			    $(errorSelector).html('Image dimension must be '+$width+'px &times; '+$height+'px<br/>');
		      }else{
		        $(submitSelector).removeAttr('disabled').css('backgroundColor', '').removeClass('disabled');
			    $(errorSelector).html('');
		      }
			}else if($width>0 && $height==0){
		      if(img.width!=$width){ 
		        $(submitSelector).attr({'disabled':'disabled'}).css('backgroundColor', '#9d9d9d').addClass('disabled');
			    $(errorSelector).html('Image width must be '+$width+'px<br/>');
		      }else{
		        $(submitSelector).removeAttr('disabled').css('backgroundColor', '').removeClass('disabled');
			    $(errorSelector).html('');
		      }
			}else if($width==0 && $height>0){
		      if(img.width!=$width){ 
		        $(submitSelector).attr({'disabled':'disabled'}).css('backgroundColor', '#9d9d9d').addClass('disabled');
			    $(errorSelector).html('Image height must be '+$height+'px<br/>');
		      }else{
		        $(submitSelector).removeAttr('disabled').css('backgroundColor', '').removeClass('disabled');
			    $(errorSelector).html('');
		      }
			}else if($width.match(/^\blower_+[\d]+$/) && $height.match(/^\blower_+[\d]+$/)){
			  $w = $width.split('_'); $h = $height.split('_');
		      if(img.width>$w[1] || img.height>$h[1]){ 
		        $(submitSelector).attr({'disabled':'disabled'}).css('backgroundColor', '#9d9d9d').addClass('disabled');
			    $(errorSelector).html('Image can\'t be wider than '+$w[1]+'px or taller than '+$h[1]+'px<br/>');
		      }else{
		        $(submitSelector).removeAttr('disabled').css('backgroundColor', '').removeClass('disabled');
			    $(errorSelector).html('');
		      }
			}
		  }
		  
		  if(($width=='square'  || $height=='square') || ($width>0  && $height>0) || ($width>0  && $height==0) || ($width==0  && $height>0) || ($width.match(/^\blower_+[\d]+$/) && $height.match(/^\blower_+[\d]+$/))){
		    img.src = reader.result;
		  }
      };
	  
      reader.readAsDataURL(file); 
	});
}


function notification(iMsg){
		var iOpts = { lines: 13, length: 11, width: 5, radius: 17, corners: 1, rotate: 0, color: '#FFF', speed: 1, trail: 60, shadow: false, hwaccel: false, className: 'spinner', zIndex: 2e9, top: 'auto', left: 'auto' };
	    var target = document.createElement("div");
	    document.body.appendChild(target);
	    var spinner = new Spinner(iOpts).spin(target);
	    overlay = iosOverlay({
		  text: iMsg,
		  spinner: spinner
	    });
}

function layercake(){
	var layerNow = 0; var divNow = 0;
	$('select.layerCake').focus(function(){
	  layerNow = $(this).val();
	  divNow = layerNow - 1;
	}).change(function(){
	  var $target = $(this).val();
	  var row = $(this).parent('td').parent('tr');
	  var len = $('select.layerCake').length;
	  var layerTarget = len - $target;
	  var divTarget = $target - 1;
	  var dragger = $('div.draggable:eq('+divNow+')');
	  var opts = '';
	  for(var i=len;i>0;i--){ opts = opts.concat('<option value="'+i+'">'+i+'</option>'); }
	  if(+$target > +layerNow){
	    $('tr#edit').remove();
	    $('div.draggable:eq('+divTarget+')').after(dragger);
	    $('tr.marker:eq('+layerTarget+')').before(row);
		$('select.layerCake').each(function(){
		  $(this).html(opts);
		  $(this).children('option[value="'+len+'"]').prop('selected', true);
		  len--;
		});
	  }else{
	    $('tr#edit').remove();
	    $('div.draggable:eq('+divTarget+')').before(dragger);
	    $('tr.marker:eq('+layerTarget+')').after(row);
		$('select.layerCake').each(function(){
		  $(this).html(opts);
		  $(this).children('option[value="'+len+'"]').prop('selected', true);
		  len--;
		});
	  }
	  layerNow = $(this).val();
	  divNow = layerNow - 1;
	});
}

function delElement(){
	$('a.del-revoelement').click(function(){
	  var $this = $(this);
	  var qString = $(this).attr('href');
	  if(confirm("Are you sure want to delete this element?")){
	  	location.href = qString;
	  }
	  return false;
	});
}

function blurUp(){
  $('input.x-pos').keyup(function(){
	 if(isNaN($(this).val())){
	   $(this).val(1);
	 }
  });
  $('input.x-pos').blur(function(){
    var $val = parseInt($(this).val());
    var row = $('tr.marker').index($(this).parent('td').parent('tr.marker'));
	var divTarget = parseInt(($('tr.marker').length - row) - 1);
	$('div.draggable:eq('+divTarget+')').css('left', $val);
  });
  $('input.y-pos').keyup(function(){
	 if(isNaN($(this).val())){
	   $(this).val(1);
	 }
  });
  $('input.y-pos').blur(function(){
    var $val = parseInt($(this).val());
    var row = $('tr.marker').index($(this).parent('td').parent('tr.marker'));
	var divTarget = parseInt(($('tr.marker').length - row) - 1);
	$('div.draggable:eq('+divTarget+')').css('top', $val);
  });
  $('input.speed').keyup(function(){
	 if(isNaN($(this).val())){
	   $(this).val(900);
	 }
  });
  $('input.delay').keyup(function(){
	 if(isNaN($(this).val())){
	   $(this).val(600);
	 }
  });
}

function editRevo(){
	$('a#edit-element').click(function(){
	  var $this = $(this);
	  var qString = $(this).attr('href').slice($(this).attr('href').indexOf('?')+1);
	  var splitr = qString.split('!=');
	  $('tr#edit').remove();
	  $.ajax({
	    url:'/edElement/'+splitr[0]+'/'+splitr[1],
		method:'GET',
		dataType:'HTML',
		cache:false,
		success:function(html){
		  $this.parent('li').parent('ul').parent('td').parent('tr').after(html);
		  var formType = (splitr[1]==1) ? 'editRevoImage' : (splitr[1]==2) ? 'editRevoText' : (splitr[1]==3) ? 'editRevoButton' : (splitr[1]==4) ? 'editTitleText' : '';
		 
		  imageSelector('input#up-image', 'div.upnoimg', 'input.submit-btn', 'span#info', 'lower_960', 'lower_550');
		  $('a#close-edit').click(function(){ $('tr#edit').remove(); return false; });
		}
	  });
	  return false;
	});
}

$(document).ready(function(){
  $('div#add').hide();
	$('div#preview').hide();
	
	$('a#canvas').click(function(){
	  $('div#property').show();
	  $('div#canvas').show();

	  $('div#add').hide();
	  $('div#preview').hide();
	  $('div#nav > a').css({color:''});
	  $(this).css({color:'#0095d3'});
	  return false;
	});
	$('a#property').click(function(){
	  $('div#canvas').show();
	  $('div#property').show();

	  $('div#add').hide();
	  $('div#preview').hide();
	  $('div#nav > a').css({color:''});
	  $(this).css({color:'#0095d3'});
	  return false;
	});
	$('a#add').click(function(){
	  $('div#canvas').hide();
	  $('div#property').hide();
	  $('div#preview').hide();
	  $('div#add').show();
	  $('div#nav > a').css({color:''});
	  $(this).css({color:'#0095d3'});
	  return false;
	});
	
				
	$('a#preview').click(function(){
	  $('div#canvas').hide();
	  $('div#property').hide();
	  $('div#add').hide();
	  $('div#preview').show();
	  $('div#nav > a').css({color:''});
	  $(this).css({color:'#0095d3'});
	  
	  var qSlide = $(this).attr('href').slice($(this).attr('href').indexOf('?')+1);
	  var tableName = $(this).attr('tableName');
	  $.ajax({
	    url:'revo-preview.php?id='+qSlide+'&table_name='+tableName,
		method:'GET',
		dataType:'HTML',
		cache:false,
		success:function(dataPreview){
		  			
		  $('div#preview').html(dataPreview);
		  if ($.fn.cssOriginal!=undefined) $.fn.css = $.fn.cssOriginal;  // CHECK IF fn.css already extended
			
		  $('div#revo-preview').revolution({  
					delay:5000,                                                
					startheight:349,                            
					startwidth:960,
					
					hideThumbs:0,
					
					navigationType:"bullet",
					navigationArrows:"solo",
						 
					navigationStyle:"round",
					navigationHAlign: "left",
					navigationVAlign: "bottom",
					navigationHOffset:0,
					navigationVOffset:0,
					
					soloArrowLeftHalign: "left",   
					soloArrowLeftValign: "center",
					soloArrowLeftHOffset:-50,
					soloArrowLeftVOffset:0,
					
					soloArrowRightHalign: "right",   
					soloArrowRightValign: "center",   
					soloArrowRightHOffset:-50,
					soloArrowRightVOffset:0,
				
					touchenabled:"on",                      
					onHoverStop:"on",  
					
					hideCaptionAtLimit:0,
					hideAllCaptionAtLilmit:0,
					hideSliderAtLimit:0,
					
					stopAtSlide:-1,
					stopAfterLoops:-1,
					
					shadow:0,
					fullWidth:"off"    
												
				});
		  
		}
	  });
	  
	  return false;
	});
	
				
	$('div#add-text').hide();
	$('div#add-button').hide();
	$('div#add-title').hide();
	
	$('select#elType').change(function(){
	  var $val = parseInt($(this).val());
	  if($val==1){
	    $('div#add-button').hide();
	    $('div#add-text').hide();
	    $('div#add-image').show();
		$('div#add-title').hide();
	  }else if($val==2){
	    $('div#add-image').hide();
	    $('div#add-button').hide();
	    $('div#add-text').show();
		$('div#add-title').hide();
	  }else if($val==3){
	    $('div#add-image').hide();
	    $('div#add-text').hide();
	    $('div#add-button').show();
		$('div#add-title').hide();
	  }else if($val==4){
	    $('div#add-title').show();
		$('div#add-image').hide();
	    $('div#add-text').hide();
	    $('div#add-button').hide();
	  }	  
	  return false;
	});
	
	editRevo(); blurUp(); delElement(); layercake();

	imageSelector('input#file-image', 'div.noimg', 'input.submit-btn', 'span#info', 'lower_960', 'lower_550');
		
	$('input[type="file"]').change(function(){
	  var file = this.files[0];
	  if(file.size>2000000){ 
		        $('input:submit').attr({'disabled':'disabled'}).css('backgroundColor', '#9d9d9d').addClass('disabled');
			    $(this).siblings('span#info-image').html('<span class="error">File size cannot be larger than 2 MB.<br/></span>');
	  }else{
		        $('input:submit').removeAttr('disabled').css('backgroundColor', '').removeClass('disabled');
			    $(this).siblings('span#info-image').html('');
	  }
	});
	
	/*upRevo('form#revoImage', 1);
	upRevo('form#revoText', 1);
	upRevo('form#revoButton', 1);
	upRevo('form#revoTitle', 1);*/
	
    for(var i=1;i<=$('div.draggable').length;i++){
	  var canvas = $('div#canvas');
	  var setx = parseInt($('div#dragger-'+i).attr('xpos'));
	  var sety = parseInt($('div#dragger-'+i).attr('ypos'));
	  $('div#dragger-'+i).css({ top:sety, left:setx });
      $('div#dragger-'+i).draggable({
          drag: function(){
              var offset = $(this).offset();
              var xpos = offset.left - canvas.offset().left;
              var ypos = offset.top - canvas.offset().top;
			  var did = $(this).attr('id').split('-');
			  $('input#xpos-'+did[1]).val(xpos);
			  $('input#ypos-'+did[1]).val(ypos);
          }
      });
	}
			
});