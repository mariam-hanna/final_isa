<?php

class Application_Form_Addcategory extends Zend_Form
{

    public function init()
    {
        $name = new Zend_Form_Element_Text('name'); 
        $name->setRequired(); 
        $name->addValidator(new Zend_Validate_Alpha());
        $name->setLabel('الاسم');
        $name->setAttribs(array('style' => 'width:300px;'));
        $name->setOptions(array('class'=>'form-control')); 
        
        
        $desc = new Zend_Form_Element_Textarea('desc'); 
        $desc->setRequired(); 
        $desc->setLabel("الوصف"); 
        $desc->setAttribs(array('style' => 'width:300px;'));
        $desc->setOptions(array('class'=>'form-control'));
        
        $submit = new Zend_Form_Element_Submit('اضف');//btn btn-warning 
        $submit->setOptions(array('class'=>'btn btn-success active btn-default'));
        
        $this->addElements(array($name,$desc,$submit));
    }


}

