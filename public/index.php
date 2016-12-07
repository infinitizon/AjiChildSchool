<?php
/*
* Include necessary filesx
*/
include_once 'core/init.inc.php';
//echo $_COOKIE['rem_emp']; exit();
if(isset($_COOKIE['rem_emp'])){
	header('Location: home/');
}

if(isset($_POST['btn_submit']) && $_POST['btn_submit']=='Log In'){
	$auth = new Auth();
//	$salt_pwd = sha1(SALT . md5($_POST['password']  . SALT));
//        $hash = password_hash($salt_pwd, PASSWORD_DEFAULT, ['cost' => 12]);
        
	$user = base64_encode( $_POST['user_name'].":".$_POST['password']);
        $user = $auth->_authenticate($user);
        if($user['code']==0){
            $err_msg = $user['msg'];
        }else{
            $success_msg = $user['msg'];
//            if ($_POST['rem_me'])
//            setcookie("rem_emp", "yes", time() + (60 * 60 * 15));  //If rem_me is set from post, set a cookie to 15days.
            switch($_SESSION['user']['type']){
                case 1: //Staff
                    header('Location: /staff');     // Allow login
                case 2: // Parent
                    break;
                case 3: // Student
                    header('Location: /student');     // Allow login
                    break;
                default:
                    header('Location: ' . $_POST['goto']);     // Allow login
            }
        }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href='/assets/images/logo.png' rel='SHORTCUT ICON' />
   
<link rel="stylesheet" type="text/css" href="assets/css/index.css"/>
   <title>Child School &rsaquo; Login</title>
</head>

<body><!---Start body-->
<div id="box_holder">

   <div id="logo_holder">
	<a href="<?php echo WEB_ROOT; ?>">
      	<img src="/assets/images/logo.png" align="absmiddle" width="35" height="35" alt="Child School" />
      </a>
   </div><!-- logo_holder ends -->
   <div id="note" style="text-align:center; font-size:11px;text-shadow:1px 1px 1px #FFF; font-weight:bold;">
<?php 
    echo isset($err_msg) ? "<div class='err'>".$err_msg.'</div>' : '';
    echo isset($success_msg) ? "<div class='success'>".$success_msg.'</div>' : '';
?>
   </div>
   <div id="login_holder">
<?php require_once ("assets/common/login_form.php"); ?>
   </div><!-- login_holder ends -->
   
   <div class="others">
Copyright<sup>&copy;</sup> Child School.
   </div><!-- others ends -->
</div><!-- box_holder ends -->
<?php if(@$_GET['message'] && @$_GET['type']): ?>
<div id="notification">
    <div class="close">x</div>
    <div class="<?php echo @$_GET['type']; ?>" ><?php echo @$_GET['message']; ?></div>
</div>
<?php endif; ?>
<script type="text/javascript" src="assets/js/jquery-1.7.2.min.js"></script>
   <script type="text/javascript" src="assets/js/jquery-ui-1.8.21.custom.min.js"></script>
<?php if(@$err_msg) echo "<script type='text/javascript'>\$(function(){\$('#login_holder').effect('shake',{times:4},100);})</script>"; ?>
<script type="text/javascript" src="assets/js/common.js"></script>
</body>
</html>