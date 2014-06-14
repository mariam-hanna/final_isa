<?php

class Application_Form_Signup extends Zend_Form
{

    public function init()
    {
        $fname = new Zend_Form_Element_Text('fname'); 
        $fname->setRequired();
        $fname->addValidator(new Zend_Validate_Alpha); 
        $fname->setLabel('الاسم الاول '); 
        $fname->setAttribs(array('style' => 'width:300px;')); 
        $fname->setOptions(array('class'=>'form-control'));
        
        
        $lname = new Zend_Form_Element_Text('lname'); 
        $lname->setRequired();
        $lname->addValidator(new Zend_Validate_Alpha); 
        $lname->setLabel('اللقب');
        $lname->setAttribs(array('style' => 'width:300px;')); 
        $lname->setOptions(array('class'=>'form-control'));
        
        $email = new Zend_Form_Element_Text('email'); 
        $email->setRequired(); 
        $email->addValidator(new Zend_Validate_EmailAddress); 
        $email->setLabel('البريد الالكتروني');
        $email->setAttribs(array('style' => 'width:300px;'));
        $email->setOptions(array('class'=>'form-control')); 
        
        
        $password = new Zend_Form_Element_Password('password'); 
        $password->setRequired(); 
        $password->setLabel("كلمة المرور"); 
        $password->setAttribs(array('style' => 'width:300px;'));
        $password->setOptions(array('class'=>'form-control'));
        
        
        $c_password = new Zend_Form_Element_Password('cpassword'); 
        $c_password->setRequired(); 
        $c_password->setLabel("كلمة المرور تاني ");
        $c_password->setAttribs(array('style' => 'width:300px;'));
        $c_password->setOptions(array('class'=>'form-control')); 
        
        $BD = new Zend_Form_Element_Text('bd');
        $BD->setRequired(); 
        $BD->setLabel("تاريخ الميلاد");
        $BD->addValidator(new Zend_Validate_Date);
        $BD->setAttribs(array('style' => 'width:300px;'));
        $BD->setOptions(array('class'=>'form-control'));
        
        $gender = new Zend_Form_Element_Radio('gender');
        $gender->setLabel("الجنس "); 
        $gender->addMultiOptions(array('انثي','ذكر')); 
        $gender->setRequired();
        
        
        $type = new Zend_Form_Element_Radio('type');
        $type->setLabel("طفل\ولي امر"); 
        $type->addMultiOptions(array('طفل','ولي امر')); 
        $type->setRequired();
        
        
        $submit = new Zend_Form_Element_Submit('سجل');//btn btn-warning 
        $submit->setOptions(array('class'=>'btn btn-success active btn-default')); 
        
        $this->addElements(array($fname,$lname,$email,$password,$c_password,$gender,$BD,$type,$submit));

    }


}

