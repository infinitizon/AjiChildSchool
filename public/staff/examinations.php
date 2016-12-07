<?php
/*
 * Include necessary files
 */
include_once 'core/init.inc.php';
if (@$_SESSION['user']['type'] != 1) {
    header('Location: /');
}
$page_title = ":: Home &rsaquo;&rsaquo; Leave management system ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css','common.css');
$page_css_files = array('general.css');
$font_awesome_files = array('font-awesome.css', 'prettify.css');
$common_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.11.min.js', 'slides.min.jquery.js');
$page_js_files = array('general.js');

$cust = new Customer($dbo);
$fxns = new Functions($dbo);
/*
* Include the header
*/
include_once 'assets/common/header.inc';
?>
<style type="text/css">
@media print {
    body * {visibility: hidden;}
    #stud_sheet, #stud_sheet * {
      visibility: visible;
      font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
      -webkit-print-color-adjust: exact; 
    }
    #stud_sheet {
      width: 96% !important;
      position: absolute;
      left: 0;top: 0;margin: 0;
      position: fixed;
      
      padding: 15px;
      font-size: 1.2em;
      line-height: 22px;
    }
    
    div.schoolTitle{width:100%; margin:auto;text-align:center;line-height: 28px;}
    div.schoolTitle img.logo{float:left; margin-left:6%;}
    div.schoolTitle div.title{margin-left:7%;}
    #stud_sheet table span.bold{font-weight:bold;}
    table input{border:none;}
    table.my_tables{
        width:100%; margin-top:20px; margin-bottom:20px;
        border-collapse: collapse;
    }
    table.my_tables td, table.my_tables th {
        font-size: 1em;
        border: 1px solid #CCC;
        padding: 3px 7px 2px 7px;
    }
    table.my_tables th {
        font-size: 1.1em; text-align: left;
        padding-top: 5px; padding-bottom: 4px;
        background:#333 !important; color: #ffffff;
    }

    table.my_tables tr.alt td {color: #000000; background:#EAF2D3 !important;}
}
</style>
<fieldset>
    <legend>Result Sheet</legend>
    <h3>In this section, assign scores to students based on test type</h3>
<?php
    //First selet a class
    echo "<h3>Select class you want to work with</h3>";
    $query = "select class_id val_id, class_name val_dsc FROM class";
    $query .= ($_COOKIE['teacher_type']!=1)?" WHERE class_id in (select class_id from class_subject where teacher_id = {$_SESSION['user']['id']})":'';
    $query .= " ORDER BY class";
    echo $fxns->_getLOVs($query, "val_id", "val_dsc", "exam_teacher_class", "p300 exam_teacher_class", "-- Select Class --", NULL);
    echo "<select class='p300 pExam_sessions' name='pExam_sessions'><option>-- Select Session --</option></select>";
    //On change of class redraw the list of students in the class
//    $query = "select session_term_id val_id, session_term_desc val_dsc FROM session_term";
//    $query .= " WHERE class_id in (SELECT class_id FROM class_subject WHERE teacher_id={$_SESSION['user']['id']})";
//    echo $fxns->_getLOVs($query, "val_id", "val_dsc", "pExam_sessions", "p300 pExam_sessions", "-- Select Session --", NULL);
?>
<div id="popStudExams"></div>
<div id="alerts"></div>
</fieldset><br /><br />
<?php

/*
* Include the footer
*/
include_once 'assets/common/footer.inc';
?>