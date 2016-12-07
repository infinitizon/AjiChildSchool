<?php
/*
 * Include necessary files
 */
include_once 'core/init.inc.php';
$fxns = new Functions($dbo);
var_dump( $fxns->_dbBackup() );
//var_dump($dbo); exit;
function backup_tables($tables = '*'){
    $link = mysql_connect(DB_HOST,DB_USER,DB_PASS);
    mysql_select_db(DB_NAME,$link);
    mysql_query("SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*'){
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result)){
            $tables[] = $row[0];
        }
    }else{
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }
    $return='';
    //cycle through
    foreach($tables as $table){
        $result = mysql_query('SELECT * FROM '.$table);
        $num_fields = mysql_num_fields($result);
        
        echo $num_fields."<br>";
//        $return.= 'DROP TABLE '.$table.';';
//        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
//        $return.= "\n\n".$row2[1].";\n\n";
//
//        for ($i = 0; $i < $num_fields; $i++){
//            while($row = mysql_fetch_row($result)){
//                $return.= 'INSERT INTO '.$table.' VALUES(';
//                for($j=0; $j<$num_fields; $j++){
//                    $row[$j] = addslashes($row[$j]);
//                    $row[$j] = str_replace("\n","\\n",$row[$j]);
//                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
//                    if ($j<($num_fields-1)) { $return.= ','; }
//                }
//                $return.= ");\n";
//            }
//        }
        $return.="\n\n\n";
    }
    return;
    //save and return file name
    $fileNm = 'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
    $handle = fopen($fileNm,'w+');
    fwrite($handle,$return);
    fclose($handle);
    return $fileNm;
}

echo backup_tables();