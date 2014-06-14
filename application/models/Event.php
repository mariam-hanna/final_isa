<?php

class Application_Model_Event extends Zend_Db_Table_Abstract {

    protected $_name = 'events';

    function getAllEvents() {
        return $this->fetchAll()->toArray();
    }

    function getEvent($id) {
        $select = $this->select()->from('events')->where("id=$id");
        return $this->fetchAll($select)->toArray();
    }
    
    function addEvent($data){
        $row = $this->createRow();
        $row->name = $data['name'];
        $row->description = $data['desc'];
        $row->date = $data['dateTime'];
        $row->location = $data['location'];
        return $row->save();
    }

}
