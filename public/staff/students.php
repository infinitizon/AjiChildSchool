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
if(@$_GET['student']){
    $_getParams = explode(":", $_GET['student']);
    
    if($_getParams[0]=='delete'){
        $params = array(':person_id' => $_getParams[1]);
            $sql = "UPDATE person SET status=0 WHERE person_id=:person_id";
            $result = $fxns->_execQuery( $sql, false, false, $params );
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
include_once 'assets/common/header.inc';
?>

<!-- Body content here http://www.elated.com/articles/admin-templates-giveaway/ -->
<?php

echo "<h3><a href='?student=new' style='float:right; font-weight:normal'><i class='fa fa-plus-circle fa-lg'></i> Add New</a>Students</h3>";

if(@$_GET['student']){
    $person_type=3;
    if(isset($_POST['submitStudent'])){
        if(file_exists(@$_FILES['pix']['tmp_name']) || is_uploaded_file(@$_FILES['pix']['tmp_name'])) {
            $temp = explode(".", $_FILES["pix"]["name"]);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $path = "/assets/images/profiles/".$newfilename;
            $folder=$_SERVER["DOCUMENT_ROOT"].$path;
            move_uploaded_file($_FILES["pix"]["tmp_name"], $folder);
            if(file_exists($_SERVER["DOCUMENT_ROOT"].@$_POST['oldImg']) ) @unlink($_SERVER["DOCUMENT_ROOT"].$_POST['oldImg']);
        }
        $path = isset($path)?$path:@$_POST['oldImg'];
        if(isset($_POST['person_id'])){
            $sql = "UPDATE person p 
                    LEFT JOIN student s
                        on p.person_id=s.student_id
                    SET p.username=:username, p.f_name=:f_name, p.m_name=:m_name
                        , p.l_name=:l_name, p.dob=:dob, p.pix=:pix, p.sex=:sex, p.phone=:phone, s.class_id=:class_id
                        , s.times_present=:times_present, s.times_punctual=:times_punctual, s.teacher_comment=:teacher_comment, s.principal_comment=:principal_comment
                    WHERE p.person_id=:person_id";
            $params = array(':person_id' => $_POST['person_id']
                        , ':pix' => $path
                        , ':username' => $_POST['username']
                        , ':f_name' => $_POST['f_name']
                        , ':m_name' => $_POST['m_name']
                        , ':l_name' => $_POST['l_name']
                        , ':dob' => date('Y-m-d', strtotime($_POST['dob']))
                        , ':sex' => $_POST['sex']
                        , ':phone' => $_POST['phone']
                        , ':class_id' => $_POST['class_id']
                        , ':times_present' => (int)$_POST['times_present']
                        , ':times_punctual' => (int)$_POST['times_punctual']
                        , ':teacher_comment' => $_POST['teacher_comment']
                        , ':principal_comment' => $_POST['principal_comment']);
            $result = $fxns->_execQuery( $sql, false, false, $params );
        }else{
            try {
                $dbo->beginTransaction();
                    $get_last_stud_id = "select last_stud_id from class where class_id=:class_id";
                    $params = array(':class_id'=>$_POST['class_id']);
                    $last_stud_id = $fxns->_execQuery( $get_last_stud_id, true, false, $params );
//                    var_dump($last_stud_id); 
//                    echo $last_stud_id['last_stud_id']+1; exit;
                    
                    $sql ="INSERT INTO person (username, person_type_id, f_name, m_name, l_name, pix, dob, sex, phone, status) "
                            . "VALUES (:username, :person_type_id, :f_name, :m_name, :l_name, :pix, :dob, :sex, :phone, :status)";
                    $params = array(':username' => $_POST['username']
                                , ':pix' => (isset($path)?$path:'')
                                , ':person_type_id' => $person_type
                                , ':f_name' => $_POST['f_name']
                                , ':m_name' => $_POST['m_name']
                                , ':l_name' => $_POST['l_name']
                                , ':dob' => date('Y-m-d', strtotime($_POST['dob']))
                                , ':sex' => $_POST['sex']
                                , ':phone' => $_POST['phone']
                                , ':status' => '1');
                        $stmt = $dbo->prepare($sql);
                        $stmt->execute($params);
                        $id = $dbo->lastInsertId();
                    $sql = "INSERT INTO student (student_id, class_id, adminNo, times_present, times_punctual, teacher_comment, principal_comment) "
                            . " VALUES (:id, :class_id, :adminNo, :times_present, :times_punctual, :teacher_comment, :principal_comment)";
                    $params = array(':id' => $id, ':class_id'=>$_POST['class_id'], ':adminNo' => "TCS/".date("Y")."/".$_POST['class_id']."/".( sprintf("%03d", $last_stud_id['last_stud_id']) )
                            , ':times_present'=>(int)$_POST['times_present'], ':times_punctual'=>(int)$_POST['times_punctual']
                            , ':teacher_comment'=>$_POST['teacher_comment'], ':principal_comment'=>$_POST['principal_comment']);
                    $result = $fxns->_execQuery( $sql, false, false, $params );
                    
                    
                    $set_last_stud_id = "UPDATE class SET last_stud_id ={$last_stud_id['last_stud_id']}+1 WHERE class_id=:class_id";
                    $params = array(':class_id'=>$_POST['class_id']);
                    $last_stud_id = $fxns->_execQuery( $set_last_stud_id, false, false, $params );
                    if(is_array($result)) throw new Exception($result['msg']);
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
    $studentTitle = array('person_id' => array('title'=>'person_id', 'display'=>0)
                        , 'person_type_id' => array('title'=>'person_type_id', 'display'=>0)
                        , 'pix' => array('title'=>'Upload Picture:', 'display'=>1, 'type'=>'file', 'class'=>'p300', 'attr'=>array('showImg'=>true, 'entity'=>'person', 'cols'=>array("img"=>"pix")))
                        , 'adminNo' => array('title'=>'Admission No.', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'username' => array('title'=>'User Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'f_name' => array('title'=>'First Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'm_name' => array('title'=>'Middle Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'l_name' => array('title'=>'Last Name:', 'display'=>1, 'type'=>'text', 'class'=>'p300')
                        , 'dob' => array('title'=>'Date Of Birth:', 'display'=>1, 'type'=>'text', 'class'=>'datePicker p300')
                        , 'sex' => array('title'=>'Sex:', 'display'=>1, 'class'=>'p300', 'type'=>'select', 'query'=>"SELECT val_id, val_dsc FROM t_wb_lov WHERE def_id='00-SEX'")
                        , 'class_id' => array('title'=>'Class:', 'display'=>1, 'class'=>'p300', 'type'=>'select', 'query'=>"SELECT class_id as val_id, class_name as val_dsc FROM class")
                        , 'phone' => array('title'=>'Phone:', 'display'=>1, 'class'=>'p300', 'type'=>'text')
                        , 'times_present' => array('title'=>'Times Present:', 'display'=>1, 'class'=>'p300', 'type'=>'number')
                        , 'times_punctual' => array('title'=>'Times Punctual:', 'display'=>1, 'class'=>'p300', 'type'=>'number')
                        , 'teacher_comment' => array('title'=>'Teacher Comment:', 'display'=>1, 'class'=>'p300', 'type'=>'text')
                        , 'principal_comment' => array('title'=>'Principal Comment:', 'display'=>1, 'class'=>'p300', 'type'=>'text')
                    );
    $readonly = array('attr'=>array('readonly'=>'readonly'));// Text
    $disabled = array('attr'=>array('disabled'=>'disabled'));
    $studentTitle['adminNo'] = array_merge ($studentTitle['adminNo'], $readonly );
    if($_COOKIE['teacher_type']==2){ // Teacher
        $studentTitle['pix']['attr'] = array_merge ($studentTitle['pix']['attr'], array('disabled'=>'disabled') );
        $studentTitle['username'] = array_merge ($studentTitle['username'], $readonly );
        $studentTitle['f_name'] = array_merge ($studentTitle['f_name'], $readonly );
        $studentTitle['m_name'] = array_merge ($studentTitle['m_name'], $readonly );
        $studentTitle['l_name'] = array_merge ($studentTitle['l_name'], $readonly );
        $studentTitle['dob'] = array_merge ($studentTitle['dob'], $readonly );
        $studentTitle['sex'] = array_merge ($studentTitle['sex'], $disabled );
        $studentTitle['class_id'] = array_merge ($studentTitle['class_id'], $disabled );
        $studentTitle['phone'] = array_merge ($studentTitle['phone'], $readonly );
        $studentTitle['principal_comment'] = array_merge ($studentTitle['principal_comment'], $readonly );
    }elseif($_COOKIE['teacher_type']==1){// Admin
        $studentTitle['times_present'] = array_merge ($studentTitle['times_present'], $readonly );
        $studentTitle['times_punctual'] = array_merge ($studentTitle['times_punctual'], $readonly );
        $studentTitle['teacher_comment'] = array_merge ($studentTitle['teacher_comment'], $readonly );
    }
    echo "<form name='studentForm' method='post' enctype='multipart/form-data'>";
    if($_getParams[0]=="edit"){
        $options=array('where'=>array("p.person_type_id"=>3,'p.person_id'=>$_getParams[1]));
        $getPrsnByType = $cust->_getPerson( $options );
        echo "<input type='hidden' name='person_id' value='$_getParams[1]'>";
        $options = array('edit'=>array('page'=>'student=edit')
                        ,'delete'=>1, 'key'=>'person_id'
                        , 'addButton'=>array('type'=>'button','name'=>'submitStudent', 'class'=>'editStudent', 'value'=>'Update'));
        echo $fxns->_buildTable($getPrsnByType, true, $studentTitle, $options);
    }else{
        $options = array( 'addButton'=>array('type'=>'button','name'=>'submitStudent', 'class'=>'addStudent', 'value'=>'Submit') );
        echo $fxns->_buildTable(array(0=>$studentTitle), true, array(), $options, 'students');
    }
    echo "</form>";
}else{
    $studentTitle = array('person_id' => array('title'=>'person_id', 'display'=>0)
                        , 'person_type_id' => array('title'=>'person_type_id', 'display'=>0)
                        , 'class_id' => array('title'=>'Class', 'display'=>1, 'type'=>'LOVDsc'
                                            , 'query'=>"SELECT class_name val_dsc FROM class WHERE class_id=:val_id"
                                            , 'aggregate'=>1)
                        , 'username' => array('title'=>'User Name', 'display'=>1)
                        , 'adminNo' => array('title'=>'Admission No.', 'display'=>1)
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
    
    $options = array('where'=>array("p.person_type_id"=>3,"p.status"=>1)
                    , 'order'=>'s.class_id,s.adminNo');
    $getPrsnByType = $cust->_getPerson($options, $page);
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
    
    $options = array('edit'=>array('page'=>'student=edit', 'class'=>'editPerson')
                    ,'delete'=>array('page'=>'student=delete'),'selectable'=>1, 'key'=>'person_id', 'page'=>$preparePaging);
    echo $fxns->_buildTable($getPrsnByType, false, $studentTitle, $options, 'myClass');
}
?>

<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc';
?>