<?php
	session_start();
  if(!isset($_GET['id']) OR !isset($_GET['table_name'])) {
  	echo "Anda harus menyertakan Parameter `id` dan `table_name`"; exit;
  }

  $rid = (int) $_GET['id'];
  $table_name = $_GET['table_name'];
	require('../../config/nuke_library.php');
	include('config/config.php');
	
	$caption 		= unserialize(CAPTION);
	$ease 			= unserialize(EASING);
	$TRANSITION = unserialize(TRANSITION);
	$KLASS 			= unserialize(KLASS);
	
	$rc = $db->query("SELECT COUNT(id) FROM big_revoslide WHERE rid = '".$rid."' AND `table_name` = '".$table_name."'");
	list($count) = $rc->fetch_row();

	$rd 	= $db->query("SELECT id, slide, class, xpos, ypos, speed, delay, easing, type, layer, type_text FROM `big_revoslide` WHERE rid=$rid AND `table_name` = '".$table_name."' ORDER BY layer ASC");
	$rr 	= $db->query("SELECT id, slide, class, xpos, ypos, speed, delay, easing, type, layer, type_text FROM `big_revoslide` WHERE rid=$rid AND `table_name` = '".$table_name."' ORDER BY layer DESC");
	$rcn 	= $db->query("SELECT images FROM `".$table_name."` WHERE id='$rid'");
	$res 	= $rcn->fetch_row();
	$imgBanner = $res[0];

	$url_back = global_select_field("m3nu_4dm1n", "specific_url", "table_name_no_prefix = '".$table_name."'");
?>
  <script src="js/jquery-2.0.0.min.js" type="text/javascript"></script>	
  <script src="js/iosOverlay.js" type="text/javascript"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/jquery.validate.js"></script> 
    
  <!-- jQuery REVOLUTION Slider  -->
  <script type="text/javascript" src="js/jquery.themepunch.plugins.min.js"></script>
  <script type="text/javascript" src="js/jquery.themepunch.revolution.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/settings.css" media="screen" />
    
  <link href="js/iosOverlay.css" rel="stylesheet" type="text/css" />
  <link href="css/cms.css" rel="stylesheet" type="text/css" />    
  <script src="js/revoscript.js" type="text/javascript"></script>
   
  <div style="width:1080px; height:720px; position:relative;margin:0 auto;">

			<div style="width:1080px;position:relative;margin:0 auto;">
					 <div id="nav" style="margin-bottom:10px;">
					   <a href="#" id="add">Add New</a> &nbsp;.&nbsp; 
					   <a href="#" id="canvas" style="color:#0095d3">Position &amp; Properties</a> &nbsp;.&nbsp; 
					   <!-- <a href="#" id="property">Properties</a> &nbsp;.&nbsp;  -->
				     <a href="?<?php echo $rid;?>" tableName="<?php echo $table_name;?>" id="preview">Preview</a> &nbsp;.&nbsp; 
				     <a href="<?php echo $ADMIN_URL.$url_back;?>" id="back">Back To Slide List</a>
					 </div>


				<form action="edit.php" method="post" class="general-form" id="revo-content">
					 <input type="hidden" name="table_name" value="<?php echo $table_name?>"/>
					 <div id="canvas" style="width:1080px; height:720px; overflow:hidden; background-size:cover;box-shadow:0 4px 10px #cdcdcd;margin-bottom:10px;position:relative;">
					 	 <img src="<?php echo $SITE_URL."uploads/".$imgBanner;?>" alt="" />
					   <input type="hidden" name="maxLayer" value="<?php echo $count;?>"/>
					   	<!-- 
					   	<option value="1" selected="selected">Smooth (color : Black)</option>
	            <option value="2">Smooth (color : White)</option>
	            <option value="3">Strong (color : Black)</option>
	            <option value="4">Strong (color : White)</option> 
	            -->
					   <?php 
					   $y = $count+1; 
					   $i = 0; 
					   while(list($id, $slide, $class, $xpos, $ypos, $speed, $delay, $easing, $type, $layer, $type_text)=$rd->fetch_row()): $y--; $i++; ?>

							  <div class="draggable" id="dragger-<?php echo $i;?>" xpos="<?php echo $xpos;?>" ypos="<?php echo $ypos;?>" mark="<?php echo $i;?>">
							    <?php if($type==3): ?>
								    <a href="#" class="green-btn" style="min-width:115px;max-width:550px;"><?php echo $slide;?></a>
								  <?php elseif($type==2): ?>
								    <span class="revo-txt2" style="width:550px; <?php echo ($type_text==2 OR $type_text==4) ? "color:#fff; text-shadow:0px 0px 2px #333;":" text-shadow:0px 0px 2px #fff;"; ?>"><?php if($type_text==3 OR $type_text==4){ echo "<strong>".$slide."</strong>";}else{ echo $slide;}?></span>
								  <?php elseif($type==4): ?>
								   	<span class="revo1-txt-big" style="width:900px; <?php echo ($type_text==2 OR $type_text==4) ? "color:#fff; text-shadow:0px 0px 2px #333;":" text-shadow:0px 0px 2px #fff;"; ?>"><?php if($type_text==3 OR $type_text==4){ echo "<strong>".$slide."</strong>";}else{ echo $slide;}?></span>           
								  <?php elseif($type=1): ?>
										<img src="<?php echo $SITE_URL."uploads/".$slide;?>" alt=""/>
								  <?php endif; ?>
							  </div>

					   <?php 
					   endwhile; ?>
					 </div>

					 

					 <!-- property layer -->
					 <div id="property" style="width:1080px;background:#f8f8f8;box-shadow:0 2px 2px #cdcdcd;padding:20px 20px;box-sizing:border-box;margin-top:1px;margin-bottom:10px;">
					   
					   <table cellspacing="0" cellpadding="0" border="0" id="drag" style="font-size:12px;">
					     <tr id="header" style="font-weight:600;">
						   <td align="center">Thumb</td><td>Type</td><td>Caption</td><td align="center">X-Pos</td><td align="center">Y-Pos</td><td align="center">Speed</td>
				           <td align="center">Delay</td><td>Easing</td><td align="center">Layer</td><td align="right">Action</td>
						 </tr>
						 <?php $x=$count+1; $j=0; while(list($id, $slide, $class, $xpos, $ypos, $speed, $delay, $easing, $type, $layer)=$rr->fetch_row()): $x--; $j++; ?>

						 <tr mark="<?php echo $x;?>" class="marker">
						   <td align="center">
						     <?php if($type==3 || $type==2 || $type==4): ?>
								<span style="font-size:10px;"><?php if(strlen($slide)>10){ echo substr($slide, 0, 9).'&hellip;'; }else{ echo $slide; } ?></span>
							<?php elseif($type=1): ?>
								<img src="<?php echo $SITE_URL."uploads/".$slide;?>" alt="" width="30px" height="15px"/>
							<?php endif; ?>
						   </td>
						   <td>
						     <?php if($type==1){ echo'Image'; }elseif($type==2){ echo'Text'; }elseif($type==3){ echo'Button'; } elseif($type==4){ echo'Title'; } ?>
						   </td>

						   <td>

						     <select name="caption[]" id="caption-<?php echo $x;?>" style="-webkit-appearance:none;-moz-appearance:none;appearance:none;width:150px;">
							   <?php foreach($caption as $key => $val): ?>
							   <option value="<?php echo $key?>" <?php if($key==$class) echo'selected="selected"'; ?>><?php echo $val;?></option>
							   <?php endforeach; ?>
							 </select>
						   </td>
						 
				           <td align="center">
						     <input type="text" name="xpos[]" id="xpos-<?php echo $x;?>" class="x-pos" value="<?php echo $xpos;?>" style="text-align:center;width:60px;"/>
						   </td>

						   <td align="center">
						     <input type="text" name="ypos[]" id="ypos-<?php echo $x;?>" class="y-pos" value="<?php echo $ypos;?>" style="text-align:center;width:60px;"/>
						   </td>

						   <td align="center">
						     <input type="text" name="speed[]" id="speed-<?php echo $x;?>" class="speed" size="7" style="text-align:center;width:50px;" value="<?php echo $speed;?>" />
						   </td align="center">

						   <td>
						     <input type="text" name="delay[]" id="delay-<?php echo $x;?>" class="delay" size="7" style="text-align:center;width:50px;" value="<?php echo $delay;?>" />
						   </td>

						   <td>

						     <select name="ease[]" id="ease-<?php echo $x;?>" style="-webkit-appearance:none;-moz-appearance:none;appearance:none;width:150px;">
							   <?php foreach($ease as $key => $val): ?>
							   <option value="<?php echo $key;?>" <?php if($key==$easing) echo'selected="selected"'; ?>><?php echo $val;?></option>
							   <?php endforeach; ?>
							 </select>
						   </td>

						   <td id="layerCake-<?php echo $x;?>">

						     <select name="layer[]" id="layer-<?php echo $x;?>" class="layerCake" style="-webkit-appearance:none;-moz-appearance:none;appearance:none;width:60px;">
							   <?php for($i=$count;$i>0;$i--): ?>
							   <option value="<?php echo $i;?>" <?php if($layer==$i) echo'selected="selected"'; ?>><?php echo $i;?></option>
							   <?php endfor; ?>
							 </select>

						   </td>

						   <td align="center">
						      <ul class="btn-list">
		                <li><a href="delete.php?id=<?php echo $id;?>&table_name=<?php echo $table_name;?>" class="bl-delete del-revoelement" title="Delete">Delete</a></li>
		              </ul><!-- .btn-list -->
						   </td>
						   <input type="hidden" name="sid[]" value="<?php echo $id;?>" />
						 </tr>
						 <?php endwhile; ?>

						 <tr>
						   <td colspan="9" align="left" style="padding-top:10px;">
						     <input type="hidden" name="section" value="revo-content" />
						     <input type="hidden" name="rid" value="<?php echo $rid;?>" />
						     <input type="submit" value="Update Slide" style="cursor:pointer;" class="submit-btn"/>
						   </td>

						 </tr>
					   </table>
					   
					 </div>
				</form>
					 
				     

					 <div id="add" style="width:50%;background:#f8f8f8;box-shadow:0 2px 2px #cdcdcd;padding:20px 60px;box-sizing:border-box;margin-top:1px;margin-bottom:10px;">
					   Type:
					   <select name="elType" id="elType" style="width:120px; padding:5px;">
						 <option value="1" selected="selected">Image</option>
				         <option value="4">Title</option>
						 <option value="2">Text</option>
						 <option value="3">Button</option>
					   </select>
				       
					   <div id="add-image" style="margin-top:20px;">
					     Select image to upload:
						 <form action="add.php" class="general-form" method="post" enctype="multipart/form-data" id="revoImage">
						 	 <input type="hidden" name="table_name" value="<?php echo $table_name?>"/>
						   <input type="file" name="image" accept="image/jpg, image/jpeg, image/x-png, image/gif" id="file-image"/><br style="line-height:50px;"/>
						   <input type="hidden" name="section" value="revo"/>
						   <input type="hidden" name="clause" value="1"/>
						   <input type="hidden" name="rid" value="<?php echo $rid;?>"/>
						   <input type="hidden" name="marker" value="<?php echo $count+1;?>"/>
				           <input type="hidden" name="type_huruf" value="0"/>
						   <div class="image-holder noimg">no image</div>
						   <span id="info"></span><br/>
						   <input type="submit" name="submit" value="Add Element" class="submit-btn"/>
						 </form>
					   </div>
				       
				       
					   <div id="add-text" style="margin-top:20px;">
					     Text Description:
						 <form action="add.php" class="general-form" method="post" enctype="multipart/form-data" id="revoText">
						 	 <input type="hidden" name="table_name" value="<?php echo $table_name?>"/>

						   <textarea name="text" rows="6" cols="50" style="width:330px;"></textarea><br />
				           
				           <br />Text Style :
				           <select name="type_huruf">
				           		<option value="1" selected="selected">Smooth (color : Black)</option>
				                <option value="2">Smooth (color : White)</option>
				                <option value="3">Strong (color : Black)</option>
				                <option value="4">Strong (color : White)</option>
				           </select><br />
				           
				           <br />
						   <input type="hidden" name="section" value="revo"/>
						   <input type="hidden" name="clause" value="2"/>
						   <input type="hidden" name="rid" value="<?php echo $rid;?>"/>
						   <input type="hidden" name="marker" value="<?php echo $count+1;?>"/>
						   <span id="info"></span><br />
						   <input type="submit" name="submit" value="Add Element" class="submit-btn"/>
						 </form>
					   </div>

					   

					   <div id="add-button" style="margin-top:20px;">
					     Button Text:
						 <form action="add.php" class="general-form" method="post" enctype="multipart/form-data" id="revoButton">
						 	 <input type="hidden" name="table_name" value="<?php echo $table_name?>"/>

						   <input type="text" name="button" style="width:260px;"/><br style="line-height:50px;"/>
						   <input type="hidden" name="section" value="revo"/>
				           <input type="hidden" name="type_huruf" value="0"/>
						   <input type="hidden" name="clause" value="3"/>
						   <input type="hidden" name="rid" value="<?php echo $rid;?>"/>
						   <input type="hidden" name="marker" value="<?php echo $count+1;?>"/>
						   <span id="info"></span><br/>
						   <input type="submit" name="submit" value="Add Element" class="submit-btn"/>
						 </form>
					   </div>
				       
				       
					   <div id="add-title" style="margin-top:20px;">
					     Title:
						 <form action="add.php" class="general-form" method="post" enctype="multipart/form-data" id="revoTitle">
						 	 <input type="hidden" name="table_name" value="<?php echo $table_name?>"/>

						   <input type="text" name="button" style="width:260px;"/><br />
				           
				           <br />Text Style :
				           <select name="type_huruf">
				           		<option value="1" selected="selected">Smooth (color : Black)</option>
				                <option value="2">Smooth (color : White)</option>
				                <option value="3">Strong (color : Black)</option>
				                <option value="4">Strong (color : White)</option>
				           </select><br /><br />
				           
						   <input type="hidden" name="section" value="revo"/>
						   <input type="hidden" name="clause" value="4"/>
						   <input type="hidden" name="rid" value="<?php echo $rid;?>"/>
						   <input type="hidden" name="marker" value="<?php echo $count+1;?>"/>
						   <span id="info"></span><br/>
						   <input type="submit" name="submit" value="Add Element" class="submit-btn"/>
						 </form>
					   </div>

				       
				       
					 </div>

					 <div id="preview" style="width:1080px;height:720px;overflow:hidden; background:url('<?php echo $SITE_URL."uploads/".$imgBanner;?>') no-repeat top left; box-shadow:0 4px 10px #cdcdcd; margin-bottom:10px; position:relative;">

					 </div>
			</div>
  </div> 

<?php if(isset($_SESSION['flashdata'])) { ?>
	<!-- ALERTIFY -->
	<script src="../js/alertifyjs/alertify.min.js"></script>
	<link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css" />
	<link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css" />
	<script>
		$('document').ready(function(){
		alertify.alert('<?php echo ($_SESSION['flashdata']['type'] == 'error' ? "alert":"confirm")?>').set({
			transition:'flipx',
			title:'<?php echo ($_SESSION['flashdata']['type'] == 'error' ? "Alert":"Confirm")?>',message: '<?php echo $_SESSION['flashdata']['message']?>'}).show();
		});
	</script>
	<?php 
	unset($_SESSION['flashdata']);
} ?>