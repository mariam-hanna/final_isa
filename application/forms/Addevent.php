<?php
      use Zend\Form\Element;
      use Zend\Form\Form;
class Application_Form_Addevent extends Zend_Form
{

    public function init()
    {
        
        $name = new Zend_Form_Element_Text('name'); 
        $name->setRequired(); 
        $name->addValidator(new Zend_Validate_Alpha($allowWhiteSpace = true));
        $name->setLabel('الاسم');
        $name->setAttribs(array('style' => 'width:300px;'));
        $name->setOptions(array('class'=>'form-control'));
        
        $location = new Zend_Form_Element_Text('location'); 
        $location->setRequired(); 
        $location->addValidator(new Zend_Validate_Alnum());
        $location->setLabel('المكان');
        $location->setAttribs(array('style' => 'width:300px;'));
        $location->setOptions(array('class'=>'form-control'));
        
        
        $desc = new Zend_Form_Element_Textarea('desc'); 
        $desc->setRequired(); 
        $desc->setLabel("الوصف"); 
        $desc->setAttrib('rows', '6');
        $desc->setAttribs(array('style' => 'width:400px;'));
        
        $desc->setOptions(array('class'=>'form-control'));
        
        $submit = new Zend_Form_Element_Submit('اضف');//btn btn-warning 
        $submit->setOptions(array('class'=>'btn btn-success active btn-default'));
        $submit->setAttrib('id', 's');
        
        $this->addElements(array($name,$location,$desc,$submit));
    }


}

