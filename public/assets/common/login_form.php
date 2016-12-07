<form action="" method="POST">
	<?php $return_url = isset($_GET['return_url']) ? urldecode($_GET['return_url']) : 'home/';	// Fetch URL to redirect to ?>
   <table width="100%" border="0">
     <tr><td colspan="2"><label>Username<br /><input type="text" name="user_name" id="user_name" class="login_ctrl text_input" value="<?php echo @$user_name ?>" /></label></td></tr>
     <tr><td colspan="2"><label>Password<br /><input type="password" name="password" id="password" class="login_ctrl text_input" value="" /></label></td></tr>
     <tr><input type="hidden" name="goto" value="<?php echo $return_url ?>" /></tr>
     <tr>
        <td align="left"><label><input type="checkbox" name="rem_me" id="rem_me" />Remember Me</label></td>
        <td align="right"><input type="submit" name="btn_submit" id="btn_submit" class="button" value="Log In" /></td>
     </tr>
   </table>
</form>
