<?php
/*
 * Include necessary files
 */
include_once 'core/init.inc.php';
if (@$_SESSION['user']['type'] != 1) {
    header('Location: /');
}
$page_title = ":: Home &rsaquo;&rsaquo; Leave management system ::";
$common_css_files = array('jquery-ui-1.8.21.custom.css','common.css', 'jqplot/jquery.jqplot.min.css');
$page_css_files = array('general.css');
$font_awesome_files = array('font-awesome.css', 'prettify.css');
$common_js_files = array('jquery-1.7.2.min.js', 'jquery-ui-1.11.min.js', 'jqplot/jquery.jqplot.min.js'
        , 'jqPlot/plugins/jqplot.pieRenderer.min.js', 'jqPlot/plugins/jqplot.donutRenderer.min.js'
        , 'jqPlot/plugins/jqplot.dateAxisRenderer.min.js', 'jqPlot/plugins/jqplot.canvasTextRenderer.min.js'
    , 'jqPlot/plugins/jqplot.CanvasAxisTickRenderer.min.js');
$page_js_files = array('general.js');


$cust = new Customer($dbo);
$fxns = new Functions($dbo);
if(isset($_POST) && @$_POST['submitDB']=="Backup DB"){
    $filename= $fxns->_dbBackup();
    if(file_exists($filename)){
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        readfile($filename);
    }
    exit;
}
/*
* Include the header
*/
include_once 'assets/common/header.inc';
if(isset($_POST) && @$_POST['updtResume']=="1"){
    /*
     * $query = "UPDATE session_term st SET st.term_start=:term_start, st.term_end=:term_end "
            . "WHERE st.session_term_desc=:session_term_desc and st.ius_yn=1";
    $params = array(':term_start'=>date('Y-m-d', strtotime($_POST['term_start']))
                    ,':term_end'=>date('Y-m-d', strtotime($_POST['term_end']))
                    ,':session_term_desc'=>$_POST['session_term_desc']);
    $ans = $fxns->_execQuery($query, false, false, $params);
     * 
     */
/**
 *
 * Before attempting to update the Next Term Starts make sure we have the new term set first
 *
 */
    
    $query = "UPDATE session_term st SET st.term_start=:nxt_term_start "
            . "WHERE st.term=:term AND st.session_nm=:session_nm";
    $term= ($_POST['term']==3)?1:$_POST['term']+1;
    $sessionNm = explode('/',$_POST['session_nm']);
    $sessionNm = ($_POST['term']==3) ? (($sessionNm[0]+1) .'/'. ($sessionNm[1]+1)) : $_POST['session_nm'] ;
    $params = array(':nxt_term_start'=>date('Y-m-d', strtotime($_POST['nxt_term_start']))
                    , ':session_nm'=>$sessionNm
                    , ':term'=> $term);
    try{            
    $ans = $fxns->_execQuery($query, false, false, $params);
    }  catch (Exception $e){
        echo $e->getMessage();
    }
    
}
?>
<!-- Body content here http://www.elated.com/articles/admin-templates-giveaway/ -->

<section>
    <h3>Dashboard</h3>
    <div id="dashboard">
        <?php
        if($_COOKIE['teacher_type']==1){
        ?>
        <div>
            <div style="float:right;"><form method="post" action=""><input type="submit" name="submitDB" value="Backup DB" class="button"/></form></div>
            <?php
                $stmt = $dbo->query("select st.session_nm, st.term, date(st.term_start), date(st.term_end), st.session_term_desc
                                        -- , (select date(term_start) from session_term WHERE term=st.term+1 and session_nm=st.session_nm limit 1)resume
                                        , (select date(term_start) from session_term 
			WHERE term=(case when st.term = 3 then 1 else st.term+1 end) 
				and session_nm=(case when st.term = 3 then concat(SUBSTRING_INDEX('2015/2016','/',1)+1, '/', SUBSTRING_INDEX('2015/2016','/',-1)+1) else st.session_nm end) 
		limit 1) resume
                                    from session_term st
                                    where ius_yn=1 limit 1");
                $result = $stmt->fetchAll();
                echo "<h2>Current Session: <span style='color:#1CF;'>".$result[0]['session_term_desc']."</span></h2>";
            ?>
            <form method="post" action="">
                <input type="hidden" name="updtResume" value="1" />
                <input type="hidden" name="term" value="<?php echo @$result[0]['term'] ?>" />
                <input type="hidden" name="session_nm" value="<?php echo @$result[0]['session_nm'] ?>" />
                <input type="hidden" name="session_term_desc" value="<?php echo @$result[0]['session_term_desc'] ?>" />
                <table>
                    <tr><!--td>Term Starts</td>
                        <td><input type="text" name="term_start" class="datePicker" value="<?php //echo date('d-M-Y', strtotime(@$result[0]['term_start'])) ?>" /></td>
                        <td>Term Ends</td>
                        <td><input type="text" name="term_end" class="datePicker" value="<?php // echo date('d-M-Y', strtotime(@$result[0]['term_end'])) ?>" /></td-->
                        <td>Next Term Starts</td>
                        <td><input type="text" name="nxt_term_start" class="datePicker" value="<?php echo date('d-M-Y', strtotime(@$result[0]['resume'])) ?>" /></td>
                        <td><input type="submit" name="updt_term" /></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php
        }
        ?>
        <div class="section" style="background:#E64F46; color:#FFF;">
            <i class="fa fa-user fa-4x"></i>
            <div style="float:right; text-align:right; font-size:1.2em;">
                Total Employees<br/>
                <span style="font-size:30px;">
                    <?php
                        $options=array('where'=>array("p.person_type_id"=>1,"p.status"=>1));
                        $getTeachers = $cust->_getPerson($options);echo count($getTeachers);
                    ?>
                </span>
            </div>
        </div>
        <div class="section" style="background:#1BACAB; color:#8BD6D4;">
            <i class="fa fa-globe fa-4x"></i>
            <div style="float:right; text-align:right; font-size:1.2em;">
                Total Visits<br/>
                <span style="font-size:30px;">
                    <?php
                        echo $counterVal;
                    ?>
                </span>
            </div>
        </div>
        <div class="section" style="background:#1EAAF1; color:#FFF;">
            <i class="fa fa-envelope-o fa-4x"></i>
            <div style="float:right; text-align:right; font-size:1.2em;">
                Messages<br/>
                <span style="font-size:30px;">
                    <?php
                        echo 0;
                    ?>
                </span>
            </div>
        </div>
        <?php
        
        $sql = "select IFNULL(p.create_date, NOW())CREATED_, count(p.person_type_id)
from person p
left join teacher t
	on p.person_id=teacher_id ";
$teacher = $sql."where p.person_type_id=1 and p.status=1 group by p.create_date";
$student = $sql."where p.person_type_id=3 and p.status=1 group by p.create_date";
    //print_r($sqlQuery); exit;
    $teacher = $fxns->_execQuery($teacher, true);
    $teacherLine = $fxns->_buildChartLine($teacher);
    
    $student = $fxns->_execQuery($student, true);
    $studentLine = $fxns->_buildChartLine($student);

    $teachStudLine = "callJqPlotlineChat('teachStudLine', ''
                        , [$teacherLine,$studentLine]
                        , ['{$teacher[0]['CREATED_']}', '1 week']
                        , [{label:'Teacher Additions'},{label:'Student Additions'}]
                    )";
                        ?>
        <div id="teachStudLine" style="margin:auto;margin-top:10em;height:300px;width:90%;"></div>
        <div class="clear_both"></div>
    </div>
</section>
<section>
    <h3>Sections</h3>
    <div id="lists">
        
        <?php if($_COOKIE['teacher_type']==1){ ?>
        <div class="section" goto="/staff/staff_teachers.php">
            <div class="img" style="background:url('/assets/images/teaching.gif') no-repeat;"></div>
            <div class="txt"><h3>Teachers</h3>Add or modify teacher details</div>
        </div>
        <?php } ?>
        <div class="section" goto="/staff/students.php">
            <div class="img" style="background:url('/assets/images/students.png') no-repeat;"></div>
            <div class="txt"><h3>Students</h3>Add or modify student data</div>
        </div>
        <?php if($_COOKIE['teacher_type']==1){ ?>
        <div class="section" goto="/staff/class.php">
            <div class="img" style="background:url('/assets/images/class.png') no-repeat;"></div>
            <div class="txt"><h3>Classes</h3>Add or modify classes and sections</div>
        </div>
        <div class="section" goto="/staff/LOVS.php">
            <div class="img" style="background:url('/assets/images/subject.png') no-repeat;"></div>
            <div class="txt"><h3>Setup</h3>Make general setup changes to the school. This include session, exams, list of values</div>
        </div>
        <?php } ?>
        <div class="clear_both"></div>
    </div>
</section>
<?php
/*
* Include the footer
*/
include_once 'assets/common/footer.inc';
?>
<script>
    $(function(){
        $("section div#lists .section").on('click',function(){
            window.location.href = $(this).attr('goto');
        })
    })
    <?php echo $teachStudLine; ?>
</script>