<?php

class Application_Model_User extends Zend_Db_Table_Abstract{

    protected $_name = 'users';
    
    
    function signUp($data) {
        $row = $this->createRow();
        $row->fname = $data['fname'];
        $row->lname = $data['lname'];
        if ($data['gender']==1){
            $row->gender = 'male';
        }
        else {
            $row->gender = 'female';
        }
        
        if ($data['type']==1){
            $row->type = 'parent';
        }
        else {
            $row->type = 'child';
        }
               
        $row->email = $data['email'];
        $row->birthdate = $data['bd'];
        $row->password = md5($data['password']);
        return $row->save();
    }
    
    
        
        
    
    

}
