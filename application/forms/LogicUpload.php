<?php

class Application_Form_LogicUpload extends Zend_Form
{

   public function init()
    {
        $this->setEnctype("multipart/form-data");
        $file =  new Zend_Form_Element_File('file');
        $file->setRequired();
        $file->addValidator('Count', false, 1);
        $file->addValidator('Size', false, 2097152);
        $file->addValidator('Extension', false, 'html');
        $file->setValueDisabled(true);
        $this->setAttrib('enctype', 'multipart/form-data');
        $file->setLabel('ملف: ');
        $category = new Zend_Form_Element_Hidden ('cat_id');
        $level = new Zend_Form_Element_Hidden ('level');
        $quiz = new Zend_Form_Element_Hidden ('quiz');
        $submit = new Zend_Form_Element_Submit('اضف');//btn btn-warning 
        $submit->setOptions(array('class'=>'btn btn-success active btn-default'));
        $this->addElements(array($file,$category,$level,$quiz,$submit));
    }


}

