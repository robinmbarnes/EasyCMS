<?php
class Admin_Form_CreateUser extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'create-user-form');
        $this->setAttrib('accept-charset', 'UTF-8');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $email = new Zend_Form_Element_Text('email');
        $email
            ->setLabel('Name')
            ->setAttrib('placeholder', 'user@domain.com')
            ->setDescription('This will also be the user\'s username')
            ->setRequired(true)
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 1, 'max' => 255)),
                     new Zend_Validate_EmailAddress(),
                )
            )
        ;

		$password = new Zend_Form_Element_Password('password');
		$password
            ->setLabel('Password')
		    ->setRequired(true)
            ->SetDescription('Must be 5 - 10 characters long')
            ->addValidators(
                array(
                     new Zend_Validate_StringLength(array('min' => 5, 'max' => 255)),
                )
            )
        ;

		$password_confirm = new Zend_Form_Element_Password('password_confirm');
		$password_confirm
            ->setLabel('Re enter password')
		    ->setRequired(true)
        ;

        $submit = new Zend_Form_Element_Submit('submit');
        $submit
            ->setValue('Create user')
            ->setLabel('')
        ;

        $this->addElements(array($email, $password, $password_confirm, $submit));

        $this->addElementPrefixPath('EasyCMS_Form_Decorator','EasyCMS/Form/Decorator','decorator');
        $this->addDisplayGroupPrefixPath('EasyCMS_Form_Decorator', 'EasyCMS/Form/Decorator');
        $this->setElementDecorators(array('Composite'));
    }
}
