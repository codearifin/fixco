(function () {
		   
		   
	var input = document.getElementById("images"), 
		formdata = false;

	function showUploadedItem (source) {
		var jumimg = $("#oriImgRel > li").length;
		var jumphoto_img = parseInt(jumimg)+1;
		if(jumphoto_img < 11){		
				var list = document.getElementById("image-list"),
					li   = document.createElement("li"),
					img  = document.createElement("img");
					li.setAttribute("class", "imagesTmp");
				img.src = source;
				li.appendChild(img);
				list.appendChild(li);
		}
	}  

	if (window.FormData) {
  		formdata = new FormData();
  		document.getElementById("btn").style.display = "none";
	}
	
	
 	input.addEventListener("change", function (evt) {
 		var i = 0, len = this.files.length, img, reader, file;		
		for (i ; i < len; i++ ) {
			file = this.files[i];
			if (!!file.type.match(/image.*/)) {
				if ( window.FileReader ) {
					reader = new FileReader();
					reader.onloadend = function (e) { 
						showUploadedItem(e.target.result, file.fileName);
					};
					reader.readAsDataURL(file);
				}
				if (formdata) {
					formdata.append("images[]", file);
				}
			}	
		}

		/* set untuk img to save */
		if(formdata) {
			$.ajax({				
				url: "/web/whaleadmin2014/lib/uploadUrelsave.php",
				type: "POST",
				data: formdata,
				processData: false,
				contentType: false,
				success: function (res) {
					var jumimg = $("#oriImgRel > li").length;
					var jumphoto_img = parseInt(jumimg)+1;
					
					if(jumphoto_img < 11){
						var tmpUrel = $("#oriImgRelTmp").html();
						var hasilTmp = tmpUrel+res;
						$("#oriImgRel").html(hasilTmp);			 
					}
					
					$("a#linkurel_save, a#linkurel_save_edit").removeAttr("style");;	
					$("#loadding_doackimg").attr("style","display:none;");
				}
			});
		}		 
	 
		
	}, false);
	
	
}());
