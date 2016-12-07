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
class Auth extends DB_Connect {

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
     * Function to authenticate a user
     *
     * @return array: User authentication details
     */
    public function _authenticate($string) {
        $user = explode( ':', base64_decode($string));
        $username = $user[0];
        $password = sha1(SALT . md5($user[1] . SALT));
        
        $stmt_chk_user = "select p.person_id, p.person_type_id, p.p_word, t.teacher_type_id
                        from person p
                        left join teacher t
                                on p.person_id=teacher_id
                        left join student s
                                on p.person_id=s.student_id
                        left join parent pa
                                on p.person_id=pa.parent_id
                         where p.status=1 AND username=:user_name";        
        try{
            $stmt = $this->dbo->prepare($stmt_chk_user);
            $stmt->execute(array(':user_name' => $username));
            if ($stmt->rowCount()) { //Check if a record is found.
                while ($row = $stmt->fetch()) {
                    $hash = $row['p_word'];
                    if (password_verify($password, $hash)) {
                        $result = ['code'=>1, 'msg'=>'Login Successful'];
                        $_SESSION['user']['id'] =  $row['person_id'];
                        $_SESSION['user']['type'] =  $row['person_type_id'];
                        setcookie('teacher_type',$row['teacher_type_id'],0,"/");
                    }else{
                        $result = ['code'=>0, 'msg'=>'Password is invalid'];
                    }
                }
            }else{
                $result = ['code'=>0, 'msg'=>'Username supplied does not exist'];
            }
        }  catch (Exception $e){
            $result = ['code'=>0, 'msg'=>$e->getMessage()];
        }
        return $result;
    }
}

?>