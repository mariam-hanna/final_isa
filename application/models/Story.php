<?php

class Application_Model_Story extends Zend_Db_Table_Abstract {

    protected $_name = 'stories';

    function liststories($cat_id) {
        $select = $this->select()->setIntegrityCheck(false)->from('stories')->where("cat_id=$cat_id");
        return $this->fetchAll($select)->toArray();
    }

    
    function liststorieschild($cat_id) {
        $select = $this->select()->setIntegrityCheck(false)->from('stories')->where("cat_id=$cat_id")->where("static = '0'")->orWhere("level=0")->where("cat_id=$cat_id");
        return $this->fetchAll($select)->toArray();
    }
    
    function getStoryLevel() {
        $storyLevel = null;
        $select = $this->select()->where("path ='" . $_SESSION['story'] . "'");
        $stories = $this->fetchAll($select)->toArray();
        foreach ($stories as $story) {
            $storyLevel = $story['level'];
        }

        return $storyLevel;
    }

    function getStaticStory() {
        $select = $this->select()->where("path ='" . $_SESSION['story'] . "'");
        $stories = $this->fetchAll($select)->toArray();
        return $stories;
    }

    function getQuizType() {
        $select = $this->select()->where("path ='" . $_SESSION['story'] . "'");
        $quizType = $this->fetchAll($select)->toArray();
        return $quizType[0]['quizType'];
    }

    function getStoryPath($cat_id, $story_level) {
        $story_path = null;
        $select = $this->select()->where("cat_id ='" . $cat_id . "'")->where("level ='" . $story_level . "'");
        $stories = $this->fetchAll($select)->toArray();

        foreach ($stories as $story) {
            $story_path = $story['path'];
        }

        return $story_path;
    }

    function addStory($data) {
        $row = $this->createRow();
        $row->setFromArray($data);
        return $row->save();
    }

    function updateStory($catId, $level, $image, $desc) {

        $select = $this->select()->where('cat_id= ?', $catId)->where('level= ?', $level);
        $result = $this->fetchRow($select)->toArray();
        $where[] = new Zend_Db_Expr(
                $this->getAdapter()->quoteInto('level = ?', $level) . ' AND ' .
                $this->getAdapter()->quoteInto('cat_id = ?', $catId)
        );
        if (is_null($result['staticImages'])) {
            $data = array('staticImages' => $image);
            $this->update($data, $where);
        } else {
            $tempImg = $result['staticImages'] . "," . $image;
            $data = array('staticImages' => $tempImg);
            $this->update($data, $where);
        }
        if (is_null($result['staticStep'])) {
            $data = array('staticStep' => $desc);
            $this->update($data, $where);
        } else {
            $tempStp = $result['staticStep'] . "," . $desc;

            $data = array('staticStep' => $tempStp);
            $this->update($data, $where);
        }
    }

    function updateDynamicStory($catId, $level) {

        $select = $this->select()->where('cat_id= ?', $catId)->where('level= ?', $level);
        $result = $this->fetchRow($select)->toArray();
        $where[] = new Zend_Db_Expr(
                $this->getAdapter()->quoteInto('level = ?', $level) . ' AND ' .
                $this->getAdapter()->quoteInto('cat_id = ?', $catId)
        );

        $data = array('static' => 0);
        $this->update($data, $where);
    }
    
    function updateDynamicStoryWithStatic($catId, $level, $image, $desc) {

        $select = $this->select()->where('cat_id= ?', $catId)->where('level= ?', $level);
        $result = $this->fetchRow($select)->toArray();
        $where[] = new Zend_Db_Expr(
                $this->getAdapter()->quoteInto('level = ?', $level) . ' AND ' .
                $this->getAdapter()->quoteInto('cat_id = ?', $catId)
        );
        if (is_null($result['staticImages'])) {
            $data = array('staticImages' => $image);
            $this->update($data, $where);
        } else {
            $tempImg = $result['staticImages'] . "," . $image;
            $data = array('staticImages' => $tempImg);
            $this->update($data, $where);
        }
        if (is_null($result['staticStep'])) {
            $data = array('staticStep' => $desc);
            $this->update($data, $where);
        } else {
            $tempStp = $result['staticStep'] . "," . $desc;

            $data = array('staticStep' => $tempStp);
            $this->update($data, $where);
        }
    }

    function updatePathStory($catId, $level, $filename) {
        $where[] = new Zend_Db_Expr(
                $this->getAdapter()->quoteInto('level = ?', $level) . ' AND ' .
                $this->getAdapter()->quoteInto('cat_id = ?', $catId)
        );

        $data = array('path' => $filename);
        $this->update($data, $where);
    }

    function MaxLevel($catId) {

        $select = $this->select();
        $select->from($this, 'MAX(level) AS level');

        $select->where('cat_id= ?', $catId);
        $result = $this->fetchRow($select);
        $result = $this->fetchRow($select)->toArray();
        $maxlevel = $result['level'] + 1;
        if (is_null($maxlevel)) {
            $maxlevel = 1;
        }
        return $maxlevel;
    }

    function check_exist($catId, $name) {
        $select = $this->select()->where('cat_id= ?', $catId)->where('name= ?', $name);
        $result = $this->fetchAll($select)->toArray();
        $result = array_filter($result);

        if (!empty($result)) {

            $ret_val = $result[0]['level'];
        } else {
            $ret_val = FALSE;
        }
        return $ret_val;
    }

    function isStatic($catId, $name) {
        $select = $this->select()->where('cat_id= ?', $catId)->where('name= ?', $name);
        $result = $this->fetchRow($select)->toArray();
        $ret_val = $result['static'];
        return $ret_val;
    }

    public function increaseStoryCounter(){
        $row = $this->fetchRow($this->select()->where('path = ?', $_SESSION['story']));
        $row->watchStory += 1;
        $row->save();    
    }

    public function increaseStepsCounter(){
        $row = $this->fetchRow($this->select()->where('path = ?', $_SESSION['story']));
        $row->watchSteps += 1;
        $row->save();    
    }

    public function getMostWatchedStories()
    {
        
        $select = $this->select()->setIntegrityCheck(false)
        ->from('categories',array('cat_name' => 'categories.name'))->joinInner('stories', 'cat_id=categories.id')->order('watchSteps DESC')->limit(5);
        $result1 = $this->fetchAll($select)->toArray();

        $select = $this->select()->setIntegrityCheck(false)
        ->from('categories',array('cat_name' => 'categories.name'))->joinInner('stories', 'cat_id=categories.id')->order('watchStory DESC')->limit(5);
        $result2 = $this->fetchAll($select)->toArray();

        $result = [$result1,$result2];
        return $result;
    }

    function getAllStory(){
        $select = $this->select()->setIntegrityCheck(false)->from('stories',array('catName' => 'categories.name','sName' =>'stories.name','watchStory' => 'watchStory', 'watchSteps' => 'watchSteps'))->joinInner('categories', 'cat_id=categories.id');
        return $this->fetchAll($select)->toArray();

    }

}
