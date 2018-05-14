<?php
  session_start();
  require('../../config/nuke_library.php');
  include('config/config.php');
  
  $rid 				= (int) $_GET['id'];
  $table_name	= $_GET['table_name'];
  $caption 		= unserialize(CAPTION);
  $ease 			= unserialize(EASING);
  $TRANSITION = unserialize(TRANSITION);
  $KLASS 			= unserialize(KLASS);
 
  $rph 		= $db->query("SELECT id, link, transition, slot, target, rank, images FROM `".$table_name."` WHERE id=$rid");
  $rwh 		= $rph->fetch_row();
 	$target = ($rwh[4]==1) ? '_blank' : '_self';
 ?>

	<div id="revo-preview" style="width:1080px; height:720px; overflow:hidden;">

		<ul>
				<li data-transition="<?php echo $TRANSITION[$rwh[2]];?>" data-slotamount="<?php echo $rwh[3];?>" <?php if(!empty($rwh[1])){ ?> data-link="<?php echo $rwh[1];?>" data-target="<?php echo $target;?>" <?php } ?> >
					<img src="<?php echo $rwh[6];?>" alt="" />
					
					<?php 
					  $rps = $db->query("SELECT id, slide, class, xpos, ypos, speed, delay, easing, type, type_text FROM big_revoslide WHERE rid=$rid AND `table_name` = '".$table_name."' ORDER BY layer ASC");
					  while($row = $rps->fetch_row()):
					  	$type_text = $row[9]; ?>

	            <div class="caption <?php echo $KLASS[$row[2]];?>" data-x="<?php echo $row[3]-60;?>" data-y="<?php echo $row[4];?>" data-speed="<?php echo $row[5];?>" data-start="<?php echo $row[6];?>" data-easing="<?php echo $ease[$row[7]];?>">
	              
		              <?php if($row[8]==3): ?>
								  					<a href="#" class="green-btn" style="min-width:115px; max-width:550px;"><?php echo $row[1]; ?></a>
									<?php elseif($row[8]==2): ?>
			                        
								            <?php 
								            if($type_text==1) 			{ echo'<span class="revo-txt1p">'.$row[1].'</span>'; }
													  else if($type_text==2) 	{ echo'<span class="revo-txt2p">'.$row[1].'</span>'; }
													  else if($type_text==3) 	{ echo'<span class="revo-txt1p"><strong>'.$row[1].'</strong></span>';}
													  else 										{ echo'<span class="revo-txt2p"><strong>'.$row[1].'</strong></span>'; }
														?>
			                          
		    					<?php elseif($row[8]==4): ?>

				    								<?php 
				    								if($type_text==1) 			{ echo'<span class="lighter revo1-txt-bigp">'.$row[1].'</span>'; }
													  else if($type_text==2) 	{ echo'<span class="lighter revo2-txt-bigp">'.$row[1].'</span>'; }
													  else if($type_text==3) 	{ echo'<span class="lighter revo1-txt-bigp"><strong>'.$row[1].'</strong></span>'; }
													  else 										{ echo'<span class="lighter revo2-txt-bigp"><strong>'.$row[1].'</strong></span>'; }
														?>

									<?php elseif($row[8]=1): ?>

									  				<img src="<?php echo $SITE_URL."uploads/".$row[1];?>" alt="" />

									<?php endif; ?>

	            </div>

						<?php 
						endwhile;?>

				</li>

	  </ul>
  	<div class="tp-bannertimer"></div>
	</div>