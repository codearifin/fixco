<?php
	require("../../config/connection.php");
	require("../../config/myconfig.php");
	require("function.php");
		
	$idorder = $_GET['idorder'];	
	$que = $db->query("SELECT `id`,`date` FROM `order_header` WHERE `id`='$idorder'");
	$row = $que->fetch_assoc();
?>
<style>
#order-detail {padding:20px 20px; background:#fff; width:280px;}
#order-detail h2 {color:#444; font-size:15px; letter-spacing:0.05em; margin-bottom:5px; text-transform:uppercase; line-height:1.2em;}
#order-detail h2 span {display:block;}
#order-detail .order-date {margin-bottom:15px; font-size:12px;}
.od-top h3 {color:#939598; font-weight:bold; letter-spacing:0.05em; margin-bottom:4px;}
.od-top p {font-size:12px; line-height:1.5em; border:1px solid #e9e9ea; padding:8px 10px; margin-bottom:12px;}

#order-detail .odb-top h2 {background:#444; letter-spacing:0.05em; text-transform:uppercase; color:#939598; padding:10px 15px; font-size:12px; margin-bottom:0;} 
.odb-child {border:1px solid #d9dbdc; margin-bottom:-1px; padding:10px 15px; overflow:hidden;}
.odb-child .img-wrap {float:left; width:35px; height:35px; border:1px solid #9e9e9e; position:relative; margin-right:5px;}
.odb-child .img-wrap:hover {border:1px solid #aa1f26;}
.odb-child .img-wrap img {display:block; max-width:90%; max-height:90%; position:absolute; margin:auto; top:0; right:0; bottom:0; left:0;}
.odb-child .scb1-txt {margin-left:0px;}
.odb-child .scb1-2 {margin:5px 0;}
.odb-left, .odb-right {font-weight:bold; color:#aa1f26; letter-spacing:0.05em; font-size:15px;} 
.odb-left {float:left;}
.odb-right {float:right;}

.la-stat {background:#f7f7f6; padding:10px 15px; border-bottom:1px solid #dbdbdc; color:#9d9fa2; line-height:1.5em; font-size:12px;}
.la-stat .status-time {font-weight:bold; color:#444; font-size:14px; margin-bottom:5px; display:block;}

.scb1-1 {}
.scb1-1 h3 a {color:#444;}
.scb1-1 h3 a:hover {color:#aa1f26;}
.scb1-2 {margin:15px 0;}
.scb1-2 input {border:1px solid #cbcdcf; width:40px; height:30px; text-align:center; color:#9d9fa2; font-size:12px; margin:0 3px;}
.scb1-3 {font-weight:bold; font-size:14px; color:#aa1f26;}

#add_listform span.error { display:block; color:#FF6600; padding-bottom:5px;}


@media(min-width:480px){

	#order-detail {width:400px;}
	#order-detail h2 span {display:inline;}
	.od-top h3 {font-size:15px;}
	.od-top p {font-size:14px;}
	#order-detail .odb-top h2 {font-size:14px;}
	.odb-child .img-wrap {width:60px; height:60px;}
	.odb-child .scb1-txt {margin-left:0px;}
	.odb-left, .odb-right {font-size:18px;}
	
	.la-stat {padding:15px 15px; font-size:14px;}
	.la-stat .status-time {font-size:16px; margin-bottom:5px;}
}
</style>

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

<div id="order-detail">
	<h2 class="f-gm">Order ID <span><?php echo sprintf('%06d',$row['id']).'';?></span></h2>
    <p class="order-date">Order Date: <?php echo $row['date'];?></p>
  
    <div class="od-bottom">
    	<div class="odb-top">
            <h2 class="f-mb">Detail Status</h2>
				<div style="clear:both; height:5px;" class="wrapper-lastpage"></div>
				<?php
                        $pagestatus = 0;
                        $quee3 = $db->query("SELECT `id`,DATE_FORMAT(`date`,'%d %M %Y %H:%i:%s') as tgl,`description` FROM `status_detailorder` WHERE `idorder`='$idorder' ORDER BY `date` DESC") or die(mysql_error());
                        while($data3 = $quee3->fetch_assoc()):
                            $output.='<div class="la-stat" id="pagenote'.$data3['id'].'">';
                                $output.='<span class="status-time">'.$data3['tgl'].' &nbsp; <a href="#" id="page-'.$data3['id'].'" class="delete_listnote"><img src="images/icon/icon-x.png" width="11"></a></span>';
                                $output.='<p class="status-text">'.nl2br($data3['description']).'</p>';
                            $output.='</div><!-- .la-stat -->';	
                            $pagestatus++;
                        endwhile;
                        echo $output;		
                        if($pagestatus==0): echo'<span style="padding-top:10px; display:block; color:#FF6600;" class="noteemptytext">Record not found.</span>'; endif;	
                ?>
				
                <div style="clear:both; height:5px;"></div>
                <div style="padding-top:10px;">
                	<form action="#" method="post" id="add_listform">
                    	<input type="hidden" name="orderid" value="<?php echo $idorder;?>" />
                    	<textarea style="width:90%; border:1px solid #ccc; padding:10px;" name="notetext"></textarea>
                    	<input type="submit" value="SUBMIT" class="savenoteorder-list" style="cursor:pointer; border:none; background:#aa1f26; color:#fff; padding:5px 10px 5px 10px; margin-top:10px;"/>
                    </form>
                </div>
                                    
        </div><!-- .odb-top -->
    </div><!-- .od-bottom -->
    
</div><!-- #order-detail -->