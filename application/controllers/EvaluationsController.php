<?php

class EvaluationsController extends Zend_Controller_Action
{

    private $sess = null;

    private $user_data = null;

    public function init()
    {
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

    public function indexAction()
    {
        // action body
    }


    public function evaluatesiteAction()
    {
        $evaluate = new Application_Model_Evaluatesite();
        $this->view->evaluate = $evaluate->getevaluation();
    }

    public function evaluatechildAction()
    {
        $evaluate = new Application_Model_Evaluatechild();
        $this->view->evaluate = $evaluate->getchildevaluation();
    }

    public function storystatisticsAction()
    {
        $evaluate = new Application_Model_Story();
        $this->view->evaluate = $evaluate->getAllStory();
    }

    public function storiesAction()
    {
        $stories = new Application_Model_Story();
        $this->view->stories = $stories->getMostWatchedStories();
    }


}



