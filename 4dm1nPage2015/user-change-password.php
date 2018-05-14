<?php include("header.php"); ?>
	
<script>
$(document).ready(function() { 
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
});
</script>
    
    <div id="cms-content" class="clearfix">
		<?php show_left_menu($theMenu);?>
        <div class="cms-main-content right">
            <?php #ga perlu privileges #check_privileges(); ?>
        	<div class="cm-top">
            	<h2>Change Password</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">
            	<?php
      					$querypage = $db->query("SELECT `id`,AES_DECRYPT(C10010289,'1abc18908aa11001') as uname FROM `1001ti14_vi3w2014` WHERE `id`='".$_SESSION[$SITE_TOKEN.'userID']."'");
      					$data= $querypage->fetch_assoc();			
      				?>
            	<form action="" method="post" class="general-form" id="change_passform">
                  <table cellspacing="0" cellpadding="0" class="browse-table">
                    <tr>
                        <td class="td1"><label for="dash-uname">Username</label></td>
                        <td><input id="dash-uname" type="text" disabled="disabled" value="<?php if($_SESSION[$SITE_TOKEN.'userID']==1): echo'Superadmin'; else: echo $data['uname']; endif;?>" /></td>
                    </tr>
                    
                    <tr>
                        <td class="td1"><label for="dash_newpass">New Password</label></td>
                        <td><input id="dash_newpass" type="password" name="dash_newpass" maxlength="20" /></td>
                    </tr>
                    <tr>
                        <td class="td1"><label for="dash_cpass">Confirm New Password</label></td>
                        <td><input id="dash_cpass" type="password" name="dash_cpass" maxlength="20" /></td>
                    </tr>
                    <tr>
                        <td class="td1"></td>
                        <td>
                            <div class="btn-area clearfix">
                                <input type="submit" value="SAVE" class="submit-btn left" />
                                <input type="reset" value="RESET" class="delete-btn left" />
                                &nbsp;
                                <img src="images/loading.gif" class="imgload" style="display:none;" />                                    
                            </div><!-- .btn-area -->
                        </td>
                    </tr>
                  </table>
              </form>
          </div><!-- .cm-mid -->
        </div><!-- .cms-main-content -->
    </div><!-- #cms-content -->
    
<?php include("footer.php"); ?>