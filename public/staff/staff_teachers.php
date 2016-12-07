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
if(isset($_POST['updtThisClass'])){
    $_POST['chkVal'] = filter_var($_POST['chkVal'], FILTER_VALIDATE_BOOLEAN)?filter_var($_POST['chkVal'], FILTER_VALIDATE_BOOLEAN):0;
    $updtThisClass = "UPDATE class_subject set ins_yn=:ins_yn WHERE class_subject_id=:class_subject";
    $params = array(':ins_yn' => $_POST['chkVal'], ':class_subject' => $_POST['key']);
    $result = $fxns->_execQuery( $updtThisClass, false, false, $params );
    
    if(!is_array($result)){
        $result= array('result'=>'Success','msg'=>'Record Updated Successfully');
    }
    echo json_encode($result);
    exit;
}
if(@$_GET['teacher']){
    $_getParams = explode(":", $_GET['teacher']);
    if($_getParams[0]=='delete'){
        $params = array(':person_id' => $_getParams[1]);
            $sql = "UPDATE person SET status=0 WHERE person_id=:person_id";
            $result = $fxns->_execQuery( $sql, false, false, $params );
//        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
/*
* Include the header
*/
include_once 'assets/common/header.inc';
?>
<style type="text/css">
    .fa-check{color:'#060'};
    .fa-times{color:'#600'};
</style>
<!-- Body content here http://www.elated.com/articles/admin-templates-giveaway/ -->
<?php

echo "<h3><a href='?teacher=new' style='float:right; font-weight:normal'><i class='fa fa-plus-circle fa-lg'></i> Add New</a>Teachers</h3>";

if(@$_GET['teacher']){
    $person_type = 1;
    if(isset($_POST['submitTeacher'])){
        if(isset($_POST['person_id'])){
            $sql = "UPDATE person p 
                    LEFT JOIN teacher t
                        on p.person_id=t.teacher_id
                    SET p.username=:username, p.f_name=:f_name, p.m_name=:m_name, p.l_name=:l_name, p.dob=:dob, p.sex=:sex, p.phone=:phone, t.teacher_type_id=:teacher_type_id
                    WHERE p.person_id=:person_id";
            $params = array(':person_id' => $_POST['person_id']
                        , ':username' => $_POST['username']
                        , ':f_name' => $_POST['f_name']
                        , ':m_name' => $_POST['m_name']
                        , ':l_name' => $_POST['l_name']
                        , ':dob' => date('Y-m-d', strtotime($_POST['dob']))
                        , ':sex' => $_POST['sex']
                        , ':phone' => $_POST['phone']
                        , ':teacher_type_id'=>$_POST['teacher_type_id']);
            $result = $fxns->_execQuery( $sql, false, false, $params );
        }else{
            
            try {
                $dbo->beginTransaction();
                    $sql ="INSERT INTO person (username, person_type_id, f_name, m_name, l_name, dob, sex, phone, p_word, status) "
                            . "VALUES (:username, :person_type_id, :f_name, :m_name, :l_name, :dob, :sex, :phone, :p_word, :status)";
                    $params = array(':username' => $_POST['username']
                                , ':person_type_id' => $person_type
                                , ':f_name' => $_POST['f_name']
                                , ':m_name' => $_POST['m_name']
                                , ':l_name' => $_POST['l_name']
                                , ':dob' => date('Y-m-d', strtotime($_POST['dob']))
                                , ':sex' => $_POST['sex']
                                , ':phone' => $_POST['phone']
                                , ':p_word' => password_hash(sha1(SALT . md5("12" . SALT)), PASSWORD_DEFAULT)
                                , ':status' => '1'
                        );
                        $stmt = $dbo->prepare($sql);
                        $stmt->execute($params);
                        $id = $dbo->lastInsertId();
                    $sql = "INSERT INTO teacher (teacher_id, teacher_type_id) VALUES (:id,:teacher_type_id)";
                    $params = array(':id' => $id, ':teacher_type_id'=>$_POST['teacher_type_id']);
                    $result = $fxns->_execQuery( $sql, false, false, $params );
                $dbo->commit();
                
            } catch (Exception $e) {
                $dbo->rollback();
                $results = array('result' => 'Failure', 'msg' => "The Transaction batch failed due to the following reason:<blockquote>" . $e->getMessage() . "</blockquote>");
            }
        }
        if(is_array($result)){
            echo $result['msg'];
        }elseif($result==0){
            echo "No Changes were detected";
        }else{
            echo "Transaction Successful";
        }
    }
    $_getParams = explode(":", $_GET['teacher']) ;
    $studentTitle = array('person_id' => array('title'=>'person_id', 'display'=>0)
                        , 'person_type_id' => array('title'=>'person_type_id', 'display'=>0)
                        , 'username' => array('title'=>'User Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'f_name' => array('title'=>'First Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'm_name' => array('title'=>'Middle Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'l_name' => array('title'=>'Last Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'dob' => array('title'=>'Date Of Birth:', 'display'=>1, 'type'=>'text', 'class'=>'datePicker p300')
                        , 'sex' => array('title'=>'Sex:', 'display'=>1, 'type'=>'select', 'class'=>'p300', 'query'=>"SELECT val_id, val_dsc FROM t_wb_lov WHERE def_id='00-SEX'")
                        , 'phone' => array('title'=>'Phone:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'teacher_type_id' => array('title'=>'Teacher Type:', 'display'=>1, 'class'=>'p300', 'type'=>'select', 'query'=>"SELECT val_id, val_dsc FROM t_wb_lov WHERE def_id='00-TT'")
                    );
    echo "<form name='studentForm' method='post'>";
    if($_getParams[0]=="edit"){
        $options=array('where'=>array("p.person_type_id"=>1,'p.person_id'=>$_getParams[1]));
        $getPrsnByType = $cust->_getPerson( $options );
        echo "<a href='$_getParams[1]' class='failBtn resetPass'>Reset password</a>";
        echo "<input type='hidden' name='person_id' value='$_getParams[1]'>";
        $options = array('edit'=>array('page'=>'student=edit')
                        ,'delete'=>1, 'key'=>'person_id'
                        , 'addButton'=>array('type'=>'button','name'=>'submitTeacher', 'class'=>'edit', 'value'=>'Update'));
        echo $fxns->_buildTable($getPrsnByType, true, $studentTitle, $options);
        
        $sqlGetCls4Tchr = "SELECT * FROM class_subject WHERE teacher_id=:teacher_id";
        $params = array(':teacher_id' => $_getParams[1]);
        $result = $fxns->_execQuery( $sqlGetCls4Tchr, true, true, $params );
        
        $classSubjTitle = array('class_subject_id' => array('title'=>'person_id', 'display'=>0)
                    , 'class_id' => array('title'=>'Class Name', 'display'=>1, 'type'=>'LOVDsc', 'query'=>"SELECT class_name val_dsc FROM class WHERE class_id=:val_id")
                    , 'subject_id' => array('title'=>'Subject', 'display'=>1)
                    , 'ins_yn' => array('title'=>'In Use?', 'display'=>1, 'type'=>'checkbox', 'class'=>'ins_yn')
                    , 'created' => array('title'=>'Date Created', 'display'=>1)
                );
        $options = array('edit'=>array(), 'delete'=>array(), 'key'=>'class_subject_id','selectable'=>1);
        echo '<fieldset><legend>Classes and Subjects currently assigned to teacher</legend>';
        echo $fxns->_buildTable($result, false, $classSubjTitle, $options, 'teacher_class');
        echo '</fieldset>';
        
    }else{
        $options = array( 'addButton'=>array('type'=>'button','name'=>'submitTeacher', 'class'=>'add', 'value'=>'Submit') );
        echo $fxns->_buildTable(array(0=>$studentTitle), true, array(), $options, 'students');
    }
    echo "</form>";
}else{
    $personTitle = array('person_id' => array('title'=>'person_id', 'display'=>0)
                        , 'person_type_id' => array('title'=>'person_type_id', 'display'=>0)
                        , 'username' => array('title'=>'User Name', 'display'=>1)
                        , 'f_name' => array('title'=>'First Name', 'display'=>1)
                        , 'm_name' => array('title'=>'Middle Name', 'display'=>1)
                        , 'l_name' => array('title'=>'Last Name', 'display'=>1)
                        , 'dob' => array('title'=>'Date Of Birth', 'display'=>1)
                        , 'sex' => array('title'=>'Sex', 'display'=>1, 'type'=>'LOVDsc', 'query'=>"SELECT val_dsc FROM t_wb_lov WHERE def_id='00-SEX' AND val_id=:val_id")
                        , 'phone' => array('title'=>'Phone', 'display'=>1)
                    );
    $currentpage=@$_GET['currentpage'];
    $page = array('rowsperpage'=>15);
    isset($currentpage)?$page['currentpage']=$currentpage:'';
    
    $getPrsnByType = $cust->_getPerson(array('where'=>array("p.person_type_id"=>1,"p.status"=>1)), $page);
    $preparePaging = array_pop($getPrsnByType);
    
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
    
    $options = array('edit'=>array('page'=>'teacher=edit', 'class'=>'editPerson'),'delete'=>array('page'=>'teacher=delete'),'selectable'=>1, 'key'=>'person_id', 'page'=>$preparePaging);
    echo $fxns->_buildTable($getPrsnByType, false, $personTitle, $options, 'myClass');
}
?>

<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc';
?>
<script type="text/javascript">
$(function(){
    
    $('a.resetPass').on('click', function(e) {
        e.preventDefault();e.stopPropagation();
        $data = $.param({resetPass:1, 'id': <?php echo $_getParams[1] ?>});
        $this = $(this);
        if($this.siblings('i').length > 0){
            $this.siblings('i').remove()
        }
        $this.after( '&nbsp;&nbsp;&nbsp;<i class="fa fa-spinner fa-spin"></i>' );
        $.ajax({
            "type": "POST","url": "assets/common/ajax.inc.php","data": $data,
            "success": function(data) {
                data = $.parseJSON(data);
                if(data.result == 'Failure'){
                    $this.siblings('i').removeClass('fa-spinner fa-spin').addClass('fa-times').hide(5000);
                    alert(data.msg);
                }else{
                    $this.siblings('i').removeClass('fa-spinner fa-spin').css({'font-size':'1.2em'}).html(data.msg);
                }
            }
        });
    })
    $('table.teacher_class .ins_yn').click(function() {
        $data = $.param({'updtThisClass': 1, chkVal:$(this).is(":checked"), key:$(this).parents('tr').find("input[name=row]").val()});
        $this = $(this);
        if($this.siblings('i').length > 0){
            $this.siblings('i').remove()
        }
        $this.after( '<i class="fa fa-spinner fa-spin"></i>' );
        $.ajax({
            "type": "POST","url": window.location.href,"data": $data,
            "success": function(data) {
                data = $.parseJSON(data);
                if(data.result == 'Failure'){
                    $this.siblings('i').removeClass('fa-spinner fa-spin').addClass('fa-times').hide(5000);
                    alert(data.msg);
                }else{
                    $this.siblings('i').removeClass('fa-spinner fa-spin').addClass('fa-check').hide(5000);
                }
            }
        });
    });
});
</script>