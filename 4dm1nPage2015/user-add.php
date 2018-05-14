<?php include("header.php"); ?>

<script>
$(document).ready(function() {
  $("#main-nav li.top-<?php echo $theMenu['url']; ?>").find("a").addClass("active");
  <?php if(isset($theSubMenu)) { ?>
  $(".side-menu li.left-<?php echo $theSubMenu['url']; ?>").find("a").addClass("current");
  <?php }?>
});
</script>

	<style>
        .check_wrap { float:left; font-weight:600; margin-right:20px;}
		.check_wrap span { float:right; margin-right:1px; margin-left:2px; width:92px; font-size:11px;}
        input.checkInput { width:10px;}
        .clearp { clear:both; height:10px;}
    </style>
 
    <div id="cms-content" class="clearfix">
		<?php show_left_menu($theMenu);?>
        <div class="cms-main-content right">
            <?php check_privileges(); ?>
        	<div class="cm-top">
            	<h2><a href="user-view.php">CMS User</a> &raquo; New User</h2>
            </div><!-- .cm-top -->
            <div class="cm-mid">
            	<form action="" method="post" class="general-form" id="adduser_form">
                    <table cellspacing="0" cellpadding="0" class="browse-table">
                        <tr>
                        	<td class="td1"><label for="name_member22">Name</label></td>
                            <td><input id="name_member22" type="text" name="name" maxlength="200" placeholder="eg: Customer Service" /></td>
                        </tr>
                                             
                        <tr>
                        	<td class="td1"><label for="name_member">Username</label></td>
                            <td><input id="name_member" type="text" name="name_member" maxlength="50" /></td>
                        </tr>
                        <tr>
                            <td class="td1"><label for="dash_newpass">Password</label></td>
                            <td><input id="dash_newpass" type="password" name="dash_newpass" maxlength="20" /></td>
                        </tr>
                        <tr>
                            <td class="td1"><label for="dash_cpass">Confirm Password</label></td>
                            <td><input id="dash_cpass" type="password" name="dash_cpass" maxlength="20" /></td>
                        </tr>
                        <tr>
                            <td class="td1"><label for="name_member22">Privilege Level</label></td>
                            <td>
                                <select name="privilege_level">
                                  <option>- Select Privilege -</option>
                                  <?php
                                  $arr_privileges = global_select("master_privilege", "*", false, "`id` ASC");
                                  if($arr_privileges) { foreach($arr_privileges AS $row) {
                                    echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                  }}
                                  ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        	<td></td>
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