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
$page_js_files = array('general.js', 'jqueryui-editable.min.js');


$cust = new Customer($dbo);
$fxns = new Functions($dbo);
/*
* Include the header
*/
if(@$_GET['sessionDef']){
    $_getParams = explode(":", $_GET['sessionDef']);
    if($_getParams[0]=='delete'){
        $params = array(':session_term_id' => $_getParams[1]);
            $sql = "delete from session_term WHERE session_term_id=:session_term_id";
            $result = $fxns->_execQuery( $sql, false, false, $params );
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
if(@$_GET['examDef']){
    $_getParams = explode(":", $_GET['examDef']);
    if($_getParams[0]=='delete'){
        $params = array(':exam_type_id' => $_getParams[1]);
            $sql = "UPDATE exam_type set ius_yn=0 WHERE exam_type_id=:exam_type_id";
            $result = $fxns->_execQuery( $sql, false, false, $params );
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
include_once 'assets/common/header.inc';
?>

<fieldset><legend>Update or create Sessions and terms per class</legend>
<div id='sessionArea'></div>
</fieldset><br /><br />
<fieldset><legend>Create / Update or Examination types per class per term</legend>
<div id='examArea'></div>
</fieldset><br /><br />
<?php
    
    // Update or create Definitions
    echo "<fieldset><legend>Update or create Definitions</legend>";
    $query="select def_id val_id, val_desc val_dsc FROM list_def";
    echo $fxns->_getLOVs($query, "val_id", "val_dsc", "definitions", "p300 definitions", "-- Select Definition --", NULL);
    echo " <i class='fa fa-plus-circle fa-2x addToDef'></i>";
    echo " <div class='hideFirst addDef' style='display:none'>"
    . "<form>"
            . " <input type='hidden' name='newDefList' value='1' />"
            . " Unique Identifier<input type='text' name='def_id' maxlength='10' />"
            . " Description<input type='text' name='val_desc' /> <i class='fa fa-floppy-o fa-2x saveDef'></i>"
    . "</form></div>";
    //On change of class redraw the list of students in the class
?>
<div id="popDefinitions"></div>
</fieldset>
<div id="alerts"></div>
<?php

/*
* Include the footer
*/
include_once 'assets/common/footer.inc';
?>
<script type="text/javascript">
$(function() {
    $data = $.param({'sessionArea': 1});
    $.ajax({
        "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
        "success": function(data) {
            $("#sessionArea").html(data);
        }
    });
    $(document).on("click", "a.sessionArea", function(e) {
        e.preventDefault();e.stopPropagation();
        $data = this.search.split('?')[1];
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $("#sessionArea").html(data);
            }
        });
    });
    //For Exam area
    $data = $.param({'examArea': 1});
    $.ajax({
        "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
        "success": function(data) {
            $("#examArea").html(data);
        }
    });
    $(document).on("click", "a.examArea", function(e) {
        e.preventDefault();e.stopPropagation();
        $data = this.search.split('?')[1];
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                $("#examArea").html(data);
            }
        });
    });
});
</script>