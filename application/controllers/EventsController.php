<?php

class EventsController extends Zend_Controller_Action
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

    public function addeventAction()
    {
        // action body
        $addEventForm = new Application_Form_Addevent();
        $this->view->addEventForm = $addEventForm;

        if ($this->getRequest()->isPost()) { 
            if ($addEventForm->isValid($this->getRequest()->getParams())) {
                /*if ($this->getRequest()->getParams("eventDate") == ""){
                   $this->view->error = "error";
               }*/
                $event = new Application_Model_Event();
                $data['name'] = $this->getRequest()->getParam("name");
                $data['desc'] = $this->getRequest()->getParam("desc");
                $data['dateTime'] = $this->getRequest()->getParam("eventDate");
                $data['location'] = $this->getRequest()->getParam("location");
                $event->addEvent($data);
                    
            }
           
        }
    }
 

}



