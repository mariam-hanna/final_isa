<?php

class Application_Form_Addquiz extends Zend_Form
{

    public function init()
    {
        
        $this->setEnctype("multipart/form-data");
        
        $img =  new Zend_Form_Element_File('img[]');
        $img->setRequired();
        $img->addValidator('Count', false, 1);
        $img->addValidator('Size', false, 100000);
        $img->addValidator('Extension', false, 'jpg,png,gif');
        $img->setValueDisabled(true);
        $this->setAttrib('enctype', 'multipart/form-data');
        $img->setLabel('الصورة: ');
        

        $submit = new Zend_Form_Element_Submit('اضف');//btn btn-warning 
        $submit->setOptions(array('class'=>'btn btn-success active btn-default'));
        
        $this->addElements(array($img,$submit));
    }


}

