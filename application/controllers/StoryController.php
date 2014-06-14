<?php

class StoryController extends Zend_Controller_Action {

    private $sess = null;
    private $user_data = null;

    public function init() {
        $cats = new Application_Model_Category();
        $this->view->cats = $cats->listAllCategory();
        $this->user_data = Zend_Auth::getInstance()->getStorage()->read();
        $this->sess = new Zend_Session_Namespace("Zend_Auth");
        $authorization = Zend_Auth::getInstance();
        $action = $this->getRequest()->getActionName();
        $_SESSION['action'] = $action;
        if ($this->user_data->admin == 'false') {
            $this->redirect("/user/");
        } else if (!$authorization->hasIdentity()) {
            $this->redirect("/user/login");
        }
    }

    public function indexAction() {
        $categoryModel = new Application_Model_Category();
        $this->view->category = $categoryModel->listAllCategory();
    }

    public function dynamicAction() {

        $category = $this->_request->getParam("c");
        $name = $this->_request->getParam("n");
        $quiz = $this->_request->getParam("q");
        $story = new Application_Model_Story();
        $ret = $story->check_exist($category, $name);

        if ($ret != FALSE) {
            $ret2 = $story->isStatic($category, $name);
            if ($ret2 == 0) {
                echo "هذه القصة موجودة ";
            } else {
                $imgFolder = "story" . $ret . "_" . $category;
                // $this->view->sname = $name;
                $this->view->imgFolder = $imgFolder;
                //$this->view->cat = $category;
                //$this->view->level = $ret;
                //$this->view->type = 0;
                $dynamicStoryForm = new Application_Form_DynamicStory();
                $dynamicStoryForm->getElement('fname')->setValue($imgFolder);
                $dynamicStoryForm->getElement('cat_id')->setValue($category);
                $dynamicStoryForm->getElement('level')->setValue($ret);
                $dynamicStoryForm->getElement('quiz')->setValue($quiz);
                $this->view->dynamicStoryForm = $dynamicStoryForm;
                if ($this->getRequest()->isPost()) {

                    if ($dynamicStoryForm->isValid($this->getRequest()->getParams())) {
                        $fname = $this->_request->getParam("fname");
                        $category = $this->_request->getParam("cat_id");
                        $level = $this->_request->getParam("level");
                        $quiz = $this->_request->getParam("quiz");
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $upload->addValidator('IsImage', false);
                        $files = $upload->getFileInfo();
                        foreach ($files as $file => $fileInfo) {
                            if ($upload->isUploaded($file)) {
                                if ($upload->isValid($file)) {
                                    $radiationPath = "/var/www/bondo2Loza/public/images/" . $fname;
                                    $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                                    $upload->addFilter('Rename', array('target' => $radiationPath . '/' . $fileInfo['name'], 'overwrite' => true));
                                    $upload->receive($file);
                                    // $story = new Application_Model_Story();
                                    // $story->updateDynamicStory($category, $level);
                                }
                            }
                        }
                    }
                    $check = $this->_request->getParam("checkbox");
                    if ($check == 0) {

                        $dynamicStoryForm->getElement('fname')->setValue($fname);
                        $dynamicStoryForm->getElement('cat_id')->setValue($category);
                        $dynamicStoryForm->getElement('level')->setValue($level);
                        $dynamicStoryForm->getElement('quiz')->setValue($quiz);
                        $this->view->dynamicStoryForm = $dynamicStoryForm;
                    } else {
                        $story = new Application_Model_Story();
                        $story->updateDynamicStory($category, $level);
                        $this->_redirect("story/logic/c/" . $category . "/l/" . $level . "/q/" . $quiz);
                    }
                }
            }
        } else {
            $this->_redirect("story/dnew/c/" . $category . "/n/" . $name . "/q/" . $quiz);
        }
    }

    public function dnewAction() {
        $dynamicStoryForm = new Application_Form_DynamicStory();

        if ($this->getRequest()->isPost()) {

            if ($dynamicStoryForm->isValid($this->getRequest()->getParams())) {
                $fname = $this->_request->getParam("fname");
                $category = $this->_request->getParam("cat_id");
                $level = $this->_request->getParam("level");
                $quiz = $this->_request->getParam("quiz");
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->addValidator('IsImage', false);
                $files = $upload->getFileInfo();
                foreach ($files as $file => $fileInfo) {
                    if ($upload->isUploaded($file)) {
                        if ($upload->isValid($file)) {
                            $radiationPath = "/var/www/bondo2Loza/public/images/" . $fname;
                            $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                            $upload->addFilter('Rename', array('target' => $radiationPath . '/' . $fileInfo['name'], 'overwrite' => true));
                            $upload->receive($file);
                        }
                    }
                }
            }
            $check = $this->_request->getParam("checkbox");
            if ($check == 0) {

                $dynamicStoryForm->getElement('fname')->setValue($fname);
                $dynamicStoryForm->getElement('cat_id')->setValue($category);
                $dynamicStoryForm->getElement('level')->setValue($level);
                $dynamicStoryForm->getElement('quiz')->setValue($quiz);
                $this->view->dynamicStoryForm = $dynamicStoryForm;
            } else {
                // $story = new Application_Model_Story();
                // $story->updateDynamicStory($category, $level);
                $this->_redirect("story/staticafterdynamic/c/" . $category . "/l/" . $level . "/q/" . $quiz);
            }
        } else {
            $category = $this->_request->getParam("c");
            $name = $this->_request->getParam("n");
            $quiz = $this->_request->getParam("q");
            $story = new Application_Model_Story();
            $level = $story->MaxLevel($category, 1);
            $imgFolder = "story" . $level . "_" . $category;
            $data = array('level' => $level, 'cat_id' => $category, 'static' => 0, 'name' => $name, 'path' => $imgFolder, 'quizType' => $quiz);
            $story->addStory($data);
            $this->view->imgFolder = $imgFolder;

            $dynamicStoryForm->getElement('fname')->setValue($imgFolder);
            $dynamicStoryForm->getElement('cat_id')->setValue($category);
            $dynamicStoryForm->getElement('level')->setValue($level);
            $dynamicStoryForm->getElement('quiz')->setValue($quiz);
            $this->view->dynamicStoryForm = $dynamicStoryForm;
        }
    }

    public function staticafterdynamicAction() {

        $cat = $this->_request->getParam("c");
        //$sname = $this->_request->getParam("n");
        $level = $this->_request->getParam("l");
        $quiz = $this->_request->getParam("q");
        // $story1 = new Application_Model_Story();
        $imgFolder = "story" . $level . "_" . $cat;
        // $this->view->sname = $sname;
        $this->view->imgFolder = $imgFolder;
        $this->view->cat = $cat;
        $this->view->level = $level;
        $this->view->quiz = $quiz;
        //$data = array('level' => $level, 'cat_id' => $cat, 'static' => 1, 'name' => $sname, 'path' => $imgFolder);
        $story = new Application_Model_Story();
        //$story->addStory($data);
        //updateDynamicStoryWithStatic($catId, $level, $image, $desc)
        $staticStoryForm = new Application_Form_StaticStory();
        $staticStoryForm->getElement('fname')->setValue($imgFolder);
        $staticStoryForm->getElement('cat_id')->setValue($cat);
        $staticStoryForm->getElement('level')->setValue($level);
        $staticStoryForm->getElement('quiz')->setValue($quiz);
        $this->view->staticStoryForm = $staticStoryForm;
        if ($this->getRequest()->isPost()) {

            if ($staticStoryForm->isValid($this->getRequest()->getParams())) {
                $fname = $this->_request->getParam("fname");
                $category = $this->_request->getParam("cat_id");
                $level = $this->_request->getParam("level");
                $desc = $this->_request->getParam("desc");
                $quiz = $this->_request->getParam("quiz");
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->addValidator('IsImage', false);
                $files = $upload->getFileInfo();
                foreach ($files as $file => $fileInfo) {
                    if ($upload->isUploaded($file)) {
                        if ($upload->isValid($file)) {
                            $radiationPath = "/var/www/bondo2Loza/public/images/" . $fname;
                            $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                            $upload->addFilter('Rename', array('target' => $radiationPath . '/static/' . $fileInfo['name'], 'overwrite' => true));
                            $upload->receive($file);
                            $story = new Application_Model_Story();
                            $story->updateDynamicStoryWithStatic($category, $level, $fileInfo['name'], $desc);
                        }
                    }
                }
            }
            $check = $this->_request->getParam("checkbox");
            if ($check == 0) {

                $staticStoryForm->getElement('fname')->setValue($fname);
                $staticStoryForm->getElement('cat_id')->setValue($cat);
                $staticStoryForm->getElement('level')->setValue($level);
                $staticStoryForm->getElement('quiz')->setValue($quiz);
                $this->view->staticStoryForm = $staticStoryForm;
            } else {
                $this->_redirect("story/logic/c/" . $cat . "/l/" . $level . "/q/" . $quiz);
            }
        }
    }

    public function staticAction() {


        $staticStoryForm = new Application_Form_StaticStory();
        if ($this->getRequest()->isPost()) {

            if ($staticStoryForm->isValid($this->getRequest()->getParams())) {
                $fname = $this->_request->getParam("fname");
                $category = $this->_request->getParam("cat_id");
                $level = $this->_request->getParam("level");
                $desc = $this->_request->getParam("desc");
                $quiz = $this->_request->getParam("quiz");
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->addValidator('IsImage', false);
                $files = $upload->getFileInfo();
                foreach ($files as $file => $fileInfo) {
                    if ($upload->isUploaded($file)) {
                        if ($upload->isValid($file)) {
                            $radiationPath = "/var/www/bondo2Loza/public/images/" . $fname;
                            $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                            $upload->addFilter('Rename', array('target' => $radiationPath . '/static/' . $fileInfo['name'], 'overwrite' => true));
                            $upload->receive($file);
                            $story = new Application_Model_Story();
                            $story->updateStory($category, $level, $fileInfo['name'], $desc);
                        }
                    }
                }
            }
            $check = $this->_request->getParam("checkbox");
            if ($check == 0) {

                $staticStoryForm->getElement('fname')->setValue($fname);
                $staticStoryForm->getElement('cat_id')->setValue($category);
                $staticStoryForm->getElement('level')->setValue($level);
                $staticStoryForm->getElement('quiz')->setValue($quiz);
                $this->view->staticStoryForm = $staticStoryForm;
            } else {
                $this->_redirect("story/logic/c/" . $category . "/l/" . $level . "/q/" . $quiz);
            }
        } else {
            $cat = $this->_request->getParam("c");
            $sname = $this->_request->getParam("n");
            $quiz = $this->_request->getParam("q");
            $story1 = new Application_Model_Story();
            $level = $story1->MaxLevel($cat, 1);
            $imgFolder = "story" . $level . "_" . $cat;
            $this->view->sname = $sname;
            $this->view->imgFolder = $imgFolder;
            $this->view->cat = $cat;
            $this->view->level = $level;
            $this->view->type = 1;
            $this->view->quiz = $quiz;
            $data = array('level' => $level, 'cat_id' => $cat, 'static' => 1, 'name' => $sname, 'path' => $imgFolder, 'quizType' => $quiz);
            $story = new Application_Model_Story();
            $story->addStory($data);

            $staticStoryForm->getElement('fname')->setValue($imgFolder);
            $staticStoryForm->getElement('cat_id')->setValue($cat);
            $staticStoryForm->getElement('level')->setValue($level);
            $staticStoryForm->getElement('quiz')->setValue($quiz);
            $this->view->staticStoryForm = $staticStoryForm;
        }
    }

    public function addAction() {
        $sname = $this->_request->getParam('story_name');
        $cat = $this->_request->getParam('cat');
        $type = $this->_request->getParam('type');
        $quiz = $this->_request->getParam('quiz');

        if (!empty($sname) && !empty($cat) && !empty($type) && !empty($quiz)) {

            if ($type == "1") {
                $this->_redirect("story/static/c/" . $cat . "/n/" . $sname . "/q/" . $quiz);
            } else {
                $this->_redirect("story/dynamic/c/" . $cat . "/n/" . $sname . "/q/" . $quiz);
            }
        }
    }

    public function logicAction() {
        $level = $this->_request->getParam("l");
        $category = $this->_request->getParam("c");
        $quiz = $this->_request->getParam("q");
        $LogicUpload = new Application_Form_LogicUpload();
        $LogicUpload->getElement('cat_id')->setValue($category);
        $LogicUpload->getElement('level')->setValue($level);
        $LogicUpload->getElement('quiz')->setValue($quiz);
        $this->view->LogicUpload = $LogicUpload;

        if ($this->getRequest()->isPost()) {

            if ($LogicUpload->isValid($this->getRequest()->getParams())) {
                $fname = $this->_request->getParam("file");
                $category = $this->_request->getParam("cat_id");
                $level = $this->_request->getParam("level");
                $quiz = $this->_request->getParam("quiz");
                $upload = new Zend_File_Transfer_Adapter_Http();
                $files = $upload->getFileInfo();

                foreach ($files as $file => $fileInfo) {
                    if ($upload->isUploaded($file)) {
                        if ($upload->isValid($file)) {
                            $radiationPath = "/var/www/bondo2Loza/public/" . $fname;
                            $extension = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
                            $upload->addFilter('Rename', array('target' => $radiationPath . $fileInfo['name'], 'overwrite' => true));
                            $upload->receive($file);
                            $story = new Application_Model_Story();
                            $story->updatePathStory($category, $level, $fileInfo['name']);
                            $this->_redirect("story/addquiz/c/" . $category . "/l/" . $level . "/q/" . $quiz);
                        }
                    }
                }
            }
        }
    }

    public function addquizAction() {
        $quiz = new Application_Model_Quiz();
        $story = new Application_Model_Story();
        
        if ($quizType === 'none' || !isset($quizType)) {
            $this->redirect('/user');
        } else {
            if ($this->getRequest()->isPost()) {

                $adapter = new Zend_File_Transfer_Adapter_Http();
                $adapter->setDestination($_SESSION['des_path']);

                $quizContent = array();

                $files = $adapter->getFileInfo();

                foreach ($files as $file => $fileInfo) {
                    if ($adapter->isUploaded($file)) {
                        if ($adapter->isValid($file)) {
                            array_push($quizContent, $fileInfo['name']);
                        }
                    }
                }

                if (!$adapter->receive()) {
                    echo 'دخل كل البيانات';
                } else {

                $quiz->addQuiz($this->getRequest()->getParams(), $quizContent, $_SESSION['story_path']);
                unset($_SESSION['des_path']);
                unset ($_SESSION['story_path']);
                }
            }
            
            else{
                $quizType = $this->_request->getParam("q");
                $storyLevel = $this->_request->getParam("l");
                $cat_id = $this->_request->getParam("c");

        
                $this->view->quizType = $quizType;
                $_SESSION['story_path'] = $story->getStoryPath($cat_id, $storyLevel);
                $path = "/var/www/bondo2Loza/public/images/story" . $storyLevel . "_" . $cat_id."/";
                $_SESSION['des_path'] = $path;
            }
            
        }
    }

    
}