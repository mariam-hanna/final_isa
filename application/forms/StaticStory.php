<?php

class Application_Form_StaticStory extends Zend_Form
{

    public function init()
    {
        $this->setEnctype("multipart/form-data");
        $img =  new Zend_Form_Element_File('img[]');
        $img->setRequired();
        $img->addValidator('Count', false, 1);
        $img->addValidator('Size', false, 2097152);
        $img->addValidator('Extension', false, 'jpg,png,gif');
        $img->setValueDisabled(true);
        $this->setAttrib('enctype', 'multipart/form-data');
        $img->setLabel('الصورة: ');
        $desc = new Zend_Form_Element_Textarea('desc'); 
        $desc->setRequired(); 
        $desc->setLabel("الوصف"); 
        $desc->setAttribs(array('style' => 'width:300px;'));
        $desc->setOptions(array('class'=>'form-control'));
        $folderName = new Zend_Form_Element_Hidden ('fname');
        $category = new Zend_Form_Element_Hidden ('cat_id');
        $level = new Zend_Form_Element_Hidden ('level');
        $quiz = new Zend_Form_Element_Hidden ('quiz');
        $checkbox = new Zend_Form_Element_Checkbox('checkbox');
        $checkbox->setLabel('أخر صورة');
        $submit = new Zend_Form_Element_Submit('اضف');//btn btn-warning 
        $submit->setOptions(array('class'=>'btn btn-success active btn-default'));
        $this->addElements(array($img,$desc,$folderName,$category,$level,$quiz,$checkbox,$submit));
    }
    
}

