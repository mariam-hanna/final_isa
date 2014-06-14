<?php

class Application_Model_Evaluatechild extends Zend_Db_Table_Abstract{
    protected $_name = 'evaluatechild';
    
    function evaluatechild($data){
        
        $row = $this->createRow();
        
        $user_data = Zend_Auth::getInstance()->getStorage()->read();
        
        $row->user_id = $user_data->id;
        $row->q1 = $data['q1'];
        $row->q2 = $data['q2'];
        $row->q3 = $data['q3'];
        $row->date = date("m-d-Y");
        
        return $row->save();
        
    }

    function getchildevaluation() {
        $select = $this->select()->setIntegrityCheck(false)->from('evaluatechild')->joinInner('users', 'user_id=users.id');
        return $this->fetchAll($select)->toArray();
    }
}

