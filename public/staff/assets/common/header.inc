<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link href='/assets/images/favicon.ico' rel='SHORTCUT ICON' />

<title><?php echo $page_title; ?></title>

<meta name="title" content="School management system for the Child School, Lagos State, Nigeria.">
<meta name="keywords" content="School Management System, School, Ministry of education, School Management, Lagos State, Nigeria.">
<meta name="description" content="A governmental application for managing leave applications in the Lagos State Ministry of Science and Tech">
<meta name="author" content="Abimbola Hassan">

<?php foreach ( $common_css_files as $css ): ?>
	<link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEB_ROOT; ?>assets/css/<?php echo $css; ?>" />
<?php endforeach; ?>
<?php foreach ( $font_awesome_files as $font_awesome ): ?>
  <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEB_ROOT; ?>assets/fontawesome/css/<?php echo $font_awesome; ?>" />
<?php endforeach; ?>
<?php foreach ( $page_css_files as $page_css ): ?>
  <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php echo WEB_ROOT; ?>staff/assets/css/<?php echo $page_css; ?>" />
<?php endforeach; ?>

</head>
<body>
    <div id="header">
        <div class="page-width"style="position:relative;">
            <div style="float:right; margin:40px 60px 0 0;">
                <?php
                    $cust = new Customer($dbo);
                    $getPrsnByType = $cust->_getPerson(array('where'=>array("p.person_id"=>$_SESSION["user"]['id'])));
                ?>
                <ul class="top-menu">
                    <li style="margin:20px; font-size:1.2em;margin-right:10px;"><span><?php echo $getPrsnByType[0]['l_name']; ?></span></li>
                    <li>
                        <a class="user_dets" href="">
                            <?php
                                echo (isset($getPrsnByType[0]['pix'])&&$getPrsnByType[0]['pix']!='') ?"<img src='{$getPrsnByType[0]['pix']}' class='fa-user' />":'<i class="fa fa-user  fa-4x"></i>';
                            ?>
                        </a>
                        <div id="user_dets" style="display: none;">
                            <div style="padding:10px;">
                                <?php
                                    echo "<strong>Welcome ".$getPrsnByType[0]['l_name'].", ".$getPrsnByType[0]['f_name']." ".$getPrsnByType[0]['m_name']."</strong>";
                                ?>
                            </div>
                            <div style="padding:10px; background:#F5F5F5; border-top:1px solid #C4C4C4;">
                                <a href="<?php echo WEB_ROOT; ?>/?logout=yes" class="fa fa-power-off button" style="background:#900; color:#FFF; float:right;"><i class=""></i> Logout</a>
                                <div style="clear:both;"></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!--div style="position:absolute;bottom:50px;right:50px;"> 
                
                <a class="user_dets" href="">
                    <?php
                        echo (isset($getPrsnByType[0]['pix'])&&$getPrsnByType[0]['pix']!='') ?"<img src='{$getPrsnByType[0]['pix']}' class='fa-user' />":'<i class="fa fa-user  fa-4x"></i>';
                    ?>
                </a>
                <div id="user_dets" style="display: none;">
                    <div style="padding:10px;">
                        <?php
                            echo "<strong>Welcome ".$getPrsnByType[0]['l_name'].", ".$getPrsnByType[0]['f_name']." ".$getPrsnByType[0]['m_name']."</strong>";
                        ?>
                    </div>
                    <div style="padding:10px; background:#F5F5F5; border-top:1px solid #C4C4C4;">
                        <a href="<?php echo WEB_ROOT; ?>/?logout=yes" class="fa fa-power-off button" style="background:#900; color:#FFF; float:right;"><i class=""></i> Logout</a>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            </div-->
            <img src="/assets/images/logo.png" alt="Child School Management System"/><span style="font-size:2em;">School Management System</span>
            <div class="clear_both"></div>
        </div>
    </div>
    <div id="general_container" class="page-width">
        <div id="sidebar_left">
            <fieldset>
                <legend>Search</legend>
                <input type="text" style="width:95%" />
            </fieldset>
            <fieldset>
                <legend>Calender</legend>
                <div id="datepicker"></div>
            </fieldset>
            <fieldset>
                <legend>Teacher Menu</legend>
                <ul class="navigation">
                    <li><a href="<?php echo WEB_ROOT; ?>staff/examinations.php">Examinations</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>staff/students.php">Students</a></li>
                </ul>
            </fieldset>
            <?php
            if(@$_COOKIE["teacher_type"]==1){
            ?>
            <fieldset>
                <legend>Admin Menu</legend>
                <ul class="navigation">
                    <li><a href="<?php echo WEB_ROOT; ?>staff/staff_teachers.php">Teachers</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>staff/class.php">Classes</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>staff/LOVS.php">Setup</a></li>
                </ul>
            </fieldset>
            <?php
            }
            ?>
        </div>
        <div id="main_content">