<?php

/**
 * Manages Customer
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to the MIT License, available at http://www.opensource.org/licenses/mit-license.html
 *
 * @author Abimbola Hassan <ahassan@infinitizon.com>
 * @copyright 2012 infinitizon Design
 * @license http://www.opensource.org/licenses/mit-license.html
 */
class Customer extends DB_Connect {

    /**
     * The eventual customer
     * @var string Stores any returned value
     */
    private $the_customer;
    private $fxns;

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
        $this->fxns = new Functions($dbo);
    }

    /**
     * Returns an individual customer detail. If $limit, then useful for search
     *
     * @return Array
     */
    public function _getPerson($options=array(), $page=array()) {
        $sql_getPerson = "select p.person_id, p.person_type_id, p.f_name, p.m_name, p.l_name, p.email
                            , p.username, p.p_word, p.dob, p.sex, p.phone, p.phone2, p.pix
                            , p.status, p.last_login_ip, p.last_login_date
                            , t.teacher_type_id, s.class_id, s.adminNo, s.times_present
                            , s.times_punctual, s.teacher_comment, s.principal_comment
                        from person p
                        left join teacher t
                                on p.person_id=teacher_id
                        left join student s
                                on p.person_id=s.student_id
                        left join parent pa
                                on p.person_id=pa.parent_id";
        $params= array();
        ## Looking for options
        ##check for and append where clause
        if(!empty($options) && is_array($options['where'])){
            $sql_getPerson .= " WHERE ";
            foreach($options['where'] as $key => $value){
                $getParam = ":".trim(substr($key, strrpos($key, ".")+1));
                $sql_getPerson .= " {$key} = {$getParam} AND ";
                $params = array_merge($params,array("{$getParam}"=>$value));
            }
        }
        $sql_getPerson = $this->fxns->_subStrAtDel($sql_getPerson, " AND");
        $sql_getPerson .= ($_COOKIE['teacher_type']!=1 && !isset($options['where']['p.person_id']))
                                ? " AND s.class_id in (select class_id from class_subject where teacher_id = {$_SESSION['user']['id']})"
                                : '';
        ##check for and append order clause
        if(!empty($options) && isset($options['order'])){
            $sql_getPerson .= " ORDER BY ".$options['order'];
        }
        ## End: Checking for options
        if(!empty($page)){
            $sqlSearchCount = "SELECT COUNT(*) count FROM (".$sql_getPerson.")t ";
            $sqlSearchCount = $this->fxns->_execQuery($sqlSearchCount, true, false, $params);
            $preparePaging = $this->fxns->_preparePaging($sqlSearchCount['count'], $page['rowsperpage'], @$page['currentpage']);
            $sql_getPerson .= " LIMIT {$preparePaging['offset']}, {$page['rowsperpage']}";
        }
        $return = $this->fxns->_execQuery($sql_getPerson, true, true, $params);
        isset($preparePaging)? array_push($return,$preparePaging):'';
        return $return;
    }

    /**
     * Returns an individual customer detail. If $limit, then useful for search
     *
     * @return Array
     */
    public function _getClass($class_id=null, $page=array()) {
        $sql_getClass = "select * FROM class";
        $sql_getClass .= ($class_id) ? " WHERE class_id=:class_id" : '';
        
        $params = array(":class_id"=>$class_id);
        
        if(!empty($page)){
            $sqlSearchCount = "SELECT COUNT(*) count FROM (".$sql_getClass.")t ";
            $sqlSearchCount = $this->fxns->_execQuery($sqlSearchCount, true, false, $params);
            $preparePaging = $this->fxns->_preparePaging($sqlSearchCount['count'], $page['rowsperpage'], @$page['currentpage']);
            $sql_getClass .= " LIMIT {$preparePaging['offset']}, {$page['rowsperpage']}";
        }
        $return = $this->fxns->_execQuery($sql_getClass, true, true, $params);
        isset($preparePaging)? array_push($return,$preparePaging):'';
        return $return;
    }

    /**
     * Returns customers detail by status type.
     *
     * @return Array
     */
    public function _getLOVS($def_id, $r_k, $page=array()) {
        $def_id = isset($def_id) ? $def_id : '%';
        $r_k = isset($r_k) ? $r_k : '%';
        $sql_getLOVs = "SELECT r_k, def_id, val_id, val_dsc FROM t_wb_lov";
        $sql_getLOVs .= " WHERE def_id LIKE :def_id AND r_k LIKE :r_k";
        $params= array(":def_id"=>$def_id, ":r_k"=>$r_k);
        if(!empty($page)){
            $sqlSearchCount = "SELECT COUNT(*) count FROM (".$sql_getLOVs.")t ";
            $sqlSearchCount = $this->fxns->_execQuery($sqlSearchCount, true, false, $params);
            $preparePaging = $this->fxns->_preparePaging($sqlSearchCount['count'], $page['rowsperpage'], @$page['currentpage']);
            $sql_getLOVs .= " LIMIT {$preparePaging['offset']}, {$page['rowsperpage']}";
        }
        $return = $this->fxns->_execQuery($sql_getLOVs, true, true, $params);
        isset($preparePaging)? array_push($return,$preparePaging):'';
        return $return;
    }
    /**
     * Returns an individual customer detail. If $limit, then useful for search
     *
     * @return Array
     */
    public function _buildCustEdit($customer) {
        return $this->the_customer;
    }

}

?>