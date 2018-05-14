<?php 
require("../../config/connection.php");
function replacehtml($text){
		$str = array("'");
		$newtext=str_replace($str,"&rsquo;",$text);
		return $newtext;
}

date_default_timezone_set('Asia/Jakarta');
$dateNow = date("Y-m-d H:i:s");
$notetext = replacehtml($_POST['notetext']);	
$orderid = $_POST['orderid'];
$dateNowpage = date("d M Y H:i:s");

//get max id
$output = '';
$qum = $db->query("SELECT MAX(id) AS idmax FROM `status_detailorder`");
$r = $qum->fetch_assoc();	
$idmaxno = $r['idmax']+1;		
$query = $db->query("INSERT INTO `status_detailorder` (`id`,`idorder`,`description`,`date`) VALUES ('$idmaxno','$orderid','$notetext','$dateNow') ");

if($query):
     $output.='<div class="la-stat" id="pagenote'.$idmaxno.'">';
      	$output.='<span class="status-time">'.$dateNowpage.' &nbsp; <a href="#" id="page-'.$idmaxno.'" class="delete_listnote"><img src="images/icon/icon-x.png" width="11"></a></span>';
        $output.='<p class="status-text">'.nl2br($notetext).'</p>';
    $output.='</div><!-- .la-stat -->';	
	echo $output;	
else:
	echo 300;
endif;			
?>
<script type="text/javascript">
	$(document).ready(function() { 
		$('form#add_listform').validate({
		  rules: {
				notetext:{ 
					 required: true
				}		
		  },
		  messages: {
				notetext: {
					required:"* This field can't be empty."
				}	
		  },
		  errorPlacement: function(error, element){
			//error.insertBefore(element);
			error.insertAfter(element);
		  },
		   submitHandler:function(form){
				  $(".imgload").removeAttr("style");	
				  var formData = new FormData($("form#add_listform")[0]);
				  $.ajax({
					  url:'eccomerce/dosave_datalistnote.php',
					  type:'POST',
					  data:formData,
					  dataType:'HTML',
					  contentType:false,
					  processData:false,
					  cache:false,
					  success:function(html){
					  	 $("form#add_listform").find('textarea').val(""); 
						 if(html==300){
						 	alert("Insert data failed, Please refresh your computer.");
						 }else{
						 	$(".noteemptytext").remove();
						 	$(".wrapper-lastpage").after(html);
						 }
					}
				  });			  
		  
		   }
		});	
			
	$("a.delete_listnote").click(function(e) {
		var idadd=$(this).attr("id");
		var itemExplode = idadd.split("-");
		var idpage = itemExplode[1];

		$.post("eccomerce/delete_noteorder.php", {"idpage": idpage},
		function(data) {
			if(data==1){
				$("#pagenote"+idpage).remove();	
			}else{
				alert("Delete unsuccessful, Please try again!");	
			} 
		}); 

		e.preventDefault();	 
	 });		
					
});
</script>
