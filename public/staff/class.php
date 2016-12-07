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
if(@$_GET['class']){
    $_getParams = explode(":", $_GET['class']);
    if($_getParams[0]=='delete'){
        $params = array(':class_id' => $_getParams[1]);
        $sql = "delete from class WHERE class_id=:class_id";
        $result = $fxns->_execQuery( $sql, false, false, $params );
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
include_once 'assets/common/header.inc';
?>

<!-- Body content here http://www.elated.com/articles/admin-templates-giveaway/ -->
<?php
echo "<h3><a href='?class=new' style='float:right; font-weight:normal'><i class='fa fa-plus-circle fa-lg'></i> Add New</a>Setup the different Classs</h3>";

if(@$_GET['class']){
    if(isset($_POST['submitClass'])){
//        var_dump($_POST);exit;
        if(isset($_POST['class_id'])){
            $sql = "UPDATE class 
                    SET class=:class, section_id=:section_id, class_name=:class_name, class_desc=:class_desc
                    WHERE class_id=:class_id";
            $params = array(':class_id' => $_POST['class_id']
                        , ':class' => $_POST['class']
                        , ':section_id' => $_POST['section_id']
                        , ':class_name' => $_POST['class_name']
                        , ':class_desc' => $_POST['class_desc']);
        }else{
            $sql ="INSERT INTO class (class, section_id, class_name, class_desc) "
                            . "VALUES (:class, :section_id, :class_name, :class_desc)";
            $params = array(':class' => $_POST['class']
                        , ':section_id' => $_POST['section_id']
                        , ':class_name' => $_POST['class_name']
                        , ':class_desc' => $_POST['class_desc']);
        }
        $result = $fxns->_execQuery( $sql, false, false, $params );
        if(is_array($result)){
            echo $result['msg'];
        }elseif($result==0){
            echo "No Changes were detected";
        }else{
            echo "Transaction Successful";
        }
    }
    $params = explode(":", $_GET['class']) ;
        $classTtl = array('class_id' => array('title'=>'class_id', 'display'=>0)
                        , 'class' => array('title'=>'Class', 'display'=>1, 'type'=>'text')
                        , 'section_id' => array('title'=>'Section', 'display'=>1, 'type'=>'select', 'query'=>"SELECT val_id, val_dsc FROM t_wb_lov WHERE def_id='CLS-SCTN'")
                        , 'class_name' => array('title'=>'Name', 'display'=>1, 'type'=>'text')
                        , 'class_desc' => array('title'=>'Description', 'display'=>1, 'type'=>'textarea')
                    );

    echo "<form name='studentForm' method='post'>";
    if($params[0]=="edit"){
        $getClass = $cust->_getClass($params[1]);
        echo "<input type='hidden' name='class_id' value='$params[1]'>";
        $options = array('edit'=>array('page'=>'class=edit')
                        ,'delete'=>1, 'key'=>'class_id'
                        , 'addButton'=>array('type'=>'button','name'=>'submitClass', 'class'=>'editClass', 'value'=>'Update'));
        echo $fxns->_buildTable($getClass, true, $classTtl, $options);
    }else{
        $options = array( 'addButton'=>array('type'=>'button','name'=>'submitClass', 'class'=>'addClass', 'value'=>'Submit') );
        echo $fxns->_buildTable(array(0=>$classTtl), true, array(), $options, 'class');
    }
    echo "</form>";
    
}else{

    $lovs = array('class_id' => array('title'=>'class_id', 'display'=>0)
                        , 'class' => array('title'=>'Class', 'display'=>1)
                        , 'section_id' => array('title'=>'Section', 'display'=>1, 'type'=>'LOVDsc', 'query'=>"SELECT val_dsc FROM t_wb_lov WHERE def_id='CLS-SCTN' AND val_id=:val_id")
                        , 'class_name' => array('title'=>'Name', 'display'=>1)
                        , 'class_desc' => array('title'=>'Description', 'display'=>1)
                    );
    $currentpage=@$_GET['currentpage'];
    $page = array('rowsperpage'=>15);
    isset($currentpage)?$page['currentpage']=$currentpage:'';
    
    $getLovs = $cust->_getClass(NULL, $page);
    $preparePaging = array_pop($getLovs);
    
    unset($_GET['currentpage']);
    $url = explode("?", $_SERVER['REQUEST_URI']) ;
    $url['1'] = explode('&',@$url['1']);
    foreach($url['1'] as $key=>$val){
        if (strpos($val,'currentpage') === false) {
            @$use .= isset($use)? '&'.$val: $val;
        }
    }
    $url['1'] = @$use;
    $preparePaging['divLink']= array('div' => 'myPaging','param'=>$url['1']);
    $preparePaging['link']= substr(WEB_ROOT, 0, -1).$url['0'];
    $options = array('edit'=>array('page'=>"class=edit", 'class'=>'editPerson'),'delete'=>array('page'=>'class=delete'),'selectable'=>1, 'key'=>'class_id', 'page'=>$preparePaging);
    
    echo "<form name='addSubject'>";
    echo $fxns->_buildTable($getLovs, false, $lovs, $options, 'classes');
    
    echo "<input type='hidden' name='addSbjClass' value='1' />";
    echo "<input type='hidden' name='class_subject_id' value='' />";
    /******   show this to display class list *********/
    echo "<h3>Select Subjects for this class</h3>";
    $query="SELECT val_id, val_dsc FROM t_wb_lov WHERE def_id='00-SUBJ'";
    echo $fxns->_getLOVs($query, "val_id", "val_dsc", "subjects[]", "class_nm", NULL, NULL, array('size'=>6));
    /******   End: display class list *********/
    /******   show this to display teachers list *********/
    echo "<h3>Select teacher for the subjects selected in the class</h3>";
    $query="SELECT person_id val_id, CONCAT(f_name, ' ', m_name, ' ', l_name) val_dsc FROM person WHERE person_type_id='1' AND status=1";
    echo $fxns->_getLOVs($query, "val_id", "val_dsc", "teacher", "teacher", NULL, NULL);
    /******   End: display teachers list *********/
    echo "<br /><input type='submit' class='button' name='submitSbjt' value='Submit' />";
    echo "</form>";

}
?>

<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc';
?>