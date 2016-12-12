<?php
/*
 * Include necessary files
 */
include_once 'core/init.inc.php';

$cust = new Customer($dbo);
$fxns = new Functions($dbo);

if(isset($_POST['resetPass'])){
    $pwd = $fxns->_genPwd();
    $storePwd = password_hash(sha1(SALT . md5($pwd . SALT)), PASSWORD_DEFAULT);
    $sqlUpdtPwd = "UPDATE person SET p_word=:p_word WHERE person_id=:person_id";
    $params = array(":p_word"=>$storePwd, ":person_id"=>$_POST['id']);
    $result = $fxns->_execQuery($sqlUpdtPwd, false, true, $params);
    if(is_array($result)){
        echo json_encode($results);
    }else{
        $result = array('Success'=>1, 'msg'=>"Password is: <span style='color:#C00'>".$pwd."</span> Please store securely");
        echo json_encode($result);
    }
}
if(isset($_POST['getClassKeys'])){
    $sql = "SELECT * FROM class_subject where class_id=:class_id";
    $params = array(":class_id"=>$_POST['getClassKeys']);
    $result = $fxns->_execQuery($sql, true, true, $params);
    echo json_encode($result);    
}
if(isset($_POST['addSbjClass'])){
    $update = 0; $insert=0;
    try{
        $dbo->beginTransaction();
            foreach($_POST['checked'] as $class_id){
                foreach($_POST['subjects'] as $subjects){
                    $params = array(":class_id"=>$class_id, ":subject_id"=>$subjects);
                    $sqlCheck = "SELECT COUNT(*) count FROM class_subject 
                                WHERE class_id=:class_id AND subject_id=:subject_id ";
                    $result = $fxns->_execQuery($sqlCheck, true, false, $params);
                    $params = array_merge($params, array(":teacher_id"=>$_POST['teacher']));
                    if($result['count']!= 0){
                        $sqlCheck = "UPDATE class_subject SET teacher_id=:teacher_id
                                    WHERE class_id=:class_id AND subject_id=:subject_id";
                        $result = $fxns->_execQuery($sqlCheck, false, false, $params);
                        $update++;
                    }else{
                        $sql = "INSERT INTO class_subject "
                                . "(class_id, subject_id, teacher_id, created)"
                                . " VALUES (:class_id, :subject_id, :teacher_id, NOW())";
                        $result = $fxns->_execQuery($sql, false, false, $params);
                        $insert++;
                    }
                    if(is_array($result)){
                        throw new Exception($result['msg']);
                    }
                }
            }
        $dbo->commit();
        $results = array('result' => 'Success', 'msg' => "Subject(s) and teacher(s) added to class(es) successfully -- ");
    } catch (Exception $e) {
        $dbo->rollback();
        $results = array('result' => 'Failure', 'msg' =>  $e->getMessage());
    }
    echo json_encode($results);    
}
if(isset($_POST['classSubj']) && isset($_POST['examTp']) && isset($_POST['score'])){
    $sqlChkExam = "SELECT * FROM examinations "
            . " WHERE class_subject_id=:class_subject_id "
            . " AND student_id=:student_id "
            . " AND exam_type_id=:exam_type_id"
            . " AND session_term_id=:session_term_id";
    $options = array(":class_subject_id"=>$_POST['classSubj'], ":student_id"=>$_POST['student']
            , ":exam_type_id"=>$_POST['examTp'], ":session_term_id"=>$_POST['sesssion_id']); 
    $result = $fxns->_execQuery($sqlChkExam, true, false, $options);
    if(!$result){ 
        $sqlPutExam = "INSERT INTO examinations (session_term_id, class_subject_id, student_id, exam_type_id, max_score, stud_score)"
                . "VALUES (:session_term_id, :class_subject_id, :student_id, :exam_type_id, :max_score, :stud_score)";
        $extraVals = array(':stud_score' => $_POST['score'], ':max_score' => @$_POST['max_score']);
        $options = array_merge($options, $extraVals);
        $result = $fxns->_execQuery($sqlPutExam, false, false, $options);
        if($result==1) $result = array('result' => 'Success', 'msg'=>'Record inserted successfully');
    }else{
        $sqlPutExam = "UPDATE examinations SET max_score=:max_score, stud_score=:stud_score"
                . " WHERE exam_id=:exam_id";
        $options = array(':stud_score' => $_POST['score'], ':max_score' => @$_POST['max_score'], ':exam_id' => $result['exam_id']);
        
        $result = $fxns->_execQuery($sqlPutExam, false, false, $options);
        if($result==1) $result = array('result' => 'Success', 'msg'=>'Record updated successfully');
        if($result==0) $result = array('result' => 'Success', 'msg'=>'No Changes recorded');
    }
    
    echo json_encode($result);
}
if(isset($_POST['newDefList'])){
    if(empty($_POST['def_id']) || empty($_POST['val_desc'])){
        $result = array('result' => 'Failure', 'msg' => "No definitions defined");
    }else{
        $query = "INSERT INTO list_def (def_id, val_desc) VALUES (:def_id, :val_desc)";
        $params=array(":def_id"=>$_POST['def_id'], ":val_desc"=>$_POST['val_desc']);
        $result = $fxns->_execQuery($query, false, false, $params);
        if($result==1){
            $result=$_POST;
        }
    }
    echo json_encode($result);
}
if(isset($_POST['definitions'])){
    $LOVTitle = array('def_id' => array('title'=>'Unique Definition', 'display'=>1)
                    , 'par_id' => array('title'=>'Parent', 'display'=>0)
                    , 'val_id' => array('title'=>'Value Id', 'display'=>1)
                    , 'val_dsc' => array('title'=>'Description', 'display'=>1)
                );
    $query = "SELECT * FROM t_wb_lov where def_id=:def_id";
    $params=array(":def_id"=>$_POST['definitions']);
    
    $sqlSearchCount = "SELECT COUNT(*) count FROM (".$query.")t ";
    $sqlSearchCount = $fxns->_execQuery($sqlSearchCount, true, false, $params);
    $currentpage = isset($_POST['currentpage'])?$_POST['currentpage']:1;
    $preparePaging = $fxns->_preparePaging($sqlSearchCount['count'], $rowsperpage = 10, $currentpage );
    $query .= " LIMIT {$preparePaging['offset']}, {$rowsperpage}";
    
    $LOVs = $fxns->_execQuery($query, true, true, $params);
            
    /** Begin Table layout **/
    $table = "<form name='popExam'><table class='my_tables myClass'>";
    $table .= "<a href='' style='float:right' class='newDef'><i class='fa fa-plus-circle fa-2x'></i></a><br />";
    $table .= "<tr>";
    foreach ($LOVTitle as $key => $values) {
        if ($LOVTitle[$key]['display']==1) {
            $table .= "<th>" . $LOVTitle[$key]['title'] . "</th>";
            @$colCount += 1;
        }
    }
    $table .= "<th>Actions</th>"; $colCount++;
    $table .= "</tr>";
    for ($i = 0; $i < count($LOVs); $i++) {
        $table .= "<tr>";
        foreach ($LOVTitle as $key => $values) {
            if ($LOVTitle[$key]['display']==1) {
                $table .=  "<td><span data-pk='{$LOVs[$i]['r_k']}'>{$LOVs[$i][$key]}</span></td>";
            }
        }
        $incOpts = null;
        $incOpts .= "<a href='' class='editDef' key='{$LOVs[$i]['r_k']}'><i class='fa fa-pencil-square-o fa-2x'></i></a>";
        $incOpts .= " <a href='' class='delDef' key='{$LOVs[$i]['r_k']}'><i class='fa fa-times-circle fa-2x'></i></a>";
        $table .= "<td align='middle'><input type='hidden' name='editLov' value='{$LOVs[$i]['r_k']}' />" . $incOpts . "</td>";

        $table .= "</tr>";
    }
    $divLinkClass = array('div' => 'myPaging', 'param'=>"definitions={$_POST['definitions']}",'link' => 'definitions');
    $link = WEB_ROOT."staff/LOVS.php";
    $table .= "<tr><td colspan='{$colCount}' style=\"text-align:center;\">"
                            . $fxns->_buildPagingLinks(3, $preparePaging['currentpage'], $preparePaging['totalpages'], $divLinkClass, $link)
                            . "</td></tr>";
//    $fxns->_buildPagingLinks($range = 3, $currentpage, $totalpages, $divLinkClass = array(), $link = WEB_ROOT);
    $table .= "</table></form>";

    echo $table;
}
if(isset($_POST['examArea'])){
    $sessionTitle = array('exam_type_id' => array('title'=>'Exam Type Id', 'display'=>0)
                    , 'exam_type_desc' => array('title'=>'Description', 'display'=>1)
                    , 'max_score' => array('title'=>'Maximum Score', 'display'=>1)
                    , 'session_term_id' => array('title'=>'Session/Class', 'display'=>1, 'type'=>'LOVDsc', 'query'=>"SELECT CONCAT(st.session_term_desc,' - ', c.class_name) val_dsc FROM session_term st
JOIN class c ON st.class_id=c.class_id where st.ius_yn=1 and session_term_id=:val_id")
                    , 'ius_yn' => array('title'=>'In Use', 'display'=>1, 'type'=>'LOVDsc', 'query'=>"SELECT val_dsc FROM t_wb_lov WHERE def_id='INS-YN' AND val_id=:val_id")
                );
    $sqlSession = "SELECT * FROM exam_type";
    
    $sqlSearchCount = "SELECT COUNT(*) count FROM (".$sqlSession.")t ";
    $sqlSearchCount = $fxns->_execQuery($sqlSearchCount, true, false);
    
    $currentpage = isset($_POST['currentpage'])?$_POST['currentpage']:1;
    $preparePaging = $fxns->_preparePaging($sqlSearchCount['count'], $rowsperpage = 10, $currentpage);
    $sqlSession .= " LIMIT {$preparePaging['offset']}, {$rowsperpage}";
    
    $sqlSession = $fxns->_execQuery($sqlSession, true, true);
    
    $table = "<a href='' style='float:right' class='newExamDef'><i class='fa fa-plus-circle fa-2x'></i></a><br />";
     
    $preparePaging['divLink']= array('div' => 'myPaging', 'param'=>"examArea=1", 'link' => 'examArea');
    $preparePaging['link']= substr(WEB_ROOT, 0, -1);
    $options = array('edit'=>array('page'=>'class=newExamDef', 'class'=>'newExamDef')
                    ,'delete'=>array('page'=>'examDef=delete'), 'key'=>'exam_type_id', 'page'=>$preparePaging);
    
    $table .= $fxns->_buildTable($sqlSession, false, $sessionTitle, $options, 'newDefTable');
    echo $table;
}
if(isset($_POST['newExamDef'])){
    $result = '';
    $sessionTitle = array('exam_type_id' => array('title'=>'Exam Type Id', 'display'=>0)
                    , 'exam_type_desc' => array('title'=>'Description', 'display'=>1, 'type'=>'text', 'required'=>'1')
                    , 'max_score' => array('title'=>'Maximum Score', 'display'=>1, 'type'=>'text', 'required'=>'1')
                    , 'session_term_id' => array('title'=>'Session/Class', 'display'=>1, 'type'=>'select'
                                            , 'query'=>"SELECT st.session_term_id val_id, CONCAT(st.session_term_desc,' - ', c.class_name) val_dsc FROM session_term st
                                                        JOIN class c ON st.class_id=c.class_id where st.ius_yn=1")
                    , 'ius_yn' => array('title'=>'In Use', 'display'=>1, 'type'=>'checkbox', 'required'=>'0')
                );
    if(isset($_POST['submitExam'])){
        foreach($_POST as $key => $vals){
            if(@$sessionTitle[$key]['required']==1 && $vals==''){
                @$err .= $sessionTitle[$key]['title']." Cannot be empty<br />";
            }
        }
        if(isset($err)){
            $result .=$err;
        }else{
            $_POST['ius_yn'] = isset($_POST['ius_yn'])?1:0;
            if(isset($_POST['exam_type_id'])){
                $sql = "UPDATE exam_type 
                        SET exam_type_desc=:exam_type_desc, max_score=:max_score, session_term_id=:session_term_id, ius_yn=:ius_yn
                        WHERE exam_type_id=:exam_type_id";
                $params = array(':exam_type_id' => $_POST['exam_type_id']
                            , ':exam_type_desc' => $_POST['exam_type_desc']
                            , ':max_score' => $_POST['max_score']
                            , ':session_term_id' => $_POST['session_term_id']
                            , ':ius_yn' => $_POST['ius_yn']);
            }else{
                
                $sql ="INSERT INTO exam_type (exam_type_desc, max_score, session_term_id, ius_yn) "
                                . " VALUES (:exam_type_desc, :max_score, :session_term_id, :ius_yn)";
                $params = array(':exam_type_desc' => $_POST['exam_type_desc']
                            , ':max_score' => $_POST['max_score']
                            , ':session_term_id' => $_POST['session_term_id']
                            , ':ius_yn' => $_POST['ius_yn']);
            }
            $ins = $fxns->_execQuery( $sql, false, false, $params );
            if(is_array($ins)){
                $result .= $ins['msg'];
            }else{
                $result .= "Transaction completed";
            }
        }
    }
    $result .= "<form><h3>New Exam Definition</h3>";
    
    $sqlSession = "SELECT * FROM exam_type";
    $result .= "<input type='hidden' name='newExamDef' value='1'>";
    
    if(@$_POST['exam_type_id'] != "undefined" && @$_POST['exam_type_id'] != null){
        $sqlSession .= " WHERE exam_type_id=:exam_type_id";
        $params=array(":exam_type_id"=>$_POST['exam_type_id']);
        
        $sqlSession = $fxns->_execQuery($sqlSession, true, true,$params);
        $result .= "<input type='hidden' name='exam_type_id' value='{$_POST['exam_type_id']}'>";
        
        $options = array('edit'=>array('page'=>'class=edit')
                        ,'delete'=>1, 'key'=>'class_id'
                        , 'addButton'=>array('type'=>'button','name'=>'submitExam', 'class'=>'addExam', 'value'=>'Update'));
        $result .= $fxns->_buildTable($sqlSession, true, $sessionTitle, $options);
    }else{
        $options = array( 'addButton'=>array('type'=>'button','name'=>'submitExam', 'class'=>'addExam', 'value'=>'Submit') );
        $result .= $fxns->_buildTable(array(0=>$sessionTitle), true, array(), $options, 'class');
    }
    $result .= "</form>";
    echo $result;
}
if(isset($_POST['sessionArea'])){
    $sessionTitle = array('session_term_id' => array('title'=>'Session Id', 'display'=>0)
                    , 'session_nm' => array('title'=>'Session Name or Id', 'display'=>1)
                    , 'term' => array('title'=>'Term Name or Id', 'display'=>1)
                    , 'class_id' => array('title'=>'Class', 'display'=>1, 'type'=>'LOVDsc', 'query'=>"SELECT class_name val_dsc FROM class WHERE class_id=:val_id")
                    , 'ius_yn' => array('title'=>'In Use', 'display'=>1)
                    , 'session_term_desc' => array('title'=>'Description', 'display'=>1)
                );
    $sqlSession = "SELECT * FROM session_term";
    
    $sqlSearchCount = "SELECT COUNT(*) count FROM (".$sqlSession.")t ";
    $sqlSearchCount = $fxns->_execQuery($sqlSearchCount, true, false);
    
    $currentpage = isset($_POST['currentpage'])?$_POST['currentpage']:1;
    $preparePaging = $fxns->_preparePaging($sqlSearchCount['count'], $rowsperpage = 10, $currentpage);
    $sqlSession .= " LIMIT {$preparePaging['offset']}, {$rowsperpage}";
    
    $sqlSession = $fxns->_execQuery($sqlSession, true, true);
    
    $table = "<a href='' style='float:right' class='newSessionDef'><i class='fa fa-plus-circle fa-2x'></i></a><br />";
     
    $preparePaging['divLink']= array('div' => 'myPaging', 'param'=>"sessionArea=1", 'link' => 'sessionArea');
    $preparePaging['link']= substr(WEB_ROOT, 0, -1);
    $options = array('edit'=>array('page'=>'class=newSessionDef', 'class'=>'newSessionDef')
                    ,'delete'=>array('page'=>'sessionDef=delete'), 'key'=>'session_term_id', 'page'=>$preparePaging);
    
    $table .= $fxns->_buildTable($sqlSession, false, $sessionTitle, $options, 'newDefTable');
    echo $table;
}
if(isset($_POST['newSessionDef'])){
    $result = '';
    $sessionTitle = array('session_term_id' => array('title'=>'Session Id', 'display'=>0)
                    , 'session_nm' => array('title'=>'Session Name / Id', 'display'=>1, 'type'=>'text', 'required'=>'1')
                    , 'term' => array('title'=>'Term Name / Id', 'display'=>1, 'type'=>'text', 'required'=>'1')
                    , 'class_id' => array('title'=>'Class', 'display'=>1, 'required'=>'1', 'type'=>'select', 'query'=>"SELECT class_id val_id, class_name val_dsc FROM class")
                    , 'ius_yn' => array('title'=>'In Use', 'display'=>1, 'type'=>'checkbox', 'required'=>'0')
                    , 'session_term_desc' => array('title'=>'Description', 'display'=>1, 'type'=>'text', 'required'=>'1')
                );
    if(isset($_POST['submitSession'])){
        foreach($_POST as $key => $vals){
            if(@$sessionTitle[$key]['required']==1 && $vals==''){
                @$err .= $sessionTitle[$key]['title']." Cannot be empty<br />";
            }
        }
        if(isset($err)){
            $result .=$err;
        }else{
            $_POST['ius_yn'] = isset($_POST['ius_yn'])?1:0;
            if($_POST['ius_yn']==1){
                $sql = "UPDATE session_term 
                        SET ius_yn=0 WHERE class_id=:class_id";
                $params = array(':class_id' => $_POST['class_id']);
                $ins = $fxns->_execQuery( $sql, false, false, $params );
            }
            if(isset($_POST['session_term_id'])){
                $sql = "UPDATE session_term 
                        SET session_nm=:session_nm, term=:term, class_id=:class_id, ius_yn=:ius_yn, session_term_desc=:session_term_desc
                        WHERE session_term_id=:session_term_id";
                $params = array(':session_term_id' => $_POST['session_term_id']
                            , ':session_nm' => $_POST['session_nm']
                            , ':term' => $_POST['term']
                            , ':class_id' => $_POST['class_id']
                            , ':ius_yn' => $_POST['ius_yn']
                            , ':session_term_desc' => $_POST['session_term_desc']);
            }else{
                
                $sql ="INSERT INTO session_term (session_nm, term, class_id, ius_yn, session_term_desc, term_start, term_end) "
                                . " VALUES (:session_nm, :term, :class_id, :ius_yn, :session_term_desc, NOW(), NOW())";
                $params = array(':session_nm' => $_POST['session_nm']
                            , ':term' => $_POST['term']
                            , ':class_id' => $_POST['class_id']
                            , ':ius_yn' => $_POST['ius_yn']
                            , ':session_term_desc' => $_POST['session_term_desc']);
            }
            $ins = $fxns->_execQuery( $sql, false, false, $params );
            if(is_array($ins)){
                $result .= $ins['msg'];
            }else{
                $result .= "Transaction completed";
            }
        }
    }
    $result .= "<form><h3>New Session Definition</h3>";
    
    $sqlSession = "SELECT * FROM session_term";
    $result .= "<input type='hidden' name='newSessionDef' value='1'>";
    
    if(@$_POST['session_term_id'] != "undefined" && @$_POST['session_term_id'] != null){
        $sqlSession .= " WHERE session_term_id=:session_term_id";
        $params=array(":session_term_id"=>$_POST['session_term_id']);
        
        $sqlSession = $fxns->_execQuery($sqlSession, true, true,$params);
        $result .= "<input type='hidden' name='session_term_id' value='{$_POST['session_term_id']}'>";
        
        $options = array('edit'=>array('page'=>'class=edit')
                        ,'delete'=>1, 'key'=>'class_id'
                        , 'addButton'=>array('type'=>'button','name'=>'submitSession', 'class'=>'editSession', 'value'=>'Update'));
        $result .= $fxns->_buildTable($sqlSession, true, $sessionTitle, $options);
    }else{
        $options = array( 'addButton'=>array('type'=>'button','name'=>'submitSession', 'class'=>'addSession', 'value'=>'Submit') );
        $result .= $fxns->_buildTable(array(0=>$sessionTitle), true, array(), $options, 'class');
    }
    $result .= "</form>";
    echo $result;
}
if(isset($_POST['editLov']) || isset($_POST['newLov'])){
    $query = "SELECT * FROM t_wb_lov where r_k=:r_k";
    $params = array(":r_k"=>0);
    isset($_POST['editLov'])? array_merge($params, $params=array(":r_k"=>$_POST['editLov'])):'';
    $LOVs = $fxns->_execQuery($query, true, true, $params);
    $table = "<form><table rules='all' border='1'>";
    $table .= "<input type='hidden' name='".(isset($_POST['editLov'])? "updateLOV" :"submitLOV")."' value='1' />";
    $table .= isset($_POST['editLov']) ? "<input type='hidden' name='r_k' value='".@$LOVs[0]['r_k']."' />" : "";
    
    $sqlGetListDef="select def_id val_id, val_desc val_dsc FROM list_def";
    $allDefs = $fxns->_getLOVs($sqlGetListDef, "val_id", "val_dsc", "defs", "defs", "-- Select Definition --", @$LOVs[0]['def_id']);
    $table .= "<tr><td>Unique Definition Id</td><td>{$allDefs}</td></tr>";
    $table .= "<tr><td>Value Id</td><td><input type='text' name='val_id' value='".@$LOVs[0]['val_id']."' /></td></tr>";
    $table .= "<tr><td>Value Desc</td><td><input type='text' name='val_dsc' value='".@$LOVs[0]['val_dsc']."' /></td></tr>";
    $table .= "<tr><td></td><td><input type='submit' class='button' name='submitLov' id='submitLov' value='".(isset($_POST['editLov'])? "Update" :"Submit")."' /></td></tr>";
    $table .= "</table></form>";
    echo $table;
}
if(isset($_POST['updateLOV']) || isset($_POST['submitLOV'])){
    $query = isset($_POST['updateLOV']) 
            ? "UPDATE t_wb_lov SET def_id=:def_id, val_id=:val_id, val_dsc=:val_dsc WHERE r_k=:r_k" 
            : (isset($_POST['submitLOV']) ? "INSERT INTO t_wb_lov ( def_id, val_id, val_dsc) VALUES (:def_id, :val_id, :val_dsc)" : "");
    $params = array(":def_id"=>$_POST['defs'], ":val_id"=>$_POST['val_id'], ":val_dsc"=>$_POST['val_dsc']);
    $params = isset($_POST['r_k'])? array_merge($params, array(":r_k"=>$_POST['r_k'])): $params;
    
    $result = $fxns->_execQuery($query, false, false, $params);
    if($result==1){
        echo isset($_POST['updateLOV'])? "Definition updated successfully!" : "Definition created successfully!";
    }else{
        echo "Error creating definition! Please contact portal admin";
    }
}
if(isset($_POST['exam_teacher_class'])){
    $sqlGetSessions = "select session_term_id val_id, session_term_desc val_dsc FROM session_term";
    $sqlGetSessions .= " WHERE class_id = :class_id";
    $params = array(':class_id'=>$_POST['exam_teacher_class']);
    $sqlGetSessions = $fxns->_execQuery($sqlGetSessions, true, true, $params);
    echo json_encode($sqlGetSessions);
}
if(isset($_POST['pExam_sessions'])){
    $getCurrSession = "SELECT session_term_id FROM session_term WHERE class_id=:class_id AND ius_yn=1";
    $params = array(':class_id'=>$_POST['pExam_sessions']);
    $CurrSession = $fxns->_execQuery($getCurrSession, true, true, $params);
    
    $studentTitle = array('person_id' => array('title'=>'person_id', 'display'=>0)
                    , 'person_type_id' => array('title'=>'person_type_id', 'display'=>0)
                    , 'f_name' => array('title'=>'First Name', 'display'=>1)
                    , 'm_name' => array('title'=>'Middle Name', 'display'=>1)
                    , 'l_name' => array('title'=>'Last Name', 'display'=>1)
                );
    $currentpage=@$_GET['currentpage'];
    $page = array('rowsperpage'=>15);
    isset($currentpage)?$page['currentpage']=$currentpage:'';
    
    $getPrsnByType = $cust->_getPerson(array('where'=>array("p.person_type_id"=>3,'s.class_id'=>$_POST['pExam_sessions']))
                                       , array('rowsperpage'=>15));
    $preparePaging = array_pop($getPrsnByType);
    
//    var_dump($_POST);
    $table = "<form name='popExam'><table class='my_tables myClass'>";
    $table .= "<input type='hidden' id='currSession' value='".@$CurrSession[0]['session_term_id']."' />";
    $table .= "<tr>";
    $table .= "<th>Print</th>";
    
    foreach ($studentTitle as $key => $values) {
        if ($studentTitle[$key]['display']==1) {
            $table .= "<th>" . $studentTitle[$key]['title'] . "</th>";
        }
    }
    $querySubj="SELECT class_subject_id val_id, subject_id val_dsc "
            . " FROM class_subject "
            . " WHERE class_id='{$_POST['pExam_sessions']}' AND teacher_id='{$_SESSION['user']['id']}'"
            . " AND ins_yn=1";
    $table .= "<th>" . $fxns->_getLOVs($querySubj, "val_id", "val_dsc", "class", "class", NULL, NULL) . "</th>";
    $sqlExamTp="SELECT exam_type_id val_id, exam_type_desc val_dsc "
            . " FROM exam_type "
            . " WHERE ius_yn=1 AND session_term_id='{$_POST['selectedSesn']}'";
    $table .= "<th>" . $fxns->_getLOVs($sqlExamTp, "val_id", "val_dsc", "class", "class", NULL, NULL) . "</th>";
    $table .= "<th><input type='text' name='score' style='width:50px' /></th>";
    $table .= "<th>Actions</th>";
    $table .= "</tr>";
    for ($i = 0; $i < count($getPrsnByType); $i++) {
        $table .= "<tr>";
        $table .=  "<td><a href='' class='printExam4Stud' key='".$getPrsnByType[$i]['person_id']."'><i class='fa fa-print fa-2x'></i></a></td>";
        foreach ($studentTitle as $key => $values) {
            if ($studentTitle[$key]['display']==1) {
                $table .=  "<td>" . $getPrsnByType[$i][$key] . "</td>";
            }
        }
        $table .= "<td align='middle'>" . $fxns->_getLOVs($querySubj, "val_id", "val_dsc", "classSubj", "classSubj", NULL, NULL) . "</td>";
        $table .= "<td align='middle'>" . $fxns->_getLOVs($sqlExamTp, "val_id", "val_dsc", "examTp", "examTp", NULL, NULL) . "</td>";
        $table .= "<td align='middle'><input type='text' name='score' style='width:50px' /></td>";

        $incOpts = null;
        $incOpts .= "<a href='' class='edtStudExam' key='".$getPrsnByType[$i]['person_id']."'><i class='fa fa-check fa-2x'></i></a>";
        $incOpts .= " <a href='' class='delStudExam' key='".$getPrsnByType[$i]['person_id']."'><i class='fa fa-minus-circle fa-2x'></i></a>";
        $table .= "<td align='middle'><input type='hidden' name='student' value='{$getPrsnByType[$i]['person_id']}' />" . $incOpts . "</td>";

        $table .= "</tr>";
    }
    $table .= "</table></form>";
    echo $table;
}

if(isset($_POST['printStud'])){
    $table = "";
    $sqlGetexamTypes = "SELECT distinct e.exam_type_id, exam_type_desc
                        FROM examinations e
                                LEFT JOIN class_subject cs
                                    ON e.class_subject_id=cs.class_subject_id
                                LEFT JOIN exam_type et
                                        ON e.exam_type_id=et.exam_type_id
                        WHERE cs.class_id=:class_id AND e.session_term_id=:session_term_id AND e.student_id=:student_id";
    $params=array(":class_id"=>$_POST['class'], ":session_term_id"=>$_POST['curr_sess'], ":student_id"=>$_POST['stud']);
    $result = $fxns->_execQuery($sqlGetexamTypes, true, true, $params);
    
    $getStudentExam = "select t.val_dsc SUBJECT";
    foreach ($result as $key => $vals){
        $getStudentExam .= " , sum(stud_score*(1-abs(sign(exam_type_id-{$vals['exam_type_id']})))) '".  strtoupper($vals['exam_type_desc'])."' ";
        @$total .= " sum(stud_score*(1-abs(sign(exam_type_id-{$vals['exam_type_id']})))) +";
    }
    $termTotal = rtrim(@$total, "+"). " as 'TERM TOTAL'";
    
    $printed=false;
    $getOtherTerms = "SELECT *, (SELECT term FROM session_term WHERE session_term_id=:session_term_id) curr_term"
            . " FROM session_term WHERE class_id=:class_id AND ius_yn<>1 AND session_nm = (SELECT session_nm FROM session_term WHERE session_term_id=:session_term_id) ";
    $params=array(":class_id"=>$_POST['class'], ":session_term_id"=>$_POST['curr_sess']);
    $otherTerms = $fxns->_execQuery($getOtherTerms, true, true, $params);

    $table .= var_dump($otherTerms);
    $countAllTerms = count($otherTerms)+1;
    $cummTotal = " (SELECT IFNULL(SUM(e.stud_score),0) FROM examinations e WHERE e.session_term_id IN (:session_term_id";
    
    $getStudentExam .= ", " . rtrim($termTotal, "+");
    foreach($otherTerms as $key => $vals){
//        if($vals['term'] > $vals['curr_term']){
//            if($printed){
//
//            }else{
//                $getStudentExam .= ", " . rtrim($termTotal, "+");
//                $printed = true;
//            }
//        }

        if($vals['term'] != $vals['curr_term']&& ($vals['term'] < $vals['curr_term'])) {
            $getStudentExam .= ", (SELECT SUM(e.stud_score) FROM examinations e "
                . " WHERE e.session_term_id={$vals['session_term_id']} AND e.class_subject_id=t.class_subject_id AND e.student_id=t.student_id) as '";
            if ($vals['term'] == 1)
                $getStudentExam .= "1ST TERM";
            elseif ($vals['term'] == 2)
                $getStudentExam .= "2ND TERM";
            elseif ($vals['term'] == 3)
                $getStudentExam .= "3RD TERM";
            $getStudentExam .= "'";
        }
        $cummTotal .= ", {$vals['session_term_id']}";
//        @$cummTotal .= " (SELECT IFNULL(SUM(e.stud_score),0) FROM examinations e "
//            . " WHERE e.session_term_id={$vals['session_term_id']} AND e.class_subject_id=t.class_subject_id AND e.student_id=t.student_id) +";
    }
    $cummTotal .= ") AND e.class_subject_id=t.class_subject_id AND e.student_id=t.student_id)";
//    $getStudentExam .= ($otherTerms[0]['curr_term']==3)?", ($total".rtrim($cummTotal, "+").")/{$countAllTerms} as 'CUMM. AVERAGE'":",'' as 'CUMM. AVERAGE'";
    $getStudentExam .= ($otherTerms[0]['curr_term']==3)?", round ($cummTotal) as 'CUMM. AVERAGE'":",'' as 'CUMM. AVERAGE'";

    $getStudentExam .="from (SELECT e.exam_id, e.session_term_id, st.term, e.exam_type_id, e.class_subject_id, e.student_id, e.stud_score, e.max_score
                                , cs.class_id, cs.subject_id, cs.teacher_id, lov.val_dsc
                        FROM examinations e
                                LEFT JOIN class_subject cs
                                    ON e.class_subject_id=cs.class_subject_id
                                LEFT JOIN session_term st 
                                    ON e.session_term_id=st.session_term_id 
                                LEFT JOIN t_wb_lov lov
                                    ON cs.subject_id=lov.val_id
                        WHERE cs.class_id=:class_id AND e.session_term_id=:session_term_id AND e.student_id=:student_id
                        )t group by t.subject_id";
//    echo $getStudentExam;
    $params=array(":class_id"=>$_POST['class'], ":session_term_id"=>$_POST['curr_sess'], ":student_id"=>$_POST['stud']);
    $result = $fxns->_execQuery($getStudentExam, true, true, $params);
    $sqlGetStud = "select p.person_id, p.person_type_id, p.f_name, p.m_name, p.l_name, p.email, s.adminNo
                            , p.username, p.p_word, TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) age
                            , (SELECT val_dsc from t_wb_lov WHERE def_id='00-SEX' AND val_id=p.sex)sex
                            , p.phone, p.phone2, p.pix, p.status, p.last_login_ip, p.last_login_date
                            , s.times_present, s.times_punctual, s.teacher_comment, s.principal_comment
                            , (SELECT class from class WHERE s.class_id = class_id)class
                            , (SELECT SUM(e.stud_score) FROM examinations e 
                                  LEFT JOIN class_subject cs
                                    ON e.class_subject_id=cs.class_subject_id WHERE cs.class_id=s.class_id AND e.student_id=p.person_id AND e.session_term_id=:session_term_id) 'gTotal'
                            , (SELECT ST.session_term_desc FROM session_term st WHERE s.class_id=st.class_id AND st.session_term_id=:session_term_id) 'endTerm'
                            , (SELECT date(st.term_start) term_start FROM session_term st WHERE s.class_id=st.class_id AND st.session_term_id>:session_term_id LIMIT 1) 'term_starts'
                    from person p
                            left join student s
                                    on p.person_id=s.student_id
                     WHERE person_id=:person_id";

    $params=array(":person_id"=>$_POST['stud'], ":session_term_id"=>$_POST['curr_sess']);
    $student = $fxns->_execQuery($sqlGetStud, true, false, $params);
    $table .= "<div id='stud_sheet'>";
    $table .= (($_COOKIE['teacher_type']==1)?"<div class='schoolTitle'><i class='fa fa-print fa-2x finalPrint'></i><div style='clear:both;'></div><br /><br />":"")
            . "<img src='{$student['pix']}' width='150' height='150' class='studPix' style='float:right;' />"
            . "<img src='/assets/images/logo.png' class='logo' />"
            . "<div class='title'>"
            . "<h1 style='margin:0;padding:0;'>THE CHILD SCHOOLS</h1>Afa road, Igbe Laara<br />Igbogbo-Ikorodu, Lagos<br /><br />"
            . "<h2 style='margin:0;padding:0;text-decoration:underline;'>TERMINAL SHEET</h2>"
            . "</div>"
            . "</div><br /><br />";
    $table .= "<table style='width:100%'>"
            . "<tr><td><span class='bold'>END OF :</span> {$student['endTerm']}</td><td width='50%'>&nbsp;</td></tr>"
            . "<tr><td><span class='bold'>NAME OF PUPIL:</span> {$student['l_name']} {$student['f_name']}</td><td width='50%'>&nbsp;</td></tr>"
            . "<tr><td><span class='bold'>SEX:</span> {$student['sex']}</td><td>&nbsp;</td></tr>"
            . "<tr><td><span class='bold'>AGE:</span> {$student['age']}</td><td>&nbsp;</td></tr>"
            . "<tr><td><span class='bold'>ADMISSION NO.:</span> {$student['adminNo']}</td><td><span class='bold'>Class:</span> {$student['class']}</td></tr>"
            . "<tr><td><span class='bold'>NO. OF TIMES PRESENT:</span> {$student['times_present']}</td><td><span class='bold'>NO. OF TIMES SCHOOL OPENED:</span> {$student['times_punctual']}</td></tr>"
            . "<tr><td><span class='bold'>NO. OF TIMES PUNCTUAL:</span> {$student['times_punctual']}</td><td>&nbsp;</td></tr>"
            . "</table>";
    $table .= "<table style='width:100%' class='my_tables'>"
            . "<tr><td align='center' colspan='4'>PHYSICAL DEVELOPMENT, HEALTH AND CLEANLINESS</td></tr>"
            . "<tr><td>HEIGHT</td><td><input type='text' /></td><td>WEIGHT</td><td><input type='text' /></td></tr>"
            . "<tr><td>CLEANLINESS</td><td><input type='text' /></td><td>CONDUCT</td><td><input type='text' /></td></tr>"
            . "</table>";
    $table .= "<table width='900' class='my_tables stud_sheet'>";
    if(count($result)>0 && @$result['result']!='Failure'){
        for ($i = 0; $i < count($result); $i++) {
            $table .= "<tr>";
            foreach (@$result[$i] as $key => $values) {
                if($i == 0){
                    $table .=  "<th>{$key}</th>";
                }
            }
            $table .= "</tr>";
        }
        for ($i = 0; $i < count($result); $i++) {
            $table .= ($i % 2 == 1) ? "<tr class='alt'>" : "<tr>";
            $count = 0;
            foreach ($result[$i] as $key => $values) {
                if($key=='1ST TERM' || $key=='2ND TERM' || $key=='TERM TOTAL'){
                    if($values) $count++;
                }
            }
            foreach ($result[$i] as $key => $values) {
                if($key=='CUMM. AVERAGE' && $values)
                    $table .=  "<td>".round($values/$count)."</td>";
                else
                    $table .=  "<td>{$values}</td>";
            }
            $table .= "</tr>";
        }
    }else{
        $table .= "<tr class='alt'><td>No Data found for this student for the term</td></tr>";
    }
    $table .= "</table>";
    $table .= "<table style='width:100%'>"
            . "<tr><td><span class='bold'>Grand Total:</span> ".(($student['gTotal']) ? $student['gTotal']: 0)."</td>"
            . "<td><span class='bold'>Number of Subjects:</span> ".(@$result['result']!='Failure'?count($result):0)."</td>"
            . "<td><span class='bold'>Weighted Average:</span> ".round( ($student['gTotal']/count($result)), 2, PHP_ROUND_HALF_UP)."%</td>"
            . "</tr>"
            . "</table>";
    $table .= "<table style='width:100%'>"
            . "<tr><td width='30%'><span class='bold'>SCHOOL RE-OPENS ON: </span></td><td width='*'>".date('d-M-Y', strtotime($student['term_starts']))."</td></tr>"
            . "<tr><td><span class='bold'>CLASS TEACHER'S REMARK:</span></td><td>".$student['teacher_comment']."</td></tr>"
            . "<tr><td><span class='bold'>PRINCIPAL'S COMMENT :</span></td><td>".$student['principal_comment']."</td></tr>"
            . "</table>";
    echo $table."</div>";
}
?>