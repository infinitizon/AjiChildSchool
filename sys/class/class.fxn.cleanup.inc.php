<?php

/**
 * General functions class
 *
 * PHP version 5+
 *
 * LICENSE: This source file is subject to the MIT License, available at http://www.opensource.org/licenses/mit-license.html
 *
 * @author Abimbola Hassan <ahassan@infinitizon.com>
 * @copyright 2014 infinitizon Design
 * @license http://www.opensource.org/licenses/mit-license.html
 */
class Functions extends DB_Connect {

    /**
     * The eventual student as an array
     * @the_fxn string Stores the student as an array
     */
    private $the_fxn = '';

    /**
     * Creates a database object and stores relevant data
     *
     * Upon instantiation, this class accepts a database object that, if not null, is stored in the object's private $_db
     * property. If null, a new PDO object is created and stored instead.
     *
     * @param object $dbo a database object
     * @return void
     */
    public function __construct($dbo = NULL) {
        /*
         * Call the parent constructor to check for a database object
         */
        parent::__construct($dbo);
    }

    /**
     * Explode a text into an array based on delimeters (array) specified as part of the string
     *
     * @return array
     */
    public function _multiexplode($string, $delimiters = array("-", " ")) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }

    /**
     * Method  : _subStrAtDel - Substring at delimeter - Get a part of a string based on a delimiter
     * @param  : $string     - String to be worked on.
     * 			$del        - Delimeter to use
     *           $fromRear   - Boolean that tells if we should start from behind or from begining.
     * @return : String
     */
    public function _subStrAtDel($string, $del, $fromRear = true) {
        return ($fromRear) ? substr($string, 0, strrpos($string, $del) + 1) : substr($string, strpos($string, $del) + 1);
    }
    /**
     * Method  : _readMore      - Generates a readmore link - 
     * @param  : $string        - String to be worked on.
     *           $length2cut    - How long we want the display to be
     *           $readMoreLink  - readmorelink is a link that is already prepared so as to append with text "Read More"
     * @return : String
     */
    public function _readMore($string, $length2cut, $readMoreLink) {
        if (strlen($string) > $length2cut) {
            // truncate string
            $stringCut = substr($string, 0, $length2cut); // truncate string
            // make sure it ends in a word so beautiful doesn't become beu...
            return substr($stringCut, 0, strrpos($stringCut, ' ')) . '...' .(isset($readMoreLink)? $readMoreLink . 'Read More</a>':'');
        }else{
            return $string;
        }
    }

    /**
     * Returns the various combinations of items in an array
     * Example: array('a', 'b') should return 'a b' and 'b a'
     *
     * @return String
     */
    public function _strCombs($items, $perms = array(), $col_nm) {
        if (empty($items)) {
            $this->the_fxn .= " '%" . join('%', $perms) . "%' OR $col_nm LIKE";
        } else {
            for ($i = count($items) - 1; $i >= 0; --$i) {
                $newitems = $items;
                $newperms = $perms;
                list($foo) = array_splice($newitems, $i, 1);
                array_unshift($newperms, $foo);
                $this->_strCombs($newitems, $newperms, $col_nm);
            }
        }
        return $this->the_fxn;
    }

    public function _genPwd($length = 8){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789~-=+!@#$%^&*>;\|";
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
    /**
     * Get LOV Description
     *
     * @return void
     */
    public function _getLOVDsc($get_LOVDsc, $val_dsc, $params=array()) {
        try {
            $stmt = $this->_execQuery($get_LOVDsc, true, false, $params);
            return $stmt['val_dsc'];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * Function that helps us build a dropdown menu. Works for either a simple select or a combobox
     * Note: We may need to style the combobox to give it the needed feel.
     * @param:	$query		- Pass in the query because we do not know how the limit clause will look
     * 			$val_id		- Id that is returned from the select
     * 			$val_dsc	- Value shown in the dropdown box
     * 			$select_nm	- Name of the select list
     * 		 	$class_nm	- Classname you want to pass to the select. Use combobox if you want a combobox
     * 		 	$null_val	- If you want a null option at the begining. Default: NULL
     * 		 	$curr_val	- The currently selected value
     * @return:	String		- The built select/combo box
     */

    public function _getLOVs($query, $val_id, $val_dsc, $select_nm, $class_nm, $null_val = NULL, $curr_val = NULL, $multiple=array()) {
        try {
            $stmt = $this->dbo->query($query);
            $this->the_fxn = "<select class='" . $class_nm;
            $this->the_fxn .= (strpos($class_nm, 'combobox') !== false) ? "" : "' name='" . $select_nm;
            $this->the_fxn .= !empty($multiple)?"' multiple size='".$multiple['size']."'" : "'";  // Close select tag
            $this->the_fxn .= ">";  // Close select tag
            $this->the_fxn .=($null_val) ? "<option value=''>$null_val</option>" : '';
            while ($items = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($items);
                $this->the_fxn .= "<option value='" . $val_id . "'";
                $this->the_fxn .=($curr_val != NULL && $curr_val == $val_id) ? ' selected="selected" ' : '';
                $this->the_fxn .=">" . $val_dsc . "</option>";;
            }
            $this->the_fxn .= "</select>";
            $this->the_fxn .= (strpos($class_nm, 'combobox') !== false) ? '<input class="combo_in" name="' . $select_nm . '" value="' . $curr_val . '" />' : "";
            return $this->the_fxn;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function _getStateOptLOV($query, $val_id, $val_dsc, $null_val = NULL, $curr_val = NULL) {
        try {
            $stmt = $this->dbo->query($query);
            $this->the_fxn .=($null_val) ? "<option value=''>$null_val</option>" : '';
            while ($items = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($items);
                $this->the_fxn .= "<option value='" . $$val_id . "'";
                $this->the_fxn .=($curr_val != NULL && $curr_val == $$val_id) ? ' selected="selected" ' : '';
                $this->the_fxn .=">" . $$val_dsc . "</option>";
            }
            //return $this->the_fxn;
            return $this->the_fxn;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /*
     * Function that performs database query
     * @param:	$query		- query to execute
     * 			$params		- parameters to use in the query execution
     * 			$multiple	- boolean: whether it returns a multiple set of a single set
     * @return:	Array		- The built result set. 
     */

    public function _execQuery($query, $isSelect = true, $isMultiple = true, $params = array()) {
        try {
            $stmt = $this->dbo->prepare($query);
            if ($stmt->execute($params)) {
                if ($isSelect) {
                    if ($isMultiple) {
                        $results = array();
                        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $results[] = $result;
                        }
                        return $results;
                    } else {
                        $results = $stmt->fetch(PDO::FETCH_ASSOC);
                        return $results;
                    }
                } else {
                    return $stmt->rowCount();
                }
            } else {
                return "Error parsing query. Please check your query";
            }
        } catch (Exception $e) {
            return array('result' => 'Failure', 'msg' => "The Transaction batch failed due to the following reason:<blockquote>" . $e->getMessage() . "</blockquote>");
        }
    }

    /*
     * Build a table from an array (array of array) of table data
     * @param:	$array		- (array of array): $array[0] holds the assoc of db cols and title
     * 			$isVertical	- Boolean: determine whether the column headers should be on the left 
     * 			$classNm	- Classname you want to give the table
     * @return:	String		- The built table set. 
     */

    public function _buildTable($array, $isVertical = true, $tblTitle = array(), $options = array(),  $classNm = "vertColNm") {
//        var_dump($array);
        $table = '<table class="my_tables ' . $classNm . '">';
        if ($isVertical) {
            $firstRow = empty($tblTitle) ? $array[0] : $tblTitle;
            foreach ($firstRow as $key => $values) {
                if ($firstRow[$key]['display']==1) {
                    $table .= "<tr>";
                    for ($i = 0; $i < count($array); $i++) {
                        $table .= "<td>" . $values["title"] . "</td><td>";
                        $valueToPut = (is_array(@$array[$i][$key])?'':@$array[$i][$key]);
                        $valueToPut = ((@$values["class"]=='datePicker' && !empty($valueToPut))? date('d-M-Y', strtotime($valueToPut)) :$valueToPut);
                        if(@$values["type"]){
                            switch ($values["type"]) {
                                case 'select':
                                    $table .= $this->_getLOVs($values["query"], "val_id", "val_dsc", $key, @$values["class"], "--Select ".@$values["title"]."--", @$valueToPut);
                                    break;
                                case 'text':
                                case 'number':
                                    $table .= "<input type='".$values["type"]."' name='".$key."' class='".@$values["class"]."' value='".$valueToPut."'";
                                        if(isset($values['attr'])){
                                            foreach($values['attr'] as $key=>$val){
                                                $table .= " $key='$val'";
                                            }
                                        }
                                    $table .=  " />";
                                    break;
                                case 'checkbox':
                                    $table .= "<input type='".$values["type"]."' name='".$key."' class='".@$values["class"]."' ".($valueToPut==1?'checked':'')." />";
                                    break;
                                case 'file':
                                    $table .= ($valueToPut?"<img src='".$valueToPut."' width='150' height='150' /><input type='hidden' name='oldImg' value='{$valueToPut}' /> <br />":'')
                                           . "<input type='".$values["type"]."' name='".$key."' class='".@$values["class"]."'";
                                        if(isset($values['attr']['disabled'])){
                                            $table .= " disabled='disabled'";
                                        }
                                    $table .=  " />";
                                    break;
                                case 'textarea':
                                    $table .= "<".$values["type"]." name='".$key."' class='".@$values["class"]."'>".$valueToPut."</".$values["type"].">";
                                    break;
                                default:
                                    $table .= $valueToPut;
                                    break;
                            }
                        }else{
                            $table .= $valueToPut;
                        }
                        $table .= "</td>";
                    }
                    $table .= "</tr>";
                }
            }
        } else {
            //Prepare Title
            if (!empty($tblTitle)) {
                $table .= "<tr>";
                $colCount=0;
                if(isset($options['selectable'])){
                    $table .= "<th><input type='checkbox' name='selectAll' /></th>";
                    $colCount++;
                }
                foreach ($tblTitle as $key => $values) {
                    if ($tblTitle[$key]['display']==1 && !isset($values['aggregate'])) {
                        $table .= "<th>" . $tblTitle[$key]['title'] . "</th>";
                        $colCount++;
                    }
                }
                if(@$options['edit'] || @$options['delete']){
                    $table .= "<th>Actions</th>";
                    $colCount++;
                }
                $table .= "</tr>";
            }
            //End: Prepare Title
            if (!empty($tblTitle))
                $total=count($array);
            else
                $total = count($tblTitle);
            
            $firstRow = empty($tblTitle) ? $array[0] : $tblTitle;
            if(count($array) == 0){
                $table .= "<tr>No Data found</tr>";
            }else{
                $table .= "<tr>";//$current_vendor = false;
                for ($i = 0; $i < $total; $i++) {
                    
                    foreach ($firstRow as $key => $values) {
                        if(isset($values['aggregate'])){
                            $valueToPut = (is_array(@$array[$i][$key])?'':@$array[$i][$key]);
                            switch ($values["type"]) {
                                case 'LOVDsc':
                                    $val =  $this->_getLOVDsc($values["query"], 'val_dsc', array(':val_id'=>$valueToPut));
                                    break;
                                default:
                                    $val =  $valueToPut;
                                    break;
                            }
                            if ($valueToPut != @$current_vendor) {
                                $table .= "<tr><td colspan=\"".$colCount."\" style=\"font-weight:bold;text-transform:uppercase; padding:10px 5px;\">$val</td></tr>"; 
                            }
                            $current_vendor = $valueToPut;
                        }
                    }
                    if(@$options['selectable']){
                        $table .= "<td><input type='checkbox' name='{$classNm}[{$i}]' /><input type='hidden' name='row' value='{$array[$i][$options['key']]}' /></td>";
                    }
                    $incOpts = null;
                    foreach ($firstRow as $key => $values) {
                        $valueToPut = (is_array(@$array[$i][$key])?'':@$array[$i][$key]);
                        $valueToPut = ((@$values["class"]=='datePicker' && !empty($valueToPut))? date('d-M-Y', strtotime($valueToPut)) :$valueToPut);
                        
                        if ($tblTitle[$key]['display']==1 && !isset($values['aggregate'])) {
                            
                            if(@$values["type"]){
                                switch ($values["type"]) {
                                    case 'LOVDsc':
                                        $table .=  "<td>" . $this->_getLOVDsc($values["query"], 'val_dsc', array(':val_id'=>$valueToPut)) . "</td>";
                                        break;
                                    case 'checkbox':
                                        $table .= "<td><input type='".$values["type"]."' name='".$key."' class='".@$values["class"]."' ".($valueToPut==1?'checked':'')." /></td>";
                                        break;
                                    default:
                                        $table .=  "<td>" . $valueToPut . "</td>";
                                        break;
                                }
                            }else{
                                $table .=  "<td>" . $valueToPut . "</td>";
                            }
                        }
                    }
                    $incOpts .= ($options['edit'] && !empty($options['edit']))?"<a href='?".$options['edit']['page']."' class='edit ".@$options['edit']['class']."' key='".$array[$i][$options['key']]."'><i class='fa fa-pencil-square-o fa-2x'></i></a>":'';
                    $incOpts .= ($options['delete'] && !empty($options['delete']))?" <a href='?".$options['delete']['page']."' class='delete ".@$options['edit']['class']."' key='".$array[$i][$options['key']]."'><i class='fa fa-minus-circle fa-2x'></i></a>":'';
                    $table .= "<td align='middle'>" . $incOpts . "</td>";
                    $table .= "</tr>";
                }
                if(@$options['page']){
                    $table .= "<tr><td colspan=\"".$colCount."\" style=\"text-align:center;\">"
                            . $this->_buildPagingLinks(5, $options['page']['currentpage'], $options['page']['totalpages'], $options['page']['divLink'], $options['page']['link'])
                            . "</td></tr>";
                }
            }
        }
        if(@$options['addButton']){
            $table .= '<tr>';
            
            if($options['addButton']['type'] == "button"){
                $table .= '<tr><td></td><td><input type="submit" name="'.$options['addButton']['name'].'" class="button '.$options['addButton']['class'].'" value="'.$options['addButton']['value'].'" /></td>';
            }
            if($options['addButton']['type'] == "ajax"){
                $table .= '<a href="" class="button '.$options['addButton']['class'].'">'.$options['addButton']['value'].'</a>';
            }
            $table .= '</tr>';
        }
        $table .= '</table>';
        return $table;
    }

    /*
     * Build a series from an array (array of array) for plotting charts
     * @param:	$array		- (array of array): $array[0] holds the assoc of db cols and title
     * @return:	String		- The built set. 
     */

    public function _buildChartLine($array) {
        $data = NULL;
        for ($i = 0; $i < count($array); $i++) {
            $data .= "[";
            foreach ($array[0] as $key => $values) {
                if ($key == 'CREATED_') {
                    $javaScriptDate = date('Y-m-d h:iA', strtotime($array[$i][$key]));
                    $data .= "'" . $javaScriptDate . "',";
                } else {
                    $data .= $array[$i][$key] . ",";
                }
            }
            $data = substr($data, 0, strrpos($data, ","));
            $data .= "],";
        }
        $data = substr($data, 0, strrpos($data, ","));
        return "[" . $data . "]";
    }

    /*
     * Execute request and gets result over disparate networks
     * @param:	$URL		- URL to connect to
     * 			$headers	- headers to pass in connection query
     * 			$data		- data to consume
     * @return:	String		- $output - returned service to consume. 
     */

    public function _consumeService($URL, $data, $headers = array()) {
        $ch = curl_init($URL);
        //curl_setopt($ch, CURLOPT_MUTE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }
    /*
     * Encrypted & utf8-encodes a string
     * @param:	$string     - The string to be encoded
     *          $encryption_key        - Key to use as salt
     * @return:	String      - $encrypted - Encrypted string. 
     */

    public function _encrypt($string, $encryption_key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($string), MCRYPT_MODE_ECB, $iv);
        return $encrypted;
    }
    /*
     * decrypt encoded a string
     * @param:	$string     - The string already encoded with my _encode fxn
     *          $key        - Key to use as salt
     * @return:	String      - $decrypted. 
     */
    public function _decrypt($string, $encryption_key) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $string, MCRYPT_MODE_ECB, $iv);
        return $decrypted;
    }

    /*
     * Checks if a value is null or not
     */

    public function _isnull($data) {
        /** only if you need this
          if (is_string($data)) {
          $data = strtolower($data);
          }
         */
        switch ($data) {
            // Add whatever your definition of null is
            // This is just an example
            //-----------------------------
            case 'unknown': // continue
            case 'undefined': // continue
            //-----------------------------
            case 'null': // continue
            case 'NULL': // continue
            case NULL:
                return true;
        }
        // return false by default
        return false;
    }

    public function _formatMoney($number, $fractional = false) {
        if ($fractional) {
            $number = sprintf('%.2f', $number);
        }
        while (true) {
            $replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1,$2', $number);
            if ($replaced != $number) {
                $number = $replaced;
            } else {
                break;
            }
        }
        return $number;
    }

    /*
     * Function that prepares paging for a query
     */

    public function _constuctUIElements($type, $name, $value, $options) {
        switch ($values["type"]) {
            case 'select':
                $table .= $this->_getLOVs($values["query"], "val_id", "val_dsc", $key, $classNm[$key], "--Select Sex--", $valueToPut);
                break;
            case 'text':
                $table .= "<input type='".$values["type"]."' name='".$key."' class='".$values["class"]."' value='".$valueToPut."' />";
            case 'LOVDsc':
                $element .=  "<td>" . $this->_getLOVDsc($values["query"], 'val_dsc', array(':val_id'=>$array[$i][$key])) . "</td>";
                break;
            default:
                $element .=  "<td>" . $array[$i][$key] . "</td>";
                break;
        }
        return $element;
    }
    /*
     * Function that prepares paging for a query
     */

    public function _preparePaging($numrows, $rowsperpage = 10, $currentpage = 1) {
        // find out total pages
        $totalpages = ceil($numrows / $rowsperpage);
        // get the current page or set a default
        if (isset($currentpage) && is_numeric($currentpage)) {
            $currentpage = (int) $currentpage; // cast var as int incase a funny person tries something phony
        } else {
            $currentpage = 1; // default page num
        } // end if
        // if current page is greater than total pages...
        if ($currentpage > $totalpages) {
            $currentpage = $totalpages; // set current page to last page
        } // end if
        // if current page is less than first page...
        if ($currentpage < 1) {
            $currentpage = 1; // set current page to first page
        } // end if
        // the offset of the list, based on current page 
        $offset = ($currentpage - 1) * $rowsperpage;

        return array('currentpage' => $currentpage
            , 'totalpages' => $totalpages
            , 'offset' => $offset);
    }

    /*
     * Function that build the pagination links
     */

    public function _buildPagingLinks($range = 3, $currentpage, $totalpages, $divLinkClass = array(), $link = WEB_ROOT) {
        //comment
        $linkClass = array('div' => '', 'link' => '', 'param'=>'');
        $divLinkClass = array_merge($linkClass,$divLinkClass);
        $div = "<div class='" . @$divLinkClass['div'] . "'>";
        // if not on page 1, don't show back links
        if ($currentpage > 1) {
            // show << link to go back to page 1
            $div .= " <a href='{$link}?currentpage=1&{$divLinkClass['param']}' class='" . @$divLinkClass['link'] . "' currentpage=1><< First</a> ";
            // get previous page num
            $prevpage = $currentpage - 1;
            // show < link to go back to 1 page
            $div .= " <a href='{$link}?currentpage=$prevpage&{$divLinkClass['param']}' class='" . @$divLinkClass['link'] . "' currentpage=$prevpage><</a> ";
        } // end if 
        // loop to show links to range of pages around current page
        for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
            // if it's a valid page number...
            if (($x > 0) && ($x <= $totalpages)) {
                // if we're on current page...
                if ($x == $currentpage) {
                    $div .= " [<b>$x of $totalpages</b>] "; // 'highlight' it but don't make a link
                } else {
                    $div .= " <a href='{$link}?currentpage=$x&{$divLinkClass['param']}' class='" . @$divLinkClass['link'] . "' currentpage=$x>$x</a> "; // make it a link
                } // end else
            } // end if 
        } // end for
        // if not on last page, show forward and last page links        
        if ($currentpage != $totalpages) {
            $nextpage = $currentpage + 1;
            // echo forward link for next page 
            $div .= " <a href='{$link}?currentpage=$nextpage&{$divLinkClass['param']}' class='" . @$divLinkClass['link'] . "' currentpage=$nextpage>></a> ";
            // echo forward link for lastpage
            $div .= " <a href='{$link}?currentpage=$totalpages&{$divLinkClass['param']}' class='" . @$divLinkClass['link'] . "' currentpage=$totalpages>Last >></a> ";
        } // end if
        $div .= "</div>";
        return $div;
    }
    
    /*
     * Perform a backup of the database
     * @param:	$tables	Array or comma seperated list of table names
     * 
     * @return:	String	 - filename of backup. 
     */
    public function _dbBackup($tables = '*', $saveDir=''){
        $this->_execQuery("SET NAMES 'utf8'", true, false);
        //get all of the tables
        if($tables == '*'){
            $tables = array();
            $stmt = $this->dbo->prepare('SHOW TABLES');
            $stmt->execute();
            
            while ($row = $stmt->fetch()) {
                $tables[] = $row[0];
            }
        }else{
            $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        $return='';
        $return.="/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
";
        //cycle through
        foreach($tables as $table){
            $result = $this->dbo->query('SELECT * FROM '.$table);
            $num_fields = $result->columnCount();
            $return.="
--
-- Table structure for table `authroles`
--
";
            $return.= 'DROP TABLE IF EXISTS '.$table.';';
            $return.="
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;";
            $stmt = $this->dbo->query('SHOW CREATE TABLE '.$table);
            $row2 = $stmt->fetchAll();
            $return.= "\n\n".$row2[0][1].";\n\n";
            $return.="/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `{$table}`
--
";
            $return.= 'LOCK TABLES `'.$table.'` WRITE;'."\n";
            $return.= '/*!40000 ALTER TABLE `'.$table.'`  DISABLE KEYS */;'."\n";
            $insert = 'INSERT INTO '.$table.' VALUES '; $ins_cnt = 0;
            for ($i = 0; $i < $num_fields; $i++){
                while($row = $result->fetch()){
                    $insert.= '(';$ins_cnt++;
                    for($j=0; $j<$num_fields; $j++){
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) { $insert.= '"'.$row[$j].'"' ; } else { $insert.= '""'; }
                        if ($j<($num_fields-1)) { $insert.= ','; }
                    }
                    $insert.= "),";
                }
//                $return .= substr($return, 0, strrpos($return, ','));
            }
            $insert = rtrim($insert,',');
            $return.= ($ins_cnt>0 ? $insert.";\n" : '');
            $return.= '/*!40000 ALTER TABLE `'.$table.'`  ENABLE KEYS */;'."\n";
            $return.= 'UNLOCK TABLES;'."\n";
            $return.="\n\n\n";
        }
        //save and return file name
        $fileNm = (($saveDir=='')?$saveDir:'').'db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
        $handle = fopen($fileNm,'w+');
        fwrite($handle,$return);
        fclose($handle);
        return $fileNm;
    }

}

?>